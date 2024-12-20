<?php
namespace App\Http\Controllers;

class PerformanceController extends Controller
{
    public function index()
    {
        $taskProgress = 75.5;
        $weeklyAchievements = 12;
        $dailyAchievements = 3;

        return view('performance.index', compact('taskProgress', 'weeklyAchievements', 'dailyAchievements'));
    }
}