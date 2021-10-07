<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AmbulanceIn extends Model
{
    protected $table = 't_ambulance_in';
    protected $primaryKey = 'id_ambulance_in';
	protected $fillable = array( 
		'no_ambulance_in', 
		'id_ambulance_out',
		'km_in', 
		'driver', 
		'tanggal_masuk', 
		'catatan',
		'id_pengguna'
	);

    public static function generate_id(){
        $ids = DB::table( 't_ambulance_in' )
                ->select( 'no_ambulance_in' )
                ->orderBy( 'no_ambulance_in', 'desc' )
                ->where( 'no_ambulance_in', 'LIKE', "%AMI%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_ambulance_in ) ) ? $ids->no_ambulance_in : "AMI0000000";
        $latest_id = str_replace( "AMI", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "AMI" . str_pad( $latest_id, 7, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
