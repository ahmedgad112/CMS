<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// أحياناً لارافل بيحتاج المسار الكامل لو الـ Alias فيه مشكلة
use Spatie\Activitylog\Models\Activity; 

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // فحص وجود الكلاس قبل التشغيل لمنع الـ 500 Error الصماء
        if (!class_exists('Spatie\Activitylog\Models\Activity')) {
            return "Error: Activitylog package is not properly installed. Run composer install.";
        }

        $query = Activity::with(['causer', 'subject'])->latest();

        if ($search = trim((string) $request->get('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%'.$search.'%')
                    ->orWhere('log_name', 'like', '%'.$search.'%')
                    ->orWhere('event', 'like', '%'.$search.'%');
            });
        }

        $activities = $query->paginate(40)->withQueryString();

        return view('admin.activity-log.index', compact('activities'));
    }
}