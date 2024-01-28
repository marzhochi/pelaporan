<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class PenggunaTableSeeder extends Seeder
{
    public function run()
    {
        $pengguna = [
            [
                'id'             => 1,
                'nama_lengkap'   => 'Kepala Petugas',
                'email'          => 'kepala@gmail.com',
                'password'       => bcrypt('testing'),
                'remember_token' => null,
                'golongan'       => 'Kepala',
                'no_telp'        => '08123456789',
                'nip'            => '123123123',
                'role'           => 1,
            ],
        ];

        Pengguna::insert($pengguna);
    }
}
