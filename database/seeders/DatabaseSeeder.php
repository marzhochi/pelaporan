<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            LokasiTableSeeder::class,
            JenisTugasTableSeeder::class,
            PetugasTableSeeder::class,
        ]);
    }
}
