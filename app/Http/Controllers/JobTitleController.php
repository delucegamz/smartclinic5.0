<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\JobTitle;
use DB;
use Response;
use Auth;

class JobTitleController extends Controller
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
        if( !current_user_can( 'data_jabatan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
    	        $datas = JobTitle::all();
            else
                $datas = JobTitle::where( 'nama_jabatan', 'LIKE', "%$s%" )->get();

        }else{
            if( empty( $s ) )
                $datas = JobTitle::paginate( $rows );
            else
                $datas = JobTitle::where( 'nama_jabatan', 'LIKE', "%$s%" )->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'jobtitle', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
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
            $jobtitle = new JobTitle();

            $jobtitle->nama_jabatan = $input['name'];
            $jobtitle->kode_jabatan = JobTitle::generate_id();
            $jobtitle->id_pengguna = $idpengguna;

            $insert = $jobtitle->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Jabatan pengguna berhasil ditambahkan.',
                    'kode_jabatan' => $jobtitle->kode_jabatan,
                    'nama_jabatan' => $jobtitle->nama_jabatan,
                    'id_jabatan' => $jobtitle->id_jabatan
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan jabatan.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menambahkan jabatan.'
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

        $jobtitle = JobTitle::find( $id );

        $jobtitle->nama_jabatan = $input['name'];
    
        $update = $jobtitle->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Jabatan pengguna berhasil diperbarui.',
                'kode_jabatan' => $jobtitle->kode_jabatan,
                'nama_jabatan' => $jobtitle->nama_jabatan,
                'id_jabatan' => $id
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui jabatan.'
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
        $jobtitle = JobTitle::find( $id );

        $delete = $jobtitle->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Jabatan pengguna berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus jabatan.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
        $latest_id = JobTitle::generate_id();

        $response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }
}
