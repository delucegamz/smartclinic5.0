<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Branch extends Model
{
    protected $table = 'm_cabang_organisasi';
    protected $primaryKey = 'id_cabang_organisasi';
	protected $fillable = array( 
		'kode_cabang_organisasi', 
		'nama_cabang', 
		'alamat_cabang', 
		'kota_cabang', 
		'provinsi_cabang', 
		'kode_pos_cabang', 
		'no_telepon_cabang_1', 
		'no_telepon_cabang_2', 
		'no_fax_cabang', 
		'email'
	);

    public static function generate_id(){
        $ids = DB::table( 'm_cabang_organisasi' )
                ->select( 'kode_cabang_organisasi' )
                ->orderBy( 'kode_cabang_organisasi', 'desc' )
                ->where( 'kode_cabang_organisasi', 'LIKE', "%CB%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_cabang_organisasi ) ) ? $ids->kode_cabang_organisasi : "CB000";
        $latest_id = str_replace( "CB", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "CB" . str_pad( $latest_id, 3, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
