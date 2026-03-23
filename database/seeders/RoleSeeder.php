<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'مدير', 'slug' => 'admin', 'description' => 'مدير النظام - لديه جميع الصلاحيات', 'is_system' => true],
            ['name' => 'طبيب', 'slug' => 'doctor', 'description' => 'طبيب - يمكنه إدارة المواعيد والوصفات', 'is_system' => true],
            ['name' => 'موظف استقبال', 'slug' => 'receptionist', 'description' => 'موظف استقبال - يمكنه إدارة المرضى والمواعيد', 'is_system' => true],
            ['name' => 'مركز اتصال', 'slug' => 'call_center', 'description' => 'مركز اتصال - يمكنه إدارة المرضى والمواعيد', 'is_system' => true],
            ['name' => 'محاسب', 'slug' => 'accountant', 'description' => 'محاسب - يمكنه إدارة الفواتير والمدفوعات', 'is_system' => true],
            ['name' => 'مخزن', 'slug' => 'storekeeper', 'description' => 'مخزن - يمكنه إدارة المخزون', 'is_system' => true],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
