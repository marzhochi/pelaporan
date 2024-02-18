<?php

Route::post('login', 'Api\\AuthController@login');
Route::get('home', 'Api\\HomeController@home')->name('home');

Route::post('pengaduan/media', 'Api\\HomeController@storeMedia')->name('pengaduan.storeMedia');
Route::get('pengaduan', 'Api\\HomeController@index')->name('pengaduan.list');
Route::get('pengaduan/{id}', 'Api\\HomeController@show')->name('pengaduan.show');
Route::post('pengaduan', 'Api\\HomeController@store')->name('pengaduan.store');
Route::post('pengaduan/{id}', 'HomeController@delete')->name('pengaduan.delete');

Route::get('laporan', 'Api\\HomeController@laporan_list')->name('laporan.list');
Route::get('laporan/{id}', 'Api\\HomeController@laporan_show')->name('laporan.show');

Route::group(['prefix' => 'admin', 'as' => 'api.', 'namespace' => 'Api\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Dashobord
    Route::get('profil', 'DashboardController@index')->name('profil');
    Route::post('profil/update', 'DashboardController@update')->name('profil.update');

    // Petugas
    Route::get('petugas', 'PetugasController@index')->name('petugas.list');
    Route::post('petugas', 'PetugasController@store')->name('petugas.store');
    Route::get('petugas/{id}', 'PetugasController@show')->name('petugas.show');
    Route::post('petugas/update', 'PetugasController@update')->name('petugas.update');
    Route::post('petugas/{id}', 'PetugasController@delete')->name('petugas.delete');

    // Lokasi
    Route::get('lokasi', 'LokasiController@index')->name('lokasi.list');
    Route::post('lokasi', 'LokasiController@store')->name('lokasi.store');
    Route::get('lokasi/{id}', 'LokasiController@show')->name('lokasi.show');
    Route::post('lokasi/update', 'LokasiController@update')->name('lokasi.update');
    Route::post('lokasi/{id}', 'LokasiController@delete')->name('lokasi.delete');

    // Jenis Tugas
    Route::get('jenis', 'JenisController@index')->name('jenis.list');

    // Penugasan
    Route::get('pengaduan', 'PenugasanController@list_pengaduan')->name('pengaduan.list');
    Route::get('penugasan', 'PenugasanController@index')->name('penugasan.list');
    Route::post('penugasan', 'PenugasanController@store')->name('penugasan.store');
    Route::get('penugasan/{id}', 'PenugasanController@show')->name('penugasan.show');
    Route::post('penugasan/update', 'PenugasanController@update')->name('penugasan.update');
    Route::post('penugasan/{id}', 'PenugasanController@delete')->name('penugasan.delete');

    Route::get('penugasan-anggota', 'PenugasanController@penugasan_anggota')->name('penugasan.anggota');
    Route::get('penugasan-riwayat', 'PenugasanController@penugasan_riwayat_anggota')->name('penugasan.riwayat');

    // Tugas
    Route::get('tugas', 'TugasController@index')->name('tugas.list');
    Route::post('tugas', 'TugasController@store')->name('tugas.store');
    Route::get('tugas/{id}', 'TugasController@show')->name('tugas.show');
    Route::post('tugas/update', 'TugasController@update')->name('tugas.update');
    Route::post('tugas/{id}', 'TugasController@delete')->name('tugas.delete');

    Route::get('tugas-anggota', 'TugasController@tugas_anggota')->name('tugas.anggota');
    Route::get('tugas-riwayat', 'TugasController@riwayat_tugas_anggota')->name('tugas.riwayat');

    // Laporan
    Route::get('laporan', 'LaporanController@index')->name('laporan.list');
    Route::post('laporan', 'LaporanController@store')->name('laporan.store');
    Route::post('laporan/penugasan', 'LaporanController@store_penugasan')->name('laporan.store_penugasan');
    Route::post('laporan/tugas', 'LaporanController@store_tugas')->name('laporan.store_tugas');
    Route::get('laporan/{id}', 'LaporanController@show')->name('laporan.show');
    Route::get('laporan/penugasan/{id}', 'LaporanController@penugasan')->name('laporan.penugasan');
    Route::get('laporan/tugas/{id}', 'LaporanController@tugas')->name('laporan.tugas');
    Route::post('laporan/update', 'LaporanController@update')->name('laporan.update');

});
