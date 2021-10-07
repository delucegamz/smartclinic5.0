<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Factory;
use DB;
use Response;
use Auth;


class FactoryController extends Controller
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
        if( !current_user_can( 'data_factory' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
    	        $datas = Factory::all();
            else
                $datas = Factory::where( 'nama_factory', 'LIKE', "%$s%" )->get();

        }else{
            if( empty( $s ) )
                $datas = Factory::paginate( $rows );
            else
                $datas = Factory::where( 'nama_factory', 'LIKE', "%$s%" )->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'factory', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
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
            $factory = new Factory();

            $factory->nama_factory = $input['name'];
            $factory->kode_factory = $input['code'];
            $factory->id_pengguna = $idpengguna;

            $insert = $factory->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Factory berhasil ditambahkan.',
                    'kode_factory' => $input['code'],
                    'nama_factory' => $input['name'],
                    'id_factory' => $factory->id_factory
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan factory.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menambahkan factory.'
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
        //
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

        $factory = Factory::find( $id );

        $factory->nama_factory = $input['name'];
    
        $update = $factory->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Factory berhasil diperbarui.',
                'kode_factory' => $factory->kode_factory,
                'nama_factory' => $input['name'],
                'id_factory' => $id
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui factory.'
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
        $factory = Factory::find( $id );

        $delete = $factory->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Factory berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus factory.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
    	$latest_id = Factory::generate_id();

    	$response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }
}
