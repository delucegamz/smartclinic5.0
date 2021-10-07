<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\ReferenceLetter;
use App\Participant;
use App\MedicalRecord;
use DB;
use Response;
use Auth;


class ReferenceLetterController extends Controller
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
        if( !current_user_can( 'surat_rujukan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
    	        $datas = ReferenceLetter::orderBy( 'id_surat_rujukan', 'desc' )->get();
            }else{
                $participants = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

                $ids = array();
                foreach ( $participants as $p ) {
                    $ids[] = $p->id_peserta;
                }

                $datas = ReferenceLetter::whereIn( 'id_peserta', $ids )->orderBy( 'id_surat_rujukan', 'desc' )->get();
            }

        }else{
            if( empty( $s ) ){
                $datas = ReferenceLetter::orderBy( 'id_surat_rujukan', 'desc' )->paginate( $rows );
            }else{
                $participants = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

                $ids = array();
                foreach ( $participants as $p ) {
                    $ids[] = $p->id_peserta;
                } 

                $datas = ReferenceLetter::whereIn( 'id_peserta',  $ids )->orderBy( 'id_surat_rujukan', 'desc' )->paginate( $rows );
            }

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'referenceletter', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $referenceletter = ReferenceLetter::find( $id );

        $medrec = MedicalRecord::find( $referenceletter->id_pemeriksaan_poli )->first();

        $response = array(
            'id_surat_rujukan' => $referenceletter->id_surat_rujukan,
            'no_surat_rujukan' => $referenceletter->no_surat_rujukan,
            'id_peserta' => $referenceletter->id_peserta,
            'provider' => $referenceletter->provider,
            'dokter_ahli' => $referenceletter->dokter_ahli,
            'diagnosa_dokter' => $referenceletter->diagnosa_dokter,
            'dokter_rujuk' => $referenceletter->dokter_rujuk,
            'nik_peserta' => get_participant_nik( $referenceletter->id_peserta ),
            'sex' => get_participant_sex( $referenceletter->id_peserta ),
            'age' => get_participant_age( $referenceletter->id_peserta ),
            'alamat' => get_participant_address( $referenceletter->id_peserta ),
            'anamnesa' => $referenceletter->anamnesa,
            'name' => get_participant_name( $referenceletter->id_peserta ),
            'pemeriksaan_fisik' => $referenceletter->pemeriksaan_fisik,
            'obat_beri' => $referenceletter->obat_beri,
            'catatan' => $referenceletter->catatan,
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

        $referenceletter = ReferenceLetter::find( $id );

        $referenceletter->dokter_ahli = $input['dokter_ahli'];
        $referenceletter->provider = $input['provider'];
        $referenceletter->anamnesa = $input['anamnesa'];
        $referenceletter->pemeriksaan_fisik = $input['pemeriksaan_fisik'];
        $referenceletter->diagnosa_dokter = $input['diagnosa_dokter'];
        $referenceletter->obat_beri = $input['obat_beri'];
        $referenceletter->catatan = $input['catatan'];
        $referenceletter->dokter_rujuk = $input['dokter_rujuk'];
        $referenceletter->status = 0;
    
        $update = $referenceletter->save();

        return redirect()->route( 'reference-letter.index' ); 
    }
}
