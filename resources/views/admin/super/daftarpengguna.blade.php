@extends('layouts.app')

@section('content')
    <ul>
        @foreach($user as $u)
            <li>{{ $u->nama }},
                @foreach($u->hakAksesPengguna()->cursor() as $item)
                    {{ $item->nama  }}
                @endforeach
            </li>
        @endforeach
    </ul>
    <br>
    <br>
    <h3>Tambah Pengguna</h3>
    <form action="{{ route('tambah.user') }}" method="post">
        {{ csrf_field() }}
        {{ method_field('put') }}
        <input type="text" name="id" placeholder="nip/nim" required>
        <input type="email" name="email" placeholder="email" required>
        <br>
        <label>Hak akses</label><br>
        <input type="checkbox" name="hakakses[]" value="Super Admin"> Super Admin<br>
        <input type="checkbox" name="hakakses[]" value="Admin Universitas"> Admin Universitas<br>
        <input type="checkbox" name="hakakses[]" value="Admin Fakultas"> Admin Fakultas<br>
        <input type="checkbox" name="hakakses[]" value="Reviewer"> Reviewer<br>
        <input type="checkbox" name="hakakses[]" value="Dosen Pembimbing"> Dosen Pembimbing<br>
        <input type="checkbox" name="hakakses[]" value="Anggota"> Mahasiswa<br>
        <input type="submit">
    </form>
@endsection