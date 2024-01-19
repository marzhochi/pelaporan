<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Users
    Route::post('users/media', 'UsersApiController@storeMedia')->name('users.storeMedia');
    Route::apiResource('users', 'UsersApiController');

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
