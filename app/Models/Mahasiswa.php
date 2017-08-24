<?php

namespace PMW\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use PMW\User;

class Mahasiswa extends Model
{

    public $table = 'mahasiswa';

    public $primaryKey = 'id_pengguna';

    public $incrementing = false;

    public $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'id_proposal',
        'ipk'
    ];

    /**
     * Mendapatkan pengguna dari mahasiswa tertentu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pengguna()
    {
        return $this->belongsTo('PMW\User',$this->primaryKey)->first();
    }

    /**
     * Mendapatkan undangan tim
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function undanganTimKetua()
    {
        return $this->belongsToMany('PMW\Models\Mahasiswa', 'undangan_tim', 'id_ketua', 'id_anggota')
            ->withPivot('ditolak');
    }

    /**
     * Mendapatkan undangan tim
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function undanganTimAnggota()
    {
        return $this->belongsToMany('PMW\Models\Mahasiswa', 'undangan_tim', 'id_anggota', 'id_ketua')
            ->withPivot('ditolak');
    }

    /**
     * Mendapatkan proposal dari mahasiswa tertentu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proposal()
    {
        return $this->belongsTo('PMW\Models\Proposal','id_proposal')->first();
    }

    public function punyaProposal()
    {
        if(!is_null($this->proposal()))
            if(!is_null($this->proposal()->direktori))
                return true;

        return false;
    }

    public function punyaProposalKosong()
    {
        if(!is_null($this->proposal()))
            return true;

        return false;
    }

    public function punyaProposalFinal()
    {
        return ($this->punyaProposal() && $this->proposal()->direktori_final);
    }

    public function punyaTim()
    {
        return !is_null($this->id_proposal);
    }

    public function ketua()
    {
        $proposal = $this->mahasiswa()->id_proposal;

        return Proposal::find($proposal)->ketua();
    }

    public function jumlahAnggotaTim()
    {
        if(!$this->punyaProposalKosong())
            return 0;

        $proposal = $this->proposal();

        return static::where('id_proposal', $proposal->id)->count();
    }

    public function bisaKirimUndanganTim($penerima = null)
    {
        return (($this->pengguna()->isAnggota() && !$this->punyaTim()) || ($this->pengguna()->isKetua() && $this->jumlahAnggotaTim() < 3));
    }

    public function bisaTerimaUndanganTim($pengirim = null)
    {

    }

    public function bisaKirimUndanganDosen()
    {
        return ($this->jumlahAnggotaTim() == 3);
    }

    public function tolakUndanganTim($dari)
    {
        $dari->mahasiswa()->undanganTimKetua()->updateExistingPivot($this->pengguna()->id, [
            'ditolak' => true
        ]);
    }

    public function timLengkap()
    {
        if(!$this->punyaProposalKosong())
            return false;

        $proposal = $this->proposal();

        return ($proposal->mahasiswa()->count() == 3);
    }

    /**
     * Memeriksa apakah user memiliki undangan dari pengirim tertentu
     *
     * @param User $pengirim
     * @return bool
     */
    public function punyaUndanganTim($pengirim)
    {
        return ($pengirim->mahasiswa()
                ->undanganTimKetua()
                ->where('id_anggota',$this->id_pengguna)
                ->count() > 0);
    }

    public function bisaUnggahProposal()
    {
        if(Carbon::now()->diffInDays(
            Carbon::parse(Pengaturan::batasUnggahProposal()
            ),false) >= 0){
            return true;
        }
        return false;
    }

}
