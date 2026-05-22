<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroupAssignment;
use App\Models\Sport;
use App\Models\Team;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $sports      = Sport::orderBy('sort_order')->get();
        $activeSport = $sports->firstWhere('slug', $request->query('sport')) ?? $sports->first();
        $teams       = Team::where('is_active', true)->orderBy('name')->get();

        $genders = $activeSport->isMixed() ? ['mixed'] : ['male', 'female'];

        $groups = GroupAssignment::where('sport_id', $activeSport->id)
            ->with('team')
            ->orderBy('gender')
            ->orderBy('group_name')
            ->orderBy('order_num')
            ->get()
            ->groupBy('gender')
            ->map(fn($g) => $g->groupBy('group_name'));

        return view('admin.groups.index', compact('sports', 'activeSport', 'teams', 'groups', 'genders'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->canManageSport($request->sport_id), 403);
        $request->validate([
            'sport_id'   => 'required|exists:sports,id',
            'gender'     => 'required|in:male,female,mixed',
            'group_name' => 'required|string|max:5',
            'team_id'    => 'required|exists:teams,id',
            'order_num'  => 'required|integer|min:1',
        ]);

        $exists = GroupAssignment::where('sport_id', $request->sport_id)
            ->where('gender', $request->gender)
            ->where('team_id', $request->team_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Энэ баг тухайн спортод аль хэдийн бүртгэгдсэн байна.');
        }

        GroupAssignment::create($request->only(['sport_id','gender','group_name','team_id','order_num']));

        $sport = Sport::find($request->sport_id);
        return redirect()->route('admin.groups.index', ['sport' => $sport->slug])
            ->with('success', 'Баг хэсэгт нэмэгдлээ.');
    }

    public function update(Request $request, GroupAssignment $group)
    {
        abort_unless(auth()->user()->canManageSport($group->sport_id), 403);
        $request->validate([
            'group_name' => 'required|string|max:5',
            'order_num'  => 'required|integer|min:1',
        ]);

        $group->update($request->only(['group_name', 'order_num']));

        $sport = Sport::find($group->sport_id);
        return redirect()->route('admin.groups.index', ['sport' => $sport->slug])
            ->with('success', 'Хэсэг шинэчлэгдлээ.');
    }

    public function destroy(GroupAssignment $group)
    {
        abort_unless(auth()->user()->canManageSport($group->sport_id), 403);
        $sport = Sport::find($group->sport_id);
        $group->delete();
        return redirect()->route('admin.groups.index', ['sport' => $sport->slug])
            ->with('success', 'Баг хэсгээс хасагдлаа.');
    }
}
