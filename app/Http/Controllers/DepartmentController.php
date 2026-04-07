<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Department::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status === 'active');
        }

        $departments = $query->orderBy('name', 'asc')->paginate(15);

        $departments->getCollection()->transform(function ($dept) {
            $dept->users_count = \App\Models\User::where('department', $dept->name)->count();
            return $dept;
        });

        return view('admin.departments', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:departments,name'],
            'code' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['is_active'] = true;

        Department::create($validated);

        return redirect()->back()->with('success', 'Department added successfully.');
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:departments,name,' . $department->id],
            'code' => ['nullable', 'string', 'max:20'],
        ]);

        $department->update($validated);

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    public function toggleStatus(Department $department): RedirectResponse
    {
        $department->update(['is_active' => !$department->is_active]);
        
        $status = $department->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()->with('success', "Department {$status} successfully.");
    }

    public function destroy(Department $department): RedirectResponse
    {
        $hasUsers = $department->users()->count();
        
        if ($hasUsers > 0) {
            return redirect()->back()->with('error', "Cannot delete department. {$hasUsers} user(s) are assigned to this department.");
        }

        $department->delete();

        return redirect()->back()->with('success', 'Department deleted successfully.');
    }
}