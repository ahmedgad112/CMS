<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $defaults = [
            'organization_display_name' => config('app.name'),
            'public_online_booking_enabled' => '1',
            'public_portal_disabled_notice' => 'حجز المواعيد عبر الموقع غير متاح حالياً. يرجى الاتصال بالعيادة أو الحضور للاستقبال.',
        ];

        foreach ($defaults as $key => $value) {
            if (! DB::table('platform_settings')->where('key', $key)->exists()) {
                DB::table('platform_settings')->insert([
                    'key' => $key,
                    'value' => $value,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('platform_settings')->whereIn('key', [
            'organization_display_name',
            'public_online_booking_enabled',
            'public_portal_disabled_notice',
        ])->delete();
    }
};
