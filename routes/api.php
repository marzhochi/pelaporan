<?php

Route::post('login', 'Api\\AuthController@login');
Route::post('pengaduan/media', 'Api\\HomeController@storeMedia')->name('pengaduan.storeMedia');
Route::apiResource('pengaduan', 'Api\\HomeController');
// Route::get('pengaduan/index', 'Api\\HomeController@index');
// Route::get('pengaduan/show/{id}', 'Api\\HomeController@show');
// Route::post('pengaduan/store', 'Api\\HomeController@store');
// Route::post('pengaduan/media', 'Api\\HomeController@storeMedia')->name('pengaduan.storeMedia');
// Route::apiResource('home', 'Api\\HomeController@index');

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
