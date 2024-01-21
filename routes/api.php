<?php

Route::post('login', 'Api\\AuthController@login');
Route::get('home', 'Api\\HomeController@index');

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Dashobord
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    // Pengguna
    Route::post('pengguna/media', 'PenggunaApiController@storeMedia')->name('pengguna.storeMedia');
    Route::apiResource('pengguna', 'PenggunaApiController');

    // Kategori
    Route::apiResource('kategori', 'KategoriApiController');

    // Lokasi
    Route::apiResource('lokasi', 'LokasiApiController');

    // Pengaduan
    Route::post('pengaduan/media', 'PengaduanApiController@storeMedia')->name('pengaduan.storeMedia');
    Route::apiResource('pengaduan', 'PengaduanApiController');

    // Laporan
    Route::post('laporan/media', 'LaporanApiController@storeMedia')->name('laporan.storeMedia');
    Route::apiResource('laporan', 'LaporanApiController');

    // Tugas
    Route::apiResource('tugas', 'TugasApiController');
});
