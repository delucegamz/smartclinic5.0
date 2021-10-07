<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;

class Poli extends Model
{
    protected $table = 'm_poli';
    protected $primaryKey = 'id_poli';
	protected $fillable = array( 'kode_poli', 'nama_poli', 'id_pengguna' );

	public static function generate_id(){
        $ids = DB::table( 'm_poli' )
                ->select( 'kode_poli' )
                ->orderBy( 'kode_poli', 'desc' )
                ->where( 'kode_poli', 'LIKE', "%PO%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_poli ) ) ? $ids->kode_poli : "PO000";
        $latest_id = str_replace( "PO", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "PO" . str_pad( $latest_id, 3, "0", STR_PAD_LEFT );

        return $latest_id;
    }

    public static function get_name( $id = 0 ){
        $poli = Cache::rememberForever('poli', function() {
            $datas = DB::table( 'm_poli' )->get();

            $poli = array();

            foreach ( $datas as $data ) {
                $poli[$data->id_poli] = array(
                    'kode_poli' => $data->kode_poli,
                    'nama_poli' => $data->nama_poli,
                );
            }

            return $poli;
        });

        if( isset( $poli[$id] ) && $poli[$id] ){
            return $poli[$id]['nama_poli'] ? $poli[$id]['nama_poli'] : '';
        }else{
            return '';
        }
    }
}
