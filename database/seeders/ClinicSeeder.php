<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Clinic;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinics = [
            "Internal Medicine Clinic - عيادة الباطنة",
            "Dentistry Clinic - عيادة الأسنان",
            "Pediatrics Clinic - عيادة الأطفال",
            "Dermatology Clinic - عيادة الجلدية",
            "Ophthalmology Clinic - عيادة العيون",
            "Orthopedic Clinic - عيادة العظام",
            "Cardiology Clinic - عيادة القلب",
            "Neurology Clinic - عيادة المخ والأعصاب",
            "Psychiatry Clinic - عيادة الطب النفسي",
            "ENT Clinic - عيادة الأنف والأذن والحنجرة",
            "Urology Clinic - عيادة المسالك البولية",
            "Gynecology and Obstetrics Clinic - عيادة النساء والتوليد",
            "Endocrinology Clinic - عيادة الغدد الصماء",
            "Gastroenterology Clinic - عيادة الجهاز الهضمي",
            "Pulmonology Clinic - عيادة الصدرية (الرئة)",
            "Nephrology Clinic - عيادة أمراض الكلى",
            "Rheumatology Clinic - عيادة أمراض الروماتيزم",
            "Oncology Clinic - عيادة الأورام",
            "Hematology Clinic - عيادة أمراض الدم",
            "Allergy and Immunology Clinic - عيادة الحساسية والمناعة",
            "Plastic Surgery Clinic - عيادة التجميل",
            "General Surgery Clinic - عيادة الجراحة العامة",
            "Physical Therapy and Rehabilitation Clinic - عيادة العلاج الطبيعي والتأهيل"
        ];
        

        // Assign clinics to random floors (1 to 4)
        foreach ($clinics as $clinic) {
            Clinic::create([
                'name' => $clinic,
                'place' => "Floor " . rand(1, 4), // Random floor assignment
            ]);
        }
    }
}
