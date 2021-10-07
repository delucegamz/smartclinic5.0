<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Legality;
use DB;
use Response;
use Auth;

class LegalityController extends Controller
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
        //if( !current_user_can( 'data_legalitas' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
    	        $datas = Legality::all();
            else
                $datas = Legality::where( 'nama_legalitas', 'LIKE', "%$s%" )->get();

        }else{
            if( empty( $s ) )
                $datas = Legality::paginate( $rows );
            else
                $datas = Legality::where( 'nama_legalitas', 'LIKE', "%$s%" )->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'legality', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
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
            $legality = new Legality();

            $legality->nama_legalitas = $input['name'];
            $legality->keterangan = $input['description'];

            $insert = $legality->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Legalitas berhasil ditambahkan.',
                    'keterangan' => $legality->keterangan,
                    'nama_legalitas' => $legality->nama_legalitas,
                    'id_legalitas' => $legality->id_legalitas
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan legalitas.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menambahkan legalitas.'
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

        $legality = Legality::find( $id );

        $legality->nama_legalitas = $input['name'];
        $legality->keterangan = $input['description'];
    
        $update = $legality->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Legalitas berhasil diperbarui.',
                'keterangan' => $legality->keterangan,
                'nama_legalitas' => $legality->nama_legalitas,
                'id_legalitas' => $id
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui legalitas.'
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
        $legality = Legality::find( $id );

        $delete = $legality->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Legalitas berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus legalitas.'
            );
        }

        return Response::json( $response );
    }
}
