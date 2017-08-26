<?php

namespace PMW\Http\Controllers\Page;

use Illuminate\Http\Request;
use PMW\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PMW\Models\Fakultas;
use PMW\Models\Jurusan;
use PMW\Models\Prodi;
use PMW\Models\Proposal;

class PageController extends Controller
{

    public function proposalDetail($id = null)
    {
        $view = view('proposaldetail');
        $proposal = Auth::user()->isMahasiswa() ? Auth::user()->mahasiswa()->proposal() : Proposal::find($id);
        $view->with('proposal', $proposal);

        if (!Auth::user()->isMahasiswa()) {
            $view->with('anggota', Proposal::find($id)->mahasiswa());
            $view->with('ketua', Proposal::find($id)->ketua());
        } else {
            if (!Auth::user()->mahasiswa()->punyaProposal())
                return view('mahasiswa.proposal');
        }

        return $view;
    }

    public function lihatHasilReview($id)
    {
        $proposal = Proposal::find($id);

        return view('hasilreview', [
            'review' => [
                'tahap1' => $proposal->daftarReview(1)->whereNotNull('komentar'),
                'tahap2' => $proposal->daftarReview(2)->WhereNotNull('komentar')
            ],
            'proposal' => $proposal
        ]);
    }

    public function pengaturan()
    {
        $fakultas = Fakultas::all();
        if (!is_null(Auth::user()->prodi()))
            $fakultas = Fakultas::where('id', '!=', Auth::user()->prodi()->jurusan()->fakultas()->id)->get();

        $jurusan = [];
        if (!is_null(Auth::user()->prodi())) {
            $jurusan = Jurusan::where('id', '!=', Auth::user()->prodi()->jurusan()->id)
                ->where('id_fakultas', Auth::user()->prodi()->jurusan()->fakultas()->id)
                ->get();
        }

        $prodi = [];
        if (!is_null(Auth::user()->prodi())) {
            $prodi = Prodi::where('id', '!=', Auth::user()->prodi()->id)
                ->where('id_jurusan', Auth::user()->prodi()->jurusan()->id)
                ->get();
        }


        return view('pengaturan', [
            'daftar_fakultas' => $fakultas,
            'daftar_jurusan' => $jurusan,
            'daftar_prodi' => $prodi
        ]);
    }

}
