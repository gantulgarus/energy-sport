<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use App\Models\Result;
use App\Models\Sport;
use App\Models\Team;
use App\Services\ScoringService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $sports      = Sport::orderBy('sort_order')->get();
        $activeSport = $sports->firstWhere('slug', $request->query('sport')) ?? $sports->first();

        $matches = GameMatch::with(['sport', 'team1', 'team2'])
            ->where('sport_id', $activeSport->id)
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy('gender')
            ->map(fn($g) => $g->groupBy(fn($m) => $m->scheduled_at->toDateString()));

        return view('admin.matches.index', compact('matches', 'sports', 'activeSport'));
    }

    public function create()
    {
        $user   = auth()->user();
        $sports = $user->is_admin
            ? Sport::orderBy('sort_order')->get()
            : Sport::where('id', $user->sport_id)->get();
        $teams  = Team::where('is_active', true)->orderBy('name')->get();
        return view('admin.matches.form', compact('sports', 'teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sport_id'     => 'required|exists:sports,id',
            'team1_id'     => 'required|exists:teams,id|different:team2_id',
            'team2_id'     => 'required|exists:teams,id',
            'gender'       => 'required|in:male,female,mixed',
            'scheduled_at' => 'required|date',
        ]);

        abort_unless(auth()->user()->canManageSport($request->sport_id), 403);

        $match = GameMatch::create($request->only([
            'sport_id', 'team1_id', 'team2_id', 'gender',
            'round', 'venue', 'scheduled_at',
            'team1_score', 'team2_score', 'status', 'notes',
        ]));

        $slug = Sport::find($match->sport_id)->slug;
        return redirect()->route('admin.matches.index', ['sport' => $slug])->with('success', 'Тоглолт нэмэгдлээ.');
    }

    public function edit(GameMatch $match)
    {
        abort_unless(auth()->user()->canManageSport($match->sport_id), 403);
        $user   = auth()->user();
        $sports = $user->is_admin
            ? Sport::orderBy('sort_order')->get()
            : Sport::where('id', $user->sport_id)->get();
        $teams  = Team::where('is_active', true)->orderBy('name')->get();
        return view('admin.matches.form', compact('match', 'sports', 'teams'));
    }

    public function update(Request $request, GameMatch $match)
    {
        abort_unless(auth()->user()->canManageSport($match->sport_id), 403);

        $request->validate([
            'sport_id'     => 'required|exists:sports,id',
            'team1_id'     => 'required|exists:teams,id|different:team2_id',
            'team2_id'     => 'required|exists:teams,id',
            'gender'       => 'required|in:male,female,mixed',
            'scheduled_at' => 'required|date',
        ]);

        $match->update($request->only([
            'sport_id', 'team1_id', 'team2_id', 'gender',
            'round', 'venue', 'scheduled_at',
            'team1_score', 'team2_score', 'status', 'notes',
        ]));

        $slug = Sport::find($match->sport_id)->slug;
        return redirect()->route('admin.matches.index', ['sport' => $slug])->with('success', 'Тоглолт шинэчлэгдлээ.');
    }

    public function destroy(GameMatch $match)
    {
        abort_unless(auth()->user()->canManageSport($match->sport_id), 403);
        $match->delete();
        return back()->with('success', 'Тоглолт устгагдлаа.');
    }

    private function recalculateStandings(int $sportId, string $gender): void
    {
        $sport = Sport::find($sportId);
        if (!$sport) return;

        $matches = GameMatch::where('sport_id', $sportId)
            ->where('gender', $gender)
            ->where('status', 'finished')
            ->whereNotNull('team1_score')
            ->whereNotNull('team2_score')
            ->get();

        // Хожлын тоог тооцно
        $wins = [];
        $played = [];
        foreach ($matches as $m) {
            $s1 = (float) $m->team1_score;
            $s2 = (float) $m->team2_score;

            $played[$m->team1_id] = ($played[$m->team1_id] ?? 0) + 1;
            $played[$m->team2_id] = ($played[$m->team2_id] ?? 0) + 1;
            $wins[$m->team1_id]   = ($wins[$m->team1_id] ?? 0);
            $wins[$m->team2_id]   = ($wins[$m->team2_id] ?? 0);

            if ($s1 > $s2) {
                $wins[$m->team1_id]++;
            } elseif ($s2 > $s1) {
                $wins[$m->team2_id]++;
            }
        }

        if (empty($wins)) return;

        // Хожлоор буурах эрэмбэлнэ
        arsort($wins);
        $place = 1;
        foreach ($wins as $teamId => $_) {
            $points = ScoringService::getPoints($sport->slug, $place);
            Result::updateOrCreate(
                ['team_id' => $teamId, 'sport_id' => $sportId, 'gender' => $gender],
                ['place' => $place, 'points' => $points]
            );
            $place++;
        }
    }
}
