<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MedicineInDetail extends Model
{
    protected $table = 't_obat_masuk_detail';
    protected $primaryKey = 'id_detail_obat_masuk';
	protected $fillable = array( 
        'no_detail_obat_masuk', 
        'no_obat_masuk', 
        'id_obat', 
        'jumlah_obat', 
        'id_pengguna'
    );

    public static function generate_id( $no_obat_masuk ){
        $ids = DB::table( 't_obat_masuk_detail' )
                ->select( 'no_detail_obat_masuk' )
                ->orderBy( 'no_detail_obat_masuk', 'desc' )
                ->where( 'no_detail_obat_masuk', 'LIKE', '%' . $no_obat_masuk . '%' )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_detail_obat_masuk ) ) ? $ids->no_detail_obat_masuk : $no_obat_masuk . "000";
        $latest_id = str_replace( $no_obat_masuk, "", $latest_id );
        $latest_id = $latest_id + 0;
        $latest_id++;

        $latest_id = $no_obat_masuk . str_pad( $latest_id, 3, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
