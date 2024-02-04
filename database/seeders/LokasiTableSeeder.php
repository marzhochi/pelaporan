<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiTableSeeder extends Seeder
{
    public function run()
    {
        $lokasi = [
            [
                'id'            => 1,
                'nama_jalan'    => 'Jalan Dipatiukur',
                'kelurahan'     => 'Lebak Gede',
                'kecamatan'     => 'Coblong',
                'latitude'      => '-6.886933',
                'longitude'     => '107.615333',
            ],
        ];

        Lokasi::insert($lokasi);
    }
}
