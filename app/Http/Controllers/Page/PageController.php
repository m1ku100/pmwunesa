<?php

namespace PMW\Http\Controllers\Page;

use Illuminate\Http\Request;
use PMW\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PMW\Models\Proposal;

class PageController extends Controller
{

    public function proposalDetail($id = null)
    {
        $view = view('proposaldetail');
        $proposal = Auth::user()->isMahasiswa() ? Auth::user()->mahasiswa()->proposal() : Proposal::find($id);
        $view->with('proposal',$proposal);

        if(!Auth::user()->isMahasiswa())
        {
            $view->with('anggota',Proposal::find($id)->mahasiswa());
            $view->with('ketua',Proposal::find($id)->ketua());
        }
        else{
            if(!Auth::user()->mahasiswa()->punyaProposal())
                return view('mahasiswa.proposal');
        }

        return $view;
    }

}