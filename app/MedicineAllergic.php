<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MedicineAllergic extends Model
{
    protected $table = 't_peserta_alergi';
    protected $primaryKey = 'id_peserta_alergi';
	protected $fillable = array( 
		'no_peserta_alergi', 
		'id_peserta', 
		'nama_peserta', 
		'nama_departemen', 
		'nama_factory', 
		'nama_client', 
		'idobat', 
		'id_pengguna', 
		'user_update' 
	);

	public static function generate_id(){
        $ids = DB::table( 't_peserta_alergi' )
                ->select( 'no_peserta_alergi' )
                ->orderBy( 'no_peserta_alergi', 'desc' )
                ->where( 'no_peserta_alergi', 'LIKE', "%AL%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_peserta_alergi ) ) ? $ids->no_peserta_alergi : "AL0000000000";
        $latest_id = str_replace( "AL", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "AL" . str_pad( $latest_id, 10, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
