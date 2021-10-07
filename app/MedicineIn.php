<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MedicineIn extends Model
{
    protected $table = 't_obat_masuk';
    protected $primaryKey = 'id_pembelian_obat';
	protected $fillable = array( 
        'no_pembelian_obat', 
        'idsupplier', 
        'tanggal_obat_masuk', 
        'catatan_pembelian_obat', 
        'status_pembelian_obat', 
        'id_pengguna', 
        'user_update',
        'total_harga',
        'jumlah_pembelian',
        'tanggal_proses'
    );

    public static function generate_id(){
        $ids = DB::table( 't_obat_masuk' )
                ->select( 'no_pembelian_obat' )
                ->orderBy( 'no_pembelian_obat', 'desc' )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_pembelian_obat ) ) ? $ids->no_pembelian_obat : "F0000000";
        $latest_id = str_replace( "F", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "F" . str_pad( $latest_id, 7, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}

