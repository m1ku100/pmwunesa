<?php

Route::group(['prefix' => 'daftar'], function (){

    Route::get('pengguna', [
        'uses'  => 'Page\SuperAdminController@tampilDataPengguna',
        'as'    => 'daftar.pengguna'
    ]);

    Route::get('fakultas', [
        'uses'  => 'Page\SuperAdminController@tampilDataFakultas',
        'as'    => 'daftar.fakultas'
    ]);

    Route::get('jurusan', [
        'uses'  => 'Page\SuperAdminController@tampilDataJurusan',
        'as'    => 'daftar.jurusan'
    ]);

    Route::get('prodi', [
        'uses'  => 'Page\SuperAdminController@tampilDataProdi',
        'as'    => 'daftar.prodi'
    ]);

    Route::get('aspek', [
        'uses'  => 'Page\SuperAdminController@tampilDataProposal',
        'as'    => 'daftar.aspek'
    ]);

    Route::get('proposal', [
        'uses'  => 'Page\SuperAdminController@tampilDataProposal',
        'as'    => 'daftar.proposal'
    ]);

});

Route::group(['prefix' => 'permintaan'], function (){

    Route::get('hakakses', [
        'uses'  => 'Page\SuperAdminController@tampilRequestHakAkses',
        'as'    => 'permintaan.hakakses'
    ]);

});

Route::group(['prefix' => 'tambah'], function (){

   Route::put('fakultas',[
       'uses'   => 'FakultasController@tambah',
       'as'     => 'tambah.fakultas'
   ]);

   Route::put('jurusan',[
       'uses'   => 'JurusanController@tambah',
       'as'     => 'tambah.jurusan'
   ]);

   Route::put('prodi',[
       'uses'   => 'ProdiController@tambah',
       'as'     => 'tambah.prodi'
   ]);

   Route::put('pengguna',[
       'uses'   => 'UserController@tambah',
       'as'     => 'tambah.user'
   ]);

    Route::put('aspek',[
        'uses'  => 'AspekController@tambah',
        'as'    => 'tambah.aspek'
    ]);

    Route::put('hakaksespengguna',[
        'uses'  => 'UserController@editHakAkses',
        'as'    => 'tambah.hakaksespengguna'
    ]);

});

Route::group(['prefix' => 'hapus'], function (){
    Route::put('fakultas',[
        'uses'  => 'FakultasController@hapus',
        'as'    => 'hapus.fakultas'
    ]);

    Route::put('jurusan',[
        'uses'  => 'JurusanController@hapus',
        'as'    => 'hapus.jurusan'
    ]);

    Route::put('prodi',[
        'uses' => 'ProdiController@hapus',
        'as'   => 'hapus.prodi'
    ]);

    Route::put('pengguna',[
        'uses' => 'UserController@hapus',
        'as'   => 'hapus.pengguna'
    ]);

    Route::put('aspek',[
        'uses'  => 'AspekController@hapus',
        'as'    => 'hapus.aspek'
    ]);
});

Route::group(['prefix' => 'edit'], function (){

    Route::put('fakultas',[
        'uses'  => 'FakultasController@edit',
        'as'    => 'edit.fakultas'
    ]);

    Route::put('jurusan',[
        'uses'  => 'JurusanController@edit',
        'as'    => 'edit.jurusan'
    ]);

    Route::put('prodi',[
        'uses'  => 'ProdiController@edit',
        'as'    => 'edit.prodi'
    ]);

    Route::put('aspek',[
        'uses'  => 'AspekController@edit',
        'as'    => 'edit.aspek'
    ]);

    Route::put('terimahakakses',[
        'uses'  => 'HakAksesController@terimaRequest',
        'as'    => 'set.terimahakakses'
    ]);

    Route::put('tolakhakakses',[
        'uses'  => 'HakAksesController@tolakRequest',
        'as'    => 'set.tolakhakakses'
    ]);

    Route::get('reviewer/{idproposal}', [
        'uses'  => 'Page\SuperAdminController@editReviewer',
        'as'    => 'edit.reviewer'
    ]);

    Route::patch('reviewer/{idproposal}', [
        'uses'  => 'ReviewerController@kelola',
        'as'    => 'edit.reviewer'
    ]);

});
