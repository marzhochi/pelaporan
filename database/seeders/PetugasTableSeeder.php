<?php

namespace Database\Seeders;

use App\Models\Petugas;
use Illuminate\Database\Seeder;

class PetugasTableSeeder extends Seeder
{
    public function run()
    {
        $petugas = [
            [
                'id'             => 1,
                'nip'            => 123456,
                'nama_lengkap'   => 'Kepala Petugas',
                'email'          => 'kepala@gmail.com',
                'password'       => bcrypt('testing'),
                'remember_token' => null,
                'golongan'       => 'Kepala',
                'no_telp'        => '08123456789',
                'role'           => 1,
                'lokasi_id'      => 1,
            ],
        ];

        Petugas::insert($petugas);
    }
}
