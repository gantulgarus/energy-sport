<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('name')->paginate(20);
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams',
            'short_name' => 'nullable|string|max:50',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Team::create($validated);
        return redirect()->route('admin.teams.index')->with('success', 'Байгууллага нэмэгдлээ.');
    }

    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'short_name' => 'nullable|string|max:50',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($team->logo) Storage::disk('public')->delete($team->logo);
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $team->update($validated);
        return redirect()->route('admin.teams.index')->with('success', 'Байгууллага шинэчлэгдлээ.');
    }

    public function destroy(Team $team)
    {
        if ($team->logo) Storage::disk('public')->delete($team->logo);
        $team->delete();
        return redirect()->route('admin.teams.index')->with('success', 'Байгууллага устгагдлаа.');
    }
}
