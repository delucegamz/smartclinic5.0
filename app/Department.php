<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;
use Factory;
use Client;

class Department extends Model
{
    protected $table = 'm_departemen';
    protected $primaryKey = 'id_departemen';
	protected $fillable = array( 'kode_departemen', 'nama_departemen', 'id_pengguna', 'nama_factory', 'nama_client' );

	public static function generate_id(){
        $ids = DB::table( 'm_departemen' )
                ->select( 'kode_departemen' )
                ->orderBy( 'kode_departemen', 'desc' )
                ->where( 'kode_departemen', 'LIKE', "%DEP%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_departemen ) ) ? $ids->kode_departemen : "DEP0000";
        $latest_id = str_replace( "DEP", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "DEP" . str_pad( $latest_id, 4, "0", STR_PAD_LEFT );

        return $latest_id;
    }

    public static function get_name( $id = 0 ){
        $department = Department::find( $id );

        return ( $department && $department->nama_departemen ) ? $department->nama_departemen : '';
    }

    public static function get_department( $id = 0 ){
        $departments = Cache::rememberForever('departments', function() {
            $datas = DB::table( 'm_departemen' )->get();

            $departments = array();

            foreach ( $datas as $data ) {
                $factory = \App\Factory::get_factory( $data->nama_factory );
                $client = \App\Client::get_client( $data->nama_client );

                $departments[$data->id_departemen] = array(
                    'kode_departemen' => $data->kode_departemen,
                    'nama_departemen' => $data->nama_departemen,
                    'kode_factory' => $data->nama_factory,
                    'nama_factory' => $factory['nama_factory'],
                    'kode_client' => $data->nama_client,
                    'nama_client' => $client['nama_client'],
                );
            }

            return $departments;
        });

        return isset( $departments[$id] ) && $departments[$id] ? $departments[$id] : null;
    }
}
