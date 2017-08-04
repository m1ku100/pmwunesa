<?php

namespace PMW\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PMW\User;
use PMW\Models\UndanganTim;
use PMW\Models\Proposal;
use PMW\Models\HakAkses;

class UndanganTimController extends Controller
{

    /**
     * Membuat undangan untuk mahasiswa lain agar bisa
     * bergabung dalam tim
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buatUndangan(Request $request)
    {
        $dari = Auth::user()->id;
        $untuk = $request->untuk;
        
        if(User::findOrFail($untuk) && $dari != $untuk)
        {
            if(is_null(Auth::user()->mahasiswa()->id_tim))
            {
                Auth::user()->mahasiswa()->undanganTimKetua()->attach(User::find($untuk)->mahasiswa());
                return response()->json([
                    'message' => 'Berhasil mengirim undangan !',
                    'error' => 0
                ]);
            }
            else
            {
                return response()->json([
                    'message' => 'Anda tidak bisa mengirim undangan !',
                    'error' => 1
                ]);
            }
        }
        return response()->json([
            'message' => 'Gagal mengirim undangan !',
            'error' => 2
        ]);
    }

    /**
     * Menerima undangan untuk bergabung dalam tim
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function terimaUndangan(Request $request)
    {
        $dari = User::find($request->dari);
        $untuk = Auth::user();

        $undangan = $untuk->mahasiswa()->undanganTimAnggota();

        // Memastikan bahwa user tidak sedang mengirim undangan dan
        // pengirim undangan bukan merupakan anggota, serta user
        // belum memiliki tim
        if($untuk->mahasiswa()->undanganTimKetua()->count() == 0 &&
            is_null($untuk->mahasiswa()->id_proposal))
        {
            // Terima undangan
            
            if(!is_null($dari->mahasiswa()->id_proposal))
            {
                $proposal = $dari->mahasiswa()->proposal();
            }
            else
            {
                $proposal = new Proposal();
                $proposal->lolos = false;
                $proposal->save();
            }
            
            // Mengupdate proposal dari pengirim undangan
            $dari->mahasiswa()->update([
                'id_proposal' => $proposal->id
            ]);

            if($dari->hasRole(User::ANGGOTA)){
                // Menghapus hak akses sebagai anggota dari pengirim undangan
                $dari->hakAksesPengguna()->detach(HakAkses::where('nama', User::ANGGOTA)->first());
                // menjadikan pengirim undangan sebagai ketua
                $dari->hakAksesPengguna()->attach(HakAkses::where('nama', User::KETUA_TIM)->first(),['status_request' => 'Approved']);
            }

            // Menambahkan id proposal pada user
            $untuk->mahasiswa()->update([
                'id_proposal' => $proposal->id
            ]);

            $dari->mahasiswa()->undanganTimKetua()->detach($untuk->mahasiswa());

            return response()->json([
                'message' => 'Anda berhasil bergabung dalam tim ' . $dari->nama,
                'error' => 0
            ]);
        }
        else
        {
            return response()->json([
                'message' => 'Anda tidak bisa menerima undangan ini !',
                'error' => 1
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tolakUndangan(Request $request)
    {
        $dari = User::find($request->dari);
        $untuk = Auth::user();

        $dari->mahasiswa()->undanganTimKetua()->where('id_anggota',$untuk->id)->first()->update([
            'ditolak' => true
        ]);
        return response()->json([
            'message' => 'Undangan telah ditolak !',
            'error' => 0
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hapusUndangan(Request $request)
    {
        $untuk = $request->id_pengguna;

        Auth::user()->mahasiswa()->undanganTimKetua()->detach(User::find($untuk)->mahasiswa());

        return back();
    }

}
