@extends('layouts.app')

@section('title', 'PMW Unesa')

@section('content')
<div class="row" style="margin-top: 50px; ">
    <div class="col-md-6 col-md-offset-3" style="text-align: center; color: #333333; background-color: #fff">
        <h2>Sistem Informasi PMW</h2>
        <h2 style="margin-top: 0px">Universitas Negeri Surabaya</h2>
    </div>
</div>
<div class="row" style="margin-top: 0px">
    <div class="col-md-6 col-md-offset-3">
        <ul class="nav nav-tabs " style="background-color: #fff; border-top-left-radius: 4px; border-top-right-radius: 4px; padding-left: 0;">
            <li class="{{ (!Session::has('tab') || (Session::has('tab') && Session::get('tab') == 'login')) ? 'active' : '' }} bg-secondary" style="background-color: #78c8ff;"><a data-toggle="tab" href="#login" aria-expanded="true" style="color: #fff">Login</a></li>
            <li class="{{ Session::has('tab') && Session::get('tab') == 'register' ? 'active' : '' }} bg-secondary" style="background-color: #78c8ff;"><a data-toggle="tab" href="#daftar" aria-expanded="false" style="color: #fff">Daftar</a></li>
            <li class="{{ Session::has('tab') && Session::get('tab') == 'reset' ? 'active' : '' }} bg-secondary" style="background-color: #78c8ff;"><a data-toggle="tab" href="#lupapass" aria-expanded="false" style="color: #fff">Reset Password</a></li>
        </ul>
        <div class="tab-content bg">
            @include('auth.include.login')
            @include('auth.include.register')
            @include('auth.include.reset')
        </div>
    </div>
</div>
<script src="{{ asset('js/auth.js') }}"></script>

@endsection