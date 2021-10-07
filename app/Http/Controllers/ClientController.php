<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Client;
use App\Province;
use DB;
use Response;
use Auth;

class ClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware( 'auth' );
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !current_user_can( 'data_klien' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $rows = 10;

        $datas = Client::all();

        $provinces = Province::all();

        $i = 1;

    	return view( 'client', [ 'datas' => $datas, 'i' => $i, 'provinces' => $provinces ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $input = $request->all();

        try {
            $client = new Client();

            $client->nama_client = $input['name'];
            $client->alamat_client = $input['address'];
            $client->propinsi = $input['province'];
            $client->kota = $input['city'];
            $client->kode_pos = $input['zip_code'];
            $client->telepon_1 = $input['phone_1'];
            $client->telepon_2 = $input['phone_2'];
            $client->fax = $input['fax'];
            $client->email = $input['email'];
            $client->id_pengguna = $idpengguna;
            $client->kode_client = Client::generate_id();

            $insert = $client->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Client berhasil ditambahkan.',
                    'client_name' => $input['name'],
                    'client_address' => $input['address'],
                    'client_code' => $client->kode_client,
                    'id_client' => $client->id_client
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan client.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menambahkan client.'
            );
        }

        return Response::json( $response );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $client = Client::find( $id );

        if( $client ){
            $response = array(
                'success' => 'true',
                'nama_client' => $client->nama_client,
                'alamat_client' => $client->alamat_client,
                'propinsi' => $client->propinsi,
                'kota' => $client->kota,
                'kode_pos' => $client->kode_pos,
                'telepon_1' => $client->telepon_1,
                'telepon_2' => $client->telepon_2,
                'fax' => $client->fax,
                'email' => $client->email,
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Tidak ada data yang ditemukan.'
            );
        }

        return Response::json( $response );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        $input = $request->all();

        $client = Client::find( $id );

        $client->nama_client = $input['name'];
        $client->alamat_client = $input['address'];
        $client->propinsi = $input['province'];
        $client->kota = $input['city'];
        $client->kode_pos = $input['zip_code'];
        $client->telepon_1 = $input['phone_1'];
        $client->telepon_2 = $input['phone_2'];
        $client->fax = $input['fax'];
        $client->email = $input['email'];
    
        $update = $client->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Client berhasil diperbarui.',
                'client_name' => $input['name'],
                'client_address' => $input['address'],
                'id_client' => $id
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui client.'
            );
        }

        return Response::json( $response );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        $client = Client::find( $id );

        $delete = $client->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Client berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus client.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
        $latest_id = Client::generate_id();

        $response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }
}
