<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Map each role slug to permission slugs (admin is handled in User::hasPermission).
     *
     * @var array<string, list<string>>
     */
    private array $matrix = [
        'doctor' => [
            'view_dashboard',
            'view_patients',
            'view_appointments',
            'view_doctors',
            'view_prescriptions',
            'create_prescriptions',
            'edit_prescriptions',
            'print_prescriptions',
            'view_invoices',
        ],
        'receptionist' => [
            'view_dashboard',
            'view_patients',
            'create_patients',
            'edit_patients',
            'delete_patients',
            'view_appointments',
            'create_appointments',
            'edit_appointments',
            'delete_appointments',
            'view_appointment_requests',
            'process_appointment_requests',
            'view_invoices',
            'create_invoices',
            'edit_invoices',
            'print_invoices',
            'view_doctors',
            'view_reports',
        ],
        'call_center' => [
            'view_dashboard',
            'view_patients',
            'create_patients',
            'edit_patients',
            'delete_patients',
            'view_appointments',
            'create_appointments',
            'edit_appointments',
            'delete_appointments',
            'view_appointment_requests',
            'process_appointment_requests',
            'view_invoices',
            'create_invoices',
            'edit_invoices',
            'print_invoices',
            'view_doctors',
            'view_reports',
        ],
        'accountant' => [
            'view_dashboard',
            'view_patients',
            'view_appointments',
            'view_invoices',
            'create_invoices',
            'edit_invoices',
            'print_invoices',
            'view_payments',
            'create_payments',
            'edit_payments',
            'view_reports',
            'financial_reports',
            'doctor_reports',
        ],
        'storekeeper' => [
            'view_dashboard',
            'view_inventory',
            'manage_inventory',
            'add_stock',
            'remove_stock',
            'view_reports',
            'inventory_reports',
        ],
    ];

    public function run(): void
    {
        $slugToId = Permission::query()->pluck('id', 'slug');

        foreach ($this->matrix as $role => $permissionSlugs) {
            foreach ($permissionSlugs as $permSlug) {
                $permissionId = $slugToId[$permSlug] ?? null;
                if ($permissionId === null) {
                    continue;
                }

                DB::table('role_permissions')->updateOrInsert(
                    [
                        'role' => $role,
                        'permission_id' => $permissionId,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
