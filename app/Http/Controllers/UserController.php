<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use PMW\Models\HakAkses;
use PMW\Models\Jurusan;
use PMW\Models\Mahasiswa;
use PMW\Models\Prodi;
use PMW\Support\RequestStatus;
use PMW\User;
use PMW\Events\UserTerdaftar;
use PMW\Mail\PasswordResetMail;

/**
 * Controller ini berfungsi untuk melakukan aksi
 * yang berkaitan dengan user
 * 
 * @author BagasMuharom <bagashidayat@mhs.unesa.ac.id|bagashidayat45@gmail.com>
 * @package PMW\Http\Controllers
 */
class UserController extends Controller
{

    /**
     * Melakukan pengeditan profil pengguna
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editProfil(Request $request)
    {
        // array untuk validasi
        $validationArr = [
            'nama' => 'required',
            'alamat_asal' => 'required',
            'alamat_tinggal' => 'required',
            'no_telepon' => 'required|numeric'
        ];

        // jika pengguna adalah mahasiswa, maka
        // mahasiswa perlu memasukkan ipk dan prodi
        if (Auth::user()->isMahasiswa()) {
            $validationArr['ipk'] = 'required|between:0.0,4.0';
            $validationArr['id_prodi'] = 'required|numeric';
        }
        // jika pengguna adalah dosen pembimbing, maka
        // perlu memasukkan prodi
        else if(Auth::user()->isDosenPembimbing()) {
            $validationArr['id_prodi'] = 'required|numeric';
        }

        // melakukan validasi
        $this->validate($request, $validationArr);

        // mengubah prodil
        Auth::user()->update([
            'nama' => $request->nama,
            'id_prodi' => $request->id_prodi,
            'alamat_asal' => $request->alamat_asal,
            'alamat_tinggal' => $request->alamat_tinggal,
            'no_telepon' => $request->no_telepon
        ]);

        // Jika user adalah mahasiswa, maka perlu melakukan update
        // nilai ipknya
        if (Auth::user()->isMahasiswa()) {
            Auth::user()->mahasiswa()->update([
                'ipk' => $request->ipk
            ]);
        }

        Session::flash('message', 'Berhasil mengubah profil !');
        Session::flash('error', false);

        return back();
    }

    /**
     * Melakukan proses penggantian kata sandi
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function gantiPassword(Request $request)
    {
        if (!Hash::check($request->plama, Auth::user()->password)) {
            return back()->withErrors([
                'plama' => 'Password tidak sama dengan password anda saat ini'
            ]);
        } else {
            if ($request->pbaru != $request->pbaru_confirm) {
                return back()->withErrors([
                    'pbaru' => 'Password tidak sama !'
                ]);
            } elseif ($request->pbaru == Auth::user()->password) {
                return back()->withErrors([
                    'pbaru' => 'Password tidak boleh sama dengan yang lama !'
                ]);
            } else {
                $user = User::find(Auth::user()->id);
                $user->password = Hash::make($request->pbaru);
                $user->save();

                Session::flash('message', 'Berhasil mengubah password !');
                return back();
            }
        }
    }

    /**
     * Melakukan proses penambahan pengguna
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tambah(Request $request)
    {
        $password = str_random(8);

        $user = User::create([
            'id' => $request->id,
            'email' => $request->email,
            'password' => bcrypt($password),
            'id_prodi' => (in_array('Admin Fakultas',$request->hakakses))?Prodi::where('id_jurusan','=',Jurusan::where('id_fakultas','=',$request->idfakultas)->first()->id)->first()->id:'',
            'request' => true
        ]);

        event(new UserTerdaftar($user, $request->hakakses, $password));

        return back();
    }

    /**
     * Melakukan pengeditan hak akses
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editHakAkses(Request $request)
    {
        if (is_null($request->hakakses))
            return back();

        foreach ($request->hakakses as $value) {
            if ($value == 'Ketua Tim')
                $value = 'Anggota';
            if (!User::find($request->id)->hasRole($value)) {
                if ($value == 'Anggota' || $value == 'Ketua Tim') {
                    Mahasiswa::create([
                        'id_pengguna' => $request->id
                    ]);
                }
                User::find($request->id)->hakAksesPengguna()->attach(HakAkses::where('nama', $value)->first(), ['status_request' => RequestStatus::APPROVED]);
            }
        }

        return back();
    }

    public function hapus(Request $request)
    {
        try {
            User::find($request->id)->delete();
        } catch (\Exception $e) {
            //
        }

        return back();
    }

    /**
     * Melakukan pencarian mahasiswa oleh mahasiswa lain
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cariMahasiswa(Request $request)
    {
        $nama = $request->nama;

        return User::cariMahasiswaUntukUndanganTim($nama);
    }

    /**
     * Melakukan pencarian calon dosen pembimbing oleh mahasiswa
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cariPembimbing(Request $request)
    {
        $nama = $request->nama;

        return User::cariDosenPembimbing($nama);
    }

    /**
     * Melakukan proses pencarian reviewer dengan kriteria yang
     * sesuai dengan tim atau proposal
     *
     * @param Request $request
     * @return mixed
     */
    public function cariReviewer(Request $request)
    {
        $nama = $request->nama;

        return User::cari('nama', $nama, HakAkses::REVIEWER);
    }

    /**
     * Melakukan proses reset password dengan mengirim password
     * baru ke email pengguna
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:pengguna,email'
        ]);

        $email = $request->email;

        $password = str_random(10);
        $user = User::where('email', $email)->first();
        $user->password = Hash::make($password);
        $user->save();

        Mail::to($email)->send(new PasswordResetMail($user, $password));

        if(Session::has('tab'))
            Session::forget('tab');

        Session::flash('tab', 'reset');
        Session::flash('message', 'Berhasil melakukan reset password. Silahkan cek email');

        return back();
    }

}
