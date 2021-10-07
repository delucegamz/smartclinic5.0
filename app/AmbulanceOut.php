<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AmbulanceOut extends Model
{
    protected $table = 't_ambulance_out';
    protected $primaryKey = 'id_ambulance_out';
	protected $fillable = array( 
		'no_ambulance_out', 
		'id_peserta', 
		'nama_peserta', 
		'nama_factory', 
		'nama_departemen', 
		'nama_client', 
		'lokasi_jemput', 
		'lokasi_kirim', 
		'emergency', 
		'tanggal_keluar',
		'km_out',
		'id_pengguna'
	);

    public static function generate_id(){
        $ids = DB::table( 't_ambulance_out' )
                ->select( 'no_ambulance_out' )
                ->orderBy( 'no_ambulance_out', 'desc' )
                ->where( 'no_ambulance_out', 'LIKE', "%AMO%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_ambulance_out ) ) ? $ids->no_ambulance_out : "AMO0000000";
        $latest_id = str_replace( "AMO", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "AMO" . str_pad( $latest_id, 7, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
