<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\ObservationAction;
use DB;
use Response;
use Auth;

class ObservationActionController extends Controller
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
        if( !current_user_can( 'data_tindakan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
    	        $datas = ObservationAction::all();
            else
                $datas = ObservationAction::where( 'nama_pemeriksaan_observasi', 'LIKE', "%$s%" )->get();

        }else{
            if( empty( $s ) )
                $datas = ObservationAction::paginate( $rows );
            else
                $datas = ObservationAction::where( 'nama_pemeriksaan_observasi', 'LIKE', "%$s%" )->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'observationaction', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
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
            $observationaction = new ObservationAction();

            $observationaction->nama_pemeriksaan_observasi = $input['name'];
            $observationaction->uraian = $input['desc'];
            $observationaction->kode_pemeriksaan_observasi = ObservationAction::generate_id();
            $observationaction->id_pengguna = $idpengguna;

            $insert = $observationaction->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Pemeriksaan Observasi berhasil ditambahkan.',
                    'kode_pemeriksaan_observasi' => $observationaction->kode_pemeriksaan_observasi,
                    'nama_pemeriksaan_observasi' => $observationaction->nama_pemeriksaan_observasi,
                    'id_pemeriksaan_observasi' => $observationaction->id_pemeriksaan_observasi,
                    'uraian' => $observationaction->uraian,
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan pemeriksaan observasi.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menambahkan pemeriksaan observasi.'
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

        $observationaction = ObservationAction::find( $id );

        $observationaction->nama_pemeriksaan_observasi = $input['name'];
        $observationaction->uraian = $input['desc'];
    
        $update = $observationaction->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Pemeriksaan observasi berhasil diperbarui.',
                'nama_pemeriksaan_observasi' => $observationaction->nama_pemeriksaan_observasi,
                'kode_pemeriksaan_observasi' => $observationaction->kode_pemeriksaan_observasi,
                'uraian' => $observationaction->uraian,
                'id_pemeriksaan_observasi' => $id
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui pemeriksaan observasi.'
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
        $observationaction = ObservationAction::find( $id );

        $delete = $observationaction->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Pemeriksaan observasi berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus pemeriksaan observasi.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
    	$latest_id = ObservationAction::generate_id();

    	$response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }
}
