<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::query()
            ->with('causer')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('log_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('causer')) {
            $query->whereHas('causer', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->input('causer')}%");
            });
        }

        if ($request->filled('log_name')) {
            $query->where('log_name', $request->input('log_name'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $logs = $query->paginate(20);

        $logNames = Activity::select('log_name')->distinct()->pluck('log_name');

        return view('activity-logs.index', compact('logs', 'logNames'));
    }

    public function show(Activity $activity)
    {
        $activity->load('causer', 'subject');

        return view('activity-logs.show', compact('activity'));
    }
}
