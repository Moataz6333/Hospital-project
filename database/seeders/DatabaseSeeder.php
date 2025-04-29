<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Employee;
use App\Models\Eventt;
use Illuminate\Database\Seeder;
use Database\Seeders\DiscountSeeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Plan;
use App\Models\Subscriber;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Youssef',
            'email' => 'youssef@receptionist.com',
            'role' => 'receptionist',
            'password' => Hash::make('password')
        ]);
        Employee::create([
            'user_id' => $user->id,
            'phone' => '0122222',
            'home_phone' => '05050505',
            'birthDate' => now(),
            'salary' => 5000,
            'gender' => 'male',
            'national_id' => '3030303030303',
            'address' => 'momom'
        ]);
        Plan::create([
            "title" => "Basic Care",
            "period" => 30,
            "icon" => "fas fa-stethoscope fa-3x",
            "price" => 750,
            "features" => "Weekly Visit\r\nMonthly Test\r\nFree Labs"
        ],[
            "title" => "Advanced Follow-up",
            "period" => 30,
            "icon" => "fas fa-hospital-user fa-3x",
            "price" => 900,
            "features" => "Weekly Visit\r\nMonthly Test\r\nDiscounts"
        ],[
            "title" => "Regular Monitoring",
            "period" => 30,
            "icon" => "fas fa-notes-medical fa-3x",
            "price" => 400,
            "features" => "Weekly Visit\r\nMonthly Test\r\nFree Labs"
        ]);
        Eventt::create([
            "title"=>"the main event",
            "description"=>"the description of the event",
            "date"=>date_create("next week"),
        ]);
        

        // $this->call([
        //     DiscountSeeder::class,
        // ]);
    }
}
