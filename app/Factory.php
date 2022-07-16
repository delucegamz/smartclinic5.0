<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;

class Factory extends Model
{
    protected $table = 'm_factory';
    protected $primaryKey = 'id_factory';
	protected $fillable = array( 'kode_factory', 'nama_factory','nama_pabrik', 'id_pengguna' );

	public static function generate_id(){
        $ids = DB::table( 'm_factory' )
                ->select( 'kode_factory' )
                ->orderBy( 'kode_factory', 'desc' )
                ->where( 'kode_factory', 'LIKE', "%F%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_factory ) ) ? $ids->kode_factory : "F000";
        $latest_id = str_replace( "F", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "F" . str_pad( $latest_id, 3, "0", STR_PAD_LEFT );

        return $latest_id;
    }

    public static function get_name( $id = 0 ){
        $factory = Factory::find( $id );

        return ( $factory && $factory->nama_factory ) ? $factory->nama_factory : '';
    }

    public static function get_pabrik( $id = 0 ){
        $factory = Factory::find( $id );

        return ( $factory && $factory->nama_pabrik ) ? $factory->nama_pabrik : '';
    }

    public static function get_factory( $id = 0 ){
        $factories = Cache::rememberForever('factories', function() {
            $datas = DB::table( 'm_factory' )->get();

            $factories = array();

            foreach ( $datas as $data ) {
                $factories[$data->id_factory] = array(
                    'kode_factory' => $data->kode_factory,
                    'nama_factory' => $data->nama_factory,
                    'nama_pabrik' => $data->nama_pabrik,
                );
            }

            return $factories;
        });

        return isset( $factories[$id] ) && $factories[$id] ? $factories[$id] : null;
    }
}
