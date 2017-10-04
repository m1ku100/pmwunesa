<?php

namespace PMW\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PMW\Models\HakAkses;
use PMW\Support\RequestStatus;

class DashboardController extends Controller
{

    public function index()
    {
        if(Auth::user()->isSuperAdmin())
            return $this->superAdmin();
        else if(Auth::user()->isAdminUniversitas())
            return $this->adminUniversitas();
        else if(Auth::user()->isAdminFakultas())
            return $this->adminFakultas();
        else if(Auth::user()->isDosenPembimbing())
            return $this->dosen();
        else if(Auth::user()->isReviewer())
            return $this->reviewer();
        else if(Auth::user()->isMahasiswa())
            return $this->mahasiswa();
        else
            return $this->tanpaHakAkses();
    }

    public function mahasiswa()
    {
        return view('mahasiswa.dashboard',[
            'undangan' => Auth::user()->mahasiswa()->undanganTimAnggota(),
        ]);
    }

    public function reviewer()
    {
        return redirect()->route('daftar.proposal.reviewer');
    }

    public function dosen()
    {
        return redirect()->route('bimbingan');

//        return view('dosen.dashboard',[
//            'undangan' => Auth::user()->bimbingan()->where('status_request',RequestStatus::REQUESTING),
//            'bimbingan' => Auth::user()->bimbingan()->where('status_request',RequestStatus::APPROVED)
//        ]);
    }

    public function adminFakultas()
    {
        return view('admin.fakultas.dashboard');
    }

    public function adminUniversitas()
    {
        return view('admin.univ.dashboard');
    }

    public function superAdmin()
    {
        return view('admin.super.dashboard');
    }

    public function tanpaHakAkses()
    {
        return view('dashboard');
    }

}
