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
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Kategori
    Route::delete('kategoris/destroy', 'KategoriController@massDestroy')->name('kategoris.massDestroy');
    Route::resource('kategoris', 'KategoriController');

    // Lokasi
    Route::delete('lokasis/destroy', 'LokasiController@massDestroy')->name('lokasis.massDestroy');
    Route::resource('lokasis', 'LokasiController');

    // Pengaduan
    Route::delete('pengaduans/destroy', 'PengaduanController@massDestroy')->name('pengaduans.massDestroy');
    Route::post('pengaduans/media', 'PengaduanController@storeMedia')->name('pengaduans.storeMedia');
    Route::post('pengaduans/ckmedia', 'PengaduanController@storeCKEditorImages')->name('pengaduans.storeCKEditorImages');
    Route::resource('pengaduans', 'PengaduanController');

    // Laporan
    Route::delete('laporans/destroy', 'LaporanController@massDestroy')->name('laporans.massDestroy');
    Route::post('laporans/media', 'LaporanController@storeMedia')->name('laporans.storeMedia');
    Route::post('laporans/ckmedia', 'LaporanController@storeCKEditorImages')->name('laporans.storeCKEditorImages');
    Route::resource('laporans', 'LaporanController');

    // Tugar
    Route::delete('tugars/destroy', 'TugarController@massDestroy')->name('tugars.massDestroy');
    Route::resource('tugars', 'TugarController');
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
