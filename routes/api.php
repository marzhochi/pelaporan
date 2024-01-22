<?php

Route::post('login', 'Api\\AuthController@login');
Route::post('pengaduan/media', 'Api\\HomeController@storeMedia')->name('pengaduan.storeMedia');
Route::apiResource('pengaduan', 'Api\\HomeController');

Route::group(['prefix' => 'kepala', 'as' => 'api.', 'namespace' => 'Api\Kepala', 'middleware' => ['auth:sanctum']], function () {
    // Dashobord
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('jenis', 'DashboardController@jenis')->name('jenis');
    Route::get('lokasi', 'DashboardController@lokasi')->name('lokasi');
    Route::get('petugas', 'DashboardController@petugas')->name('petugas');
    // // Pengguna
    // Route::post('pengguna/media', 'PenggunaApiController@storeMedia')->name('pengguna.storeMedia');
    // Route::apiResource('pengguna', 'PenggunaApiController');

    // // Kategori
    // Route::apiResource('kategori', 'KategoriApiController');

    // // Lokasi
    // Route::apiResource('lokasi', 'LokasiApiController');

    // // Pengaduan
    // Route::post('pengaduan/media', 'PengaduanApiController@storeMedia')->name('pengaduan.storeMedia');
    // Route::apiResource('pengaduan', 'PengaduanApiController');

    // // Laporan
    // Route::post('laporan/media', 'LaporanApiController@storeMedia')->name('laporan.storeMedia');
    // Route::apiResource('laporan', 'LaporanApiController');

    // // Tugas
    // Route::apiResource('tugas', 'TugasApiController');
});

Route::group(['prefix' => 'anggota', 'as' => 'api.', 'namespace' => 'Api\Anggota', 'middleware' => ['auth:sanctum']], function () {
    // Dashobord
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
});
