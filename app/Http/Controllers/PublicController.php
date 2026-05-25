<?php

namespace App\Http\Controllers;

use App\Models\GroupAssignment;
use App\Models\Sport;
use App\Models\Team;
use App\Models\Result;
use App\Models\GameMatch;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home()
    {
        $sports = Sport::orderBy('sort_order')->get();
        $teamsCount = Team::where('is_active', true)->count();
        $schedule = GameMatch::with(['sport', 'team1', 'team2'])
            ->orderByDesc('scheduled_at')
            ->get()
            ->groupBy(fn($m) => $m->scheduled_at->toDateString())
            ->map(fn($day) => $day->sortBy(fn($m) => match($m->status) {
                'finished' => 0, 'live' => 1, default => 2
            }));
        return view('public.home', compact('sports', 'teamsCount', 'schedule'));
    }

    public function standings()
    {
        $teams = Team::where('is_active', true)
            ->with(['results.sport'])
            ->get()
            ->map(function ($team) {
                $team->total_points = $team->results->sum('points');
                return $team;
            })
            ->sortByDesc('total_points')
            ->values();

        $sports = Sport::orderBy('sort_order')->get();

        return view('public.standings', compact('teams', 'sports'));
    }

    public function sport(string $slug)
    {
        $sport = Sport::where('slug', $slug)->firstOrFail();

        $groups = GroupAssignment::where('sport_id', $sport->id)
            ->with('team')
            ->orderBy('gender')
            ->orderBy('group_name')
            ->orderBy('order_num')
            ->get()
            ->groupBy('gender')
            ->map(fn($g) => $g->groupBy('group_name'));

        $results = Result::where('sport_id', $sport->id)
            ->with('team')
            ->orderBy('gender')
            ->orderBy('place')
            ->get()
            ->groupBy('gender');

        $matches = GameMatch::where('sport_id', $sport->id)
            ->with(['team1', 'team2'])
            ->orderBy('scheduled_at')
            ->get()
            ->groupBy(fn($m) => $m->scheduled_at->toDateString());

        return view('public.sport', compact('sport', 'results', 'matches', 'groups'));
    }
}
