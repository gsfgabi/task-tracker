<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\Collaborator;
use App\Models\TimeTracker;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Routing\Controller as BaseController;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $collaborator = Auth::user();

        // Get today's time trackers
        $todayTimeTrackers = TimeTracker::where('collaborator_id', $collaborator->id)
            ->whereDate('start_time', Carbon::today())
            ->whereNotNull('end_time')
            ->get();

        $todayTime = $this->formatTime($todayTimeTrackers->sum(function ($tt) {
            return $tt->duration;
        }));

        // Get current month's time trackers
        $monthTimeTrackers = TimeTracker::where('collaborator_id', $collaborator->id)
            ->whereMonth('start_time', Carbon::now()->month)
            ->whereYear('start_time', Carbon::now()->year)
            ->whereNotNull('end_time')
            ->get();

        $monthTime = $this->formatTime($monthTimeTrackers->sum(function ($tt) {
            return $tt->duration;
        }));

        // Get active tasks count
        $activeTasks = Task::where('status', 'in_progress')->count();

        // Get total projects count
        $totalProjects = Project::count();

        // Get recent time trackers
        $recentTimeTrackers = TimeTracker::with(['task.project', 'collaborator'])
            ->where('collaborator_id', $collaborator->id)
            ->orderBy('start_time', 'desc')
            ->limit(5)
            ->get();

        // Get pending tasks
        $pendingTasks = Task::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get all projects and collaborators for filters
        $projects = Project::all();
        $collaborators = Collaborator::all();

        // Get all tasks for the tasks tab
        $tasks = Task::with(['project', 'collaborator'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact(
            'collaborator',
            'todayTime',
            'monthTime',
            'activeTasks',
            'totalProjects',
            'recentTimeTrackers',
            'pendingTasks',
            'projects',
            'collaborators',
            'tasks'
        ));
    }

    private function formatTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
