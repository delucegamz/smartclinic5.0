<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Legality;
use App\LegalityDetail;
use DB;
use Response;
use Auth;

class LegalityDetailController extends Controller
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

        $status = ( isset( $_GET['status'] ) && $_GET['status'] != '' ) ? filter_var( $_GET['status'], FILTER_VALIDATE_INT ) : '';
        $name = ( isset( $_GET['name'] ) && $_GET['name'] != '' ) ? filter_var( $_GET['name'], FILTER_SANITIZE_STRING ) : '';

        if( $rows == 'all' ){

            $datas = LegalityDetail::where( function( $q ) use( $s, $status, $name ){
                $q->where( 'id_t_legalitas', '!=', '' );
                if( $s ) $q->where( 'nama_pemilik', 'LIKE', "%$s%" );
                if( $status ) $q->where( 'status', '=', $status );
                if( $name ) $q->where( 'nama_legalitas', 'LIKE', $name );
            })->get();

        }else{
            
            $datas = LegalityDetail::where( function( $q ) use( $s, $status, $name ){
                $q->where( 'id_t_legalitas', '!=', '' );
                if( $s ) $q->where( 'nama_pemilik', 'LIKE', "%$s%" );
                if( $status ) $q->where( 'status', '=', $status );
                if( $name ) $q->where( 'nama_legalitas', 'LIKE', $name );
            })->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $legalities = Legality::all();

    	return view( 'legalitydetail', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'legalities' => $legalities ]);
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
            $legalitydetail = new LegalityDetail();

            $legalitydetail->nama_pemilik = $input['nama_pemilik'];
            $legalitydetail->nama_legalitas = $input['nama_legalitas'];
            $legalitydetail->exp_legalitas = $input['exp_legalitas'];
            $legalitydetail->status = $input['status'];
            $legalitydetail->keterangan = $input['keterangan'];

            $insert = $legalitydetail->save();

            switch ( $legalitydetail->status ) {
                case '1' :
                    $status = 'Masih berlaku';
                    break;
                case '2' :
                    $status = 'Sudah hampir habis (< 6 bulan)';
                    break;
                case '3' :
                    $status = 'Sudah hampir habis (< 3 bulan)';
                    break;
                case '4' :
                    $status = 'Habis masa berlaku';
                    break;
            }

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Kepemilikan legalitas berhasil ditambahkan.',
                    'keterangan' => $legalitydetail->keterangan,
                    'nama_legalitas' => $legalitydetail->nama_legalitas,
                    'nama_pemilik' => $legalitydetail->nama_pemilik,
                    'exp_legalitas' => $legalitydetail->exp_legalitas,
                    'status' => $legalitydetail->status,
                    'status_text' => $status,
                    'id' => $legalitydetail->id_t_legalitas
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan kepemilikan legalitas.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menambahkan kepemilikan legalitas.'
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

        $legalitydetail = LegalityDetail::find( $id );

        $legalitydetail->nama_pemilik = $input['nama_pemilik'];
        $legalitydetail->nama_legalitas = $input['nama_legalitas'];
        $legalitydetail->exp_legalitas = $input['exp_legalitas'];
        $legalitydetail->status = $input['status'];
        $legalitydetail->keterangan = $input['keterangan'];
        $update = $legalitydetail->save();

        switch ( $legalitydetail->status ) {
            case '1' :
                $status = 'Masih berlaku';
                break;
            case '2' :
                $status = 'Sudah hampir habis (< 6 bulan)';
                break;
            case '3' :
                $status = 'Sudah hampir habis (< 3 bulan)';
                break;
            case '4' :
                $status = 'Habis masa berlaku';
                break;
        }

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Kepemilikan legalitas berhasil diperbarui.',
                'keterangan' => $legalitydetail->keterangan,
                'nama_legalitas' => $legalitydetail->nama_legalitas,
                'nama_pemilik' => $legalitydetail->nama_pemilik,
                'exp_legalitas' => $legalitydetail->exp_legalitas,
                'status' => $legalitydetail->status,
                'status_text' => $status,
                'id' => $id
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui kepemilikan legalitas.'
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
        $legalitydetail = LegalityDetail::find( $id );

        $delete = $legalitydetail->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Kepemilikan legalitas berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus kepemilikan legalitas.'
            );
        }

        return Response::json( $response );
    }
}
