<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Staff;
use App\AccessRight;
use DB;
use Response;
use Auth;
use URL;


class UserController extends Controller
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
        if( !current_user_can( 'data_pengguna' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
                $datas = User::all();
            else
                $datas = User::where( 'username', 'LIKE', "%" . $s . "%" )->get();
        }else{
            if( empty( $s ) )
                $datas = User::paginate( $rows );
            else
                $datas = User::where( 'username', 'LIKE', "%" . $s . "%" )->paginate( $rows );
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        return view( 'user', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
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
        $input = $request->all();

        try {
            $check_user = User::where( function ( $q ) use ( $input ) {
                $q->where( 'username', 'LIKE', $input['username'] )
                  ->orWhere( 'id_karyawan', '=', $input['id_karyawan'] );
            } )->first();   

            if( !$check_user ){
                $user = new User();

                $staff = Staff::where( 'id_karyawan', '=', $input['id_karyawan'] )->first();

                $user->idpengguna = User::generate_id();
                $user->id_karyawan = $input['id_karyawan']; 
                $user->username = $input['username']; 
                $user->password = bcrypt( $input['password_1'] ); 
                $user->email = $staff->email; 
                $user->status = $input['status'];

                $insert = $user->save();

                if( $insert ){
                    if( isset( $input['access-rights'] ) && is_array( $input['access-rights'] ) && count( $input['access-rights'] ) > 0 ){
                        $accrights = AccessRight::where( 'id_pengguna', '=', $user->idpengguna )->first();

                        if( !$accrights ){
                            $accrights = new AccessRight();

                            $accrights->no_hak_akses = AccessRight::generate_id();
                            $accrights->id_pengguna = $user->idpengguna;
                        }

                        $access_rights = array();

                        foreach( $input['access-rights'] as $key => $value ){
                            $access_rights[] = $value;
                        }

                        $accrights->hak_akses = serialize( $access_rights );

                        $accrights->save();
                    }

                    $html = '';  $i = 1; $pagination = '';
                    $rows = $input['rows'];

                    $users = User::paginate( $rows );

                    foreach( $users as $s ){
                        $staff = Staff::where( 'id_karyawan', '=', $s->id_karyawan )->first();

                        $html .= '<tr class="item" id="item-'. $s->idpengguna . '">
                                    <td class="column-no">' . $i . '</td>
                                    <td class="column-id">' . $s->idpengguna . '</td>
                                    <td class="column-username">' . $s->username . '</td>
                                    <td class="column-id-staff">' . $s->id_karyawan . '</td>
                                    <td class="column-nama-karyawan">' . $staff->nama_karyawan . '</td>
                                    <td class="column-jabatan">' . ( $s->id_jabatan ? get_job_title_name( $s->id_jabatan ) : '-' ) . '</td>
                                    <td class="column-status">' . ( $s->status ? 'Aktif' : 'Tidak Aktif' ) . '</td>
                                    <td class="column-action">
                                        <a href="#" title="Edit" class="edit" data-id="' . $s->idpengguna . '"><img src="' . URL::asset( 'assets/images/icon-file.png' ) . '" alt="Edit" /></a>
                                    </td>
                                <tr>';

                        $i++;
                    }

                    $pagination = generate_pagination( $users, $rows );

                    $response = array(
                        'success' => 'true',
                        'message' => 'Pengguna berhasil ditambahkan.',
                        'user' => $user,
                        'html' => $html,
                        'pagination' => $pagination
                    );
                }else{
                    $response = array(
                        'success' => 'false',
                        'message' => 'Terjadi kesalahan ketika menambahkan pengguna.'
                    );
                }
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Username telah terpakai atau karyawan sudah terdaftar sebelumnya.'
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
     * Update user profile
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_profile(Request $request)
    {
        $currentuser = Auth::user();

        $idpengguna = $currentuser['original']['idpengguna'];

        $user = User::find( $idpengguna );

        $input = $request->all();

        try {
            if( $user->username != $input['username'] ){
                $check_user = User::where( function ( $q ) use ( $input ) {
                    $q->where( 'username', 'LIKE', $input['username'] );
                } )->first();   

                if( $check_user ){
                    return redirect( 'user/profile' )->with( 'error', 'Username yang dipilih sudah terdaftar, harap pilih username yang lain.' );
                }
            }

            $user->username = $input['username']; 
            $user->email = $input['email']; 

            if( $input['password_1'] && !empty( $input['password_1'] ) ){
                $user->password = bcrypt( $input['password_1'] ); 
            }

            if( $request->file( 'foto' )->isValid() ){
                $file_uri = $request->file( 'foto' )->getRealPath();

                $destinationPath = 'uploads'; // upload path
                $extension = $request->file( 'foto' )->getClientOriginalExtension(); // getting image extension
                $fileName = rand( 11111,99999 ) . '.' . $extension; // renameing image
                $request->file( 'foto' )->move( $destinationPath, $fileName ); // uploading file to given path

                $user->foto = $fileName;
            };
        
            $update = $user->save();

            if( $update ){
                return redirect( 'user/profile' )->with( 'message', 'Profil anda berhasil diperbarui.' );
            }else{
                return redirect( 'user/profile' )->with( 'error', 'Terjadi kesalahan ketika memperbarui profil anda.' );
            }
            

        } catch (\Exception $e) {
            return redirect( 'user/profile' )->with( 'error', 'Terjadi kesalahan ketika memperbarui profil anda. Error message: ' . $e->getMessage() );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $user = User::find( $id );

        if( $user ){
            $staff = Staff::find( $user->id_karyawan );

            $accrights = AccessRight::where( 'id_pengguna', '=', $id )->first();

            $access_rights = array();
            if( $accrights ){
                $accrights_results = unserialize( $accrights->hak_akses );
                
                foreach ( $accrights_results as $key => $value ) {
                    $access_rights[] = $value;
                }
            }

            $response = array(
                'success' => 'true',
                'staff' => $staff,
                'user' => $user,
                'jabatan' => get_job_title_name( $staff->id_jabatan ),
                'access_rights' => $access_rights
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Data tidak ditemukan'
            );
        }

        return Response::json( $response );
    }

    /**
     * Display the current user profile.
     *
     */
    public function profile()
    {
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $datas = User::find( $idpengguna );
        $staff = Staff::find( $datas->id_karyawan );

        return view( 'profile', [ 'datas' => $datas, 'staff' => $staff ]);
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
    public function update( Request $request, $id )
    {
        $input = $request->all();

        try {
            
            $user = User::find( $id );

            $staff = Staff::where( 'id_karyawan', '=', $input['id_karyawan'] )->first();

            if( !empty( $input['password_1'] ) ){
                $user->password = bcrypt( $input['password_1'] ); 
            }
            $user->status = $input['status'];

            $update = $user->save();

            if( isset( $input['access-rights'] ) && is_array( $input['access-rights'] ) && count( $input['access-rights'] ) > 0 ){
                $accrights = AccessRight::where( 'id_pengguna', '=', $user->idpengguna )->first();

                if( !$accrights ){
                    $accrights = new AccessRight();

                    $accrights->no_hak_akses = AccessRight::generate_id();
                    $accrights->id_pengguna = $user->idpengguna;
                }

                $access_rights = array();

                foreach( $input['access-rights'] as $key => $value ){
                    $access_rights[] = $value;
                }

                $accrights->hak_akses = serialize( $access_rights );

                $accrights->save();
            }

            if( $update ){
                $html = '';  $i = 1; $pagination = '';
                $rows = $input['rows'];

                $users = User::paginate( $rows );

                foreach( $users as $s ){
                    $staff = Staff::where( 'id_karyawan', '=', $s->id_karyawan )->first();

                    $html .= '<tr class="item" id="item-'. $s->idpengguna . '">
                                <td class="column-no">' . $i . '</td>
                                <td class="column-id">' . $s->idpengguna . '</td>
                                <td class="column-username">' . $s->username . '</td>
                                <td class="column-id-staff">' . $s->id_karyawan . '</td>
                                <td class="column-nama-karyawan">' . $staff->nama_karyawan . '</td>
                                <td class="column-jabatan">' . ( $s->id_jabatan ? get_job_title_name( $s->id_jabatan ) : '-' ) . '</td>
                                <td class="column-status">' . ( $s->status ? 'Aktif' : 'Tidak Aktif' ) . '</td>
                                <td class="column-action">
                                    <a href="#" title="Edit" class="edit" data-id="' . $s->idpengguna . '"><img src="' . URL::asset( 'assets/images/icon-file.png' ) . '" alt="Edit" /></a>
                                   
                                </td>
                            <tr>';

                    $i++;
                }

                $pagination = generate_pagination( $users, $rows );

                $response = array(
                    'success' => 'true',
                    'message' => 'Pengguna berhasil diperbarui.',
                    'user' => $user,
                    'html' => $html,
                    'pagination' => $pagination
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika memperbarui pengguna.'
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
       //
    }

    public function user_registration(){
        return view( 'user-registration' );
    }

    public function latest_id(){
        $latest_id = User::generate_id();

        $response = array(
            'success' => 'true',
            'latest_id' => $latest_id
        );

        return Response::json( $response );
    }

    public function logout(){
        Auth::logout();

        return redirect('login');
    }
}
