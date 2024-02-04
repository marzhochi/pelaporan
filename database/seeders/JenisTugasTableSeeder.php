<?php

namespace Database\Seeders;

use App\Models\Jenis;
use Illuminate\Database\Seeder;

class JenisTugasTableSeeder extends Seeder
{
    public function run()
    {
        $jenis = [
            [
                'id'            => 1,
                'nama_jenis'    => 'Sistem Ganjil Genap',
            ],
            [
                'id'            => 2,
                'nama_jenis'    => 'Rekayasa Lalu Lintas',
            ],
            [
                'id'            => 3,
                'nama_jenis'    => 'Penutupan Jalan',
            ],
            [
                'id'            => 4,
                'nama_jenis'    => 'Pengaturan dan Pengawasan Lalu Lintas',
            ],
        ];

        Jenis::insert($jenis);
    }
}
