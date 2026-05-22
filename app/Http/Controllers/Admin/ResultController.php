<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $sports = \App\Models\Sport::orderBy('sort_order')->get();
        return view('admin.results.index', compact('sports'));
    }

    public function edit(string $sportSlug)
    {
        $sport = \App\Models\Sport::where('slug', $sportSlug)->firstOrFail();
        $teams = \App\Models\Team::where('is_active', true)->orderBy('name')->get();

        $genders = $sport->isMixed() ? ['mixed'] : ['male', 'female'];

        $existingResults = \App\Models\Result::where('sport_id', $sport->id)
            ->get()
            ->groupBy('gender')
            ->map(fn($g) => $g->keyBy('team_id'));

        return view('admin.results.edit', compact('sport', 'teams', 'genders', 'existingResults'));
    }

    public function update(Request $request, string $sportSlug)
    {
        $sport = \App\Models\Sport::where('slug', $sportSlug)->firstOrFail();
        abort_unless(auth()->user()->is_admin, 403);
        $genders = $sport->isMixed() ? ['mixed'] : ['male', 'female'];

        $results = $request->input('results', []);

        foreach ($genders as $gender) {
            if (!isset($results[$gender])) continue;

            foreach ($results[$gender] as $teamId => $data) {
                $place = isset($data['place']) && $data['place'] !== '' ? (int)$data['place'] : null;
                $points = $place ? \App\Services\ScoringService::getPoints($sport->slug, $place) : 0;

                \App\Models\Result::updateOrCreate(
                    ['team_id' => $teamId, 'sport_id' => $sport->id, 'gender' => $gender],
                    ['place' => $place, 'points' => $points, 'notes' => $data['notes'] ?? null]
                );
            }
        }

        return redirect()->route('admin.results.index')->with('success', $sport->name . ' үр дүн хадгалагдлаа.');
    }
}
