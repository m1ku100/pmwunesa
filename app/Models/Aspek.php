<?php

namespace PMW\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Aspek extends Model
{
    public $table = 'aspek';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'nama'
    ];

    /**
     * Mendapatkan daftar penilaian
     *
     * @return BelongsToMany
     */
    public function penilaian(){
        return $this->belongsToMany('PMW\Models\Review','penilaian','id_aspek','id_review')->withPivot('nilai');
    }
}
