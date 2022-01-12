<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class adsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ad_posts')->insert([
            'posted_by' => 'farhankhan',
            'brand' => 'MG',
            'name' => 'HS',
            'variant' => '1.5 turbo',
            'engine_size' => '1500',
            'model' => '2021',
            'assembly' => 'Local',
            'color' => 'black',
            'fuel_type' => 'Petrol',
            'transmission' => 'Automatic',
            'milage' => '2500',
            'registration_city' => 'lahore',
            'contact' => '03124578963',
            'location' => 'multan',
            'Price' => '5150000',
            'discription' => 'first owner lady doctor used lush condition',
            'title_image' => '',
        ]);
    }
}
