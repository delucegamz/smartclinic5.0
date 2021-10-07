<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Diagnosis;
use DB;
use Response;
use Auth;


class DiagnosisController extends Controller
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
        if( !current_user_can( 'data_diagnosa' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
    	        $datas = Diagnosis::all();
            else
                $datas = Diagnosis::where( 'nama_diagnosa', 'LIKE', "%$s%" )->get();

        }else{
            if( empty( $s ) )
                $datas = Diagnosis::paginate( $rows );
            else
                $datas = Diagnosis::where( 'nama_diagnosa', 'LIKE', "%$s%" )->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'diagnosis', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
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
            $diagnosa = new Diagnosis();

            $diagnosa->nama_diagnosa = $input['name'];
            $diagnosa->kode_diagnosa = Diagnosis::generate_id();
            $diagnosa->id_pengguna = $idpengguna;

            $insert = $diagnosa->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Diagnosa berhasil ditambahkan.',
                    'kode_diagnosa' => $input['code'],
                    'nama_diagnosa' => $input['name'],
                    'id_diagnosa' => $diagnosa->id_diagnosa
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan diagnosa.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menambahkan diagnosa.'
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

        $diagnosa = Diagnosis::find( $id );

        $diagnosa->nama_diagnosa = $input['name'];
    
        $update = $diagnosa->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Diagnosa berhasil diperbarui.',
                'nama_diagnosa' => $input['name'],
                'kode_diagnosa' => $diagnosa->kode_diagnosa,
                'id_diagnosa' => $id
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui diagnosa.'
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
        $diagnosa = Diagnosis::find( $id );

        $delete = $diagnosa->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Diagnosa berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus diagnosa.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
    	$latest_id = Diagnosis::generate_id();

    	$response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }

    public function search(Request $request){
        $input = $request->all();

        $value = $input['val'];

        $dd = Diagnosis::where( function($q) use ( $value ){
            $q->where( 'kode_diagnosa', 'like', "%" . $value . "%" )
              ->orWhere( 'nama_diagnosa', 'like', "%" . $value . "%" );
        })->get(); 

        $responses = array();

        if( $dd ){
            foreach( $dd as $d ){
                $responses[] = array(
                    'id_diagnosa' => $d['id_diagnosa'],
                    'kode_diagnosa' => $d['kode_diagnosa'],
                    'nama_diagnosa' => $d['nama_diagnosa'],
                    'display_name' => $d['kode_diagnosa'] .' - ' . $d['nama_diagnosa']
                );
            }

        }else{
            $responses = array(
                'success' => 'false',
            );
        }

        return Response::json( $responses );
    }
}
