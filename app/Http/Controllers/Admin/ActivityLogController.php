<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $subjectTypes = ActivityLog::query()
            ->select('subject_type')
            ->distinct()
            ->orderBy('subject_type')
            ->pluck('subject_type');

        $baseQuery = ActivityLog::query()
            ->when($request->filled('action'), fn ($query) => $query->where('action', (string) $request->string('action')))
            ->when($request->filled('subject_type'), fn ($query) => $query->where('subject_type', (string) $request->string('subject_type')))
            ->when($request->filled('subject_id'), fn ($query) => $query->where('subject_id', $request->integer('subject_id')))
            ->when($request->filled('request_method'), fn ($query) => $query->where('request_method', strtoupper((string) $request->string('request_method'))))
            ->when($request->filled('from_date'), fn ($query) => $query->whereDate('created_at', '>=', $request->date('from_date')))
            ->when($request->filled('to_date'), fn ($query) => $query->whereDate('created_at', '<=', $request->date('to_date')))
            ->when($request->filled('search'), function ($query) use ($request): void {
                $search = '%'.$request->string('search').'%';

                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('subject_label', 'like', $search)
                        ->orWhere('causer_name', 'like', $search)
                        ->orWhere('causer_email', 'like', $search)
                        ->orWhere('ip_address', 'like', $search)
                        ->orWhere('url', 'like', $search)
                        ->orWhere('subject_type', 'like', $search);
                });
            });

        $summary = [
            'total' => (clone $baseQuery)->count(),
            'created' => (clone $baseQuery)->where('action', 'created')->count(),
            'updated' => (clone $baseQuery)->where('action', 'updated')->count(),
            'deleted' => (clone $baseQuery)->where('action', 'deleted')->count(),
            'auth' => (clone $baseQuery)->whereIn('action', ['logged_in', 'logged_out'])->count(),
        ];

        $activityLogs = (clone $baseQuery)
            ->with(['causer:id,name,email', 'subject'])
            ->recent()
            ->paginate(20)
            ->withQueryString();

        return view('admin.activity-logs.index', compact('activityLogs', 'subjectTypes', 'summary'));
    }

    public function show(ActivityLog $activityLog): View
    {
        $activityLog->load(['causer:id,name,email', 'subject']);

        return view('admin.activity-logs.show', compact('activityLog'));
    }
}
