<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Observation;
use App\ObservationDetail;
use App\PoliRegistration;
use App\Participant;
use App\MedicalRecord;
use App\SickLetter;
use App\ReferenceLetter;
use App\DayOffLetter;
use App\DoctorRecipe;
use App\ObservationAction;
use App\ObservationActionTransaction;
use DB;
use Response;
use Auth;


class ObservationController extends Controller
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
        if( !current_user_can( 'observasi' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
                $datas = Observation::where( 'status', '=', 1 )->orderBy( 'id_observasi', 'DESC' )->get();
            }else{
                $datas = Observation::where( 'nama_peserta', 'LIKE',  '%' . $s . '%' )->orderBy( 'id_observasi', 'DESC' )->get();
            }

        }else{
            if( empty( $s ) ){
                $datas = Observation::where( 'status', '=', 1 )->orderBy( 'id_observasi', 'DESC' )->paginate( $rows );
            }else{
                $datas = Observation::where( 'nama_peserta', 'LIKE',  '%' . $s . '%' )->orderBy( 'id_observasi', 'DESC' )->paginate( $rows );
            }
           
        }

        

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        return view( 'listobservation', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $observation = Observation::find( $id );

        $poli_check = MedicalRecord::find( $observation->id_pemeriksaan_poli );

        $poliregistration = PoliRegistration::find( $poli_check->id_pendaftaran_poli );

        $participant = Participant::find( $observation->id_peserta );

        $sickletter = SickLetter::where( 'id_pemeriksaan_poli', '=', $observation->id_pemeriksaan_poli )->first();
        $referenceletter = ReferenceLetter::where( 'id_pemeriksaan_poli', '=', $observation->id_pemeriksaan_poli )->first();
        $dayoffletter = DayOffLetter::where( 'id_pemeriksaan_poli', '=', $observation->id_pemeriksaan_poli )->first();
        $doctorrecipe = DoctorRecipe::where( 'id_pemeriksaan_poli', '=', $observation->id_pemeriksaan_poli )->first();

        $is_sick_letter = $sickletter ? 1 : 0;
        $is_reference_letter = $referenceletter ? 1 : 0;
        $is_doctor_recipe = $doctorrecipe ? 1 : 0;
        $is_dayoff_letter = $dayoffletter ? 1 : 0;

        $observation_detail = ObservationDetail::where( 'no_observasi', '=', $id )->first();

        $observation_detail = isset( $observation_detail->id_observasi_detail ) ? $observation_detail : new ObservationDetail();

        $observationaction = ObservationAction::all();

        $observation_checks = ObservationActionTransaction::where( 'id_observasi', '=', $id )->get();

        $medrec = MedicalRecord::find( $observation->id_pemeriksaan_poli );

        return view( 'observation', [ 
            'participant' => $participant, 
            'poliregistration' => $poliregistration, 
            'poli_check' => $poli_check, 
            'is_sick_letter' => $is_sick_letter, 
            'is_reference_letter' => $is_reference_letter, 
            'is_dayoff_letter' => $is_dayoff_letter, 
            'is_doctor_recipe' => $is_doctor_recipe,
            'observation' => $observation,
            'observation_detail' => $observation_detail,
            'observation_actions' => $observationaction,
            'observation_checks' => $observation_checks,
            'medrec' => $medrec
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id )
    {
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $input = $request->all();

        $keadaan_umum = $input['general-condition'];
        $k_mata = $input['eye-opening'];
        $k_bicara = $input['verbal-response'];
        $k_motorik = $input['motoric-response'];        
        $td_atas = $input['mm'];
        $td_bawah = $input['hg'];
        $suhu = $input['temperature'];
        $nadi = $input['blood-pulse'];
        $jalan_nafas = $input['breath'];
        $actions = $input['actions'];
        $hasil_observasi = $input['more-desc'];
        $sick_letter = $input['sick-letter'];
        $reference_letter = $input['reference-letter'];
        $day_off_letter = $input['dayoff-letter'];
        $doctor_recipe = $input['doctor-recipe'];
        $diagnosa_akhir = $input['final-diagnosis'];
        $kesimpulan_observasi = $input['summary'];
        
        try {
            $observation = Observation::find( $id );

            $observation->diagnosa_akhir = $diagnosa_akhir;
            $observation->kesimpulan_observasi = $kesimpulan_observasi;
            $observation->hasil_observasi = $hasil_observasi;
            $observation->user_update = $idpengguna;
            $observation->tanggal_selesai = date( 'Y-m-d H:i:s' );
            $observation->status = 1;
            $observation->save();

            $observation_detail = ObservationDetail::where( 'no_observasi', '=', $id )->first();
            $observation_detail = ( isset( $observation_detail->id_observasi_detail ) ) ? $observation_detail : new ObservationDetail();

            $observation_detail->no_observasi_detail = $observation_detail->no_observasi_detail ? $observation_detail->no_observasi_detail : ObservationDetail::generate_id( $observation->no_observasi );
            $observation_detail->no_observasi = $observation_detail->no_observasi ? $observation_detail->no_observasi : $id;
            $observation_detail->keadaan_umum = $keadaan_umum;
            $observation_detail->k_mata = $k_mata;
            $observation_detail->k_bicara = $k_bicara;
            $observation_detail->k_motorik = $k_motorik;
            $observation_detail->td_atas = $td_atas;
            $observation_detail->td_bawah = $td_bawah;
            $observation_detail->suhu = $suhu;
            $observation_detail->nadi = $nadi;
            $observation_detail->jalan_nafas = $jalan_nafas;
            $observation_detail->user_update = $idpengguna;
            $observation_detail->id_pengguna = $observation_detail->id_pengguna ? $observation_detail->id_pengguna : $idpengguna;
            $observation_detail->tanggal_obs_detail = $observation_detail->tanggal_obs_detail ? $observation_detail->tanggal_obs_detail : date( 'Y-m-d H:i:s' );
            $observation_detail->tgl_obs_entry = $observation_detail->tgl_obs_entry ? $observation_detail->tgl_obs_entry : date( 'Y-m-d H:i:s' );

            $observation_detail->save();

            if( $sick_letter ){ // Buat entry surat sakit
                $sks = SickLetter::where( 'id_pemeriksaan_poli', '=', $observation->id_pemeriksaan_poli )->first();

                // Check apakah sudah ada observasi atau belum
                if( !$sks ){
                    $sks = new SickLetter();

                    $sks->no_surat_sakit = SickLetter::generate_id();
                    $sks->id_pemeriksaan_poli = $observation->id_pemeriksaan_poli;
                    $sks->id_peserta = $observation->id_peserta;
                    $sks->umur_peserta = get_participant_age( $observation->id_peserta );
                    $sks->id_pengguna = $idpengguna;
                    $sks->user_update = $idpengguna;
                    $sks->status = 1;

                    $sks->save();
                }
            }

            if( $reference_letter ){ // Buat entry surat rujukan
                $srd = ReferenceLetter::where( 'id_pemeriksaan_poli', '=', $observation->id_pemeriksaan_poli )->first();

                // Check apakah sudah ada observasi atau belum
                if( !$srd ){
                    $srd = new ReferenceLetter();

                    $srd->no_surat_rujukan = ReferenceLetter::generate_id();
                    $srd->id_pemeriksaan_poli = $observation->id_pemeriksaan_poli;
                    $srd->id_peserta = $observation->id_peserta;
                    $srd->id_pengguna = $idpengguna;
                    $srd->user_update = $idpengguna;
                    $srd->status = 1;

                    $srd->save();
                }
            }

            if( $day_off_letter ){ // Buat entry surat cuti
                $sc = DayOffLetter::where( 'id_pemeriksaan_poli', '=', $observation->id_pemeriksaan_poli )->first();

                // Check apakah sudah ada observasi atau belum
                if( !$sc ){
                    $sc = new DayOffLetter();

                    $sc->no_surat_cuti = DayOffLetter::generate_id();
                    $sc->id_pemeriksaan_poli = $observation->id_pemeriksaan_poli;
                    $sc->id_peserta = $observation->id_peserta;
                    $sc->umur_peserta = get_participant_age( $observation->id_peserta );
                    $sc->id_pengguna = $idpengguna;
                    $sc->user_update = $idpengguna;
                    $sc->status = 1;

                    $sc->save();
                }
            }

            if( $doctor_recipe ){ // Buat entry resep doktor
                $rsp = DoctorRecipe::where( 'id_pemeriksaan_poli', '=', $observation->id_pemeriksaan_poli )->first();

                // Check apakah sudah ada observasi atau belum
                if( !$rsp ){
                    $rsp = new DoctorRecipe();

                    $rsp->no_resep = DoctorRecipe::generate_id();
                    $rsp->id_pemeriksaan_poli = $observation->id_pemeriksaan_poli;
                    $rsp->id_pengguna = $idpengguna;
                    $rsp->user_update = $idpengguna;

                    $rsp->save();
                }
            }
        } catch (\Exception $e) {
            die_dump($e->getMessage());
        }

        return redirect()->route( 'observation.show', [ 'id' => $id ] ); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        $observation_check = ObservationActionTransaction::find( $id );

        $delete = $observation_check->delete();

        if( $delete ){
            $response = array(
                'success' => 'true',
                'message' => 'Pemeriksaan observasi berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus pemeriksaan observasi.'
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
    public function store(Request $request)
    {
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $input = $request->all();

        try {
            $observation_check = ObservationActionTransaction::where( 'id_pemeriksaan_observasi', '=', $input['id_pemeriksaan_observasi'] )
                                                             ->where( 'id_observasi', '=', $input['id_observasi'] )
                                                             ->first();
            if( !$observation_check ){ 
                $observation_check = new ObservationActionTransaction();
                $observation_check->id_pemeriksaan_observasi = $input['id_pemeriksaan_observasi'];
                $observation_check->id_observasi = $input['id_observasi'];
                $observation_check->id_pengguna = $idpengguna;

                $insert = $observation_check->save();

                if( $insert ){
                    $response = array(
                        'success' => 'true',
                        'message' => 'Pemeriksaan observasi berhasil ditambahkan.',
                        'id' => $observation_check->no_pemeriksaan_observasi
                    );
                }else{
                    $response = array(
                        'success' => 'false',
                        'message' => 'Terjadi kesalahan ketika menambahkan pemeriksaan observasi.'
                    );
                }
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Pemeriksaan observasi sudah ditambahkan sebelumnya.'
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
}
