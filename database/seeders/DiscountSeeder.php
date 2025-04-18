<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Discount::create([
            'name'=>'5Appointments',
            'description'=>'when patient has 5 appointments with same doctor',
            'discount'=>0.15
        ],[
            'name'=>'10Appointments',
            'description'=>'when patient has 10 appointments with same doctor',
            'discount'=>0.25
        ],[
            'name'=>'donator',
            'description'=>'when a patient is a donator',
            'discount'=>0.30
        ]);
    }
}
