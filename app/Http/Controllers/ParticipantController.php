<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Participant;
use App\Department;
use App\Province;
use DB;
use Response;
use Auth;
use Excel;
use App\Factory;
use App\Client;
use App\PregnantParticipant;

class ParticipantController extends Controller
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
        if( !current_user_can( 'data_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

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
    	        $datas = Participant::all();
            else
                $datas = Participant::where( function( $q ) use( $s ){ 
                    $q->where( 'nama_peserta', 'LIKE', "%$s%" )
                      ->orWhere( 'nik_peserta', 'LIKE', "%$s%" );
                })->get();

        }else{
            if( empty( $s ) )
                $datas = Participant::paginate( $rows );
            else
                $datas = Participant::where( function( $q ) use( $s ){ 
                    $q->where( 'nama_peserta', 'LIKE', "%$s%" )
                      ->orWhere( 'nik_peserta', 'LIKE', "%$s%" );
                })->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $departments = Department::orderBy( 'nama_departemen', 'asc' )->get();
        $provinces = Province::all();

    	return view( 'participant', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'departments' => $departments, 'provinces' => $provinces ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anc()
    {   
        if( !current_user_can( 'data_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

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

        $anc_results = PregnantParticipant::all();
        $anc = array();
        foreach ( $anc_results as $res ) {
            $anc[] = $res->id_peserta;
        }

        debug_var($anc);

        if( $rows == 'all' ){
            if( empty( $s ) )
                $datas = Participant::whereIn( 'id_peserta', $anc )->get();
            else
                $datas = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->get();
        }else{
            if( empty( $s ) )
                $datas = Participant::whereIn( 'id_peserta', $anc )->paginate( $rows );
            else
                $datas = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->paginate( $rows );
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $departments = Department::all(); 
        $provinces = Province::all();

        return view( 'participant', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'departments' => $departments, 'provinces' => $provinces ]);
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
            $participant = new Participant();

            $participant->nama_peserta = $input['name'];
            $participant->kode_peserta = Participant::generate_id();
            $participant->id_pengguna = $idpengguna;
            $participant->no_medrec = Participant::generate_medrec();
            $participant->nik_peserta = $input['nik_peserta'];
            $participant->id_departemen = $input['id_departemen'];
            $participant->jenis_kelamin = $input['jenis_kelamin'];
            $participant->tempat_lahir = $input['tempat_lahir'];
            $participant->tanggal_lahir = $input['tanggal_lahir'];
            $participant->alamat = $input['alamat'];
            $participant->kota = $input['kota'];
            $participant->provinsi = $input['propinsi'];
            $participant->kodepos = $input['kodepos'];
            $participant->tanggal_aktif = $input['tanggal_aktif'];
            $participant->tanggal_nonaktif = $input['tanggal_nonaktif'];
            $participant->status_aktif = $input['status_aktif'];
            $participant->status_kawin = $input['status_kawin'];
            $participant->jumlah_anak = $input['jumlah_anak'];
            $participant->id_pengguna = $idpengguna;

            $insert = $participant->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Peserta berhasil ditambahkan.',
                    'kode_peserta' => $participant->kode_peserta,
                    'nama_peserta' => $participant->nama_peserta,
                    'no_medrec' => $participant->no_medrec,
                    'nik_peserta' => $participant->nik_peserta,
                    'id_departemen' => get_department_name( $participant->id_departemen ),
                    'jenis_kelamin' => $participant->jenis_kelamin,
                    'tempat_lahir' => $participant->tempat_lahir,
                    'tanggal_lahir' => $participant->tanggal_lahir,
                    'alamat' => $participant->alamat,
                    'kota' => $participant->kota,
                    'propinsi' => $participant->provinsi,
                    'kodepos' => $participant->kodepos,
                    'tanggal_aktif' => $participant->tanggal_aktif,
                    'tanggal_nonaktif' => $participant->tanggal_nonaktif,
                    'status_aktif' => ( $participant->status_aktif == 1 ) ? 'Aktif' : 'Tidak Aktif',
                    'status_kawin' => $participant->status_kawin,
                    'jumlah_anak' => $participant->jumlah_anak,
                    'id_pengguna' => $participant->id_pengguna,
                    'id_peserta' => $participant->id_peserta,
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan peserta.'
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
        $participant = Participant::find( $id );

        $response = array(
            'success' => 'true',
            'message' => 'Peserta berhasil ditambahkan.',
            'kode_peserta' => $participant->kode_peserta,
            'nama_peserta' => $participant->nama_peserta,
            'no_medrec' => $participant->no_medrec,
            'nik_peserta' => $participant->nik_peserta,
            'id_departemen' => $participant->id_departemen,
            'jenis_kelamin' => $participant->jenis_kelamin,
            'tempat_lahir' => $participant->tempat_lahir,
            'tanggal_lahir' => $participant->tanggal_lahir,
            'alamat' => $participant->alamat,
            'kota' => $participant->kota,
            'propinsi' => $participant->provinsi,
            'kodepos' => $participant->kodepos,
            'tanggal_aktif' => $participant->tanggal_aktif,
            'tanggal_nonaktif' => $participant->tanggal_nonaktif,
            'status_aktif' => $participant->status_aktif,
            'status_kawin' => $participant->status_kawin,
            'jumlah_anak' => $participant->jumlah_anak,
            'id_pengguna' => $participant->id_pengguna,
            'id_peserta' => $participant->id_peserta,
        );

        return Response::json( $response );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
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

        $participant = Participant::find( $id );

        $participant->nama_peserta = $input['name'];
        $participant->nik_peserta = $input['nik_peserta'];
        $participant->id_departemen = $input['id_departemen'];
        $participant->jenis_kelamin = $input['jenis_kelamin'];
        $participant->tempat_lahir = $input['tempat_lahir'];
        $participant->tanggal_lahir = $input['tanggal_lahir'];
        $participant->alamat = $input['alamat'];
        $participant->kota = $input['kota'];
        $participant->provinsi = $input['propinsi'];
        $participant->kodepos = $input['kodepos'];
        $participant->tanggal_aktif = $input['tanggal_aktif'];
        $participant->tanggal_nonaktif = $input['tanggal_nonaktif'];
        $participant->status_aktif = $input['status_aktif'];
        $participant->status_kawin = $input['status_kawin'];
        $participant->jumlah_anak = $input['jumlah_anak'];
    
        $update = $participant->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Peserta berhasil diperbarui.',
                'kode_peserta' => $participant->kode_peserta,
                'nama_peserta' => $participant->nama_peserta,
                'no_medrec' => $participant->no_medrec,
                'nik_peserta' => $participant->nik_peserta,
                'id_departemen' => get_department_name( $participant->id_departemen ),
                'jenis_kelamin' => $participant->jenis_kelamin,
                'tempat_lahir' => $participant->tempat_lahir,
                'tanggal_lahir' => $participant->tanggal_lahir,
                'alamat' => $participant->alamat,
                'kota' => $participant->kota,
                'propinsi' => $participant->provinsi,
                'kodepos' => $participant->kodepos,
                'tanggal_aktif' => $participant->tanggal_aktif,
                'tanggal_nonaktif' => $participant->tanggal_nonaktif,
                'status_aktif' => ( $participant->status_aktif == 1 ) ? 'Aktif' : 'Tidak Aktif',
                'status_kawin' => $participant->status_kawin,
                'jumlah_anak' => $participant->jumlah_anak,
                'id_pengguna' => $participant->id_pengguna,
                'id_peserta' => $participant->id_peserta,
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui peserta.'
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
        $participant = Participant::find( $id );

        $delete = $participant->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Peserta berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus peserta.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
    	$latest_id = Participant::generate_id();

    	$response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }

    public function latest_medrec(){
        $latest_medrec = Participant::generate_medrec();

        $response = array(
            'success' => 'true',
            'latest_medrec' => $latest_medrec,
        );

        return Response::json( $response );
    }

    public function import( Request $request ){
        $factories = Factory::all();
        $clients = Client::all();

        return view( 'import', [ 'factories' => $factories, 'clients' => $clients ] );
    }

    /*public function action_import( Request $request ){
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $input = $request->all();
        $factory = $input['factory'];
        $client = $input['client'];
        $type = $input['type'];

        $file_url = '';
        $file_mimes = array( 'text/csv', 'text/comma-separated-values', 'application/csv', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel' );
        
        $dept_results = Department::all();
        $departments = array();
        foreach ( $dept_results as $dept ) {
            $departments[$dept->kode_departemen] = $dept->id_departemen;
        }

        if( $request->file( 'import' )->isValid() ){
            
            $file_uri = $request->file( 'import' )->getRealPath();

            $file = fopen( $file_uri, "r" );
            $i = 1;

            $success = 0; $failed = 0; $messages = '';

            if( $type == 'add-new' ){
                while( ( $datas = fgetcsv( $file, 0, ',' ) ) !== FALSE ){ 

                    if( $i > 1 ){
                        /*
                        0 - NO,
                        1 - FACT. NO
                        2 - NIK
                        3 - NAMA
                        4 - DEPT CODE
                        5 - DEPT NAME
                        6 - SEX
                        7 - TGL LAHIR
                        8 - TMPT LAHIR
                        9 - ALAMAT
                        10 - BPJS
                        11 - FASKES NO
                        12 - TINGGAL DI MESS
                        */
                        /*
                        $nik = $datas[2];
                        $nama = $datas[3];
                        $dept_code = trim( $datas[4] );
                        $dept_name = trim( $datas[5] );
                        $sex = $datas[6];
                        $tgl_lahir= $datas[7];
                        $tmpt_lahir = $datas[8];
                        $alamat = $datas[9];

                        $participant = Participant::where( 'nik_peserta', '=', $nik )->first();

                        if( !$participant ){
                            $participant = new Participant();
                            $participant->kode_peserta = Participant::generate_id();
                            $participant->id_pengguna = $idpengguna;
                            $participant->no_medrec = Participant::generate_medrec();
                            $participant->nik_peserta = $nik;
                        }

                        $participant->nama_peserta = ucwords( strtolower( $nama ) );
                        $participant->jenis_kelamin = ( $sex == 'M' ? 'laki-laki' : 'perempuan' );
                        $participant->tanggal_aktif = date( 'Y-m-d' );
                        $participant->tanggal_lahir = generate_date_from_number( $tgl_lahir );
                        $participant->tempat_lahir = $tmpt_lahir;
                        $participant->alamat = ucwords( strtolower( $alamat ) );
                        $participant->status_aktif = 1;

                        if( isset( $departments[$dept_code] ) && $departments[$dept_code] ){
                            $participant->id_departemen = $departments[$dept_code];
                        }else{
                            $dept_code = !empty( $dept_code ) ? $dept_code : Department::generate_id(); 

                            $department = new Department();
                            $department->kode_departemen = $dept_code;
                            $department->nama_departemen = $dept_name;
                            $department->nama_factory = $factory;
                            $department->nama_client = $client;
                            $department->id_pengguna = $idpengguna;

                            $department->save();

                            $departments[$department->kode_departemen] = $department->id_departemen;

                            $participant->id_departemen = $department->id_departemen;
                        }

                        $update = $participant->save();

                        if( $update ){
                            $success++;

                            $messages .= '<div class="alert alert-success">Peserta dengan NIK ' . $nik . ' berhasil ditambahkan/diperbarui.</div>';
                        } else {
                            $failed++;

                            $messages .= '<div class="alert alert-danger">Gagal menambahkan/memperbarui peserta dengan nik ' . $nik . '</div>';
                        } 
                    }

                    $i++;
                }
            }else{
                while( ( $datas = fgetcsv( $file, 0, ',' ) ) !== FALSE ){ 

                    if( $i > 1 ){
                        // NO, NIK, NAMA, TGL NONAKTIF
                        $nik = $datas[1];
                        $tgl_nonaktif = $datas[3];

                        $participant = Participant::where( 'nik_peserta', '=', $nik )->first();

                        if( $participant ){
                            $participant->status_aktif = 0;
                            $participant->tanggal_nonaktif = $tgl_nonaktif;

                            $update = $participant->save();

                            if( $update ){
                                $success++;

                                $messages .= '<div class="alert alert-success">Peserta dengan NIK ' . $nik . ' berhasil dinonaktifkan.</div>';
                            } else {
                                $failed++;

                                $messages .= '<div class="alert alert-danger">Gagal menonaktifkan peserta dengan nik ' . $nik . '.</div>';
                            } 
                        }else{
                            $failed++;

                            $messages .= '<div class="alert alert-danger">Peserta dengan nik ' . $nik . ' tidak ditemukan.</div>';
                        }
                    }

                    $i++;
                }
            }

            fclose( $file );

            $messages .= '<div class="alert alert-warning">Berhasil diproses: ' . $success . '<br />Gagal diproses: ' . $failed . '</div>';

            $response = array(
                "message" => $messages,
                "state" => "success"
            );
        }else{
            $response = array(
                "message" => '<div class="alert alert-danger">Proses upload gagal</div>',
                "state" => "failed"
            );
        }

        return Response::json( $response );
    }*/

    public function action_import( Request $request ){
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $input = $request->all();

        $file_url = '';
        $file_mimes = array( 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' );
        
        if( $request->file( 'import' )->isValid() ){
            
            $file_uri = $request->file( 'import' )->getRealPath(); 

            $results = null;

            // Excel::load( $file_uri, function($reader) use( $results ) {
            //     // Getting all results
            //     $results = $reader->get();
            // })->get();

            $datas = Excel::load( $file_uri )->get();

            $response = array(
                "datas" => $datas,
                "count" => count( $datas ),
                "state" => "success"
            );
        }else{
            $response = array(
                "message" => '<div class="alert alert-danger">Proses upload gagal</div>',
                "state" => "failed"
            );
        }

        return Response::json( $response );
    }

    public function do_import( Request $request ){
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $input = $request->all();
        $factory = $input['factory'];
        $client = $input['client'];
        $type = $input['type'];

        $dept_results = Department::all();
        $departments = array();
        foreach ( $dept_results as $dept ) {
            $departments[$dept->kode_departemen] = $dept->id_departemen;
        }

        $success = false; $messages = '';

        if( $type == 'add-new' ){
        
            $nik = $input['datas']['nik'];
            $nama = $input['datas']['nama'];
            $dept_code = trim( $input['datas']['dept_code'] );
            $dept_name = trim( $input['datas']['dept_name'] );
            $sex = $input['datas']['sex'];
            $tgl_lahir= $input['datas']['tgl_lahir'];
            $tmpt_lahir = $input['datas']['tempat_lahir'];
            $alamat = $input['datas']['alamat'];

            $participant = Participant::where( 'nik_peserta', '=', $nik )->first();

            if( !$participant ){
                $participant = new Participant();
                $participant->kode_peserta = Participant::generate_id();
                $participant->id_pengguna = $idpengguna;
                $participant->no_medrec = Participant::generate_medrec();
                $participant->nik_peserta = $nik;
            }

            $participant->nama_peserta = ucwords( strtolower( $nama ) );
            $participant->jenis_kelamin = ( $sex == 'M' ? 'laki-laki' : 'perempuan' );
            $participant->tanggal_aktif = date( 'Y-m-d' );
            $participant->tanggal_lahir = generate_date_from_number( $tgl_lahir );
            $participant->tempat_lahir = $tmpt_lahir;
            $participant->alamat = ucwords( strtolower( $alamat ) );
            $participant->status_aktif = 1;

            if( isset( $departments[$dept_code] ) && $departments[$dept_code] ){
                $participant->id_departemen = $departments[$dept_code];
            }else{
                $dept_code = !empty( $dept_code ) ? $dept_code : Department::generate_id(); 

                $department = new Department();
                $department->kode_departemen = $dept_code;
                $department->nama_departemen = $dept_name;
                $department->nama_factory = $factory;
                $department->nama_client = $client;
                $department->id_pengguna = $idpengguna;

                $department->save();

                $departments[$department->kode_departemen] = $department->id_departemen;

                $participant->id_departemen = $department->id_departemen;
            }

            $update = $participant->save();

            if( $update ){
                $success = true;

                $messages .= '<div class="alert alert-success">Peserta dengan NIK ' . $nik . ' berhasil ditambahkan/diperbarui.</div>';
            } else {
                $messages .= '<div class="alert alert-danger">Gagal menambahkan/memperbarui peserta dengan nik ' . $nik . '</div>';
            } 
                


        }else{
            $nik = $input['datas']['nik'];
            $tgl_nonaktif = $input['datas']['tgl_nonaktif'];

            $participant = Participant::where( 'nik_peserta', '=', $nik )->first();

            if( $participant ){
                $participant->status_aktif = 0;
                $participant->tanggal_nonaktif = $tgl_nonaktif;

                $update = $participant->save();

                if( $update ){
                    $success = true;

                    $messages .= '<div class="alert alert-success">Peserta dengan NIK ' . $nik . ' berhasil dinonaktifkan.</div>';
                } else {
                    $messages .= '<div class="alert alert-danger">Gagal menonaktifkan peserta dengan nik ' . $nik . '.</div>';
                } 
            }else{
                $messages .= '<div class="alert alert-danger">Peserta dengan nik ' . $nik . ' tidak ditemukan.</div>';
            }
                
        }


        $response = array(
            'state' => $success,
            'message' => $messages
        );

        return Response::json( $response );
    }
}
