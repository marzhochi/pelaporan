<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'kategori_create',
            ],
            [
                'id'    => 18,
                'title' => 'kategori_edit',
            ],
            [
                'id'    => 19,
                'title' => 'kategori_show',
            ],
            [
                'id'    => 20,
                'title' => 'kategori_delete',
            ],
            [
                'id'    => 21,
                'title' => 'kategori_access',
            ],
            [
                'id'    => 22,
                'title' => 'lokasi_create',
            ],
            [
                'id'    => 23,
                'title' => 'lokasi_edit',
            ],
            [
                'id'    => 24,
                'title' => 'lokasi_show',
            ],
            [
                'id'    => 25,
                'title' => 'lokasi_delete',
            ],
            [
                'id'    => 26,
                'title' => 'lokasi_access',
            ],
            [
                'id'    => 27,
                'title' => 'pengaduan_create',
            ],
            [
                'id'    => 28,
                'title' => 'pengaduan_edit',
            ],
            [
                'id'    => 29,
                'title' => 'pengaduan_show',
            ],
            [
                'id'    => 30,
                'title' => 'pengaduan_delete',
            ],
            [
                'id'    => 31,
                'title' => 'pengaduan_access',
            ],
            [
                'id'    => 32,
                'title' => 'laporan_create',
            ],
            [
                'id'    => 33,
                'title' => 'laporan_edit',
            ],
            [
                'id'    => 34,
                'title' => 'laporan_show',
            ],
            [
                'id'    => 35,
                'title' => 'laporan_delete',
            ],
            [
                'id'    => 36,
                'title' => 'laporan_access',
            ],
            [
                'id'    => 37,
                'title' => 'tugas_create',
            ],
            [
                'id'    => 38,
                'title' => 'tugas_edit',
            ],
            [
                'id'    => 39,
                'title' => 'tugas_show',
            ],
            [
                'id'    => 40,
                'title' => 'tugas_delete',
            ],
            [
                'id'    => 41,
                'title' => 'tugas_access',
            ],
            [
                'id'    => 42,
                'title' => 'master_data_access',
            ],
            [
                'id'    => 43,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}
