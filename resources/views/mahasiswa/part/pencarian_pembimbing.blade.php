<p class="alert alert-warning">Anda belum memiliki dosen pembimbing</p>

<form class="card cari" action="{{ route('cari.pembimbing') }}" method="post" id="cari-pembimbing">

    <input  type="text" name="nama" placeholder="Cari Pembimbing" autofocus/>

    <button type="submit"><i class="fa fa-search"></i></button>

</form>

<div class="card">
    <div class="card-header" data-background-color="blue">
        <h4>Hasil Pencarian</h4>
    </div>
    <div class="card-content table-responsive">
        <div id="belum-cari" style="text-align:center;">
            <i class="fa fa-user fa-5x"></i>
            <p style="font-size:30px;padding-top:10px;">Mulailah untuk mencari dosen pembimbingmu</p>
        </div>
        <div id="not-found" style="text-align:center;display:none">
            <i class="fa fa-user-times fa-5x"></i>
            <p style="font-size:30px;padding-top:10px;">Tidak menemukan user yang anda cari</p>
        </div>
        <table class="table table-striped" style="display:none">
            <thead>
                <tr class="text-default">
                    <th>Nama</th>
                    <th>Asal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="hasil-pencarian">
            </tbody>
        </table>
        <div id="hasil-pencarian"></div>
        <form action="{{ route('undang.pembimbing') }}" method="post" style="display: none" id="undang-pembimbing">
            {{ csrf_field() }}
            <input type="hidden" name="dosen" />
        </form>
    </div>
</div>
