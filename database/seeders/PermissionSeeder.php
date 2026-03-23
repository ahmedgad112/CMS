<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'عرض لوحة التحكم', 'slug' => 'view_dashboard', 'category' => 'dashboard', 'description' => 'عرض لوحة التحكم الرئيسية'],
            
            // Patients
            ['name' => 'عرض المرضى', 'slug' => 'view_patients', 'category' => 'patients', 'description' => 'عرض قائمة المرضى'],
            ['name' => 'إضافة مرضى', 'slug' => 'create_patients', 'category' => 'patients', 'description' => 'إضافة مرضى جدد'],
            ['name' => 'تعديل المرضى', 'slug' => 'edit_patients', 'category' => 'patients', 'description' => 'تعديل بيانات المرضى'],
            ['name' => 'حذف المرضى', 'slug' => 'delete_patients', 'category' => 'patients', 'description' => 'حذف المرضى'],
            
            // Appointments
            ['name' => 'عرض المواعيد', 'slug' => 'view_appointments', 'category' => 'appointments', 'description' => 'عرض قائمة المواعيد'],
            ['name' => 'إضافة مواعيد', 'slug' => 'create_appointments', 'category' => 'appointments', 'description' => 'إضافة مواعيد جديدة'],
            ['name' => 'تعديل المواعيد', 'slug' => 'edit_appointments', 'category' => 'appointments', 'description' => 'تعديل المواعيد'],
            ['name' => 'حذف المواعيد', 'slug' => 'delete_appointments', 'category' => 'appointments', 'description' => 'حذف المواعيد'],
            
            // Prescriptions
            ['name' => 'عرض الوصفات', 'slug' => 'view_prescriptions', 'category' => 'prescriptions', 'description' => 'عرض الوصفات الطبية'],
            ['name' => 'إضافة وصفات', 'slug' => 'create_prescriptions', 'category' => 'prescriptions', 'description' => 'إضافة وصفات طبية'],
            ['name' => 'تعديل الوصفات', 'slug' => 'edit_prescriptions', 'category' => 'prescriptions', 'description' => 'تعديل الوصفات الطبية'],
            ['name' => 'طباعة الوصفات', 'slug' => 'print_prescriptions', 'category' => 'prescriptions', 'description' => 'طباعة الوصفات الطبية'],
            
            // Invoices
            ['name' => 'عرض الفواتير', 'slug' => 'view_invoices', 'category' => 'invoices', 'description' => 'عرض قائمة الفواتير'],
            ['name' => 'إنشاء فواتير', 'slug' => 'create_invoices', 'category' => 'invoices', 'description' => 'إنشاء فواتير جديدة'],
            ['name' => 'تعديل الفواتير', 'slug' => 'edit_invoices', 'category' => 'invoices', 'description' => 'تعديل الفواتير'],
            ['name' => 'طباعة الفواتير', 'slug' => 'print_invoices', 'category' => 'invoices', 'description' => 'طباعة الفواتير'],
            
            // Payments
            ['name' => 'عرض المدفوعات', 'slug' => 'view_payments', 'category' => 'payments', 'description' => 'عرض قائمة المدفوعات'],
            ['name' => 'إضافة مدفوعات', 'slug' => 'create_payments', 'category' => 'payments', 'description' => 'إضافة مدفوعات جديدة'],
            ['name' => 'تعديل المدفوعات', 'slug' => 'edit_payments', 'category' => 'payments', 'description' => 'تعديل المدفوعات'],
            
            // Inventory
            ['name' => 'عرض المخزون', 'slug' => 'view_inventory', 'category' => 'inventory', 'description' => 'عرض قائمة المخزون'],
            ['name' => 'إدارة المخزون', 'slug' => 'manage_inventory', 'category' => 'inventory', 'description' => 'إضافة وتعديل المخزون'],
            ['name' => 'إضافة كمية', 'slug' => 'add_stock', 'category' => 'inventory', 'description' => 'إضافة كمية للمخزون'],
            ['name' => 'سحب كمية', 'slug' => 'remove_stock', 'category' => 'inventory', 'description' => 'سحب كمية من المخزون'],
            
            // Users
            ['name' => 'عرض المستخدمين', 'slug' => 'view_users', 'category' => 'users', 'description' => 'عرض قائمة المستخدمين'],
            ['name' => 'إضافة مستخدمين', 'slug' => 'create_users', 'category' => 'users', 'description' => 'إضافة مستخدمين جدد'],
            ['name' => 'تعديل المستخدمين', 'slug' => 'edit_users', 'category' => 'users', 'description' => 'تعديل بيانات المستخدمين'],
            ['name' => 'حذف المستخدمين', 'slug' => 'delete_users', 'category' => 'users', 'description' => 'حذف المستخدمين'],
            
            // Roles & Permissions
            ['name' => 'إدارة الأدوار', 'slug' => 'manage_roles', 'category' => 'roles', 'description' => 'إدارة الأدوار والصلاحيات'],
            
            // Reports
            ['name' => 'عرض التقارير', 'slug' => 'view_reports', 'category' => 'reports', 'description' => 'عرض التقارير'],
            ['name' => 'تقارير مالية', 'slug' => 'financial_reports', 'category' => 'reports', 'description' => 'عرض التقارير المالية'],
            ['name' => 'تقارير الأطباء', 'slug' => 'doctor_reports', 'category' => 'reports', 'description' => 'عرض تقارير أداء الأطباء'],
            ['name' => 'تقارير المخزون', 'slug' => 'inventory_reports', 'category' => 'reports', 'description' => 'عرض تقارير المخزون'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }
    }
}
