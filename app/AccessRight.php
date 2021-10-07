<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class AccessRight extends Model
{
    protected $table = 't_hak_akses';
    protected $primaryKey = 'id_hak_akses';
	protected $fillable = array( 'no_hak_akses', 'id_pengguna', 'hak_akses' );

	public static function generate_id(){
        $ids = DB::table( 't_hak_akses' )
                ->select( 'no_hak_akses' )
                ->orderBy( 'no_hak_akses', 'desc' )
                ->where( 'no_hak_akses', 'LIKE', "H%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_hak_akses ) ) ? $ids->no_hak_akses : "H00001";
        $latest_id = str_replace( "H", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "H" . str_pad( $latest_id, 5, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
