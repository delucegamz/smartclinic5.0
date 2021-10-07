<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Observation extends Model
{
    protected $table = 't_observasi';
    protected $primaryKey = 'id_observasi';
	protected $fillable = array( 'no_observasi', 'id_pemeriksaan_poli', 'id_peserta', 'nama_peserta', 'nama_factory', 'nama_departemen', 'nama_client', 'umur_peserta', 'tanggal_mulai', 'tanggal_selesai', 'diagnosa_akhir', 'kesimpulan_observasi', 'hasil_observasi', 'status', 'id_pengguna', 'user_update' );

	public static function generate_id(){
        $ids = DB::table( 't_observasi' )
                ->select( 'no_observasi' )
                ->orderBy( 'no_observasi', 'desc' )
                ->where( 'no_observasi', 'LIKE', "%OBS%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_observasi ) ) ? $ids->no_observasi : "OBS0000000";
        $latest_id = str_replace( "OBS", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "OBS" . str_pad( $latest_id, 7, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
