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
                'nip'            => 111111,
                'nama_lengkap'   => 'Asep Kuswara, A.ma, PKB, ST, MT',
                'email'          => 'kepala@gmail.com',
                'password'       => bcrypt('testing'),
                'remember_token' => null,
                'golongan'       => 'ASN',
                'jenis_kelamin'  => 'Laki-Laki',
                'no_telp'        => '081111111111',
                'role'           => 1,
                'lokasi_id'      => 1,
            ],
            [
                'id'             => 2,
                'nip'            => 222222,
                'nama_lengkap'   => 'Valdy Dwi N',
                'email'          => 'anggota1@gmail.com',
                'password'       => bcrypt('testing'),
                'remember_token' => null,
                'golongan'       => 'Non ASN',
                'jenis_kelamin'  => 'Laki-Laki',
                'no_telp'        => '082222222222',
                'role'           => 2,
                'lokasi_id'      => 1,
            ],
            [
                'id'             => 3,
                'nip'            => 333333,
                'nama_lengkap'   => 'Arnhol Prakoso',
                'email'          => 'anggota2@gmail.com',
                'password'       => bcrypt('testing'),
                'remember_token' => null,
                'golongan'       => 'Non ASN',
                'jenis_kelamin'  => 'Laki-Laki',
                'no_telp'        => '083333333333',
                'role'           => 2,
                'lokasi_id'      => 1,
            ],
            [
                'id'             => 4,
                'nip'            => 444444,
                'nama_lengkap'   => 'Luthfi Lukman',
                'email'          => 'anggota3@gmail.com',
                'password'       => bcrypt('testing'),
                'remember_token' => null,
                'golongan'       => 'Non ASN',
                'jenis_kelamin'  => 'Laki-Laki',
                'no_telp'        => '084444444444',
                'role'           => 2,
                'lokasi_id'      => 1,
            ],
        ];

        Petugas::insert($petugas);
    }
}
