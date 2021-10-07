<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\MedicineGroup;
use DB;
use Response;
use Auth;

class MedicineGroupController extends Controller
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
        if( !current_user_can( 'data_golongan_obat' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
    	        $datas = MedicineGroup::all();
            else
                $datas = MedicineGroup::where( 'nama_golongan_obat', 'LIKE', "%$s%" )->get();

        }else{
            if( empty( $s ) )
                $datas = MedicineGroup::paginate( $rows );
            else
                $datas = MedicineGroup::where( 'nama_golongan_obat', 'LIKE', "%$s%" )->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'medicinegroup', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
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
            $medicine_group = new MedicineGroup();

            $medicine_group->nama_golongan_obat = $input['name'];
            $medicine_group->kode_golongan_obat = MedicineGroup::generate_id();

            $medicine_group->id_pengguna = $idpengguna;

            $insert = $medicine_group->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Golongan obat berhasil ditambahkan.',
                    'kode_golongan_obat' => $input['code'],
                    'nama_golongan_obat' => $input['name'],
                    'id_golongan_obat' => $medicine_group->id_golongan_obat
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan golongan obat.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menambahkan golongan obat.'
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

        $medicine_group = MedicineGroup::find( $id );

        $medicine_group->nama_golongan_obat = $input['name'];
    
        $update = $medicine_group->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Golongan obat berhasil diperbarui.',
                'kode_golongan_obat' => $medicine_group->kode_golongan_obat,
                'nama_golongan_obat' => $input['name'],
                'id_golongan_obat' => $id
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui golongan obat.'
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
        $medicine_group = MedicineGroup::find( $id );

        $delete = $medicine_group->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Golongan obat berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus golongan obat.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
    	$latest_id = MedicineGroup::generate_id();

    	$response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }
}
