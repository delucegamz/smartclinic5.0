<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Staff;
use App\JobTitle;
use App\Province;
use DB;
use Response;
use Auth;
use URL;


class StaffController extends Controller
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
        if( !current_user_can( 'data_karyawan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
    	        $datas = Staff::all();
            else
                $datas = Staff::where( function( $q ) use ( $s ){
                    $q->where( 'kode_karyawan', 'like', "%" . $s . "%" )
                      ->orWhere( 'nik_karyawan', 'like', "%" . $s . "%" )
                      ->orWhere( 'nama_karyawan', 'like', "%" . $s . "%" );
                } )->get();

        }else{
            if( empty( $s ) )
                $datas = Staff::paginate( $rows );
            else
                $datas = Staff::where( function( $q ) use ( $s ){
                    $q->where( 'kode_karyawan', 'like', "%" . $s . "%" )
                      ->orWhere( 'nik_karyawan', 'like', "%" . $s . "%" )
                      ->orWhere( 'nama_karyawan', 'like', "%" . $s . "%" );
                } )->paginate( $rows );

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $jobtitles = JobTitle::all();
        $provinces = Province::all();

    	return view( 'staff', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'jobtitles' => $jobtitles, 'provinces' => $provinces ]);
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
            $staff = new Staff();

            $staff->kode_karyawan = Staff::generate_id();
            $staff->nik_karyawan = Staff::generate_nik();
            $staff->nama_karyawan = $input['nama_karyawan']; 
            $staff->id_jabatan = $input['id_jabatan']; 
            $staff->jenis_kelamin = $input['jenis_kelamin']; 
            $staff->status_kawin = $input['status_kawin']; 
            $staff->jumlah_anak = $input['jumlah_anak']; 
            $staff->t_badan = $input['t_badan']; 
            $staff->b_badan = $input['b_badan']; 
            $staff->tempat_lahir = $input['tempat_lahir']; 
            $staff->tanggal_lahir = $input['tanggal_lahir'];
            $staff->alamat = $input['alamat'];
            $staff->kota = $input['kota'];
            $staff->provinsi = $input['propinsi'];
            $staff->kode_pos = $input['kode_pos'];
            $staff->no_telepon = $input['no_telepon'];
            $staff->email = $input['email'];
            $staff->agama = $input['agama'];
            $staff->bank = $input['bank'];
            $staff->no_rekening = $input['no_rekening'];
            $staff->jenis_id = $input['jenis_id'];
            $staff->no_id = $input['no_id'];
            $staff->no_KK = $input['no_kk'];
            $staff->no_bpjs = $input['no_bpjs'];
            $staff->no_jamsostek = $input['no_jamsostek'];
            $staff->status = $input['status'];
            //$staff->foto_karyawan = $input['foto_karyawan'];
            $staff->id_pengguna = $idpengguna;

            $insert = $staff->save();

            if( $insert ){
                $html = '';  $i = 1; $pagination = '';
                $rows = $input['rows'];

                $staffes = Staff::paginate( $rows );

                foreach( $staffes as $s ){
                    $html .= '<tr class="item" id="item-'. $s->id_karyawan . '">
                                <td class="column-no">' . $i . '</td>
                                <td class="column-nik">' . $s->nik_karyawan . '</td>
                                <td class="column-name">' . $s->nama_karyawan . '</td>
                                <td class="column-sex">' . $s->jenis_kelamin . '</td>
                                <td class="column-phone">' . ( $s->no_telepon ? $s->no_telepon : '-' ) . '</td>
                                <td class="column-position">' . ( $s->id_jabatan ? get_job_title_name( $s->id_jabatan ) : '-' ) . '</td>
                                <td class="column-action">
                                    <a href="#" title="Edit" class="edit" data-id="' . $s->id_karyawan . '"><img src="' . URL::asset( 'assets/images/icon-file.png' ) . '" alt="Edit" /></a>
                                </td>
                            <tr>';

                    $i++;
                }

                $pagination = generate_pagination( $staffes, $rows );

                $response = array(
                    'success' => 'true',
                    'message' => 'Karyawan berhasil ditambahkan.',
                    'staff' => $staff,
                    'html' => $html,
                    'pagination' => $pagination
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan karyawan.'
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
        $staff = Staff::find( $id );

        if( $staff ){
            $response = array(
                'success' => 'true',
                'message' => '',
                'staff' => $staff
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Data tidak ditemukan',
                'staff' => ''
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
    public function edit( $id )
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

        $staff = Staff::find( $id );

        $staff->nama_karyawan = $input['nama_karyawan']; 
        $staff->id_jabatan = $input['id_jabatan']; 
        $staff->jenis_kelamin = $input['jenis_kelamin']; 
        $staff->status_kawin = $input['status_kawin']; 
        $staff->jumlah_anak = $input['jumlah_anak']; 
        $staff->t_badan = $input['t_badan']; 
        $staff->b_badan = $input['b_badan']; 
        $staff->tempat_lahir = $input['tempat_lahir']; 
        $staff->tanggal_lahir = $input['tanggal_lahir'];
        $staff->alamat = $input['alamat'];
        $staff->kota = $input['kota'];
        $staff->provinsi = $input['propinsi'];
        $staff->kode_pos = $input['kode_pos'];
        $staff->no_telepon = $input['no_telepon'];
        $staff->email = $input['email'];
        $staff->agama = $input['agama'];
        $staff->bank = $input['bank'];
        $staff->no_rekening = $input['no_rekening'];
        $staff->jenis_id = $input['jenis_id'];
        $staff->no_id = $input['no_id'];
        $staff->no_KK = $input['no_kk'];
        $staff->no_bpjs = $input['no_bpjs'];
        $staff->no_jamsostek = $input['no_jamsostek'];
        $staff->status = $input['status'];
        //$staff->foto_karyawan = $input['foto_karyawan'];
    
        $update = $staff->save();

        if( $update !== false ){
            $html = '';  $i = 1; $pagination = '';
            $rows = $input['rows'];

            $staffes = Staff::paginate( $rows );

            foreach( $staffes as $s ){
                $html .= '<tr class="item" id="item-'. $s->id_karyawan . '">
                            <td class="column-no">' . $i . '</td>
                            <td class="column-nik">' . $s->nik_karyawan . '</td>
                            <td class="column-name">' . $s->nama_karyawan . '</td>
                            <td class="column-sex">' . $s->jenis_kelamin . '</td>
                            <td class="column-phone">' . ( $s->no_telepon ? $s->no_telepon : '-' ) . '</td>
                            <td class="column-position">' . ( $s->id_jabatan ? get_job_title_name( $s->id_jabatan ) : '-' ) . '</td>
                            <td class="column-action">
                                <a href="#" title="Edit" class="edit" data-id="' . $s->id_karyawan . '"><img src="' . URL::asset( 'assets/images/icon-file.png' ) . '" alt="Edit" /></a>
                            </td>
                        <tr>';

                $i++;
            }

            $pagination = generate_pagination( $staffes, $rows );

            $response = array(
                'success' => 'true',
                'message' => 'Karyawan berhasil diperbarui.',
                'staff' => $staff,
                'html' => $html,
                'pagination' => $pagination
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui karyawan.'
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
        $staff = Staff::find( $id );

        $delete = $staff->delete();

        if( $delete ){
            $html = '';  $i = 1; $pagination = '';
            $rows = 10;

            $staffes = Staff::paginate( $rows );

            if( count( $staffes ) > 1 ){
                foreach( $staffes as $s ){
                    $html .= '<tr class="item" id="item-'. $s->id_karyawan . '">
                                <td class="column-no">' . $i . '</td>
                                <td class="column-nik">' . $s->nik_karyawan . '</td>
                                <td class="column-name">' . $s->nama_karyawan . '</td>
                                <td class="column-sex">' . $s->jenis_kelamin . '</td>
                                <td class="column-phone">' . ( $s->no_telepon ? $s->no_telepon : '-' ) . '</td>
                                <td class="column-position">' . ( $s->id_jabatan ? get_job_title_name( $s->id_jabatan ) : '-' ) . '</td>
                                <td class="column-action">
                                    <div class="action-item first">
                                        <a href="#" title="Edit" class="edit" data-id="' . $s->id_karyawan . '"><img src="' . URL::asset( 'assets/images/icon-file.png' ) . '" alt="Edit" /></a>
                                    </div>
                                    <div class="action-item last">
                                        <a href="#" title="Delete" class="delete" data-id="' . $s->id_karyawan . '"><img src="' . URL::asset( 'assets/images/icon-delete.png' ) . '" alt="Delete" /></a>
                                    </div>
                                </td>
                            <tr>';

                    $i++;
                }
            }else{
                $html = '<tr class="no-data">
                            <td colspan="7">Tidak ada data yang ditemukan.</td>
                        </tr>';
            }

            $pagination = generate_pagination( $staffes, $rows );

            $response = array(
                'success' => 'true',
                'message' => 'Karyawan berhasil dihapus.',
                'html' => $html,
                'pagination' => $pagination
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus karyawan.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
    	$kode_karyawan = Staff::generate_id();
        $nik_karyawan = Staff::generate_nik();

    	$response = array(
            'success' => 'true',
            'kode_karyawan' => $kode_karyawan,
            'nik_karyawan' => $nik_karyawan,
        );

        return Response::json( $response );
    }

    public function search_staff( Request $request ){
        $input = $request->all();

        $value = $input['value'];

        $staffes = Staff::where( function($q) use ( $value ){
            $q->where( 'nik_karyawan', 'like', "%" . $value . "%" )
              ->orWhere( 'nama_karyawan', 'like', "%" . $value . "%" );
        })->get(); 

        $responses = array();

        if( $staffes ){
            if( count( $staffes ) == 1 ){
                $responses = array(
                    'nama_karyawan' => $staffes[0]['nama_karyawan'],
                    'nik_karyawan' => $staffes[0]['nik_karyawan'],
                    'email' => $staffes[0]['email'],
                    'display_name' => $staffes[0]['nik_karyawan'] .  ' - ' .$staffes[0]['nama_karyawan'],
                    'no_telepon' => $staffes[0]['no_telepon'],
                    'id_karyawan' => $staffes[0]['id_karyawan'],
                    'jabatan' => get_job_title_name( $staffes[0]['id_jabatan'] ),
                    'status' => $staffes[0]['status'],
                    'success' => 'true',
                    'type' => 'single'
                );
            }elseif( count( $staffes ) > 1 ) {
                $responses['success'] = 'true';
                $responses['type'] = 'list';
                $responses['data'] = array();

                foreach( $staffes as $staff ){
                    $responses['data'][] = array(
                        'nama_karyawan' => $staff['nama_karyawan'],
                        'nik_karyawan' => $staff['nik_karyawan'],
                        'email' => $staff['email'],
                        'display_name' => $staff['nik_karyawan'] .  ' - ' .$staff['nama_karyawan'],
                        'no_telepon' => $staff['no_telepon'],
                        'id_karyawan' => $staff['id_karyawan'],
                        'jabatan' => get_job_title_name( $staff['id_jabatan'] ),
                        'status' => $staff['status'],
                    );
                }
            }

        }else{
            $responses = array(
                'success' => 'false',
            );
        }

        return Response::json( $responses );
    }
}
