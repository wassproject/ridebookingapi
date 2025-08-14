<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'Mumbai',
            'Delhi',
            'Bangalore',
            'Hyderabad',
            'Chennai',
            'Kolkata',
            'Pune',
            'Ahmedabad',
        ];

        foreach ($cities as $name) {
            City::create(['name' => $name]);
        }
    }
}
