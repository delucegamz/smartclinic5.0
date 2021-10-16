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
use App\Observation;
use App\Anc;
use App\Staff;
use App\JobTitle;
use App\Diagnosis;
use DB;
use Response;
use Auth;
use Carbon\Carbon;

class PrintController extends Controller
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

    public function staff_status(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.staff_status' );
    }

    public function staff_sex(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.staff_sex' );
    }

    public function staff_user(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.staff_user' );
    }

    public function staff_jobtitle(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.staff_jobtitle' );
    }

    public function participant_sex(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.participant_sex' );
    }

    public function participant_pregnant(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.participant_pregnant' );
    }

    public function participant_tb(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.participant_tb' );
    }

    public function participant_factory(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.participant_factory' );
    }

    public function participant_department(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.participant_department' );
    }

    public function participant_client(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.participant_client' );
    }

    public function participant_status(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.participant_status' );
    }

    public function participant_data(){
        if( !current_user_can( 'laporan_peserta' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.participant_data' );
    }

    public function organization_client(){
        if( !current_user_can( 'laporan_organisasi' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.organization_client' );
    }

    public function organization_factory(){
        if( !current_user_can( 'laporan_organisasi' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.organization_factory' );
    }

    public function organization_department(){
        if( !current_user_can( 'laporan_organisasi' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.organization_department' );
    }

    public function visit(){
        if( !current_user_can( 'laporan_kunjungan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $view = ( isset( $_GET['view'] ) && $_GET['view'] != '' ) ? $_GET['view']  : '';
        $participant = ( isset( $_GET['participant'] ) && $_GET['participant'] != '' ) ? $_GET['participant']  : '';
        $department = ( isset( $_GET['department'] ) && $_GET['department'] != '' ) ? $_GET['department']  : '';
        $client = ( isset( $_GET['client'] ) && $_GET['client'] != '' ) ? $_GET['client']  : '';
        $factory = ( isset( $_GET['factory'] ) && $_GET['factory'] != '' ) ? $_GET['factory']  : '';
        $date_from = ( isset( $_GET['date_from'] ) && $_GET['date_from'] != '' ) ? $_GET['date_from']  : '';
        $date_to = ( isset( $_GET['date_to'] ) && $_GET['date_to'] != '' ) ? $_GET['date_to']  : '';

        $datas = NULL;

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

            if( $date_from && $date_to ){
                $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                     ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                     ->whereIn( 'id_peserta', function( $query ) use( $department ){
                                        $query->select( 'id_peserta' )
                                            ->where( 'id_departemen', '=', $department )
                                            ->from( 'm_peserta' );
                                     })
                                     ->orderBy( 'id_poli', 'asc' )
                                     ->get();
            }elseif( $date_from ){
                $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                     ->whereIn( 'id_peserta', function( $query ) use( $department ){
                                        $query->select( 'id_peserta' )
                                            ->where( 'id_departemen', '=', $department )
                                            ->from( 'm_peserta' );
                                     })
                                     ->orderBy( 'id_poli', 'asc' )
                                     ->get();
            }elseif( $date_to ){
                $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                     ->whereIn( 'id_peserta', function( $query ) use( $department ){
                                        $query->select( 'id_peserta' )
                                            ->where( 'id_departemen', '=', $department )
                                            ->from( 'm_peserta' );
                                     })
                                     ->orderBy( 'id_poli', 'asc' )
                                     ->get();
            }else{
                $datas = PoliRegistration::whereIn( 'id_peserta', function( $query ) use( $department ){
                                        $query->select( 'id_peserta' )
                                            ->where( 'id_departemen', '=', $department )
                                            ->from( 'm_peserta' );
                                     })
                                     ->orderBy( 'id_poli', 'asc' )
                                     ->get();
            }
        }elseif( $view == 'client' ){
            $deps = array();

            $d = Department::where( 'nama_client', '=', $client )->get();
            foreach ( $d as $f ) {
                $deps[] = $f->id_departemen;
            }

            if( $date_from && $date_to ){
                $datas = DB::table( 't_pendaftaran' )
                            ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                            ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                            ->whereIn( 'id_peserta', function( $query ) use ( $deps ){
                                $query->select( 'id_peserta' )
                                      ->from( 'm_peserta' )
                                      ->whereIn( 'id_departemen', $deps );
                            })
                            ->orderBy( 'id_poli', 'asc' ) 
                            ->get();
            }elseif( $date_from ){
                $datas = DB::table( 't_pendaftaran' )
                            ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                            ->whereIn( 'id_peserta', function( $query ) use ( $deps ){
                                $query->select( 'id_peserta' )
                                      ->from( 'm_peserta' )
                                      ->whereIn( 'id_departemen', $deps );
                            })
                            ->orderBy( 'id_poli', 'asc' ) 
                            ->get();
            }elseif( $date_to ){
                $datas = DB::table( 't_pendaftaran' )
                            ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                            ->whereIn( 'id_peserta', function( $query ) use ( $deps ){
                                $query->select( 'id_peserta' )
                                      ->from( 'm_peserta' )
                                      ->whereIn( 'id_departemen', $deps );
                            })
                            ->orderBy( 'id_poli', 'asc' ) 
                            ->get();
            }else{
                 $datas = DB::table( 't_pendaftaran' )
                            ->whereIn( 'id_peserta', function( $query ) use ( $deps ){
                                $query->select( 'id_peserta' )
                                      ->from( 'm_peserta' )
                                      ->whereIn( 'id_departemen', $deps );
                            })
                            ->orderBy( 'id_poli', 'asc' ) 
                            ->get();
            }
        }elseif( $view == 'factory' ){
            $deps = array();

            $f = Department::where( 'nama_factory', '=', $factory )->get();
            foreach ( $f as $g ) {
                $deps[] = $g->id_departemen;
            }

           if( $date_from && $date_to ){
                $datas = DB::table( 't_pendaftaran' )
                            ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                            ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                            ->whereIn( 'id_peserta', function( $query ) use ( $deps ){
                                $query->select( 'id_peserta' )
                                      ->from( 'm_peserta' )
                                      ->whereIn( 'id_departemen', $deps );
                            })
                            ->orderBy( 'id_poli', 'asc' ) 
                            ->get();
            }elseif( $date_from ){
                $datas = DB::table( 't_pendaftaran' )
                            ->where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                            ->whereIn( 'id_peserta', function( $query ) use ( $deps ){
                                $query->select( 'id_peserta' )
                                      ->from( 'm_peserta' )
                                      ->whereIn( 'id_departemen', $deps );
                            })
                            ->orderBy( 'id_poli', 'asc' ) 
                            ->get();
            }elseif( $date_to ){
                $datas = DB::table( 't_pendaftaran' )
                            ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                            ->whereIn( 'id_peserta', function( $query ) use ( $deps ){
                                $query->select( 'id_peserta' )
                                      ->from( 'm_peserta' )
                                      ->whereIn( 'id_departemen', $deps );
                            })
                            ->orderBy( 'id_poli', 'asc' ) 
                            ->get();
            }else{
                 $datas = DB::table( 't_pendaftaran' )
                            ->whereIn( 'id_peserta', function( $query ) use ( $deps ){
                                $query->select( 'id_peserta' )
                                      ->from( 'm_peserta' )
                                      ->whereIn( 'id_departemen', $deps );
                            })
                            ->orderBy( 'id_poli', 'asc' ) 
                            ->get();
            }
        }

        return view( 'print.visit', [ 
            'datas' => $datas, 
            'participant' => $participant, 
            'department' => $department, 
            'client' => $client, 
            'factory' => $factory,
            'view' => $view,
            'date_from' => $date_from,
            'date_to' => $date_to  
        ]);
    }

    public function medicinestock(){
        if( !current_user_can( 'laporan_stock_obat' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.medicinestock' );
    }

    public function medicinein(){
        if( !current_user_can( 'laporan_obat_masuk' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.medicinein' );
    }

    public function medicineout(){
        if( !current_user_can( 'laporan_obat_keluar' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.medicineout' );
    }

    public function doctorrecipe(){
        if( !current_user_can( 'laporan_resep_dokter' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.doctorrecipe' );
    }

    public function ambulance(){
        if( !current_user_can( 'laporan_ambulance' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        $participant_id = isset( $_GET['participant_id'] ) ? $_GET['participant_id']  : 0;
        $participant = isset( $_GET['participant'] ) ? $_GET['participant']  : '';

        $view = ( isset( $_GET['view'] ) && $_GET['view'] != '' ) ? filter_var( $_GET['view'], FILTER_SANITIZE_STRING ) : 'out';

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
            $datas = AmbulanceOut::where( 'id_peserta', '=', $participant_id )->get();
        }

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

        return view( 'print.ambulance', [ 
            'datas' => $datas, 
            'ambulances' => $ambulances, 
            'date_from' => $date_from, 
            'date_to' => $date_to,
            'view' => $view,
            'participant' => $participant
        ] );   
    }

    public function poliregistration(){
        if( !current_user_can( 'laporan_kunjungan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $date_from = isset( $_GET['date_from'] ) ? $_GET['date_from']  : '';
        $date_to = isset( $_GET['date_to'] ) ? $_GET['date_to']  : '';

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
        
        return view( 'print.registration', [ 
            'datas' => $datas, 
            'date_from' => $date_from,
            'date_to' => $date_to,
            'filter' => $filter
        ]);
    }

    public function medrec_detail(){
        if( !current_user_can( 'laporan_rekam_medis' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.medrec_detail' );
    }

    public function referenceletter(){
        if( !current_user_can( 'surat_rujukan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.referenceletter' );
    }

    public function sickletter(){
        if( !current_user_can( 'surat_keterangan_sakit' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.sickletter' );
    }

    public function dayoffletter(){
        if( !current_user_can( 'surat_cuti' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'print.dayoffletter' );
    }

    public function letter(){
        if( !current_user_can( 'laporan_surat' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $view = ( isset( $_GET['view'] ) && $_GET['view'] != '' ) ? $_GET['view'] : '';
        $participant = ( isset( $_GET['participant_id'] ) && $_GET['participant_id'] != '' ) ? $_GET['participant_id'] : 0;
        $datas = null;

        if( $view == 'day-off' ){
         
            if( $participant )
                $datas = DayOffLetter::where( 'id_peserta', '=', $participant )->orderBy( 'id_surat_cuti', 'desc' )->get();
            else
                $datas = DayOffLetter::orderBy( 'id_surat_cuti', 'desc' )->get();
            
        }elseif( $view == 'reference' ){
           
            if( $participant )
                $datas = ReferenceLetter::where( 'id_peserta', '=', $participant )->orderBy( 'id_surat_rujukan', 'desc' )->get();
            else
                $datas = ReferenceLetter::orderBy( 'id_surat_rujukan', 'desc' )->get();
            
        }elseif( $view == 'sick' ){
            
            if( $participant )
                $datas = SickLetter::where( 'id_peserta', '=', $participant )->orderBy( 'id_surat_sakit', 'desc' )->get();
            else
                $datas = SickLetter::orderBy( 'id_surat_sakit', 'desc' )->get();
            
        }

        $items = array(); $i = 1; $letter = '';
        foreach( $datas as $data ){
            if( $view == 'day-off' ){
                $items[] = array(
                    'NO' => $i,
                    'No Surat Cuti' => $data->no_surat_cuti,
                    'NIK Peserta' => get_participant_nik( $data->id_peserta ),
                    'Nama Peserta' => get_participant_name( $data->id_peserta ),
                    'Umur Peserta' => get_participant_age( $data->id_peserta ),
                    'Jenis Kelamin' => get_participant_sex( $data->id_peserta ),
                    'Unit Kerja' => get_participant_department( $data->id_peserta ),
                    'Pabrik' => get_participant_factory( $data->id_peserta ),
                    'Perusahaan' => get_participant_client( $data->id_peserta ),
                    'Jenis Cuti' => $data->jenis_cuti,
                    'Lama Cuti' => $data->lama,
                    'Tanggal Mulai Cuti' => $data->dari_tanggal,
                    'Tanggal Akhir Cuti' => $data->sampai_tanggal,
                    'Dokter Jaga' => $data->dokter_jaga 
                );

                $letter = 'Cuti';
            }elseif( $view == 'reference' ){
                $items[] = array(
                    'NO' => $i,
                    'No Surat Rujukan' => $data->no_surat_rujukan,
                    'NIK Peserta' => get_participant_nik( $data->id_peserta ),
                    'Nama Peserta' => get_participant_name( $data->id_peserta ),
                    'Umur Peserta' => get_participant_age( $data->id_peserta ),
                    'Jenis Kelamin' => get_participant_sex( $data->id_peserta ),
                    'Unit Kerja' => get_participant_department( $data->id_peserta ),
                    'Pabrik' => get_participant_factory( $data->id_peserta ),
                    'Perusahaan' => get_participant_client( $data->id_peserta ),
                    'RS Rujukan' => $data->provider,
                    'Dokter Rujukan' => $data->dokter_ahli,
                    'Diagnosa' => $data->diagnosa_dokter,
                    'Anamnesa' => $data->anamnesa,
                    'Pemeriksaan Fisik' => $data->pemeriksaan_fisik,
                    'Obat Beri' => $data->obat_beri,
                    'Catatan' => $data->catatan,
                    'Pemberi Rujukan' => $data->dokter_rujuk 
                );

                $letter = 'Rujukan';
            }elseif( $view == 'sick' ){
                $items[] = array(
                    'NO' => $i,
                    'No Surat Sakit' => $data->no_surat_sakit,
                    'NIK Peserta' => get_participant_nik( $data->id_peserta ),
                    'Nama Peserta' => get_participant_name( $data->id_peserta ),
                    'Umur Peserta' => get_participant_age( $data->id_peserta ),
                    'Jenis Kelamin' => get_participant_sex( $data->id_peserta ),
                    'Unit Kerja' => get_participant_department( $data->id_peserta ),
                    'Pabrik' => get_participant_factory( $data->id_peserta ),
                    'Perusahaan' => get_participant_client( $data->id_peserta ),
                    'Lama Cuti' => $data->lama,
                    'Tanggal Mulai Cuti' => $data->dari_tanggal,
                    'Tanggal Akhir Cuti' => $data->sampai_tanggal,
                    'Dokter Jaga' => $data->dokter_jaga 
                );

                $letter = 'Sakit';
            }

            $i++;
        }

        return view( 'print.letter', [ 
            'items' => $items,
            'view' => $view,
            'participant' => $participant,
            'letter' => $letter
        ]);
    }

    public function medrec(){
        if( !current_user_can( 'laporan_rekam_medis' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $participant = ( isset( $_GET['participant'] ) && $_GET['participant'] != '' ) ? $_GET['participant'] : '';
        $date_from = isset( $_GET['date_from'] ) ? $_GET['date_from'] : '';
        $date_from_full = $date_from . ' 00:00:00';
        $date_to = isset( $_GET['date_to'] ) ? $_GET['date_to'] : '';
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

        if( $participant ){
            if( $date_from || $date_to ){
                $others = MedicalRecord::where( 'id_peserta', '=', $participant )->whereIn( 'id_pendaftaran_poli', $poliregistrations )->orderBy( 'id_pemeriksaan_poli', 'DESC' )->get();    
            }else{
                $others = MedicalRecord::where( 'id_peserta', '=', $participant )->orderBy( 'id_pemeriksaan_poli', 'DESC' )->get();    
            }
        }else{
            if( $date_from || $date_to ){
                $others = MedicalRecord::whereIn( 'id_pendaftaran_poli', $poliregistrations )->orderBy( 'id_pemeriksaan_poli', 'DESC' )->get();    
            }else{
                $others = MedicalRecord::orderBy( 'id_pemeriksaan_poli', 'DESC' )->get();    
            }
        }

        $items = array(); $i = 1;
        foreach( $others as $o ){
            $p = PoliRegistration::find( $o->id_pendaftaran_poli );

            $tl = '';

            if( is_observation( $o->id_pemeriksaan_poli ) ){
                $tl .= 'OBSERVASI, ';
            }

            if( is_sick_letter( $o->id_pemeriksaan_poli ) ){
                $tl .= 'SKS, ';
            }

            if( is_reference_letter( $o->id_pemeriksaan_poli ) ){
                $tl .= 'RUJUKAN, ';
            }

            if( is_dayoff_letter( $o->id_pemeriksaan_poli ) ){
                $tl .= 'SKC, ';
            }
            
            if( is_doctor_recipe( $o->id_pemeriksaan_poli ) ){
                $tl .= 'RESEP, ';
            }

            if( empty( $tl ) ){
                $tl = 'Kembali Bekerja';
            }

            $items[] = array(
                'NO' => $i,
                'TGL' => date( 'd/m/Y', strtotime( $p->tgl_daftar ) ),
                'REGISTRASI' => $o->no_pemeriksaan_poli,
                'PABRIK' => get_participant_factory( $o->id_peserta ),
                'DEPT' => get_participant_department( $o->id_peserta ),
                'NIK' => get_participant_nik( $o->id_peserta ),
                'NO MEDREK' => get_participant_medrec_no( $o->id_peserta ),
                'NAMA' => get_participant_name( $o->id_peserta ),
                'JK' => get_participant_sex( $o->id_peserta ),
                'UMUR' => get_participant_age( $o->id_peserta ),
                'ICD' => $o->iddiagnosa,
                'DIAGNOSA' => get_diagnosis_name( $o->iddiagnosa ),
                'DOKTER' => $o->dokter_rawat,
                'POLI' => get_poli_name( $p->id_poli ),
                'TL' => $tl,
                'IN' => date( 'H:i:s', strtotime( $p->tgl_daftar ) ),
                'OUT' => date( 'H:i:s', strtotime( $p->tgl_selesai ) ),
                'BL' => get_visit( $o->id_peserta ) > 1 ? 'Lama' : 'Baru'
            );

            $i++;
        }

        return view( 'print.medrec', [ 
            'datas' => $items, 
            'date_from' => $date_from,
            'date_to' => $date_to,
            'participant' => $participant
        ]);
    }

    public function observation(){
        if( !current_user_can( 'laporan_observasi' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $participant = ( isset( $_GET['participant'] ) && $_GET['participant'] != '' ) ? $_GET['participant'] : '';
        $date_from = isset( $_GET['date_from'] ) ? $_GET['date_from'] : '';
        $date_from_full = $date_from . ' 00:00:00';
        $date_to = isset( $_GET['date_to'] ) ? $_GET['date_to'] : '';
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

        if( $participant ){
            if( $date_from || $date_to ){
                $others = Observation::where( 'id_peserta', '=', $participant )->whereIn( 'id_observasi', $observations )->orderBy( 'id_observasi', 'DESC' )->get();    
            }else{
                $others = Observation::where( 'id_peserta', '=', $participant )->orderBy( 'id_observasi', 'DESC' )->get();    
            }
        }else{
            if( $date_from || $date_to ){
                $others = Observation::whereIn( 'id_observasi', $observations )->orderBy( 'id_observasi', 'DESC' )->get();    
            }else{
                $others = Observation::orderBy( 'id_observasi', 'DESC' )->get();    
            }
        }

        return view( 'print.observation', [ 
            'datas' => $others, 
            'date_from' => $date_from,
            'date_to' => $date_to,
            'participant' => $participant
        ]);
    }

    public function anc(){
        if( !current_user_can( 'laporan_anc' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $participant = ( isset( $_GET['participant'] ) && $_GET['participant'] != '' ) ? $_GET['participant'] : '';
        $date_from = isset( $_GET['date_from'] ) ? $_GET['date_from'] : '';
        $date_from_full = $date_from . ' 00:00:00';
        $date_to = isset( $_GET['date_to'] ) ? $_GET['date_to'] : '';
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

        
        if( $participant ){
            if( $date_from || $date_to ){
                $others = Anc::where( 'id_peserta', '=', $participant )->whereIn( 'id_pemeriksaan_anc', $ancs )->orderBy( 'id_pemeriksaan_anc', 'DESC' )->get();    
            }else{
                $others = Anc::where( 'id_peserta', '=', $participant )->orderBy( 'id_pemeriksaan_anc', 'DESC' )->get();    
            }
        }else{
            if( $date_from || $date_to ){
                $others = Anc::whereIn( 'id_pemeriksaan_anc', $ancs )->orderBy( 'id_pemeriksaan_anc', 'DESC' )->get();    
            }else{
                $others = Anc::orderBy( 'id_pemeriksaan_anc', 'DESC' )->get();    
            }
        }
        

        return view( 'print.anc', [ 
            'datas' => $others, 
            'date_from' => $date_from,
            'date_to' => $date_to,
            'participant' => $participant
        ]);
    }

    public function recap(){
        if( !current_user_can( 'laporan_rekap_kunjungan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

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
        $res_doctors = '';
        $res_services = '';
        $res_diagnosis = '';
        $res_poli = '';
        $res_factories = '';
        $res_departments = '';
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

        return view( 'print.recap', [ 
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

        return view( 'print.top10disease', [ 'datas' => $datas, 'date_from' => $date_from, 'date_to' => $date_to, 'count' => $count ]);
    }

    public function accident(Request $request){
        if( !current_user_can( 'laporan_rekam_medis' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $filter_by = $request->input('filter_by');
        $accident = $request->input('accident');
        $start_date = $request->input('start_date');
		$end_date = $request->input('end_date');
        $nik_peserta = $request->input('nik_peserta');

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

        return view( 'print.accident', [ 
            'medicalRecords' => $medicalRecords,  
            'start_date' => $start_date,
            'end_date' => $end_date 
        ]);
    }
}
