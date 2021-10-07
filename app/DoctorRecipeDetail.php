<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class DoctorRecipeDetail extends Model
{
    protected $table = 't_resep_detail';
    protected $primaryKey = 'id_resep_detail';
	protected $fillable = array( 
		't_resep_detail', 
		'id_resep', 
		'jumlah_obat', 
		'id_obat',
	);

	public static function generate_id(){
		$date = date( 'ym' );

        $ids = DB::table( 't_resep_detail' )
                ->select( 't_resep_detail' )
                ->orderBy( 't_resep_detail', 'desc' )
                ->where( 't_resep_detail', 'LIKE', "%RES" . $date . "%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->t_resep_detail ) ) ? $ids->t_resep_detail : "RES" . $date . "00000";
        $latest_id = str_replace( "RSP" . $date, "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "RES" . $date . str_pad( $latest_id, 5, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
