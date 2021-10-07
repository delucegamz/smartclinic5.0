<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class DoctorRecipe extends Model
{
    protected $table = 't_resep';
    protected $primaryKey = 'id_resep';
	protected $fillable = array( 
		'no_resep', 
		'id_pemeriksaan_poli', 
		'jumlah_obat', 
		'catatan',
		'id_pengguna',
		'user_update' 
	);

	public static function generate_id(){
		$date = date( 'ym' );

        $ids = DB::table( 't_resep' )
                ->select( 'no_resep' )
                ->orderBy( 'no_resep', 'desc' )
                ->where( 'no_resep', 'LIKE', "%RSP" . $date . "%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_resep ) ) ? $ids->no_resep : "RSP" . $date . "00000";
        $latest_id = str_replace( "RSP" . $date, "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "RSP" . $date . str_pad( $latest_id, 5, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
