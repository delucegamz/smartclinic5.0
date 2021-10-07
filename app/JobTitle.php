<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class JobTitle extends Model
{
    protected $table = 'm_jabatan';
    protected $primaryKey = 'id_jabatan';
	protected $fillable = array( 'kode_jabatan', 'nama_jabatan', 'id_pengguna' );

	public static function generate_id(){
        $ids = DB::table( 'm_jabatan' )
                ->select( 'kode_jabatan' )
                ->orderBy( 'kode_jabatan', 'desc' )
                ->where( 'kode_jabatan', 'LIKE', "J%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_jabatan ) ) ? $ids->kode_jabatan : "J00";
        $latest_id = str_replace( "J", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "J" . str_pad( $latest_id, 2, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
