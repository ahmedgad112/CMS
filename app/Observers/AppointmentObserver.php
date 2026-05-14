<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Models\PlatformSetting;
use App\Services\AppointmentInvoiceFactory;

class AppointmentObserver
{
    public function updated(Appointment $appointment): void
    {
        if (! $appointment->wasChanged('status')) {
            return;
        }

        if ($appointment->status !== 'confirmed') {
            return;
        }

        if (! PlatformSetting::getBool('auto_invoice_on_appointment_confirm', true)) {
            return;
        }

        AppointmentInvoiceFactory::ensureUnpaidInvoiceForAppointment($appointment);
    }
}
