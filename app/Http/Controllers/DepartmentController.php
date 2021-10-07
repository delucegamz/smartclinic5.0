<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Department;
use App\Factory;
use App\Client;
use DB;
use Response;
use Auth;

class DepartmentController extends Controller
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
        if( !current_user_can( 'data_unit_kerja' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

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
    	        $datas = Department::all();
            else
                $datas = Department::where( 'nama_departemen', 'LIKE', "%$s%" )->get();

        }else{
            if( empty( $s ) )
                $datas = Department::paginate( $rows );
            else
                $datas = Department::where( 'nama_departemen', 'LIKE', "%$s%" )->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $client = Client::all();
        $factory = Factory::all();

    	return view( 'department', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'clients' => $client, 'factories' => $factory ]);
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
            $department = new Department();

            $department->nama_departemen = $input['name'];
            $department->kode_departemen = Department::generate_id();
            $department->nama_factory = $input['factory'];
            $department->nama_client = $input['client'];
            $department->id_pengguna = $idpengguna;

            $insert = $department->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Departemen berhasil ditambahkan.',
                    'kode_departemen' => $input['code'],
                    'nama_departemen' => $input['name'],
                    'factory' => $input['factory'],
                    'client' => $input['client'],
                    'nama_factory' => get_factory_name( $input['factory'] ),
                    'nama_client' => get_client_name( $input['client'] ),
                    'id_departemen' => $department->id_departemen
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan departemen.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menambahkan departemen.'
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

        $department = Department::find( $id );

        $department->nama_departemen = $input['name'];
        $department->nama_factory = $input['factory'];
        $department->nama_client = $input['client'];
    
        $update = $department->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Departemen berhasil diperbarui.',
                'kode_departemen' => $department->kode_departemen,
                'nama_departemen' => $input['name'],
                'factory' => $input['factory'],
                'client' => $input['client'],
                'nama_factory' => get_factory_name( $input['factory'] ),
                'nama_client' => get_client_name( $input['client'] ),
                'id_departemen' => $id
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui departemen.'
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
        $department = Department::find( $id );

        $delete = $department->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Departemen berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus departemen.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
    	$latest_id = Department::generate_id();

    	$response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }
}
