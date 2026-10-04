<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $projects = $request->user()
            ->spinnerProjects()
            ->withCount('questions')
            ->latest()
            ->get();

        return view('dashboard.index', compact('projects'));
    }
}