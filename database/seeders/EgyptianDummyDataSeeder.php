<?php

namespace Database\Seeders;

use App\Models\AppointmentRequest;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;

class EgyptianDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('role', '=', 'admin')->first();
        $doctor = User::query()->where('role', '=', 'doctor')->first();

        $department = Department::firstOrCreate(
            ['name' => 'الأمراض الباطنة والقلب'],
            [
                'name_en' => 'Internal Medicine & Cardiology',
                'description' => 'بيانات تجريبية للتطوير',
                'is_active' => true,
            ]
        );

        $specialization = Specialization::firstOrCreate(
            ['name' => 'أمراض القلب والأوعية'],
            [
                'department_id' => $department->id,
                'name_en' => 'Cardiology',
                'description' => null,
                'is_active' => true,
            ]
        );

        $clinic = Clinic::firstOrCreate(
            ['name' => 'مركز الدلتا الطبي — فرع المنصورة'],
            [
                'name_en' => 'Delta Medical Center — Mansoura',
                'phone' => '0502234567',
                'email' => 'info@delta-medical-dummy.test',
                'address' => '٤٥ شارع الجمهورية، بجوار جامعة المنصورة، محافظة الدقهلية',
                'city' => 'المنصورة',
                'description' => 'فرع تجريبي لملء واجهات النظام أثناء التطوير',
                'working_hours' => 'من ١٠ صباحاً إلى ٨ مساءً (يومياً عدا الجمعة)',
                'is_main' => true,
                'is_active' => true,
            ]
        );

        if ($doctor) {
            if (! $doctor->specialization_id) {
                $doctor->update(['specialization_id' => $specialization->id]);
            }
            if (! $doctor->clinic_id) {
                $doctor->update(['clinic_id' => $clinic->id]);
            }
            if (! $doctor->clinics()->where('clinic_id', $clinic->id)->exists()) {
                $doctor->clinics()->attach($clinic->id);
            }
        }

        $rows = [
            [
                'full_name' => 'محمود السيد إبراهيم',
                'national_id' => '29501150102341',
                'phone_number' => '01001122334',
                'gender' => 'male',
                'age' => 45,
                'address' => '١٢ شارع مصطفى النحاس، مدينة نصر، القاهرة',
                'medical_history' => 'متابعة ضغط دم منذ ٢٠٢٢',
                'chronic_diseases' => 'ارتفاع ضغط الدم',
            ],
            [
                'full_name' => 'أميرة كمال فؤاد',
                'national_id' => '29803080203452',
                'phone_number' => '01002233445',
                'gender' => 'female',
                'age' => 32,
                'address' => '٦ شارع الهرم، المريلاند، الجيزة',
                'medical_history' => null,
                'chronic_diseases' => null,
            ],
            [
                'full_name' => 'حسام الدين محمد عمر',
                'national_id' => '28307050104563',
                'phone_number' => '01003344556',
                'gender' => 'male',
                'age' => 52,
                'address' => 'برج النخيل، شارع التسعين، التجمع الخامس',
                'medical_history' => 'سكري نمط ٢',
                'chronic_diseases' => 'سكري من النمط الثاني',
            ],
            [
                'full_name' => 'سلمى أحمد عبدالرحمن',
                'national_id' => '29711010305674',
                'phone_number' => '01004455667',
                'gender' => 'female',
                'age' => 29,
                'address' => '٣٩ شارع جمال عبدالناصر، وسط البلد، المنصورة',
                'medical_history' => null,
                'chronic_diseases' => 'آلام مزمنة في الركبة',
            ],
            [
                'full_name' => 'عبدالله ياسر محمود',
                'national_id' => '30002040106785',
                'phone_number' => '01005566778',
                'gender' => 'male',
                'age' => 26,
                'address' => 'حي الجامعة، طنطا، الغربية',
                'medical_history' => null,
                'chronic_diseases' => null,
            ],
            [
                'full_name' => 'نهى سامي إبراهيم',
                'national_id' => '29109150207896',
                'phone_number' => '01006677889',
                'gender' => 'female',
                'age' => 48,
                'address' => '١٧ شارع الثورة، سموحة، الإسكندرية',
                'medical_history' => 'أنيميا خفيفة',
                'chronic_diseases' => null,
            ],
            [
                'full_name' => 'كريم فتحي عبداللطيف',
                'national_id' => '28905060208901',
                'phone_number' => '01007788990',
                'gender' => 'male',
                'age' => 37,
                'address' => '٥ شارع ٢٦ يوليو، دمنهور، البحيرة',
                'medical_history' => null,
                'chronic_diseases' => 'ربو تحسسي',
            ],
            [
                'full_name' => 'مروة طارق حلمي',
                'national_id' => '29606180309012',
                'phone_number' => '01008899001',
                'gender' => 'female',
                'age' => 30,
                'address' => 'حي أول المحلة الكبرى، الغربية',
                'medical_history' => 'حمل مستقر — الأسبوع ٢٤',
                'chronic_diseases' => null,
            ],
            [
                'full_name' => 'يوسف عادل مصطفى',
                'national_id' => '30512100400123',
                'phone_number' => '01009900112',
                'gender' => 'male',
                'age' => 21,
                'address' => 'شبرا الخيمة، القليوبية',
                'medical_history' => null,
                'chronic_diseases' => null,
            ],
            [
                'full_name' => 'دينا محمد شعبان',
                'national_id' => '29304120501234',
                'phone_number' => '01010011223',
                'gender' => 'female',
                'age' => 41,
                'address' => '٨ شارع النصر، العباسية',
                'medical_history' => null,
                'chronic_diseases' => 'فرط شحوم بالدم',
            ],
        ];

        $patients = [];
        foreach ($rows as $row) {
            $patients[] = Patient::updateOrCreate(
                ['phone_number' => $row['phone_number']],
                [
                    'full_name' => $row['full_name'],
                    'national_id' => $row['national_id'],
                    'gender' => $row['gender'],
                    'age' => $row['age'],
                    'address' => $row['address'],
                    'medical_history' => $row['medical_history'],
                    'chronic_diseases' => $row['chronic_diseases'],
                    'created_by' => $admin?->id,
                    'clinic_id' => $clinic->id,
                ]
            );
        }

        if (! AppointmentRequest::query()->where('notes', '=', 'طلب تجريبي — مريض مسجل')->exists()) {
            AppointmentRequest::create([
                'patient_id' => $patients[0]->id,
                'guest_payload' => null,
                'service_type' => 'checkup',
                'specialization_id' => $specialization->id,
                'preferred_doctor_id' => $doctor?->id,
                'preferred_clinic_id' => $clinic->id,
                'status' => 'pending',
                'notes' => 'طلب تجريبي — مريض مسجل، يفضل قبل الظهر',
            ]);
        }

        if (! AppointmentRequest::query()->where('notes', '=', 'طلب تجريبي — زائر')->exists()) {
            AppointmentRequest::create([
                'patient_id' => null,
                'guest_payload' => [
                    'full_name' => 'فاطمة حسن عبدالله',
                    'phone_number' => '01012349876',
                    'gender' => 'female',
                    'age' => 28,
                    'medical_history' => 'لا توجد عمليات سابقة',
                    'chronic_diseases' => null,
                ],
                'service_type' => 'consultation',
                'specialization_id' => $specialization->id,
                'preferred_doctor_id' => $doctor?->id,
                'preferred_clinic_id' => $clinic->id,
                'status' => 'pending',
                'notes' => 'طلب تجريبي — زائر',
            ]);
        }
    }
}
