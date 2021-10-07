<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\SickLetter;
use App\Participant;
use App\MedicalRecord;
use DB;
use Response;
use Auth;


class SickLetterController extends Controller
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
        if( !current_user_can( 'surat_keterangan_sakit' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
    	        $datas = SickLetter::orderBy( 'id_surat_sakit', 'desc' )->get();
            }else{
                $participants = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

                $ids = array();
                foreach ( $participants as $p ) {
                    $ids[] = $p->id_peserta;
                }

                $datas = SickLetter::whereIn( 'id_peserta', $ids )->orderBy( 'id_surat_sakit', 'desc' )->get();
            }

        }else{
            if( empty( $s ) ){
                $datas = SickLetter::orderBy( 'id_surat_sakit', 'desc' )->paginate( $rows );
            }else{
                $participants = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

                $ids = array();
                foreach ( $participants as $p ) {
                    $ids[] = $p->id_peserta;
                } 

                $datas = SickLetter::whereIn( 'id_peserta',  $ids )->orderBy( 'id_surat_sakit', 'desc' )->paginate( $rows );
            }

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'sickletter', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sickletter = SickLetter::find( $id );

        $medrec = MedicalRecord::find( $sickletter->id_pemeriksaan_poli );

        $response = array(
            'id_surat_sakit' => $sickletter->id_surat_sakit,
            'no_surat_sakit' => $sickletter->no_surat_sakit,
            'id_peserta' => $sickletter->id_peserta,
            'dari_tanggal' => $sickletter->dari_tanggal,
            'sampai_tanggal' => $sickletter->sampai_tanggal,
            'lama' => $sickletter->lama,
            'dokter_jaga' => $sickletter->dokter_jaga,
            'nik_peserta' => get_participant_nik( $sickletter->id_peserta ),
            'sex' => get_participant_sex( $sickletter->id_peserta ),
            'age' => get_participant_age( $sickletter->id_peserta ),
            'alamat' => get_participant_address( $sickletter->id_peserta ),
            'diagnosa' => $medrec->diagnosa_dokter,
            'name' => get_participant_name( $sickletter->id_peserta )
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

        $sickletter = SickLetter::find( $id );

        $sickletter->lama = $input['lama'];
        $sickletter->dari_tanggal = $input['dari_tanggal'];
        $sickletter->sampai_tanggal = $input['sampai_tanggal'];
        $sickletter->dokter_jaga = $input['dokter_jaga'];
        $sickletter->status = 0;
    
        $update = $sickletter->save();

        return redirect()->route( 'sick-letter.index' ); 
    }
}
