<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Poli;
use DB;
use Response;
use Auth;


class PoliController extends Controller
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
        if( !current_user_can( 'data_poli' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $s = ( isset( $_GET['s'] ) && $_GET['s'] != '' ) ? filter_var( $_GET['s'], FILTER_SANITIZE_STRING ) : '';

        if( $rows == 'all' ){
            if( empty( $s ) )
    	        $datas = Poli::all();
            else
                $datas = Poli::where( 'nama_poli', 'LIKE', "%$s%" )->get();

        }else{
            if( empty( $s ) )
                $datas = Poli::paginate( $rows );
            else
                $datas = Poli::where( 'nama_poli', 'LIKE', "%$s%" )->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'poli', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
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
    public function store(Request $request)
    {
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $input = $request->all();

        try {
            $poli = new Poli();

            $poli->nama_poli = $input['name'];
            $poli->kode_poli = $input['code'];
            $poli->id_pengguna = $idpengguna;

            $insert = $poli->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Poli berhasil ditambahkan.',
                    'kode_poli' => $input['code'],
                    'nama_poli' => $input['name'],
                    'id_poli' => $poli->id_poli
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan poli.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menambahkan poli.'
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $poli = Poli::find( $id );

        $poli->nama_poli = $input['name'];
    
        $update = $poli->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Poli berhasil diperbarui.',
                'kode_poli' => $poli->kode_poli,
                'nama_poli' => $input['name'],
                'id_poli' => $id
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui poli.'
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
        $poli = Poli::find( $id );

        $delete = $poli->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Poli berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus poli.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
    	$latest_id = Poli::generate_id();

    	$response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }
}
