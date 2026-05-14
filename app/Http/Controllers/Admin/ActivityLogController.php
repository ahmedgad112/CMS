<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::query()->latest();

        if ($search = trim((string) $request->get('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%'.$search.'%')
                    ->orWhere('log_name', 'like', '%'.$search.'%')
                    ->orWhere('event', 'like', '%'.$search.'%');
            });
        }

        $activities = $query
            ->with(['causer', 'subject'])
            ->paginate(40)
            ->withQueryString();

        return view('admin.activity-log.index', compact('activities'));
    }
}
