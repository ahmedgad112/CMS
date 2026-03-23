<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function index(Request $request)
    {
        $query = Specialization::with(['department']);

        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        $specializations = $query->withCount('doctors')->latest()->paginate(15);
        $departments = Department::where('is_active', true)->get();

        return view('admin.specializations.index', compact('specializations', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.specializations.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        Specialization::create($validated);

        return redirect()->route('admin.specializations.index')
            ->with('success', 'تم إضافة التخصص بنجاح.');
    }

    public function show(Specialization $specialization)
    {
        $specialization->load(['department', 'doctors']);
        return view('admin.specializations.show', compact('specialization'));
    }

    public function edit(Specialization $specialization)
    {
        $departments = Department::where('is_active', true)->get();
        return view('admin.specializations.edit', compact('specialization', 'departments'));
    }

    public function update(Request $request, Specialization $specialization)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $specialization->update($validated);

        return redirect()->route('admin.specializations.index')
            ->with('success', 'تم تحديث التخصص بنجاح.');
    }

    public function destroy(Specialization $specialization)
    {
        if ($specialization->doctors()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف التخصص لأنه يحتوي على أطباء.']);
        }

        $specialization->delete();

        return redirect()->route('admin.specializations.index')
            ->with('success', 'تم حذف التخصص بنجاح.');
    }
}
