<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\PoliRegistration;
use App\Participant;
use App\MedicalRecord;
use App\Poli;
use App\Department;
use DB;
use Response;
use Auth;

class PoliRegistrationController extends Controller
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
        if( !current_user_can( 'pendaftaran_poli' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

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
    	        $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                         ->where( 'status', '=', 1 )
                                         ->where( 'id_poli', '!=', 1 )
                                         ->orderBy( 'no_antrian', 'desc' )
                                         ->get();
            else{
            	$participants = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

            	$ids = array();
            	foreach ( $participants as $p ) {
            		$ids[] = $p->id_peserta;
            	}

                $datas = PoliRegistration::whereIn( 'id_peserta',  $ids )
                                         ->where( 'id_poli', '!=', 1 )
                                         ->orderBy( 'id_pendaftaran', 'desc' )
                                         ->get();
            }

        }else{
            if( empty( $s ) )
                $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                         ->where( 'id_poli', '!=', 1 )
                                         ->where( 'status', '=', 1 )
                                         ->orderBy( 'no_antrian', 'desc' )
                                         ->paginate( $rows );
            else{
            	$participants = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

            	$ids = array();
            	foreach ( $participants as $p ) {
            		$ids[] = $p->id_peserta;
            	}  

                $datas = PoliRegistration::whereIn( 'id_peserta',  $ids )
                                         ->where( 'id_poli', '!=', 1 )
                                         ->orderBy( 'id_pendaftaran', 'desc' )
                                         ->paginate( $rows );
            }
           
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $poli = Poli::where( 'id_poli', '<>', 1 )->get();

        $departments = Department::orderBy( 'nama_departemen', 'asc')->get();

    	return view( 'poliregistration', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'poli' => $poli, 'departments' => $departments ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function emergency()
    {
        if( !current_user_can( 'pendaftaran_igd' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
                $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                         ->where( 'status', '=', 1 )
                                         ->where( 'id_poli', '=', 1 )
                                         ->orderBy( 'id_pendaftaran', 'asc' )
                                         ->get();
            else{
                $participants = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

                $ids = array();
                foreach ( $participants as $p ) {
                    $ids[] = $p->id_peserta;
                }

                $datas = PoliRegistration::whereIn( 'id_peserta',  $ids )
                                         ->where( 'id_poli', '=', 1 )
                                         ->orderBy( 'id_pendaftaran', 'desc' )
                                         ->get();
            }

        }else{
            if( empty( $s ) )
                $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                         ->where( 'status', '=', 1 )
                                         ->where( 'id_poli', '=', 1 )
                                         ->orderBy( 'id_pendaftaran', 'asc' )
                                         ->paginate( $rows );
            else{
                $participants = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

                $ids = array();
                foreach ( $participants as $p ) {
                    $ids[] = $p->id_peserta;
                }  

                $datas = PoliRegistration::whereIn( 'id_peserta',  $ids )
                                         ->where( 'id_poli', '=', 1 )
                                         ->orderBy( 'id_pendaftaran', 'desc' )
                                         ->paginate( $rows );
            }
           
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $poli = Poli::all();

        $departments = Department::orderBy( 'nama_departemen', 'asc')->get();

        return view( 'emergency', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'poli' => $poli, 'departments' => $departments ]);
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
            $poliregistration = new PoliRegistration();

            $tgl_daftar = $input['tanggal'];
            $id_peserta = $input['id_peserta'];
            $poli = $input['poli'];
            $catatan = ( $poli != 1 ? $input['catatan'] : $input['uraian'] );

            if( !$id_peserta ){
                $nama = $input['nama'];
                $medrec = $input['medrec'];
                $department = $input['department'];
                $sex = $input['sex'];
                $age = $input['age'];

                $participant = new Participant();
                $participant->kode_peserta = Participant::generate_id();
                $participant->no_medrec = Participant::generate_medrec();
                $participant->nik_peserta = $medrec;
                $participant->nama_peserta = $nama;
                $participant->id_departemen = $department;
                $participant->jenis_kelamin = $sex;
                $participant->tanggal_lahir = $age;
                $participant->tanggal_aktif = date( 'Y-m-d' );
                $participant->status_aktif = 1;
                $participant->id_pengguna = $idpengguna;
                $participant->save();

                $id_peserta = $participant->id_peserta;
            }

            if( $poli != 1 ){
                $poliregistration->no_antrian = PoliRegistration::get_ordering_no();
            }else{
                $poliregistration->no_antrian = 0;
            }

            $poliregistration->no_daftar = PoliRegistration::generate_id();
            $poliregistration->id_peserta = $id_peserta;
            $poliregistration->id_poli = $poli;
            $poliregistration->tgl_daftar = $tgl_daftar;
            $poliregistration->catatan_pendaftaran = $catatan;
            $poliregistration->status = 1;
            $poliregistration->id_pengguna = $idpengguna;
            $poliregistration->user_update = $idpengguna;

            $insert = $poliregistration->save();

            $igd = (int) get_setting( 'igd' );
            $poli_umum = (int) get_setting( 'poli_umum' );
            $poli_kebidanan = (int) get_setting( 'poli_kebidanan' );

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Pendaftaran ' . ( $poliregistration->id_poli == $igd ? 'IGD' : 'poli' ) . ' berhasil ditambahkan.',
                    'no_daftar' => $poliregistration->no_daftar,
					'tanggal_daftar' => date( 'Y-m-d H:i:s', strtotime( $poliregistration->tgl_daftar ) ),
					'no_antrian' => $poliregistration->no_antrian,
					'nik_peserta' => get_participant_nik( $poliregistration->id_peserta ),
					'nama_peserta' => get_participant_name( $poliregistration->id_peserta ),
					'umur_peserta' => get_participant_age( $poliregistration->id_peserta ),
					'jenis_kelamin' => get_participant_sex( $poliregistration->id_peserta ),
					'unit_kerja' => get_participant_department( $poliregistration->id_peserta ),
					'pabrik' => get_participant_factory( $poliregistration->id_peserta ),
					'perusahaan' => get_participant_client( $poliregistration->id_peserta ),
					'nama_poli' => get_poli_name( $poliregistration->id_poli ),
					'catatan' => $poliregistration->catatan_pendaftaran,
                    'id_pendaftaran' => $poliregistration->id_pendaftaran,
                    'id_peserta' => $poliregistration->id_peserta
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan pendaftaran.',
                );
            }

        } catch (\Exception $e) {
            // @TODO: check the erros
            $response = array(
                'success' => 'false',
                'message' => $e->getmessage()
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
        $poliregistration = PoliRegistration::find( $id );
        //$medicalrecord = MedicalRecord::where( 'id_pendaftaran_poli', '=', $poliregistration->id_pendaftaran )->first();

        $response = array(
            'no_daftar' => $poliregistration->no_daftar,
            'tanggal_daftar' => date( 'Y-m-d H:i:s', strtotime( $poliregistration->tgl_daftar ) ),
            'no_antrian' => $poliregistration->no_antrian,
            'nik_peserta' => get_participant_nik( $poliregistration->id_peserta ),
            'nama_peserta' => get_participant_name( $poliregistration->id_peserta ),
            'umur_peserta' => get_participant_age( $poliregistration->id_peserta ),
            'jenis_kelamin' => get_participant_sex( $poliregistration->id_peserta ),
            'sex' => get_participant_sex( $poliregistration->id_peserta ),
            'unit_kerja' => get_participant_department( $poliregistration->id_peserta ),
            'department' => get_participant_department( $poliregistration->id_peserta, true ),
            'pabrik' => get_participant_factory( $poliregistration->id_peserta ),
            'perusahaan' => get_participant_client( $poliregistration->id_peserta ),
            'nama_poli' => $poliregistration->id_poli,
            'catatan' => $poliregistration->catatan_pendaftaran,
            'id_pendaftaran' => $poliregistration->id_pendaftaran,
            'id_peserta' => $poliregistration->id_peserta,
            //'uraian' => $medicalrecord->uraian
        );

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
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $input = $request->all();

        $tgl_daftar = $input['tanggal'];
        $id_peserta = $input['id_peserta'];
        $poli = $input['poli'];
        $catatan = ( $poli != 1 ? $input['catatan'] : $input['uraian'] );

        $poliregistration = PoliRegistration::find( $id );

        $poliregistration->id_peserta = $id_peserta;
        $poliregistration->id_poli = $poli;
        $poliregistration->tgl_daftar = $tgl_daftar;
        $poliregistration->catatan_pendaftaran = $catatan;
        $poliregistration->user_update = $idpengguna;
    
        $update = $poliregistration->save();

        if( $update !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Poli berhasil diperbarui.',
                'no_daftar' => $poliregistration->no_daftar,
				'tanggal_daftar' => date( 'Y-m-d H:i:s', strtotime( $poliregistration->tgl_daftar ) ),
				'no_antrian' => $poliregistration->no_antrian,
				'nik_peserta' => get_participant_nik( $poliregistration->id_peserta ),
				'nama_peserta' => get_participant_name( $poliregistration->id_peserta ),
				'umur_peserta' => get_participant_age( $poliregistration->id_peserta ),
				'jenis_kelamin' => get_participant_sex( $poliregistration->id_peserta ),
				'unit_kerja' => get_participant_department( $poliregistration->id_peserta ),
				'pabrik' => get_participant_factory( $poliregistration->id_peserta ),
				'perusahaan' => get_participant_client( $poliregistration->id_peserta ),
				'nama_poli' => get_poli_name( $poliregistration->id_poli ),
				'catatan' => $poliregistration->catatan_pendaftaran,
                'id_pendaftaran' => $poliregistration->id_pendaftaran
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui poli.'
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
        $poliregistration = PoliRegistration::find( $id );

        $delete = $poliregistration->delete();

        if( $delete ){
            $medicalrecord = MedicalRecord::where( 'id_pendaftaran_poli', '=', $id )->first();

            if( $medicalrecord ) $delete_medrec = $medicalrecord->delete();

            $response = array(
                'success' => 'true',
                'message' => 'Pendaftaran berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus pendaftaran.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
    	$latest_id = PoliRegistration::generate_id();

    	$response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }

    public function ordering_no(){
    	$latest_no = PoliRegistration::get_ordering_no();

    	$response = array(
            'success' => 'true',
            'latest_id' => $latest_no,
        );

        return Response::json( $response );
    }

    public function search_id_card(Request $request){
    	$input = $request->all();

        $value = $input['val'];

        $participants = Participant::where( function($q) use ( $value ){
            $q->where( 'no_medrec', 'like', "%" . $value . "%" )
              ->orWhere( 'nik_peserta', 'like', "%" . $value . "%" )
              ->orWhere( 'nama_peserta', 'like', "%" . $value . "%" );
        })->get(); 

        $responses = array();

        if( $participants ){
            foreach( $participants as $participant ){
                $responses[] = array(
                    'nama_peserta' => $participant['nama_peserta'],
                    'nik_peserta' => $participant['nik_peserta'],
                    'no_medrec' => $participant['no_medrec'],
                    'display_name' => $participant['no_medrec'] . '/' . $participant['nik_peserta'] . ' - ' .$participant['nama_peserta'],
                    'umur' => get_age_by_mysql_date( $participant['tanggal_lahir'] ),
                    'jenis_kelamin' => ucwords( $participant['jenis_kelamin'] ),
                    'unit_kerja' => get_department_name( $participant['id_departemen'] ),
                    'pabrik' => get_participant_factory( $participant['id_peserta'] ),
                    'perusahaan' => get_participant_client( $participant['id_peserta'] ),
                    'id_peserta' => $participant['id_peserta']
                );
            }

        }else{
			$responses = array(
	            'success' => 'false',
        	);
        }

        return Response::json( $responses );
    }

    public function search_medrec( Request $request ){
        $input = $request->all();

        $value = $input['value'];

        $participants = Participant::where( function($q) use ( $value ){
            $q->where( 'no_medrec', 'like', "%" . $value . "%" )
              ->orWhere( 'nik_peserta', 'like', "%" . $value . "%" )
              ->orWhere( 'nama_peserta', 'like', "%" . $value . "%" );
        })->get(); 

        $responses = array();

        if( count( $participants ) ){
            if( count( $participants ) == 1 ){
                if( $participants[0]['status_aktif'] == 1 ){
                    $responses = array(
                        'nama_peserta' => $participants[0]['nama_peserta'],
                        'nik_peserta' => $participants[0]['nik_peserta'],
                        'no_medrec' => $participants[0]['no_medrec'],
                        'display_name' => $participants[0]['no_medrec'] . '/' . $participants[0]['nik_peserta'] . ' - ' .$participants[0]['nama_peserta'],
                        'umur' => get_age_by_mysql_date( $participants[0]['tanggal_lahir'] ),
                        'jenis_kelamin' => ucwords( $participants[0]['jenis_kelamin'] ),
                        'sex' => $participants[0]['jenis_kelamin'],
                        'unit_kerja' => get_department_name( $participants[0]['id_departemen'] ),
                        'department' => $participants[0]['id_departemen'],
                        'pabrik' => get_participant_factory( $participants[0]['id_peserta'] ),
                        'perusahaan' => get_participant_client( $participants[0]['id_peserta'] ),
                        'id_peserta' => $participants[0]['id_peserta'],
                        'status' => $participants[0]['status_aktif'],
                        'success' => 'true',
                        'type' => 'single'
                    );
                }else{
                    $responses = array(
                        'nama_peserta' => $participants[0]['nama_peserta'],
                        'nik_peserta' => $participants[0]['nik_peserta'],
                        'no_medrec' => $participants[0]['no_medrec'],
                        'display_name' => $participants[0]['no_medrec'] . '/' . $participants[0]['nik_peserta'] . ' - ' .$participants[0]['nama_peserta'],
                        'umur' => get_age_by_mysql_date( $participants[0]['tanggal_lahir'] ),
                        'jenis_kelamin' => ucwords( $participants[0]['jenis_kelamin'] ),
                        'sex' => $participants[0]['jenis_kelamin'],
                        'unit_kerja' => get_department_name( $participants[0]['id_departemen'] ),
                        'department' => $participants[0]['id_departemen'],
                        'pabrik' => get_participant_factory( $participants[0]['id_peserta'] ),
                        'perusahaan' => get_participant_client( $participants[0]['id_peserta'] ),
                        'id_peserta' => $participants[0]['id_peserta'],
                        'status' => $participants[0]['status_aktif'],
                        'success' => 'resigned',
                        'type' => 'single',
                        'message' => 'Karyawan sudah tidak aktif/resign'
                    );
                }
            }elseif( count( $participants ) > 1 ) {
                $responses['success'] = 'true';
                $responses['type'] = 'list';
                $responses['data'] = array();

                foreach( $participants as $participant ){
                    if( $participant['status_aktif'] == 1 ){
                        $responses['data'][] = array(
                            'nama_peserta' => $participant['nama_peserta'],
                            'nik_peserta' => $participant['nik_peserta'],
                            'no_medrec' => $participant['no_medrec'],
                            'display_name' => $participant['no_medrec'] . '/' . $participant['nik_peserta'] . ' - ' .$participant['nama_peserta'],
                            'umur' => get_age_by_mysql_date( $participant['tanggal_lahir'] ),
                            'jenis_kelamin' => ucwords( $participant['jenis_kelamin'] ),
                            'sex' => $participant['jenis_kelamin'],
                            'unit_kerja' => get_department_name( $participant['id_departemen'] ),
                            'department' => $participant['id_departemen'],
                            'pabrik' => get_participant_factory( $participant['id_peserta'] ),
                            'perusahaan' => get_participant_client( $participant['id_peserta'] ),
                            'id_peserta' => $participant['id_peserta'],
                            'status' => $participant['status_aktif'],
                            'success' => 'true',
                            'type' => 'list'
                        );
                    }
                }
            }

        }else{
            $responses['success'] = 'notfound';
            $responses['message'] = 'Karyawan tidak ditemukan';
        }

        return Response::json( $responses );
    }
}
