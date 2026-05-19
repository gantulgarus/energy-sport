<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $teamsCount = \App\Models\Team::count();
        $resultsCount = \App\Models\Result::count();
        $sports = \App\Models\Sport::withCount('results')->orderBy('sort_order')->get();
        return view('admin.dashboard', compact('teamsCount', 'resultsCount', 'sports'));
    }
}
