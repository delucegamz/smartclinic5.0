<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ObservationDetail extends Model
{
    protected $table = 't_observasi_detail';
    protected $primaryKey = 'id_observasi_detail';
	protected $fillable = array( 
		'no_observasi', 
		'no_observasi_detail', 
		'tanggal_obs_detail', 
		'keadaan_umum', 
		'k_mata', 
		'k_bicara', 
		'k_motorik', 
		'jalan_nafas', 
		'td_atas', 
		'td_bawah', 
		'nadi', 
		'suhu', 
		'total', 
		'tgl_obs_entry', 
		'id_pengguna', 
		'user_update' 
	);

	public static function generate_id( $observation_id ){
        $ids = DB::table( 't_observasi_detail' )
                ->select( 'no_observasi_detail' )
                ->orderBy( 'no_observasi_detail', 'desc' )
                ->where( 'no_observasi_detail', 'LIKE', $observation_id )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_observasi_detail ) ) ? $ids->no_observasi_detail : $observation_id . "000";
        $latest_id = str_replace( $observation_id, "", $latest_id );
        $latest_id = $latest_id + 0;
        $latest_id++;

        $latest_id = $observation_id . str_pad( $latest_id, 3, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
