<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run()
{
    DB::table('sections')->insert([
        ['name' => 'products', 'is_visible' => true],
        ['name' => 'projects', 'is_visible' => true],
        ['name' => 'team', 'is_visible' => true],
        ['name' => 'solutions', 'is_visible' => true],
        ['name' => 'testimonials', 'is_visible' => true],
        ['name' => 'about', 'is_visible' => true],
        ['name' => 'contact', 'is_visible' => true],
    ]);
}

}
