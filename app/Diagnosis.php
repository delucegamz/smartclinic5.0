<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Diagnosis extends Model
{
    protected $table = 'm_diagnosa';
    protected $primaryKey = 'id_diagnosa';
	protected $fillable = array( 'kode_diagnosa', 'nama_diagnosa', 'id_pengguna' );

	public static function generate_id(){
        $ids = DB::table( 'm_diagnosa' )
                ->select( 'kode_diagnosa' )
                ->orderBy( 'kode_diagnosa', 'desc' )
                ->where( 'kode_diagnosa', 'LIKE', "%A0%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_diagnosa ) ) ? $ids->kode_diagnosa : "A0000";
        $latest_id = str_replace( "A0", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "A0" . str_pad( $latest_id, 3, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
