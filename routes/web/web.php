<<<<<<< HEAD
<?php

/**
 * Ketika membuka halaman awal,
 * maka user akan diarahkan ke halaman dashboard
 */

Route::get('/',function(){
    return redirect()->route('dashboard');
});

Route::group(['middleware' => 'auth'] ,function(){

    Route::group(['middleware' => 'profil'], function(){

        /**
         * Jika user mmebuka halaman dashboard, maka akan
         * dilakukan pengecekan apakah user sedang login atau belum.
         * Jika belum, maka akan diarahkan ke halaman login.
         */
        Route::get('dashboard',function(){
            return view('dashboard');
        })->name('dashboard');

        Route::group(['prefix' => 'cari'],function(){
            Route::post('carimahasiswa',[
                'uses' => 'PencarianController@cariMahasiswa',
                'as' => 'cari.mahasiswa'
            ]);
        });

    });

    Route::get('pengaturan',function(){
         return view('pengaturan');
    })->name('pengaturan');

    Route::group(['prefix' => 'ubah'],function(){

        Route::get('password',function(){
            return view('ubahpassword');
        })->name('ubah.password');

        Route::post('profil',[
            'uses' => 'UserController@editProfil',
            'as' => 'ubah.profil'
        ]);
        
    });

});

/*
|-----
| Autentikasi bawaan dari Laravel
|-----
*/
Auth::routes();
=======
<?php

/**
 * Ketika membuka halaman awal,
 * maka user akan diarahkan ke halaman dashboard
 */

Route::get('/',function(){
    return redirect()->route('dashboard');
});

Route::group(['middleware' => 'auth'] ,function(){

    Route::group(['middleware' => 'profil'], function(){

        /**
         * Jika user mmebuka halaman dashboard, maka akan
         * dilakukan pengecekan apakah user sedang login atau belum.
         * Jika belum, maka akan diarahkan ke halaman login.
         */
        Route::get('dashboard',function(){
            return view('dashboard');
        })->name('dashboard');

        Route::group(['prefix' => 'cari'],function(){
//            Route::post('carimahasiswa',[
//                'uses' => '',
//                'as' => 'cari.mahasiswa'
//            ]);
        });

    });

    Route::get('pengaturan',function(){
         return view('pengaturan');
    })->name('pengaturan');

    Route::group(['prefix' => 'ubah'],function(){

        Route::get('password',function(){
            return view('ubahpassword');
        })->name('ubah.password');

        Route::post('profil',[
            'uses' => 'UserController@editProfil',
            'as' => 'ubah.profil'
        ]);
        
    });

    Route::get('role',function(){
        return Illuminate\Support\Facades\Auth::user()->hakAkses()->where('nama','Ketua Tim')->count();
    });

});

/*
|-----
| Autentikasi bawaan dari Laravel
|-----
*/
Auth::routes();
>>>>>>> 2f0dc69406361a8d1f3308eb18f341117f5e92c2
