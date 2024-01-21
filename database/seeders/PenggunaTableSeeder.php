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
                'nama_lengkap'   => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'golongan'       => '',
                'no_telp'        => '',
                'role'           => 1,
            ],
        ];

        Pengguna::insert($pengguna);
    }
}
