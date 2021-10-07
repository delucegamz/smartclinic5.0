<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class PregnantParticipant extends Model
{
    protected $table = 'm_peserta_hamil';
    protected $primaryKey = 'id_peserta_hamil';
	protected $fillable = array( 
        'no_peserta_hamil', 
        'id_peserta', 
        'golongan_darah', 
        'tanggal_kunjungan', 
        'gravida', 
        'partus', 
        'abortus', 
        'hidup', 
        'tanggal_hpht', 
        'tp', 
        'bb_normal', 
        'tinggi_badan', 
        'riwayat_komplikasi', 
        'tanggal_cuti', 
        'status_hamil', 
        'id_pengguna' 
    );

	public static function generate_id(){
        $date = date( 'ym' );

        $ids = DB::table( 'm_peserta_hamil' )
                ->select( 'no_peserta_hamil' )
                ->orderBy( 'no_peserta_hamil', 'desc' )
                ->where( 'no_peserta_hamil', 'LIKE', "$date%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_peserta_hamil ) ) ? $ids->no_peserta_hamil : $date . "00000000";
        $latest_id++;

        return $latest_id;
    }
}
