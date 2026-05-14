<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;

class PlatformSettingsController extends Controller
{
    public function edit()
    {
        $sections = config('platform.sections', []);
        $values = $this->currentValues($sections);

        return view('admin.settings.edit', compact('sections', 'values'));
    }

    public function update(Request $request)
    {
        $sections = config('platform.sections', []);
        $rules = $this->buildValidationRules($sections);
        $request->validate($rules, [
            'organization_display_name.required' => 'اسم المنشأة مطلوب.',
        ]);

        foreach ($sections as $section) {
            foreach ($section['settings'] as $key => $meta) {
                $type = $meta['type'];
                if ($type === 'boolean') {
                    PlatformSetting::setValue($key, $request->boolean($key) ? '1' : '0');

                    continue;
                }
                if ($type === 'text') {
                    PlatformSetting::setValue($key, (string) $request->input($key, ''));

                    continue;
                }
                PlatformSetting::setValue($key, trim((string) $request->input($key, '')));
            }
        }

        return redirect()->route('admin.settings.edit')
            ->with('success', 'تم حفظ إعدادات المنصة.');
    }

    /**
     * @param  array<string, mixed>  $sections
     * @return array<string, mixed>
     */
    private function currentValues(array $sections): array
    {
        $appName = config('app.name');
        $values = [];

        foreach ($sections as $section) {
            foreach ($section['settings'] as $key => $meta) {
                $type = $meta['type'];
                if ($type === 'boolean') {
                    $values[$key] = PlatformSetting::getBool($key, (bool) ($meta['default'] ?? false));

                    continue;
                }
                if ($type === 'text') {
                    $values[$key] = PlatformSetting::getValue($key) ?? ($meta['default'] ?? '');

                    continue;
                }
                $values[$key] = PlatformSetting::getValue($key) ?? $appName;
            }
        }

        return $values;
    }

    /**
     * @param  array<string, mixed>  $sections
     * @return array<string, mixed>
     */
    private function buildValidationRules(array $sections): array
    {
        $rules = [];

        foreach ($sections as $section) {
            foreach ($section['settings'] as $key => $meta) {
                $type = $meta['type'];
                if ($type === 'boolean') {
                    $rules[$key] = 'nullable';
                } elseif ($type === 'text') {
                    $rules[$key] = 'nullable|string|max:5000';
                } else {
                    $rules[$key] = 'required|string|max:200';
                }
            }
        }

        return $rules;
    }
}
