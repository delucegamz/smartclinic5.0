<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MedicineOutDetail extends Model
{
    protected $table = 't_pengeluaran_obat_detail';
    protected $primaryKey = 'id_detail_pengeluaran_obat';
	protected $fillable = array( 
        'no_detail_pengeluaran_obat', 
        'id_obat', 
        'jumlah_obat', 
        'id_pengguna'
    );

    public static function generate_id( $no_pengeluaran_obat ){
        $ids = DB::table( 't_pengeluaran_obat_detail' )
                ->select( 'no_detail_pengeluaran_obat' )
                ->orderBy( 'no_detail_pengeluaran_obat', 'desc' )
                ->where( 'no_detail_pengeluaran_obat', 'LIKE', "%" . $no_pengeluaran_obat . "%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_detail_pengeluaran_obat ) ) ? $ids->no_detail_pengeluaran_obat  : $no_pengeluaran_obat . "000";
        $latest_id = str_replace( $no_pengeluaran_obat, "", $latest_id );
        $latest_id = $latest_id + 0;
        $latest_id++;

        $latest_id = $no_pengeluaran_obat . str_pad( $latest_id, 3, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}

