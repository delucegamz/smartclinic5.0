<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;

class Client extends Model
{
    protected $table = 'm_client';
    protected $primaryKey = 'id_client';
	protected $fillable = array( 'kode_client', 'nama_client', 'alamat_client', 'propinsi', 'kota', 'kode_pos', 'telepon_1', 'telepon_2', 'fax', 'email', 'id_pengguna' );

	public static function get_name( $id ){
        $client = Client::find($id);

        return $client->nama_client;
    }

    public static function generate_id(){
        $ids = DB::table( 'm_client' )
                ->select( 'kode_client' )
                ->orderBy( 'kode_client', 'desc' )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_client ) ) ? $ids->kode_client : "110000";
        $latest_id++;

        return $latest_id;
    }

    public static function get_client( $id = 0 ){
        $clients = Cache::rememberForever( 'clients', function() {
            $datas = DB::table( 'm_client' )->get();

            $clients = array();

            foreach ( $datas as $data ) {
                $clients[$data->id_client] = array(
                    'kode_client' => $data->kode_client,
                    'nama_client' => $data->nama_client,
                    'alamat_client' => $data->alamat_client,
                    'propinsi' => $data->propinsi,
                    'kota' => $data->kota,
                    'kode_pos' => $data->kode_pos,
                    'telepon_1' => $data->telepon_1,
                    'telepon_2' => $data->telepon_2,
                    'fax' => $data->fax,
                    'email' => $data->email
                );
            }

            return $clients;
        });

        return isset( $clients[$id] ) && $clients[$id] ? $clients[$id] : null;
    }
}
