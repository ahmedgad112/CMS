<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index(Request $request)
    {
        $query = Role::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roles = $query->withCount('users')->latest()->paginate(15);

        return view('admin.role-permissions.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        return view('admin.role-permissions.create', compact('permissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug|regex:/^[a-z_]+$/',
            'description' => 'nullable|string',
            'is_system' => 'boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'slug.regex' => 'يجب أن يحتوي المعرف على أحرف صغيرة وشرطات سفلية فقط (مثل: admin, doctor_role)',
        ]);

        $validated['is_system'] = $request->has('is_system') ? true : false;

        DB::transaction(function () use ($validated, $request, &$role) {
            $role = Role::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'is_system' => $validated['is_system'],
            ]);

            // Attach permissions
            if ($request->has('permissions') && is_array($request->permissions)) {
                $now = now();
                foreach ($request->permissions as $permissionId) {
                    DB::table('role_permissions')->insert([
                        'role' => $role->slug,
                        'permission_id' => $permissionId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        });

        return redirect()->route('admin.role-permissions.index')
            ->with('success', 'تم إضافة الدور بنجاح.');
    }

    /**
     * Display the specified role
     */
    public function show(Role $role)
    {
        $role->load('users');
        
        // Get permissions for this role
        $permissions = DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role', $role->slug)
            ->select('permissions.*')
            ->get()
            ->groupBy('category');
        
        return view('admin.role-permissions.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        
        // Get selected permissions for this role
        $selectedPermissions = DB::table('role_permissions')
            ->where('role', $role->slug)
            ->pluck('permission_id')
            ->toArray();
        
        // Get users with this role
        $usersWithRole = $role->users;
        
        return view('admin.role-permissions.edit', compact('role', 'permissions', 'selectedPermissions', 'usersWithRole'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($role->id),
                'regex:/^[a-z_]+$/'
            ],
            'description' => 'nullable|string',
            'is_system' => 'boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'slug.regex' => 'يجب أن يحتوي المعرف على أحرف صغيرة وشرطات سفلية فقط (مثل: admin, doctor_role)',
        ]);

        $validated['is_system'] = $request->has('is_system') ? true : false;

        // Get affected users count before update
        $affectedUsersCount = $role->users()->count();
        $affectedUsers = $role->users()->pluck('id')->toArray();

        DB::transaction(function () use ($role, $validated, $request) {
            $oldSlug = $role->slug;
            $role->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'is_system' => $validated['is_system'],
            ]);

            // If slug changed, update role_permissions table
            if ($oldSlug !== $role->slug) {
                DB::table('role_permissions')
                    ->where('role', $oldSlug)
                    ->update(['role' => $role->slug]);
                
                // Update users table
                DB::table('users')
                    ->where('role', $oldSlug)
                    ->update(['role' => $role->slug]);
            }

            // Sync permissions - delete old ones first
            DB::table('role_permissions')->where('role', $role->slug)->delete();
            
            // Insert new permissions
            if ($request->has('permissions') && is_array($request->permissions)) {
                $now = now();
                foreach ($request->permissions as $permissionId) {
                    DB::table('role_permissions')->insert([
                        'role' => $role->slug,
                        'permission_id' => $permissionId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        });

        $message = 'تم تحديث الدور بنجاح.';
        if ($affectedUsersCount > 0) {
            $message .= " تم تطبيق التغييرات تلقائياً على {$affectedUsersCount} مستخدم.";
        }

        return redirect()->route('admin.role-permissions.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified role
     */
    public function destroy(Role $role)
    {
        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف الدور لأنه مرتبط بمستخدمين.']);
        }

        // Prevent deletion of system roles
        if ($role->is_system) {
            return back()->withErrors(['error' => 'لا يمكن حذف أدوار النظام الأساسية.']);
        }

        DB::transaction(function () use ($role) {
            // Delete role permissions
            DB::table('role_permissions')->where('role', $role->slug)->delete();
            
            // Delete role
            $role->delete();
        });

        return redirect()->route('admin.role-permissions.index')
            ->with('success', 'تم حذف الدور بنجاح.');
    }

    /**
     * Show user permissions management
     */
    public function userPermissions(User $user)
    {
        // Get all permissions grouped by category
        $permissions = Permission::orderBy('category')->orderBy('name')->get()->groupBy('category');
        
        // Get user's role permissions
        $userRolePermissions = DB::table('role_permissions')
            ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
            ->where('role_permissions.role', $user->role)
            ->pluck('permissions.id')
            ->toArray();
        
        return view('admin.role-permissions.user-permissions', compact('user', 'permissions', 'userRolePermissions'));
    }

    /**
     * Update user permissions
     */
    public function updateUserPermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,slug',
        ]);

        DB::transaction(function () use ($user, $validated) {
            // Update user's role
            $user->update(['role' => $validated['role']]);
        });

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'تم تحديث دور المستخدم بنجاح.');
    }
}

