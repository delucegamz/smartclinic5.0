<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Medrec2 extends Model
{
    protected $table = 't_pemeriksaan_poli';
    protected $primaryKey = 'id_pemeriksaan_poli';
	protected $fillable = array( 
        'no_pemeriksaan_poli', 
        'id_pendaftaran_poli', 
        'id_peserta', 
        'nama_peserta', 
        'nama_factory', 
        'nama_client', 
        'nama_departemen', 
        'iddiagnosa', 
        'diagnosa_dokter', 
        'uraian', 
        'dokter_rawat', 
        'keluhan', 
        'catatan_pemeriksaan', 
        'pahk', 
        'tb', 
        'status',
        'id_pengguna', 
        'user_update'
    );


}

