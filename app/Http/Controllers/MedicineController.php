<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Medicine;
use App\MedicineGroup;
use DB;
use Response;
use Auth;

class MedicineController extends Controller
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
        if( !current_user_can( 'data_obat' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

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
    	        $datas = Medicine::all();
            else
                $datas = Medicine::where( 'nama_obat', 'LIKE', "%$s%" )->get();

        }else{
            if( empty( $s ) )
                $datas = Medicine::paginate( $rows );
            else
                $datas = Medicine::where( 'nama_obat', 'LIKE', "%$s%" )->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $groups = MedicineGroup::all();

    	return view( 'medicine', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'groups' => $groups ]);
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
            $medicine = new Medicine();

            $medicine->nama_obat = $input['name'];
            $medicine->kode_obat = Medicine::generate_id();
            $medicine->id_golongan_obat = $input['id_golongan_obat'];
            $medicine->satuan = $input['satuan'];
            $medicine->jenis_obat = $input['jenis_obat'];
            $medicine->stock_min = $input['stock_min'];
            $medicine->stock_obat = $input['stock_obat'];
            $medicine->keterangan = $input['keterangan'];
            $medicine->id_pengguna = $idpengguna;

            $insert = $medicine->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Obat berhasil ditambahkan.',
                    'kode_obat' => $medicine->kode_obat,
                    'nama_obat' => $medicine->nama_obat,
                    'id_obat' => $medicine->id_obat,
                    'id_golongan_obat' => get_medicine_group_name( $medicine->id_golongan_obat ), 
					'satuan' => number_format( $medicine->satuan, 0, ',', '.' ),
					'jenis_obat' => $medicine->jenis_obat,
					'stock_min' => $medicine->stock_min,
					'stock_obat' => $medicine->stock_obat,
					'keterangan' => $medicine->keterangan
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan obat.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => $e->getMessage()
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
        $medicine = Medicine::find( $id );

        if( $medicine ){
            $response = array(
                'success' => 'true',
                'message' => '',
                'kode_obat' => $medicine->kode_obat,
                'nama_obat' => $medicine->nama_obat,
                'id_obat' => $medicine->id_obat,
                'id_golongan_obat' => $medicine->id_golongan_obat,
    			'satuan' => $medicine->satuan,
    			'jenis_obat' => get_medicine_group_name( $medicine->id_golongan_obat ),
    			'stock_min' => $medicine->stock_min,
    			'stock_obat' => $medicine->stock_obat,
    			'keterangan' => $medicine->keterangan
        	);
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Tidak dapat menemukan detail obat yang dicari.'
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
    public function edit($id)
    {
        
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

        $medicine = Medicine::find( $id );

        $medicine->nama_obat = $input['name'];
        $medicine->id_golongan_obat = $input['id_golongan_obat'];
        $medicine->satuan = $input['satuan'];
        $medicine->jenis_obat = $input['jenis_obat'];
        $medicine->stock_min = $input['stock_min'];
        $medicine->stock_obat = $input['stock_obat'];
        $medicine->keterangan = $input['keterangan'];    

        $update = $medicine->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Obat berhasil diperbarui.',
                'kode_obat' => $medicine->kode_obat,
                'nama_obat' => $medicine->nama_obat,
                'id_obat' => $id,
                'id_golongan_obat' => get_medicine_group_name( $medicine->id_golongan_obat ),
				'satuan' => number_format( $medicine->satuan, 0, ',', '.' ),
				'jenis_obat' => $medicine->jenis_obat,
				'stock_min' => $medicine->stock_min,
				'stock_obat' => $medicine->stock_obat,
				'keterangan' => $medicine->keterangan
        	);
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui obat.'
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
        $medicine = Medicine::find( $id );

        $delete = $medicine->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Obat berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus obat.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
    	$latest_id = Medicine::generate_id();

    	$response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }

    public function search_med_by_name( Request $request ){
        $input = $request->all();

        $value = $input['val'];

        $dd = Medicine::where( 'nama_obat', 'LIKE', '%' . $value . '%' )->get(); 

        $responses = array();

        if( $dd ){
            foreach( $dd as $d ){
                $responses[] = array(
                    'id_obat' => $d['id_obat'],
                    'kode_obat' => $d['kode_obat'],
                    'nama_obat' => $d['nama_obat']
                );
            }

        }else{
            $responses = array(
                'success' => 'false',
            );
        }

        return Response::json( $responses );
    }

    public function search_med_by_code( Request $request ){
        $input = $request->all();

        $value = $input['val'];

        $dd = Medicine::where( 'kode_obat', 'LIKE', '%' . $value . '%' )->get(); 

        $responses = array();

        if( $dd ){
            foreach( $dd as $d ){
                $responses[] = array(
                    'id_obat' => $d['id_obat'],
                    'kode_obat' => $d['kode_obat'],
                    'nama_obat' => $d['nama_obat']
                );
            }

        }else{
            $responses = array(
                'success' => 'false',
            );
        }

        return Response::json( $responses );
    }

    public function search_med_by_code_or_name( Request $request ){
        $input = $request->all();

        $value = $input['val'];

        $dd = Medicine::where( function($q) use ( $value ){
            $q->where( 'kode_obat', 'like', '%' . $value . '%' )
              ->orWhere( 'nama_obat', 'like', '%' . $value . '%' );
        })->get(); 

        $responses = array();

        if( $dd ){
            foreach( $dd as $d ){
                $responses[] = array(
                    'id_obat' => $d['id_obat'],
                    'kode_obat' => $d['kode_obat'],
                    'nama_obat' => $d['nama_obat'],
                    'display_name' => $d['kode_obat'] . ' / ' . $d['nama_obat'] 
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
