<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\DayOffLetter;
use App\Participant;
use App\MedicalRecord;
use DB;
use Response;
use Auth;


class DayOffLetterController extends Controller
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
        if( !current_user_can( 'surat_cuti' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
            if( empty( $s ) ){
    	        $datas = DayOffLetter::orderBy( 'id_surat_cuti', 'desc' )->get();
            }else{
                $datas = DayOffLetter::whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })->orderBy( 'id_surat_cuti', 'desc' )->get();
            }

        }else{
            if( empty( $s ) ){
                $datas = DayOffLetter::orderBy( 'id_surat_cuti', 'desc' )->paginate( $rows );
            }else{
                $datas = DayOffLetter::whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })->orderBy( 'id_surat_cuti', 'desc' )->paginate( $rows );
            }

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'dayoffletter', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dayoffletter = DayOffLetter::find( $id );

        $medrec = MedicalRecord::find( $dayoffletter->id_pemeriksaan_poli )->first();

        $response = array(
            'id_surat_cuti' => $dayoffletter->id_surat_cuti,
            'no_surat_cuti' => $dayoffletter->no_surat_cuti,
            'id_peserta' => $dayoffletter->id_peserta,
            'dari_tanggal' => $dayoffletter->dari_tanggal,
            'sampai_tanggal' => $dayoffletter->sampai_tanggal,
            'lama' => $dayoffletter->lama,
            'dokter_jaga' => $dayoffletter->dokter_jaga,
            'nik_peserta' => get_participant_nik( $dayoffletter->id_peserta ),
            'sex' => get_participant_sex( $dayoffletter->id_peserta ),
            'age' => get_participant_age( $dayoffletter->id_peserta ),
            'alamat' => get_participant_address( $dayoffletter->id_peserta ),
            'jenis_cuti' => $dayoffletter->jenis_cuti,
            'name' => get_participant_name( $dayoffletter->id_peserta )
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
        $input = $request->all();

        $dayoffletter = DayOffLetter::find( $id );

        $dayoffletter->lama = $input['lama'];
        $dayoffletter->dari_tanggal = $input['dari_tanggal'];
        $dayoffletter->sampai_tanggal = $input['sampai_tanggal'];
        $dayoffletter->dokter_jaga = $input['dokter_jaga'];
        $dayoffletter->jenis_cuti = $input['jenis_cuti'];
        $dayoffletter->tgl_surat_cuti = date( 'Y-m-d H:i:s' );
        $dayoffletter->status = 0;
    
        $update = $dayoffletter->save();

        return redirect()->route( 'day-off-letter.index' ); 
    }
}
