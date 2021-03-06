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

    /**
     * Memastikan bahwa tim memiliki proposal yang sudah memliki judul atau
     * atribut yang diperlukan
     * @return bool
     */
    public function punyaProposal()
    {
        if(!is_null($this->proposal()))
            if(!is_null($this->proposal()->direktori))
                return true;

        return false;
    }

    /**
     * Proposal kosong disini adalah proposal dengan judul yang masih kosong
     *
     * @return bool
     */
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

    /**
     * Mendapatkan jumlah anggota tim
     * mengembalikan nilai -1 jika mahasiswa belum memiliki tim
     *
     * @return int
     */
    public function jumlahAnggotaTim()
    {
        if(!$this->punyaProposalKosong())
            return -1;

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

    /**
     * Mengecek apakah user dalam mengirim undangan ke dosen untuk menjadi
     * dosen pembimbing
     *
     * @return bool
     */
    public function bisaKirimUndanganDosen()
    {
        return ($this->jumlahAnggotaTim() == 3);
    }

    /**
     * Melakukan penolakan terhadap undangan dari mahasiswa lain
     *
     * @param $dari
     */
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

    /**
     * Memastikan apakah ketua tim dapat melakukan pengunggahan proposal
     * dengan meilhat bahwa tanggal saat ini belum melewati tanggal batas unggah
     * proposal
     *
     * @return bool
     */
    public function bisaUnggahProposal()
    {
        if(!$this->pengguna()->isKetua())
            return false;

        if(Pengaturan::melewatiBatasUnggahProposal())
            return false;
        
        if(!is_null($this->proposal()->direktori))
            return false;
            
        return true;
    }

    /**
     * Memastikan apakah ketua tim dapat mengedit proposal dengan mengecek apakah
     * tanggal batas unggah proposal belum dilewati, dan memastikan bahwa proposal
     * telah memiliki judul
     *
     * @return bool
     */
    public function bisaEditProposal()
    {
        if(!$this->pengguna()->isKetua())
            return false;

        if(Pengaturan::melewatiBatasUnggahProposal())
            return false;
        
        if(is_null($this->proposal()->direktori))
            return false;
            
        return true;
    }

    /**
     * Mengecek apakah mahasiswa terkait bisa melakukan
     * pengunggahan proposal final
     *
     * @return bool
     */
    public function bisaUnggahProposalFinal()
    {
        if(!$this->pengguna()->isKetua())
            return false;

        if(Pengaturan::melewatiBatasUnggahProposalFinal())
            return false;
        
        if(!is_null($this->proposal()->direktori_final))
            return false;
            
        return true;
    }

    /**
     * Mengecek apakah mahasiswa bisa melakukan pengeditan
     * proposal final
     *
     * @return bool
     */
    public function bisaEditProposalFinal()
    {
        if(!$this->pengguna()->isKetua())
            return false;

        if(Pengaturan::melewatiBatasUnggahProposalFinal())
            return false;
        
        if(is_null($this->proposal()->direktori_final))
            return false;
            
        return true;
    }

}
