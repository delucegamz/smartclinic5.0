<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MedicineOut extends Model
{
    protected $table = 't_pengeluaran_obat';
    protected $primaryKey = 'id_pengeluaran_obat';
	protected $fillable = array( 
        'no_pengeluaran_obat', 
        'id_resep', 
        'tanggal_pengeluaran_obat', 
        'jumlah_pengeluaran_obat', 
        'catatan_pengeluaran_obat', 
        'id_pengguna', 
        'user_update'
    );

    public static function generate_id(){
        $ids = DB::table( 't_pengeluaran_obat' )
                ->select( 'no_pengeluaran_obat' )
                ->orderBy( 'no_pengeluaran_obat', 'desc' )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_pengeluaran_obat ) ) ? $ids->no_pengeluaran_obat  : "G0000000";
        $latest_id = str_replace( "G", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "G" . str_pad( $latest_id, 7, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}

