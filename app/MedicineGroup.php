<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MedicineGroup extends Model
{
    protected $table = 'm_golongan_obat';
    protected $primaryKey = 'id_golongan_obat';
	protected $fillable = array( 'nama_golongan_obat', 'id_pengguna', 'kode_golongan_obat' );

	public static function generate_id(){
        $ids = DB::table( 'm_golongan_obat' )
                ->select( 'kode_golongan_obat' )
                ->orderBy( 'kode_golongan_obat', 'desc' )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_golongan_obat ) ) ? $ids->kode_golongan_obat : "00";
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = str_pad( $latest_id, 2, "0", STR_PAD_LEFT );

        return $latest_id;
    }

    public static function get_name( $id = 0 ){
        $medicinegroup = MedicineGroup::find( $id );

        return ( $medicinegroup && $medicinegroup->nama_golongan_obat ) ? $medicinegroup->nama_golongan_obat : '';
    }
}
