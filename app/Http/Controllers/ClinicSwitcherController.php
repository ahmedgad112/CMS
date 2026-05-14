<?php

namespace App\Http\Controllers;

use App\Support\ClinicContext;
use Illuminate\Http\Request;

class ClinicSwitcherController extends Controller
{
    public function switch(Request $request)
    {
        if (! ClinicContext::canSwitch()) {
            abort(403, 'لا تملك صلاحية للتنقل بين الفروع.');
        }

        $request->validate([
            'clinic_id' => 'nullable|exists:clinics,id',
        ]);

        $clinicId = $request->input('clinic_id');
        ClinicContext::setCurrent($clinicId ? (int) $clinicId : null);

        return redirect()->back()->with('success', $clinicId
            ? 'تم التحويل إلى فرع: '.\App\Models\Clinic::find($clinicId)?->name
            : 'تم عرض كل الفروع.');
    }
}
