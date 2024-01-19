<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Users
    Route::post('users/media', 'UsersApiController@storeMedia')->name('users.storeMedia');
    Route::apiResource('users', 'UsersApiController');

    // Kategori
    Route::apiResource('kategoris', 'KategoriApiController');

    // Lokasi
    Route::apiResource('lokasis', 'LokasiApiController');

    // Pengaduan
    Route::post('pengaduans/media', 'PengaduanApiController@storeMedia')->name('pengaduans.storeMedia');
    Route::apiResource('pengaduans', 'PengaduanApiController');

    // Laporan
    Route::post('laporans/media', 'LaporanApiController@storeMedia')->name('laporans.storeMedia');
    Route::apiResource('laporans', 'LaporanApiController');

    // Tugar
    Route::apiResource('tugars', 'TugarApiController');
});
