<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    // Users
    // Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    // Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    // Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    // Route::resource('users', 'UsersController');

    // Pengguna
    Route::delete('pengguna/destroy', 'PenggunaController@massDestroy')->name('pengguna.massDestroy');
    Route::post('pengguna/media', 'PenggunaController@storeMedia')->name('pengguna.storeMedia');
    Route::post('pengguna/ckmedia', 'PenggunaController@storeCKEditorImages')->name('pengguna.storeCKEditorImages');
    Route::resource('pengguna', 'PenggunaController');

    // Pengguna
    // Route::get('pengguna/list', 'PenggunaController@index')->name('pengguna.index');
    // Route::get('pengguna/create', 'PenggunaController@create')->name('pengguna.create');
    // Route::delete('pengguna/destroy', 'PenggunaController@massDestroy')->name('pengguna.massDestroy');
    // Route::post('pengguna/store', 'PenggunaController@store')->name('pengguna.store');
    // Route::get('pengguna/edit/{id}', 'PenggunaController@edit')->name('pengguna.edit');
    // Route::get('pengguna/show/{id}', 'PenggunaController@show')->name('pengguna.show');
    // Route::delete('pengguna/delete/{id}', 'PenggunaController@destroy')->name('pengguna.destroy');

    // Route::post('pengguna/media', 'PenggunaController@storeMedia')->name('pengguna.storeMedia');
    // Route::post('pengguna/update/{id}', 'PenggunaController@update')->name('pengguna.update');
    // Route::post('pengguna/ckmedia', 'PenggunaController@storeCKEditorImages')->name('pengguna.storeCKEditorImages');

    // Jenis
    Route::delete('jenis/destroy', 'JenisController@massDestroy')->name('jenis.massDestroy');
    Route::resource('jenis', 'JenisController');

    // Lokasi
    Route::delete('lokasi/destroy', 'LokasiController@massDestroy')->name('lokasi.massDestroy');
    Route::resource('lokasi', 'LokasiController');

    // Pengaduan
    Route::delete('pengaduan/destroy', 'PengaduanController@massDestroy')->name('pengaduan.massDestroy');
    Route::post('pengaduan/media', 'PengaduanController@storeMedia')->name('pengaduan.storeMedia');
    Route::post('pengaduan/ckmedia', 'PengaduanController@storeCKEditorImages')->name('pengaduan.storeCKEditorImages');
    Route::resource('pengaduan', 'PengaduanController');

    // Laporan
    Route::delete('laporan/destroy', 'LaporanController@massDestroy')->name('laporan.massDestroy');
    Route::post('laporan/media', 'LaporanController@storeMedia')->name('laporan.storeMedia');
    Route::post('laporan/ckmedia', 'LaporanController@storeCKEditorImages')->name('laporan.storeCKEditorImages');
    Route::resource('laporan', 'LaporanController');

    // Tugas
    Route::delete('tugas/destroy', 'TugasController@massDestroy')->name('tugas.massDestroy');
    Route::resource('tugas', 'TugasController');
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
