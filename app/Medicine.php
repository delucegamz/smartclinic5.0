<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Medicine extends Model
{
    protected $table = 'm_obat';
    protected $primaryKey = 'id_obat';
	protected $fillable = array( 'kode_obat', 'nama_obat', 'id_golongan_obat', 'satuan', 'jenis_obat', 'stock_min', 'stock_obat', 'keterangan', 'id_pengguna' );

	public static function generate_id(){
        $ids = DB::table( 'm_obat' )
                ->select( 'kode_obat' )
                ->orderBy( 'kode_obat', 'desc' )
                ->where( 'kode_obat', 'LIKE', "%MED%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_obat ) ) ? $ids->kode_obat : "MED0000";
        $latest_id = str_replace( "MED", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "MED" . str_pad( $latest_id, 4, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
