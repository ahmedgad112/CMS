<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('doctors')
            ->with('specializations')
            ->latest()
            ->paginate(15);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Department::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', 'تم إضافة القسم بنجاح.');
    }

    public function show(Department $department)
    {
        $department->load(['doctors', 'specializations']);
        return view('admin.departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', 'تم تحديث القسم بنجاح.');
    }

    public function destroy(Department $department)
    {
        if ($department->doctors()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف القسم لأنه يحتوي على أطباء.']);
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'تم حذف القسم بنجاح.');
    }
}
