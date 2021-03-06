@extends('layouts.app')

@section('title', 'Dasbor')
@section('brand', 'Dasbor')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            @include('mahasiswa.part.daftar_tim')

            @if($undangan->count() > 0 && !Auth::user()->mahasiswa()->timLengkap())
                <div class="card">
                    <div class="card-header">
                        <h4>Undangan tim</h4>
                    </div>

                    <div class="card-content">
                        Anda mendapat undangan dari <br/>
                        <ul class="list-group">
                            @foreach($undangan->cursor() as $item)
                                <li class="list-group-item">
                                    <b>{{ $item->pengguna()->nama }}</b>
                                    <form action="{{ route('terima.undangan.tim') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden"  name="dari" value="{{ $item->id_pengguna }}"/>
                                        <button class="btn btn-primary">Terima Undangan</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-6">
            @include('part.linimasa')
        </div>
    </div>

@endsection