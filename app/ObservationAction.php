<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ObservationAction extends Model
{
	protected $table = 'm_pemeriksaan_observasi';
    protected $primaryKey = 'id_pemeriksaan_observasi';
	protected $fillable = array( 
		'kode_pemeriksaan_observasi', 
		'nama_pemeriksaan_observasi', 
		'uraian', 
		'id_pengguna'
	);

	public static function generate_id(){
        $ids = DB::table( 'm_pemeriksaan_observasi' )
                ->select( 'kode_pemeriksaan_observasi' )
                ->orderBy( 'kode_pemeriksaan_observasi', 'desc' )
                ->where( 'kode_pemeriksaan_observasi', 'LIKE', '%OB%' )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_pemeriksaan_observasi ) ) ? $ids->kode_pemeriksaan_observasi : "OB000";
        $latest_id = str_replace( "OB", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "OB" . str_pad( $latest_id, 3, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
