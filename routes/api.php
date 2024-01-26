<?php

Route::post('login', 'Api\\AuthController@login');
Route::post('pengaduan/media', 'Api\\HomeController@storeMedia')->name('pengaduan.storeMedia');
Route::apiResource('pengaduan', 'Api\\HomeController');

Route::group(['prefix' => 'kepala', 'as' => 'api.', 'namespace' => 'Api\Kepala', 'middleware' => ['auth:sanctum']], function () {
    // Dashobord
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('list-jenis', 'DashboardController@list_jenis')->name('jenis');
    Route::get('list-lokasi', 'DashboardController@list_lokasi')->name('lokasi');
    Route::get('list-petugas', 'DashboardController@list_petugas')->name('petugas');
    Route::get('list-pengaduan', 'DashboardController@list_pengaduan')->name('pengaduan');

    Route::post('penugasan', 'DashboardController@penugasan')->name('penugasan');
    Route::get('data-penugasan', 'DashboardController@data_penugasan')->name('data.penugasan');
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

    Route::get('data-tugas', 'DashboardController@data_tugas')->name('data.tugas');
    Route::get('detail-tugas/{id}', 'DashboardController@tugas_detail')->name('detail.tugas');

});

Route::group(['prefix' => 'anggota', 'as' => 'api.', 'namespace' => 'Api\Anggota', 'middleware' => ['auth:sanctum']], function () {
    // Dashobord
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
});
