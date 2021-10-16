<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\PoliRegistration;
use App\Participant;
use App\Poli;
use App\Department;
use App\Client;
use App\Factory;
use App\MedicalRecord;
use App\DayOffLetter;
use App\SickLetter;
use App\ReferenceLetter;
use App\AmbulanceIn;
use App\AmbulanceOut;
use App\DoctorRecipe;
use App\DoctorRecipeDetail;
use App\MedicineIn;
use App\MedicineInDetail;
use App\MedicineOut;
use App\MedicineOutDetail;
use App\Medicine;
use App\MedicineGroup;
use App\Observation;
use App\Anc;
use App\Staff;
use App\Medrec2;
use App\JobTitle;
use App\Diagnosis;
use DB;
use Response;
use Auth;
use Carbon\Carbon;
use Excel;

class ReportController extends Controller
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

    public function staff()
    {
        if( !current_user_can( 'laporan_karyawan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'reports.staff' );
    }

    public function participant()
    {
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'reports.participant' );
    }

    public function organization()
    {
        if( !current_user_can( 'laporan_organisasi' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'reports.organization' );
    }

    public function registration()
    {
        if( !current_user_can( 'laporan_kunjungan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        if( isset( $_GET['filter'] ) && $_GET['filter'] != '' ){
            $filter = $_GET['filter'];
        }else{
            $filter = 'all';
        }

        if( $filter == 'belum-direkam' || $filter == 'all' ){
            $medrec_results = array();
        }elseif( $filter == 'tidak-direkam' ){  
            $medrec_results = MedicalRecord::where( 'status', '=', 1 )->get();
        }elseif( $filter == 'sudah-direkam' ){
            $medrec_results = MedicalRecord::where( 'status', '=', 0 )->get();
        }

        $medrecs = array();
        if( count( $medrec_results ) > 0 ){
            foreach( $medrec_results as $res ){
                $medrecs[] = $res->id_pendaftaran_poli;
            }
        }

        $datas = NULL;

        if( $rows == 'all' ){
            if( $filter == 'all' ){
                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->get();
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->get();
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->get();
                }else{
                    $datas = PoliRegistration::orderBy( 'tgl_daftar', 'desc' )->get();
                }
            }elseif( $filter == 'belum-direkam' ){
                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'status', '=', 1 )
                                         ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->get();
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'status', '=', 1 )
                                         ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->get();
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'status', '=', 1 )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->get();
                }else{
                    $datas = PoliRegistration::where( 'status', '=', 1 )->orderBy( 'tgl_daftar', 'desc' )->get();
                }
            }elseif( $filter == 'tidak-direkam' || $filter == 'sudah-direkam' ){
                if( $date_from && $date_to ){
                    $datas = PoliRegistration::whereIn( 'id_pendaftaran', $medrecs )
                                         ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->get();
                }elseif( $date_from ){
                    $datas = PoliRegistration::whereIn( 'id_pendaftaran', $medrecs )
                                         ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->get();
                }elseif( $date_to ){
                    $datas = PoliRegistration::whereIn( 'id_pendaftaran', $medrecs )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->get();
                }else{
                    $datas = PoliRegistration::whereIn( 'id_pendaftaran', $medrecs )->orderBy( 'tgl_daftar', 'desc' )->get();
                }
            }
        }else{
            if( $filter == 'all' ){
                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->paginate( $rows );
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->paginate( $rows );
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->paginate( $rows );
                }else{
                    $datas = PoliRegistration::orderBy( 'tgl_daftar', 'desc' )->paginate( $rows );
                }
            }elseif( $filter == 'belum-direkam' ){
                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'status', '=', 1 )
                                         ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->paginate( $rows );
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'status', '=', 1 )
                                         ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->paginate( $rows );
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'status', '=', 1 )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->paginate( $rows );
                }else{
                    $datas = PoliRegistration::where( 'status', '=', 1 )->orderBy( 'tgl_daftar', 'desc' )->paginate( $rows );
                }
            }elseif( $filter == 'tidak-direkam' || $filter == 'sudah-direkam' ){
                if( $date_from && $date_to ){
                    $datas = PoliRegistration::whereIn( 'id_pendaftaran', $medrecs )
                                         ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->paginate( $rows );
                }elseif( $date_from ){
                    $datas = PoliRegistration::whereIn( 'id_pendaftaran', $medrecs )
                                         ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->paginate( $rows );
                }elseif( $date_to ){
                    $datas = PoliRegistration::whereIn( 'id_pendaftaran', $medrecs )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->orderBy( 'tgl_daftar', 'desc' )
                                         ->paginate( $rows );
                }else{
                    $datas = PoliRegistration::whereIn( 'id_pendaftaran', $medrecs )->orderBy( 'tgl_daftar', 'desc' )->paginate( $rows );
                }
            }
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $poli = Poli::all();

        return view( 'reports.registration', [ 
            'datas' => $datas, 
            'rows' => $rows, 
            'page' => $page, 
            'i' => $i,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'poli' => $poli,
            'filter' => $filter
        ]);
    }

    public function medrec(){
        if( !current_user_can( 'laporan_rekam_medis' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $participant = ( isset( $_GET['participant'] ) && $_GET['participant'] != '' ) ? $_GET['participant'] : '';
        $participant_id = ( isset( $_GET['participant_id'] ) && $_GET['participant_id'] != '' ) ? $_GET['participant_id'] : '';
        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from'] : '';
        $date_from_full = $date_from . ' 00:00:00';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to'] : '';
        $date_to_full = $date_to . ' 23:59:59';

        $poliregistrations = array(); $regs = NULL;
        if( $date_from && $date_to ){
            $regs = PoliRegistration::where( 'tgl_selesai', '>=', $date_from_full )->where( 'tgl_selesai', '<=', $date_to_full )->get();
        }elseif( $date_from && !$date_to ){
            $regs = PoliRegistration::where( 'tgl_selesai', '>=', $date_from_full )->get();
        }elseif( !$date_from && $date_to ){
            $regs = PoliRegistration::where( 'tgl_selesai', '<=', $date_to_full )->get();
        }

        if( $regs ){
            foreach ( $regs as $reg ) {
                $poliregistrations[] = $reg->id_pendaftaran;
            }
        }

        if( $rows == 'all' ){
            if( $participant_id ){
                if( $date_from || $date_to ){
                    $others = MedicalRecord::where( 'id_peserta', '=', $participant_id )->whereIn( 'id_pendaftaran_poli', $poliregistrations )->orderBy( 'id_pemeriksaan_poli', 'ASC' )->get();    
                }else{
                    $others = MedicalRecord::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_pemeriksaan_poli', 'ASC' )->get();    
                }
            }else{
                if( $date_from || $date_to ){
                    $others = MedicalRecord::whereIn( 'id_pendaftaran_poli', $poliregistrations )->orderBy( 'id_pemeriksaan_poli', 'ASC' )->get();    
                }else{
                    $others = MedicalRecord::orderBy( 'id_pemeriksaan_poli', 'ASC' )->get();    
                }
            }
        }else{
            if( $participant_id ){
                if( $date_from || $date_to ){
                    $others = MedicalRecord::where( 'id_peserta', '=', $participant_id )->whereIn( 'id_pendaftaran_poli', $poliregistrations )->orderBy( 'id_pemeriksaan_poli', 'ASC' )->paginate( $rows );    
                }else{
                    $others = MedicalRecord::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_pemeriksaan_poli', 'ASC' )->paginate( $rows );   
                }
            }else{
                if( $date_from || $date_to ){
                    $others = MedicalRecord::whereIn( 'id_pendaftaran_poli', $poliregistrations )->orderBy( 'id_pemeriksaan_poli', 'ASC' )->paginate( $rows );    
                }else{
                    $others = MedicalRecord::orderBy( 'id_pemeriksaan_poli', 'ASC' )->paginate( $rows );  
                }
            }
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        return view( 'reports.medrec', [ 
            'datas' => $others, 
            'rows' => $rows, 
            'page' => $page, 
            'i' => $i, 
            'participant' => $participant, 
            'participant_id' => $participant_id,
            'date_from' => $date_from,
            'date_to' => $date_to 
        ]);
    }

    public function medrec2(){
        $halaman= 'medrec';
        $medrec_list = medrec2::all();
        return view('reports/medrec2', compact('halaman','medrec_list'));


    }


    public function accident(Request $request)
    {
        $filter_by = $request->input('filter_by');
        $accident = $request->input('accident');
        $start_date = $request->input('start_date');
		$end_date = $request->input('end_date');
        $nik_peserta = $request->input('nik_peserta');
        $per_page = $request->input('per_page', 10);

        if( !current_user_can( 'laporan_rekam_medis' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $medicalRecords = MedicalRecord::with([
                'participant',
                'accident',
                'poliRegistration',
                'poliRegistration.poli',
            ])
            ->when($start_date || $end_date, function ($query) use($start_date, $end_date) {
                return $query->whereHas('poliRegistration', function ($query) use ($start_date, $end_date) {
                    if ($start_date) {
                        $start_date = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay()->toDateTimeString();
                        $query->whereDate('tgl_selesai', '>=', $start_date);
                    }
    
                    if ($end_date) {
                        $end_date = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay()->toDateTimeString();
                        $query->whereDate('tgl_selesai', '<=', $end_date);
                    }
    
                    return $query;
                });
            })
            ->when($filter_by == 'nik' && $nik_peserta, function ($query) use ($nik_peserta) {
                return $query->whereHas('participant', function ($query) use ($nik_peserta) {
                    return $query->where('nik_peserta', $nik_peserta);
                });
            })
            ->when($filter_by == 'kecelakaan' && $accident, function ($query) use ($accident) {
                return $query->where('uraian', $accident);
            })
            ->withCount(['sickLetter', 'referenceLetter'])
            ->orderBy( 'created_at', 'DESC')
            ->paginate($per_page);

        return view( 'reports.accident', [ 
            'medicalRecords' => $medicalRecords,
            'filter_by'      => $filter_by,
            'start_date'     => $start_date,
            'end_date'       => $end_date,
            'per_page'       => $per_page,
            'nik_peserta'    => $nik_peserta,
            'accident'       => $accident,
        ]);
    }

    public function export(Request $request)
    {
        $filter_by = $request->input('filter_by');
        $accident = $request->input('accident');
        $start_date = $request->input('start_date');
		$end_date = $request->input('end_date');
        $nik_peserta = $request->input('nik_peserta');
        $per_page = $request->input('per_page', 10);

        if( !current_user_can( 'laporan_rekam_medis' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $medicalRecords = MedicalRecord::with([
                'participant',
                'accident',
                'poliRegistration',
                'poliRegistration.poli',
            ])
            ->when($start_date || $end_date, function ($query) use($start_date, $end_date) {
                return $query->whereHas('poliRegistration', function ($query) use ($start_date, $end_date) {
                    if ($start_date) {
                        $start_date = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay()->toDateTimeString();
                        $query->whereDate('tgl_selesai', '>=', $start_date);
                    }
    
                    if ($end_date) {
                        $end_date = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay()->toDateTimeString();
                        $query->whereDate('tgl_selesai', '<=', $end_date);
                    }
    
                    return $query;
                });
            })
            ->when($filter_by == 'nik' && $nik_peserta, function ($query) use ($nik_peserta) {
                return $query->whereHas('participant', function ($query) use ($nik_peserta) {
                    return $query->where('nik_peserta', $nik_peserta);
                });
            })
            ->when($filter_by == 'kecelakaan' && $accident, function ($query) use ($accident) {
                return $query->where('uraian', $accident);
            })
            ->withCount(['sickLetter', 'referenceLetter'])
            ->orderBy( 'created_at', 'DESC')
            ->get();

            return Excel::create('kecelakaan_kerja', function ($excel) use ($medicalRecords) {
                $excel->sheet('Sheet', function($sheet) use ($medicalRecords) {
                    $sheet->loadView('excel.accident', [
                        'medicalRecords' => $medicalRecords,
                    ]);
                });
            })->download('xlsx');
    }

    public function observation(){
        if( !current_user_can( 'laporan_observasi' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $participant = ( isset( $_GET['participant'] ) && $_GET['participant'] != '' ) ? $_GET['participant'] : '';
        $participant_id = ( isset( $_GET['participant_id'] ) && $_GET['participant_id'] != '' ) ? $_GET['participant_id'] : '';
        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from'] : '';
        $date_from_full = $date_from . ' 00:00:00';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to'] : '';
        $date_to_full = $date_to . ' 23:59:59';

        $observations = array(); $obvs = NULL;
        if( $date_from && $date_to ){
            $obvs = Observation::where( 'tanggal_mulai', '>=', $date_from_full )->where( 'tanggal_selesai', '<=', $date_to_full )->get();
        }elseif( $date_from && !$date_to ){
            $obvs = Observation::where( 'tanggal_mulai', '>=', $date_from_full )->get();
        }elseif( !$date_from && $date_to ){
            $obvs = Observation::where( 'tanggal_selesai', '<=', $date_to_full )->get();
        }

        if( $obvs ){
            foreach ( $obvs as $obv ) {
                $observations[] = $obv->id_observasi;
            }
        }

        if( $rows == 'all' ){
            if( $participant_id ){
                if( $date_from || $date_to ){
                    $others = Observation::where( 'id_peserta', '=', $participant_id )->whereIn( 'id_observasi', $observations )->orderBy( 'id_observasi', 'DESC' )->get();    
                }else{
                    $others = Observation::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_observasi', 'DESC' )->get();    
                }
            }else{
                if( $date_from || $date_to ){
                    $others = Observation::whereIn( 'id_observasi', $observations )->orderBy( 'id_observasi', 'DESC' )->get();    
                }else{
                    $others = Observation::orderBy( 'id_observasi', 'DESC' )->get();    
                }
            }
        }else{
            if( $participant_id ){
                if( count( $observations ) ){
                    $others = Observation::where( 'id_peserta', '=', $participant_id )->whereIn( 'id_observasi', $observations )->orderBy( 'id_observasi', 'DESC' )->paginate( $rows );    
                }else{
                    $others = Observation::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_observasi', 'DESC' )->paginate( $rows );   
                }
            }else{
                if( count( $observations ) ){
                    $others = Observation::whereIn( 'id_observasi', $observations )->orderBy( 'id_observasi', 'DESC' )->paginate( $rows );    
                }else{
                    $others = Observation::orderBy( 'id_observasi', 'DESC' )->paginate( $rows );  
                }
            }
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        return view( 'reports.observation', [ 
            'datas' => $others, 
            'rows' => $rows, 
            'page' => $page, 
            'i' => $i, 
            'participant' => $participant, 
            'participant_id' => $participant_id,
            'date_from' => $date_from,
            'date_to' => $date_to 
        ]);
    }

    public function anc(){
        if( !current_user_can( 'laporan_anc' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $participant = ( isset( $_GET['participant'] ) && $_GET['participant'] != '' ) ? $_GET['participant'] : '';
        $participant_id = ( isset( $_GET['participant_id'] ) && $_GET['participant_id'] != '' ) ? $_GET['participant_id'] : '';
        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from'] : '';
        $date_from_full = $date_from . ' 00:00:00';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to'] : '';
        $date_to_full = $date_to . ' 23:59:59';

        $ancs = array(); $regs = NULL;
        if( $date_from && $date_to ){
            $regs = Anc::where( 'created_at', '>=', $date_from_full )->where( 'created_at', '<=', $date_to_full )->get();
        }elseif( $date_from && !$date_to ){
            $regs = Anc::where( 'created_at', '>=', $date_from_full )->get();
        }elseif( !$date_from && $date_to ){
            $regs = Anc::where( 'created_at', '<=', $date_to_full )->get();
        }

        if( $regs ){
            foreach ( $regs as $reg ) {
                $ancs[] = $reg->id_pemeriksaan_anc;
            }
        }

        if( $rows == 'all' ){
            if( $participant_id ){
                if( $date_from || $date_to ){
                    $others = Anc::where( 'id_peserta', '=', $participant_id )->whereIn( 'id_pemeriksaan_anc', $ancs )->orderBy( 'id_pemeriksaan_anc', 'DESC' )->get();    
                }else{
                    $others = Anc::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_pemeriksaan_anc', 'DESC' )->get();    
                }
            }else{
                if( $date_from || $date_to ){
                    $others = Anc::whereIn( 'id_pemeriksaan_anc', $ancs )->orderBy( 'id_pemeriksaan_anc', 'DESC' )->get();    
                }else{
                    $others = Anc::orderBy( 'id_pemeriksaan_anc', 'DESC' )->get();    
                }
            }
        }else{
            if( $participant_id ){
                if( $date_from || $date_to ){
                    $others = Anc::where( 'id_peserta', '=', $participant_id )->whereIn( 'id_pemeriksaan_anc', $ancs )->orderBy( 'id_pemeriksaan_anc', 'DESC' )->paginate( $rows );    
                }else{
                    $others = Anc::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_pemeriksaan_anc', 'DESC' )->paginate( $rows );   
                }
            }else{
                if( $date_from || $date_to ){
                    $others = Anc::whereIn( 'id_pemeriksaan_anc', $ancs )->orderBy( 'id_pemeriksaan_anc', 'DESC' )->paginate( $rows );    
                }else{
                    $others = Anc::orderBy( 'id_pemeriksaan_anc', 'DESC' )->paginate( $rows );  
                }
            }
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        return view( 'reports.anc', [ 
            'datas' => $others, 
            'rows' => $rows, 
            'page' => $page, 
            'i' => $i, 
            'participant' => $participant, 
            'participant_id' => $participant_id,
            'date_from' => $date_from,
            'date_to' => $date_to 
        ]);
    }

    public function letter(){
        if( !current_user_can( 'laporan_surat' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $view = ( isset( $_GET['view'] ) && $_GET['view'] != '' ) ? $_GET['view'] : '';
        $participant = ( isset( $_GET['participant'] ) && $_GET['participant'] != '' ) ? $_GET['participant'] : '';
        $participant_id = ( isset( $_GET['participant_id'] ) && $_GET['participant_id'] != '' ) ? $_GET['participant_id'] : 0;
        $datas = null;

        if( $view == 'day-off' ){
            if( $rows == 'all' ){
                if( $participant )
                    $datas = DayOffLetter::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_surat_cuti', 'desc' )->get();
                else
                    $datas = DayOffLetter::orderBy( 'id_surat_cuti', 'desc' )->get();
            }else{
                if( $participant )
                    $datas = DayOffLetter::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_surat_cuti', 'desc' )->paginate( $rows );
                else
                    $datas = DayOffLetter::orderBy( 'id_surat_cuti', 'desc' )->paginate( $rows );
            }
        }elseif( $view == 'reference' ){
            if( $rows == 'all' ){
                if( $participant )
                    $datas = ReferenceLetter::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_surat_rujukan', 'desc' )->get();
                else
                    $datas = ReferenceLetter::orderBy( 'id_surat_rujukan', 'desc' )->get();
            }else{
                if( $participant )
                    $datas = ReferenceLetter::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_surat_rujukan', 'desc' )->paginate( $rows );
                else
                    $datas = ReferenceLetter::orderBy( 'id_surat_rujukan', 'desc' )->paginate( $rows );
            }
        }elseif( $view == 'sick' ){
             if( $rows == 'all' ){
                if( $participant )
                    $datas = SickLetter::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_surat_sakit', 'desc' )->get();
                else
                    $datas = SickLetter::orderBy( 'id_surat_sakit', 'desc' )->get();
            }else{
                if( $participant )
                    $datas = SickLetter::where( 'id_peserta', '=', $participant_id )->orderBy( 'id_surat_sakit', 'desc' )->paginate( $rows );
                else
                    $datas = SickLetter::orderBy( 'id_surat_sakit', 'desc' )->paginate( $rows );
            }
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $participants = Participant::all();

        return view( 'reports.letter', [ 'datas' => $datas, 'rows' => $rows, 'page' => $page, 'i' => $i, 'participant' => $participant, 'participant_id' => $participant_id, 'view' => $view, 'participants' => $participants ]);
    }

    public function visit(){
        if( !current_user_can( 'laporan_kunjungan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

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

        $view = isset( $_GET['view'] ) ? $_GET['view']  : '';
        $participant = isset( $_GET['participant'] ) ? $_GET['participant']  : '';
        $participantsearch = isset( $_GET['participantsearch'] ) ? $_GET['participantsearch']  : '';
        $department = isset( $_GET['department'] ) ? $_GET['department']  : '';
        $client = isset( $_GET['client'] ) ? $_GET['client']  : '';
        $factory = isset( $_GET['factory'] ) ? $_GET['factory']  : '';
        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        $datas = NULL;

        if( $rows == 'all' ){
           if( $view == 'participant' ){
                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->where( 'id_peserta', '=', $participant )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'id_peserta', '=', $participant )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->where( 'id_peserta', '=', $participant )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }else{
                    $datas = PoliRegistration::where( 'id_peserta', '=', $participant )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }
            }elseif( $view == 'department' ){
                $ids = array();

                $p = Participant::where( 'id_departemen', '=', $department )->get();
                foreach ( $p as $a ) {
                    $ids[] = $a->id_peserta;
                }

                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }else{
                    $datas = PoliRegistration::whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }
            }elseif( $view == 'client' ){
                $ids = array(); $deps = array();

                $d = Department::where( 'nama_client', '=', $client )->get();
                foreach ( $d as $f ) {
                    $deps[] = $f->id_departemen;
                }

                $p = Participant::whereIn( 'id_departemen', $deps )->get();
                foreach ( $p as $a ) {
                    $ids[] = $a->id_peserta;
                }

                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }else{
                    $datas = PoliRegistration::whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }
            }elseif( $view == 'factory' ){
                $ids = array(); $facs = array();

                $f = Department::where( 'nama_factory', '=', $factory )->get();
                foreach ( $f as $g ) {
                    $deps[] = $g->id_departemen;
                }

                $p = Participant::whereIn( 'id_departemen', $deps )->get();
                foreach ( $p as $a ) {
                    $ids[] = $a->id_peserta;
                }

                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }else{
                    $datas = PoliRegistration::whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->get();
                }
            }

        }else{
             if( $view == 'participant' ){
                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->where( 'id_peserta', '=', $participant )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'id_peserta', '=', $participant )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->where( 'id_peserta', '=', $participant )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }else{
                    $datas = PoliRegistration::where( 'id_peserta', '=', $participant )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }
            }elseif( $view == 'department' ){
                $ids = array();

                $p = Participant::where( 'id_departemen', '=', $department )->get();
                foreach ( $p as $a ) {
                    $ids[] = $a->id_peserta;
                }

                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }else{
                    $datas = PoliRegistration::whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }
            }elseif( $view == 'client' ){
                $ids = array(); $deps = array();

                $d = Department::where( 'nama_client', '=', $client )->get();
                foreach ( $d as $f ) {
                    $deps[] = $f->id_departemen;
                }

                $p = Participant::whereIn( 'id_departemen', $deps )->get();
                foreach ( $p as $a ) {
                    $ids[] = $a->id_peserta;
                }

                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }else{
                    $datas = PoliRegistration::whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }
            }elseif( $view == 'factory' ){
                 $ids = array(); $facs = array();

                $f = Department::where( 'nama_factory', '=', $factory )->get();
                foreach ( $f as $g ) {
                    $deps[] = $g->id_departemen;
                }

                $p = Participant::whereIn( 'id_departemen', $deps )->get();
                foreach ( $p as $a ) {
                    $ids[] = $a->id_peserta;
                }

                if( $date_from && $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }elseif( $date_from ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }elseif( $date_to ){
                    $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                         ->whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }else{
                    $datas = PoliRegistration::whereIn( 'id_peserta', $ids )
                                         ->orderBy( 'id_poli', 'asc' )
                                         ->paginate( $rows );
                }
            }
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $poli = Poli::all();
        $departments = Department::all();
        $clients = Client::all();
        $factories = Factory::all();

        return view( 'reports.visit', [ 
            'datas' => $datas, 
            'rows' => $rows, 
            's' => $s, 
            'page' => $page, 
            'i' => $i, 
            'poli' => $poli, 
            'departments' => $departments, 
            'clients' => $clients, 
            'factories' => $factories, 
            'participant' => $participant, 
            'participantsearch' => $participantsearch,
            'department' => $department, 
            'client' => $client, 
            'factory' => $factory,
            'view' => $view,
            'date_from' => $date_from,
            'date_to' => $date_to  
        ]);
    }

    public function recap(){
        if( !current_user_can( 'laporan_rekap_kunjungan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $doctor = JobTitle::where( 'nama_jabatan', 'LIKE', "dokter" )->orWhere( 'nama_jabatan', 'LIKE', "bidan" )->get();
        $doctor_ids = array();
        foreach( $doctor as $d ){
            $doctor_ids[] = $d->id_jabatan;
        }

        $doctors = Staff::whereIn( 'id_jabatan', $doctor_ids )->get();

        $diagnosis = Diagnosis::all();

        $poli = Poli::all();

        $factories = Factory::all();

        $department_srcs = Department::all();

        $departments = array();
        foreach( $department_srcs as $d ){
            $departments[$d->nama_factory][] = $d;
        }

        $is_results = false;
        $res_doctors = array( 'all' );
        $res_services = array( 'all' );
        $res_diagnosis = 'all';
        $res_poli = array( 'all' );
        $res_factories = array( 'all' );
        $res_departments = array( 'all' );
        $date_from = '';
        $date_to = '';
        $type = '';

        if( isset( $_GET['type'] ) && !empty( $_GET['type'] ) ){
            $type = $_GET['type'];

            $res_doctors = isset( $_GET['doctor'] ) && !empty( $_GET['doctor'] ) ? $_GET['doctor'] : array( 'all' ); 
            $res_services = isset( $_GET['service'] ) && !empty( $_GET['service'] ) ? $_GET['service'] : array( 'all' ); 
            $res_diagnosis = isset( $_GET['diagnosis-id'] ) && !empty( $_GET['diagnosis-id'] ) ? $_GET['diagnosis-id'] : 'all'; 
            $res_poli = isset( $_GET['poli'] ) && !empty( $_GET['poli'] ) ? $_GET['poli'] : array( 'all' ); 
            $res_factories = isset( $_GET['factory'] ) && !empty( $_GET['factory'] ) ? $_GET['factory'] : array( 'all' ); 
            $res_departments = isset( $_GET['department'] ) && !empty( $_GET['department'] ) ? $_GET['department'] : array( 'all' );
            $date_from = isset( $_GET['date-from'] ) && !empty( $_GET['date-from'] ) ? $_GET['date-from'] : '';
            $date_to = isset( $_GET['date-to'] ) && !empty( $_GET['date-to'] ) ? $_GET['date-to'] : '';

            $is_results = true;
        }

        return view( 'reports.recap', [ 
            'doctors' => $doctors, 
            'diagnosis' => $diagnosis, 
            'poli' => $poli, 
            'factories' => $factories, 
            'departments' => $departments,
            'is_results' => $is_results, 
            'res_doctors' => $res_doctors,
            'res_services' => $res_services,
            'res_diagnosis' => $res_diagnosis,
            'res_poli' => $res_poli,
            'res_factories' => $res_factories,
            'res_departments' => $res_departments,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'type' => $type
        ] );
    }

    public function doctorcheck()
    {
        if( !current_user_can( 'laporan_pemeriksaan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        $datas = NULL;

        $ids = array();

        $medrecs = MedicalRecord::all();
        foreach( $medrecs as $medrec ){
            $ids[] = $medrec->id_pendaftaran_poli;
        }


        if( $rows == 'all' ){
            if( $date_from && $date_to ){
                $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                     ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                     ->whereIn( 'id_pendaftaran', $ids )
                                     ->orderBy( 'tgl_daftar', 'desc' )
                                     ->get();
            }elseif( $date_from ){
                $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                     ->whereIn( 'id_pendaftaran', $ids )
                                     ->orderBy( 'tgl_daftar', 'desc' )
                                     ->get();
            }elseif( $date_to ){
                $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                     ->whereIn( 'id_pendaftaran', $ids )
                                     ->orderBy( 'tgl_daftar', 'desc' )
                                     ->get();
            }else{
                $datas = PoliRegistration::whereIn( 'id_pendaftaran', $ids )->orderBy( 'tgl_daftar', 'desc' )->get();
            }
        }else{
            if( $date_from && $date_to ){
                $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                     ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                     ->whereIn( 'id_pendaftaran', $ids )
                                     ->orderBy( 'tgl_daftar', 'desc' )
                                     ->paginate( $rows );
            }elseif( $date_from ){
                $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                     ->whereIn( 'id_pendaftaran', $ids )
                                     ->orderBy( 'tgl_daftar', 'desc' )
                                     ->paginate( $rows );
            }elseif( $date_to ){
                $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                     ->whereIn( 'id_pendaftaran', $ids )
                                     ->orderBy( 'tgl_daftar', 'desc' )
                                     ->paginate( $rows );
            }else{
                $datas = PoliRegistration::whereIn( 'id_pendaftaran', $ids )->orderBy( 'tgl_daftar', 'desc' )->paginate( $rows );
            }
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $poli = Poli::all();

        return view( 'reports.doctorcheck', [ 
            'datas' => $datas, 
            'rows' => $rows, 
            'page' => $page, 
            'i' => $i,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'poli' => $poli
        ]);
    }

    public function ambulancereport(){
        if( !current_user_can( 'laporan_ambulance' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        $participant_id = isset( $_GET['participant_id'] ) ? $_GET['participant_id']  : 0;
        $participant = isset( $_GET['participant'] ) ? $_GET['participant']  : '';

        $view = ( isset( $_GET['view'] ) && $_GET['view'] != '' ) ? filter_var( $_GET['view'], FILTER_SANITIZE_STRING ) : 'participant';

        if( $rows == 'all' ){
            if( $view == 'out' ){
                $results = AmbulanceIn::whereNull( 'tanggal_masuk' )->get();

                $ins = array();
                foreach( $results as $res ){
                    $ins[] = $res->id_ambulance_out;
                }

    
                if( $date_from && $date_to ){
                    $datas = AmbulanceOut::whereIn( 'id_ambulance_out', $ins )
                                          ->where( 'tanggal_keluar', '>=', $date_from . ' 00:00:00')
                                          ->where( 'tanggal_keluar', '<=', $date_to . ' 23:59:59')
                                          ->get();
                }elseif( $date_from ){
                    $datas = AmbulanceOut::whereIn( 'id_ambulance_out', $ins )
                                          ->where( 'tanggal_keluar', '>=', $date_from . ' 00:00:00')
                                          ->get();
                }elseif( $date_to ){
                    $datas = AmbulanceOut::whereIn( 'id_ambulance_out', $ins )
                                          ->where( 'tanggal_keluar', '<=', $date_to . ' 23:59:59')
                                          ->get();
                }else{
                    $datas = AmbulanceOut::whereIn( 'id_ambulance_out', $ins )->get();
                }
            }elseif( $view == 'in' ){
                if( $date_from && $date_to ){
                    $datas = AmbulanceIn::where( 'tanggal_masuk', '>=', $date_from . ' 00:00:00')
                                          ->where( 'tanggal_masuk', '<=', $date_to . ' 23:59:59')
                                          ->get();
                }elseif( $date_from ){
                    $datas = AmbulanceIn::where( 'tanggal_masuk', '>=', $date_from . ' 00:00:00')
                                          ->get();
                }elseif( $date_to ){
                    $datas = AmbulanceIn::where( 'tanggal_masuk', '<=', $date_to . ' 23:59:59')
                                          ->get();
                }else{
                    $datas = AmbulanceIn::where( 'tanggal_masuk', '!=', NULL )->get();
                }
            }elseif( $view == 'participant' ){
                $datas = AmbulanceOut::where( function( $q ) use( $participant_id, $date_from, $date_to ){
                    $q->where( 'id_peserta', '=', $participant_id );

                    if( $date_from && $date_to ){
                        $q->where( 'tanggal_keluar', '>=', $date_from . ' 00:00:00')
                          ->where( 'tanggal_keluar', '<=', $date_to . ' 23:59:59');
                    }elseif( $date_from ){
                        $q->where( 'tanggal_keluar', '>=', $date_from . ' 00:00:00');
                    }elseif( $date_to ){
                        $q->where( 'tanggal_keluar', '<=', $date_to . ' 23:59:59');
                    }
                } )->get();
            }
        }else{
            if( $view == 'out' ){
                $results = AmbulanceIn::where( 'tanggal_masuk', '=', NULL )->get();

                $ins = array();
                foreach( $results as $res ){
                    $ins[] = $res->id_ambulance_out;
                }

    
                if( $date_from && $date_to ){
                    $datas = AmbulanceOut::whereIn( 'id_ambulance_out', $ins )
                                          ->where( 'tanggal_keluar', '>=', $date_from . ' 00:00:00')
                                          ->where( 'tanggal_keluar', '<=', $date_to . ' 23:59:59')
                                          ->paginate( $rows );
                }elseif( $date_from ){
                    $datas = AmbulanceOut::whereIn( 'id_ambulance_out', $ins )
                                          ->where( 'tanggal_keluar', '>=', $date_from . ' 00:00:00')
                                          ->paginate( $rows );
                }elseif( $date_to ){
                    $datas = AmbulanceOut::whereIn( 'id_ambulance_out', $ins )
                                          ->where( 'tanggal_keluar', '<=', $date_to . ' 23:59:59')
                                          ->paginate( $rows );
                }else{
                    $datas = AmbulanceOut::whereIn( 'id_ambulance_out', $ins )->paginate( $rows );
                }
            }elseif( $view == 'in' ){
                if( $date_from && $date_to ){
                    $datas = AmbulanceIn::where( 'tanggal_masuk', '>=', $date_from . ' 00:00:00')
                                          ->where( 'tanggal_masuk', '<=', $date_to . ' 23:59:59')
                                          ->paginate( $rows );
                }elseif( $date_from ){
                    $datas = AmbulanceIn::where( 'tanggal_masuk', '>=', $date_from . ' 00:00:00')
                                          ->paginate( $rows );
                }elseif( $date_to ){
                    $datas = AmbulanceIn::where( 'tanggal_masuk', '<=', $date_to . ' 23:59:59')
                                          ->paginate( $rows );
                }else{
                    $datas = AmbulanceIn::where( 'tanggal_masuk', '!=', NULL )->paginate( $rows );
                }
            }elseif( $view == 'participant' ){
                $datas = AmbulanceOut::where( function( $q ) use( $participant_id, $date_from, $date_to ){
                    $q->where( 'id_peserta', '=', $participant_id );

                    if( $date_from && $date_to ){
                        $q->where( 'tanggal_keluar', '>=', $date_from . ' 00:00:00')
                          ->where( 'tanggal_keluar', '<=', $date_to . ' 23:59:59');
                    }elseif( $date_from ){
                        $q->where( 'tanggal_keluar', '>=', $date_from . ' 00:00:00');
                    }elseif( $date_to ){
                        $q->where( 'tanggal_keluar', '<=', $date_to . ' 23:59:59');
                    }
                } )->paginate( $rows );
            }
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $ambulances = array();
        if( $view == 'out' ){
            foreach( $datas as $data ){
                $ambulances[] = array(
                    'no_ambulance_out' => $data->no_ambulance_out,
                    'no_ambulance_in' => '-',
                    'id_peserta' => $data->id_peserta,
                    'tanggal' => date( 'd-m-Y', strtotime( $data->tanggal_keluar ) ),
                    'jam_datang' => date( 'H:i:s', strtotime( $data->tanggal_keluar ) ),
                    'jam_pulang' => '-',
                    'lokasi_penjemputan' => $data->lokasi_jemput,
                    'lokasi_pengiriman' => $data->lokasi_kirim,
                    'km_out' => $data->km_out,
                    'km_in' => '-',
                    'driver' => '-',
                    'catatan' => '-',
                    'id_ambulance_out' => $data->id_ambulance_out
                );
            }
        }elseif( $view == 'in' ){
            foreach( $datas as $data ){
                $out = AmbulanceOut::where( 'id_ambulance_out', '=', $data->id_ambulance_out )->first();

                $ambulances[] = array(
                    'no_ambulance_out' => $out->no_ambulance_out,
                    'no_ambulance_in' => $data->no_ambulance_in,
                    'id_peserta' => $out->id_peserta,
                    'tanggal' => date( 'd-m-Y', strtotime( $out->tanggal_keluar ) ),
                    'jam_datang' => date( 'H:i:s', strtotime( $out->tanggal_keluar ) ),
                    'jam_pulang' => date( 'H:i:s', strtotime( $out->tanggal_masuk ) ),
                    'lokasi_penjemputan' => $out->lokasi_jemput,
                    'lokasi_pengiriman' => $out->lokasi_kirim,
                    'km_out' => $out->km_out,
                    'km_in' => $data->km_in,
                    'driver' => $data->driver,
                    'catatan' => $data->catatan,
                    'id_ambulance_out' => $out->id_ambulance_out
                );
            }
        }elseif( $view == 'participant' ){
            foreach( $datas as $data ){
                $in = AmbulanceIn::where( 'id_ambulance_out', '=', $data->id_ambulance_out )->first();

                if( $in && isset( $in->id_ambulance_in ) && $in->tanggal_masuk !== NULL ){
                    $ambulances[] = array(
                        'no_ambulance_out' => $data->no_ambulance_out,
                        'no_ambulance_in' => $in->no_ambulance_in,
                        'id_peserta' => $data->id_peserta,
                        'tanggal' => date( 'd-m-Y', strtotime( $data->tanggal_keluar ) ),
                        'jam_datang' => date( 'H:i:s', strtotime( $data->tanggal_keluar ) ),
                        'jam_pulang' => date( 'H:i:s', strtotime( $data->tanggal_masuk ) ),
                        'lokasi_penjemputan' => $data->lokasi_jemput,
                        'lokasi_pengiriman' => $data->lokasi_kirim,
                        'km_out' => $data->km_out,
                        'km_in' => $in->km_in,
                        'driver' => $in->driver,
                        'catatan' => $in->catatan,
                        'id_ambulance_out' => $data->id_ambulance_out
                    );
                }else{
                    $ambulances[] = array(
                        'no_ambulance_out' => $data->no_ambulance_out,
                        'no_ambulance_in' => '-',
                        'id_peserta' => $data->id_peserta,
                        'tanggal' => date( 'd-m-Y', strtotime( $data->tanggal_keluar ) ),
                        'jam_datang' => date( 'H:i:s', strtotime( $data->tanggal_keluar ) ),
                        'jam_pulang' => '-',
                        'lokasi_penjemputan' => $data->lokasi_jemput,
                        'lokasi_pengiriman' => $data->lokasi_kirim,
                        'km_out' => $data->km_out,
                        'km_in' => '-',
                        'driver' => '-',
                        'catatan' => 'Ambulance Out',
                        'id_ambulance_out' => $data->id_ambulance_out
                    );
                }
            }
        }

        return view( 'reports.ambulance', [ 'datas' => $datas, 'ambulances' => $ambulances, 'rows' => $rows, 'page' => $page, 'i' => $i, 'view' => $view, 'date_from' => $date_from, 'date_to' => $date_to, 'participant' => $participant, 'participant_id' => $participant_id ]);
    }

    public function doctorrecipe(){
        if( !current_user_can( 'laporan_resep_dokter' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        if( $rows == 'all' ){
            if( $date_from && $date_to ) :
                $datas = DoctorRecipe::where( 'created_at', '>=', $date_from . " 00:00:00" )->where( 'created_at', '<=', $date_to . " 23:59:59" )->orderBy( 'id_resep', 'desc' )->get();
            elseif( $date_from ) :
                $datas = DoctorRecipe::where( 'created_at', '>=', $date_from . " 00:00:00" )->orderBy( 'id_resep', 'desc' )->get();
            elseif( $date_to ) :
                $datas = DoctorRecipe::where( 'created_at', '<=', $date_to . " 23:59:59" )->orderBy( 'id_resep', 'desc' )->get();
            else :
                $datas = DoctorRecipe::orderBy( 'id_resep', 'desc' )->get();
            endif;
        }else{
            if( $date_from && $date_to ) :
                $datas = DoctorRecipe::where( 'created_at', '>=', $date_from . " 00:00:00" )->where( 'created_at', '<=', $date_to . " 23:59:59" )->orderBy( 'id_resep', 'desc' )->paginate( $rows );
            elseif( $date_from ) : 
                $datas = DoctorRecipe::where( 'created_at', '>=', $date_from . " 00:00:00" )->orderBy( 'id_resep', 'desc' )->paginate( $rows );
            elseif( $date_to ) :
                $datas = DoctorRecipe::where( 'created_at', '<=', $date_to . " 23:59:59" )->orderBy( 'id_resep', 'desc' )->paginate( $rows );
            else :
                $datas = DoctorRecipe::orderBy( 'id_resep', 'desc' )->paginate( $rows );
            endif;
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        return view( 'reports.doctorrecipe', [ 'datas' => $datas, 'rows' => $rows, 'page' => $page, 'i' => $i, 'date_from' => $date_from, 'date_to' => $date_to ]);
    }

    public function medicinestock(){
        if( !current_user_can( 'laporan_stock_obat' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $medicinegroup = isset( $_GET['medicinegroup'] ) ? absint( $_GET['medicinegroup'] ) : '';

        if( $rows == 'all' ){
            if( $medicinegroup )
                $datas = Medicine::where( 'id_golongan_obat', '=', $medicinegroup )->get();
            else
                $datas = Medicine::all();
        }else{
            if( $medicinegroup )
                $datas = Medicine::where( 'id_golongan_obat', '=', $medicinegroup )->paginate( $rows );
            else
                $datas = Medicine::paginate( $rows );
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;
        

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $medicinegroups = MedicineGroup::all();

        return view( 'reports.medicinestock', [ 'datas' => $datas, 'rows' => $rows, 'page' => $page, 'i' => $i, 'medicinegroups' => $medicinegroups, 'medicinegroup' => $medicinegroup ]);
    }

    public function medicinein(){
        if( !current_user_can( 'laporan_obat_masuk' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        if( $rows == 'all' ){
            if( $date_from && $date_to )
                $datas = MedicineIn::where( 'tanggal_obat_masuk', '>=', $date_from )->where( 'tanggal_obat_masuk', '<=', $date_to )->orderBy( 'id_pembelian_obat', 'desc' )->get();
            elseif( $date_from )
                $datas = MedicineIn::where( 'tanggal_obat_masuk', '>=', $date_from )->orderBy( 'id_pembelian_obat', 'desc' )->get();
            elseif( $date_to )
                $datas = MedicineIn::where( 'tanggal_obat_masuk', '<=', $date_to )->orderBy( 'id_pembelian_obat', 'desc' )->get();
            else
                $datas = MedicineIn::orderBy( 'id_pembelian_obat', 'desc' )->get();
        }else{
            if( $date_from && $date_to )
                $datas = MedicineIn::where( 'tanggal_obat_masuk', '>=', $date_from )->where( 'tanggal_obat_masuk', '<=', $date_to )->orderBy( 'id_pembelian_obat', 'desc' )->paginate( $rows );
            elseif( $date_from )
                $datas = MedicineIn::where( 'tanggal_obat_masuk', '>=', $date_from )->orderBy( 'id_pembelian_obat', 'desc' )->paginate( $rows );
            elseif( $date_to )
                $datas = MedicineIn::where( 'tanggal_obat_masuk', '<=', $date_to )->orderBy( 'id_pembelian_obat', 'desc' )->paginate( $rows );
            else
                $datas = MedicineIn::orderBy( 'id_pembelian_obat', 'desc' )->paginate( $rows );
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        return view( 'reports.medicinein', [ 'datas' => $datas, 'rows' => $rows, 'page' => $page, 'i' => $i, 'date_from' => $date_from, 'date_to' => $date_to ]);
    }

    public function medicineout(){
        if( !current_user_can( 'laporan_obat_keluar' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        if( $rows == 'all' ){
            if( $date_from && $date_to )
                $datas = MedicineOut::where( 'tanggal_pengeluaran_obat', '>=', $date_from )->where( 'tanggal_pengeluaran_obat', '<=', $date_to )->orderBy( 'id_pengeluaran_obat', 'desc' )->get();
            elseif( $date_from )
                $datas = MedicineOut::where( 'tanggal_pengeluaran_obat', '>=', $date_from )->orderBy( 'id_pengeluaran_obat', 'desc' )->get();
            elseif( $date_to )
                $datas = MedicineOut::where( 'tanggal_pengeluaran_obat', '<=', $date_to )->orderBy( 'id_pengeluaran_obat', 'desc' )->get();
            else
                $datas = MedicineOut::orderBy( 'id_pengeluaran_obat', 'desc' )->get();
        }else{
            if( $date_from && $date_to )
                $datas = MedicineOut::where( 'tanggal_pengeluaran_obat', '>=', $date_from )->where( 'tanggal_pengeluaran_obat', '<=', $date_to )->orderBy( 'id_pengeluaran_obat', 'desc' )->paginate( $rows );
            elseif( $date_from )
                $datas = MedicineOut::where( 'tanggal_pengeluaran_obat', '>=', $date_from )->orderBy( 'id_pengeluaran_obat', 'desc' )->paginate( $rows );
            elseif( $date_to )
                $datas = MedicineOut::where( 'tanggal_pengeluaran_obat', '<=', $date_to )->orderBy( 'id_pengeluaran_obat', 'desc' )->paginate( $rows );
            else
                $datas = MedicineOut::orderBy( 'id_pengeluaran_obat', 'desc' )->paginate( $rows );
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        return view( 'reports.medicineout', [ 'datas' => $datas, 'rows' => $rows, 'page' => $page, 'i' => $i, 'date_from' => $date_from, 'date_to' => $date_to ]);
    }


    public function top10disease(){
        if( !current_user_can( 'laporan_top_10_penyakit' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        $date_from_full = $date_from ? $date_from . " 00:00:00" : "";
        $date_to_full = $date_to ? $date_to . " 23:59:59" : "";

        $count = ( isset( $_GET['count'] ) && $_GET['count'] != '' ) ? $_GET['count'] : 10;

        if( $date_from || $date_to ){
            if( $date_from && $date_to ){
                $datas = DB::table( 't_pemeriksaan_poli' )
                    ->select( DB::raw( 'count(id_pemeriksaan_poli) as count, iddiagnosa' ) )
                    ->where( 'created_at', '>=', $date_from_full )
                    ->where( 'created_at', '<=', $date_to_full )
                    ->where( 'iddiagnosa', '<>', "" )
                    ->where( 'iddiagnosa', '<>', NULL )
                    ->groupBy( 'iddiagnosa' )
                    ->limit( $count )
                    ->orderBy( 'count', 'desc' )
                    ->get();
            }elseif( $date_from && !$date_to ){
                $datas = DB::table( 't_pemeriksaan_poli' )
                    ->select( DB::raw( 'count(id_pemeriksaan_poli) as count, iddiagnosa' ) )
                    ->where( 'created_at', '>=', $date_from_full )
                    ->where( 'iddiagnosa', '<>', "" )
                    ->where( 'iddiagnosa', '<>', NULL )
                    ->groupBy( 'iddiagnosa' )
                    ->limit( $count )
                    ->orderBy( 'count', 'desc' )
                    ->get();
            }elseif( !$date_from && $date_to ){
                $datas = DB::table( 't_pemeriksaan_poli' )
                    ->select( DB::raw( 'count(id_pemeriksaan_poli) as count, iddiagnosa' ) )
                    ->where( 'created_at', '<=', $date_to_full )
                    ->where( 'iddiagnosa', '<>', "" )
                    ->where( 'iddiagnosa', '<>', NULL )
                    ->groupBy( 'iddiagnosa' )
                    ->limit( $count )
                    ->orderBy( 'count', 'desc' )
                    ->get();
            }
        }else{
            $datas = DB::table( 't_pemeriksaan_poli' )
                ->select( DB::raw( 'count(id_pemeriksaan_poli) as count, iddiagnosa' ) )
                ->where( 'iddiagnosa', '<>', "" )
                ->where( 'iddiagnosa', '<>', NULL )
                ->groupBy( 'iddiagnosa' )
                ->limit( 10 )
                ->orderBy( 'count', 'desc' )
                ->get();
        }

        return view( 'reports.top10disease', [ 'datas' => $datas, 'date_from' => $date_from, 'date_to' => $date_to, 'count' => $count ]);
    }
}
