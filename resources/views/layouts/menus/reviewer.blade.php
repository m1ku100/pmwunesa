<li @if(Route::currentRouteName() === 'daftar.proposal.reviewer' ) class="active" @endif>
    <a href="{{ route('daftar.proposal.reviewer') }}">
        <i class="material-icons">book</i>
        <p>Daftar Proposal</p>
    </a>
</li>

<li>
    <a href="/">
        <i class="material-icons">library_books</i>
        <p>Proposal Final</p>
    </a>
</li>

<li>
    <a href="/">
        <i class="material-icons">library_books</i>
        <p>Laporan Kemajuan</p>
    </a>
</li>

<li>
    <a href="/">
        <i class="material-icons">library_books</i>
        <p>Laporan Akhir</p>
    </a>
</li>
