<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MedicalRecord extends Model
{
    public static $snakeAttributes = false;
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
        'created_at',
        'status',
        'id_pengguna', 
        'user_update' 
    );

	public static function generate_id(){
		$date = date( 'Ymd' );

        $ids = DB::table( 't_pemeriksaan_poli' )
                ->select( 'no_pemeriksaan_poli' )
                ->orderBy( 'no_pemeriksaan_poli', 'desc' )
                ->where( 'no_pemeriksaan_poli', 'LIKE', "%C" . $date . "%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_pemeriksaan_poli ) ) ? $ids->no_pemeriksaan_poli : "C" . $date . "000";
        $latest_id = str_replace( "C" . $date, "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "C$date" . str_pad( $latest_id, 3, "0", STR_PAD_LEFT );

        return $latest_id;
    }

    public static function get_registration_no( $id_registration, $id_participant ){ 
        // $medrec = MedicalRecord::where( 'id_pendaftaran_poli', '=', $id_registration )
        //                        ->where( 'id_peserta', '=', $id_participant )
        //                        ->first();
        // return $medrec->id_pemeriksaan_poli;

        return $id_registration;
    }

    public function poliRegistration()
    {
        return $this->belongsTo(PoliRegistration::class, 'id_pendaftaran_poli', 'id_pendaftaran');
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class, 'id_peserta');
    }

    public function accident()
    {
        return $this->hasOne(Accident::class, 'id_pemeriksaan_poli', 'id_pemeriksaan_poli');
    }

    public function referenceLetter()
    {
        return $this->hasOne(ReferenceLetter::class, 'id_pemeriksaan_poli', 'id_pemeriksaan_poli');
    }

    public function sickLetter()
    {
        return $this->hasOne(SickLetter::class, 'id_pemeriksaan_poli', 'id_pemeriksaan_poli');
    }
}
