<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\MedicalRecord;
use App\PoliRegistration;
use App\Participant;
use App\PregnantParticipant;
use App\Poli;
use App\Diagnosis;
use App\Observation;
use App\ObservationDetail;
use App\SickLetter;
use App\ReferenceLetter;
use App\DayOffLetter;
use App\DoctorRecipe;
use App\MedicineAllergic;
use App\Accident;
use App\JobTitle;
use App\Staff;
use App\Anc;
use DB;
use Response;
use Auth;
use Session;

class MedicalRecordController extends Controller
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
        if( !current_user_can( 'pemeriksaan_dokter' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        if( isset( $_GET['poli'] ) && $_GET['poli'] != '' ){
            $poli = absint( $_GET['poli'] );
        }else{
            //$general_poli = Poli::where( 'nama_poli', '=', 'Umum' )->first();

            //$poli = ( $general_poli && isset( $general_poli->id_poli ) ) ? $general_poli->id_poli : 0;
            $poli = '';
        }

        if( isset( $_GET['filter'] ) && $_GET['filter'] != '' ){
            $filter = $_GET['filter'];
        }else{
            $filter = 'pendaftaran-today';
        }

        $medrecs = array();

        if( $filter == 'belum-direkam' || $filter == 'pendaftaran-today' ){
            $medrec_results = array();
        }elseif( $filter == 'tidak-direkam' ){  
            $medrec_results = MedicalRecord::where( 'status', '=', 1 )->get();
        }elseif( $filter == 'sudah-direkam' ){
            $medrec_results = MedicalRecord::where( 'status', '=', 0 )->get();
        }

        if( count( $medrec_results ) > 0 ){
            foreach( $medrec_results as $res ){
                $medrecs[] = $res->id_pendaftaran_poli;
            }
        }

        $s = '';
        if( isset( $_GET['s'] ) && $_GET['s'] != '' ){
            $s = filter_var( $_GET['s'], FILTER_SANITIZE_STRING );

            $participants = Participant::where( function( $q ) use ( $s ){
                $q->where( 'nama_peserta', 'LIKE', "%$s%" )
                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" );
            })->get();

            $ids = array();
            foreach ( $participants as $p ) {
                $ids[] = $p->id_peserta;
            } 
        }

        $date_from = ( isset( $_GET['date-from'] ) && $_GET['date-from'] != '' ) ? $_GET['date-from'] . ' 00:00:00' : date( 'Y-m-d' ) . ' 00:00:00';
        $date_to = ( isset( $_GET['date-to'] ) && $_GET['date-to'] != '' ) ? $_GET['date-to'] . ' 23:59:59' : date( 'Y-m-d' ) . ' 23:59:59';

        $date_from_unformat = ( isset( $_GET['date-from'] ) && $_GET['date-from'] != '' ) ? $_GET['date-from'] : '';
        $date_to_unformat = ( isset( $_GET['date-to'] ) && $_GET['date-to'] != '' ) ? $_GET['date-to'] : '';
        
        if( $rows == 'all' ){
            if( empty( $s ) ){
                if( !$poli ){
                    if( $filter == 'pendaftaran-today' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                             ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                             ->where( 'status', '=', 1 )
                                             ->orderBy( 'no_antrian', 'desc' )
                                             ->get();
                    }elseif( $filter == 'belum-direkam' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from )
                                             ->where( 'tgl_daftar', '<=', $date_to )
                                             ->where( 'status', '=', 1 )
                                             ->orderBy( 'id_pendaftaran', 'asc' )
                                             ->get();
                    }elseif( $filter == 'tidak-direkam' || $filter == 'sudah-direkam' ){
                        $datas = PoliRegistration::whereIn( 'id_pendaftaran', function( $query ) use( $filter ){
                                                if( $filter == 'tidak-direkam' ){
                                                    $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 1 )->from( 't_pemeriksaan_poli' );
                                                }elseif( $filter == 'sudah-direkam' ){
                                                    $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 0 )->from( 't_pemeriksaan_poli' );
                                                }
                                             })
                                             ->where( 'tgl_daftar', '>=', $date_from )
                                             ->where( 'tgl_daftar', '<=', $date_to )
                                             ->orderBy( 'id_pendaftaran', 'desc' )
                                             ->get();
                    }
                    
                }else{
                    if( $filter == 'pendaftaran-today' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                             ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                             ->where( 'status', '=', 1 )
                                             ->where( 'id_poli', '=', $poli )
                                             ->orderBy( 'no_antrian', 'desc' )
                                             ->get();
                    }elseif( $filter == 'belum-direkam' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from )
                                             ->where( 'tgl_daftar', '<=', $date_to )
                                             ->where( 'status', '=', 1 )
                                             ->where( 'id_poli', '=', $poli )
                                             ->orderBy( 'id_pendaftaran', 'asc' )
                                             ->get();
                    }elseif( $filter == 'tidak-direkam' || $filter == 'sudah-direkam' ){
                        $datas = PoliRegistration::whereIn( 'id_pendaftaran', function( $query ) use( $filter ){
                                                if( $filter == 'tidak-direkam' ){
                                                    $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 1 )->from( 't_pemeriksaan_poli' );
                                                }elseif( $filter == 'sudah-direkam' ){
                                                    $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 0 )->from( 't_pemeriksaan_poli' );
                                                }
                                             })
                                             ->where( 'tgl_daftar', '>=', $date_from )
                                             ->where( 'tgl_daftar', '<=', $date_to )
                                             ->where( 'id_poli', '=', $poli )
                                             ->orderBy( 'id_pendaftaran', 'desc' )
                                             ->get();
                    }
                }
            }else{
                if( !$poli ){
                    if( $filter == 'pendaftaran-today' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                         ->whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->where( 'status', '=', 1 )
                                         ->orderBy( 'no_antrian', 'asc' )
                                         ->get();
                    }elseif( $filter == 'belum-direkam' ){
                        $datas = PoliRegistration::whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->where( 'status', '=', 1 )
                                         ->orderBy( 'id_pendaftaran', 'asc' )
                                         ->get();
                    }elseif( $filter == 'tidak-direkam' || $filter == 'sudah-direkam' ){
                        $datas = PoliRegistration::whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->whereIn( 'id_pendaftaran', function( $query ) use( $filter ){
                                            if( $filter == 'tidak-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 1 )->from( 't_pemeriksaan_poli' );
                                            }elseif( $filter == 'sudah-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 0 )->from( 't_pemeriksaan_poli' );
                                            }
                                         })
                                         ->where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->orderBy( 'id_pendaftaran', 'desc' )
                                         ->get();
                    }
                }else{
                    if( $filter == 'pendaftaran-today' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                         ->whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->where( 'id_poli', '=', $poli )
                                         ->where( 'status', '=', 1 )
                                         ->orderBy( 'no_antrian', 'asc' )
                                         ->get();
                    }elseif( $filter == 'belum-direkam' ){
                        $datas = PoliRegistration::whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->where( 'id_poli', '=', $poli )
                                         ->where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->where( 'status', '=', 1 )
                                         ->orderBy( 'id_pendaftaran', 'asc' )
                                         ->get();
                    }elseif( $filter == 'tidak-direkam' || $filter == 'sudah-direkam' ){
                        $datas = PoliRegistration::whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->where( 'id_poli', '=', $poli )
                                         ->whereIn( 'id_pendaftaran', function( $query ) use( $filter ){
                                            if( $filter == 'tidak-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 1 )->from( 't_pemeriksaan_poli' );
                                            }elseif( $filter == 'sudah-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 0 )->from( 't_pemeriksaan_poli' );
                                            }
                                         })
                                         ->where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->orderBy( 'id_pendaftaran', 'desc' )
                                         ->get();
                    }
                }
            }
        }else{
            if( empty( $s ) ){
                if( !$poli ){
                    if( $filter == 'pendaftaran-today' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                         ->where( 'status', '=', 1 )
                                         ->orderBy( 'no_antrian', 'asc' )
                                         ->paginate( $rows );
                    }elseif( $filter == 'belum-direkam' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->where( 'status', '=', 1 )
                                         ->orderBy( 'id_pendaftaran', 'asc' )
                                         ->paginate( $rows );
                    }elseif( $filter == 'tidak-direkam' || $filter == 'sudah-direkam' ){
                        $datas = PoliRegistration::whereIn( 'id_pendaftaran', function( $query ) use( $filter ){
                                            if( $filter == 'tidak-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 1 )->from( 't_pemeriksaan_poli' );
                                            }elseif( $filter == 'sudah-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 0 )->from( 't_pemeriksaan_poli' );
                                            }
                                         })
                                         ->where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->orderBy( 'id_pendaftaran', 'desc' )
                                         ->paginate( $rows );
                    }

                    
                }else{
                    if( $filter == 'pendaftaran-today' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                         ->where( 'status', '=', 1 )
                                         ->where( 'id_poli', '=', $poli )
                                         ->orderBy( 'no_antrian', 'asc' )
                                         ->paginate( $rows );
                    }elseif( $filter == 'belum-direkam' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->where( 'status', '=', 1 )
                                         ->where( 'id_poli', '=', $poli )
                                         ->orderBy( 'id_pendaftaran', 'asc' )
                                         ->paginate( $rows );
                    }elseif( $filter == 'tidak-direkam' || $filter == 'sudah-direkam' ){
                        $datas = PoliRegistration::whereIn( 'id_pendaftaran', function( $query ) use( $filter ){
                                            if( $filter == 'tidak-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 1 )->from( 't_pemeriksaan_poli' );
                                            }elseif( $filter == 'sudah-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 0 )->from( 't_pemeriksaan_poli' );
                                            }
                                         })
                                         ->where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->where( 'id_poli', '=', $poli )
                                         ->orderBy( 'id_pendaftaran', 'desc' )
                                         ->paginate( $rows );
                    }

                    
                }
            }else{
                if( !$poli ){
                    if( $filter == 'pendaftaran-today' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                         ->whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->orderBy( 'id_pendaftaran', 'asc' )
                                         ->paginate( $rows );
                    }elseif( $filter == 'belum-direkam' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->where( 'status', '=', 1 )
                                         ->whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->orderBy( 'id_pendaftaran', 'asc' )
                                         ->paginate( $rows );
                    }elseif( $filter == 'tidak-direkam' || $filter == 'sudah-direkam' ){
                        $datas = PoliRegistration::whereIn( 'id_pendaftaran', function( $query ) use( $filter ){
                                            if( $filter == 'tidak-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 1 )->from( 't_pemeriksaan_poli' );
                                            }elseif( $filter == 'sudah-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 0 )->from( 't_pemeriksaan_poli' );
                                            }
                                         })
                                         ->where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->orderBy( 'id_pendaftaran', 'desc' )
                                         ->paginate( $rows );
                    }
                }else{
                    if( $filter == 'pendaftaran-today' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', date( 'Y-m-d' ) . ' 00:00:00' )
                                         ->where( 'tgl_daftar', '<=', date( 'Y-m-d' ) . ' 23:59:59' )
                                         ->where( 'status', '=', 1 )
                                         ->whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->where( 'id_poli', '=', $poli )
                                         ->orderBy( 'id_pendaftaran', 'asc' )
                                         ->paginate( $rows );
                    }elseif( $filter == 'belum-direkam' ){
                        $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->where( 'status', '=', 1 )
                                         ->whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->where( 'id_poli', '=', $poli )
                                         ->orderBy( 'id_pendaftaran', 'asc' )
                                         ->paginate( $rows );
                    }elseif( $filter == 'tidak-direkam' || $filter == 'sudah-direkam' ){
                        $datas = PoliRegistration::whereIn( 'id_pendaftaran', function( $query ) use( $filter ){
                                            if( $filter == 'tidak-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 1 )->from( 't_pemeriksaan_poli' );
                                            }elseif( $filter == 'sudah-direkam' ){
                                                $query->select( 'id_pendaftaran_poli' )->where( 'status', '=', 0 )->from( 't_pemeriksaan_poli' );
                                            }
                                         })
                                         ->where( 'tgl_daftar', '>=', $date_from )
                                         ->where( 'tgl_daftar', '<=', $date_to )
                                         ->whereIn( 'id_peserta',  function( $query ) use( $s ){
                                            $query->select( 'id_peserta' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->orWhere( 'nik_peserta', 'LIKE', "%$s%" )
                                                  ->from( 'm_peserta' );
                                         })
                                         ->where( 'id_poli', '=', $poli )
                                         ->orderBy( 'id_pendaftaran', 'desc' )
                                         ->paginate( $rows );
                    }
                }
            }
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $polis = Poli::all();

        return view( 'doctorcheck', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'poli' => $poli, 'filter' => $filter, 'date_from' => $date_from_unformat, 'date_to' => $date_to_unformat, 'polis' => $polis ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {   
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $poliregistration = PoliRegistration::find( $id );

        $poli_check = MedicalRecord::where( 'id_pendaftaran_poli', '=', $id )->first();

        $igd = (int) get_setting( 'igd' );
        $poli_umum = (int) get_setting( 'poli_umum' );
        $poli_kebidanan = (int) get_setting( 'poli_kebidanan' );

        if( !$poli_check ){
            $poli_check = new MedicalRecord();
            $poli_check->no_pemeriksaan_poli = MedicalRecord::generate_id();
            $poli_check->id_pendaftaran_poli = $id;
            $poli_check->id_peserta = $poliregistration->id_peserta; 
            $poli_check->nama_peserta = get_participant_name( $poliregistration->id_peserta ); 
            $poli_check->nama_factory = get_participant_factory( $poliregistration->id_peserta ); 
            $poli_check->nama_client = get_participant_client( $poliregistration->id_peserta ); 
            $poli_check->nama_departemen = get_participant_department( $poliregistration->id_peserta ); 
            $poli_check->iddiagnosa = ''; 
            $poli_check->diagnosa_dokter = '';
            $poli_check->uraian = ( $poliregistration->id_poli == $igd ? $poliregistration->catatan_pendaftaran : 11 );
            $poli_check->dokter_rawat = '';
            $poli_check->keluhan = '';
            $poli_check->catatan_pemeriksaan = ''; 
            $poli_check->pahk = '';
            $poli_check->tb = '';
            $poli_check->status = 1;
            $poli_check->id_pengguna = $idpengguna;

            $poli_check->save();
        }

        $participant = Participant::find( $poliregistration->id_peserta );

        $diagnosis = $poli_check->iddiagnosa ? Diagnosis::where( 'kode_diagnosa', '=', $poli_check->iddiagnosa )->first() : new Diagnosis();

        $observation = $poli_check ? Observation::where( 'id_pemeriksaan_poli', '=', $poli_check->id_pemeriksaan_poli )->first() : NULL;
        $sickletter = $poli_check ? SickLetter::where( 'id_pemeriksaan_poli', '=', $poli_check->id_pemeriksaan_poli )->first() : NULL;
        $referenceletter = $poli_check ? ReferenceLetter::where( 'id_pemeriksaan_poli', '=', $poli_check->id_pemeriksaan_poli )->first() : NULL;
        $dayoffletter = $poli_check ? DayOffLetter::where( 'id_pemeriksaan_poli', '=', $poli_check->id_pemeriksaan_poli )->first() : NULL;
        $doctorrecipe = $poli_check ? DoctorRecipe::where( 'id_pemeriksaan_poli', '=', $poli_check->id_pemeriksaan_poli )->first() : NULL;

        $others = MedicalRecord::where( 'id_peserta', '=', $poliregistration->id_peserta )->orderBy( 'id_pemeriksaan_poli', 'DESC' )->get();

        $medicineallergics = MedicineAllergic::where( 'id_peserta', '=', $poliregistration->id_peserta )->get();

        $accident = $poli_check ? Accident::where( 'id_pemeriksaan_poli', '=', $poli_check->id_pemeriksaan_poli )->first() : NULL;

        if( !$accident ){
            $accident = new Accident();

            $accident->jenis_kecelakaan = '';
            $accident->akibat_kecelakaan = '';
            $accident->tindakan = '';
            $accident->penyebab_kecelakaan = '';
            $accident->rekomendasi = '';
            $accident->keterangan_kecelakaan = '';
            $accident->hari_kejadian = '';
            $accident->tanggal_kejadian = '';
            $accident->saksi = '';
            $accident->atasan_langsung = '';
            $accident->telepon = '';
            $accident->nama_penanggung_jawab = '';
            $accident->jabatan = '';
        }   

        if( $poliregistration->id_poli == $poli_kebidanan ){
             $jobtitleresults = JobTitle::where( 'nama_jabatan', 'LIKE', '%dokter%' )->orWhere( 'nama_jabatan', 'LIKE', '%bidan%' )->get();
        }else{
            $jobtitleresults = JobTitle::where( 'nama_jabatan', 'LIKE', '%dokter%' )->get();
        }
        $jobtitles = array();

        foreach( $jobtitleresults as $res ){
            $jobtitles[] = $res->id_jabatan;
        }

        $doctors = Staff::whereIn( 'id_jabatan', $jobtitles )->where( 'status', '=', 1 )->get();

        if( $poliregistration->id_poli != 2 ){
            return view( 'medical-record', [ 
                'participant' => $participant, 
                'poliregistration' => $poliregistration, 
                'poli_check' => $poli_check, 
                'diagnosis' => $diagnosis, 
                'observation' => $observation, 
                'sickletter' => $sickletter, 
                'referenceletter' => $referenceletter, 
                'dayoffletter' => $dayoffletter, 
                'doctorrecipe' => $doctorrecipe,
                'others' => $others,
                'medicineallergics' => $medicineallergics,
                'accident' => $accident,
                'doctors' => $doctors
            ]);
        }else{
            $pregnant = PregnantParticipant::where( 'id_peserta', '=', $poliregistration->id_peserta )->first();

            if( !$pregnant ){
                $pregnant = new PregnantParticipant();
            }

            $anc = Anc::where( 'id_pemeriksaan_poli', '=', $poli_check->id_pemeriksaan_poli )->where( 'id_peserta', '=', $poliregistration->id_peserta )->first();

            if( !$anc ){
                $anc = new Anc();
            }

            if( isset( $anc->id_pemeriksaan_anc ) && $anc->id_pemeriksaan_anc ){
                $last_anc = Anc::where( 'id_peserta', '=', $poliregistration->id_peserta )
                               ->where( 'id_pemeriksaan_anc', '<>', $anc->id_pemeriksaan_anc )
                               ->orderBy( 'id_pemeriksaan_anc', 'DESC' )
                               ->first();
            }else{
                $last_anc = Anc::where( 'id_peserta', '=', $poliregistration->id_peserta )
                               ->orderBy( 'id_pemeriksaan_anc', 'DESC' )
                               ->first();
            } 
            
            if( !$last_anc ) $last_anc = new Anc();

            $anc_checks = Anc::where( 'id_peserta', '=', $poliregistration->id_peserta )->get(); $ancs = array();
            foreach( $anc_checks as $c ){
                $ancs[] = $c->id_pemeriksaan_poli;
            }

            $anc_items = MedicalRecord::whereIn( 'id_pemeriksaan_poli', $ancs )->orderBy( 'id_pemeriksaan_poli', 'DESC' )->get();

            return view( 'medical-record', [ 
                'participant' => $participant, 
                'poliregistration' => $poliregistration, 
                'poli_check' => $poli_check, 
                'diagnosis' => $diagnosis, 
                'observation' => $observation, 
                'sickletter' => $sickletter, 
                'referenceletter' => $referenceletter, 
                'dayoffletter' => $dayoffletter, 
                'doctorrecipe' => $doctorrecipe,
                'others' => $others,
                'medicineallergics' => $medicineallergics,
                'accident' => $accident,
                'doctors' => $doctors,
                'anc' => $anc,
                'anc_items' => $anc_items,
                'pregnant' => $pregnant,
                'last_anc' => $last_anc,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $id ){
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $input = $request->all();

        $iddiagnosa = $input['icdx'];
        $diagnosa_dokter = $input['doctor-diagnostic'];
        $uraian = $input['action-type'];
        $dokter_rawat = $input['doctor'];
        $keluhan = $input['patient-brief'];
        $catatan_pemeriksaan = $input['doctor-note'];
        $pahk = ( isset( $input['work-dismissed'] ) && $input['work-dismissed'] == 1 ) ? 1 : 0;
        $tb = ( isset( $input['tbc-suspect'] ) && $input['tbc-suspect'] == 1 ) ? 1 : 0;

        $observation = ( isset( $input['need-observation'] ) && $input['need-observation'] == 1 ) ? 1 : 0;
        $sick_letter = ( isset( $input['need-sick-letter'] ) && $input['need-sick-letter'] == 1 ) ? 1 : 0;
        $reference_letter = ( isset( $input['need-reference-letter'] ) && $input['need-reference-letter'] == 1 ) ? 1 : 0;
        $day_off_letter = ( isset( $input['need-day-off-letter'] ) && $input['need-day-off-letter'] == 1 ) ? 1 : 0;
        $doctor_recipe = ( isset( $input['need-doctor-recipe'] ) && $input['need-doctor-recipe'] == 1 ) ? 1 : 0;

        $user_update = $idpengguna;

        try {
            $medicalrecord = MedicalRecord::where( 'id_pendaftaran_poli', '=', $id )->first();
            $poliregistration = PoliRegistration::where( 'id_pendaftaran', '=', $medicalrecord->id_pendaftaran_poli )->first();

            if( !$medicalrecord ){
                $medicalrecord = new MedicalRecord();
                $medicalrecord->no_pemeriksaan_poli = MedicalRecord::generate_id();
                $medicalrecord->id_pendaftaran_poli = $id;
                $medicalrecord->id_peserta = $input['id-peserta'];
                $medicalrecord->id_pengguna = $idpengguna;

                $participant = Participant::find( $input['id-peserta'] );

                $medicalrecord->nama_peserta = $participant->nama_peserta;
                $medicalrecord->nama_factory = get_participant_factory( $participant->id_peserta );
                $medicalrecord->nama_client = get_participant_client( $participant->id_peserta );
                $medicalrecord->nama_departemen = get_participant_department( $participant->id_peserta );
            }

            $medicalrecord->iddiagnosa = $iddiagnosa;
            $medicalrecord->diagnosa_dokter = $diagnosa_dokter;
            $medicalrecord->uraian = $uraian;
            $medicalrecord->dokter_rawat = $dokter_rawat;
            $medicalrecord->keluhan = $keluhan;
            $medicalrecord->catatan_pemeriksaan = $catatan_pemeriksaan;
            $medicalrecord->pahk = $pahk;
            $medicalrecord->tb = $tb;
            $medicalrecord->user_update = $user_update;
            $medicalrecord->status = 0;

            $update = $medicalrecord->save();

            if( isset( $input['is-accident'] ) && $input['is-accident'] == 1 ){
                $accident = Accident::where( 'id_pemeriksaan_poli', '=', $medicalrecord->id_pemeriksaan_poli )->first();

                if( !$accident ){
                    $accident = new Accident();

                    $accident->no_kecelakaan = Accident::generate_id();
                    $accident->id_pemeriksaan_poli = $medicalrecord->id_pemeriksaan_poli;
                    $accident->id_peserta = $medicalrecord->id_peserta;
                    $accident->nama_peserta = $medicalrecord->nama_peserta;
                    $accident->nama_factory = $medicalrecord->nama_factory;
                    $accident->nama_departemen = $medicalrecord->nama_departemen;
                    $accident->nama_client = $medicalrecord->nama_client;
                    $accident->tanggal_lapor = date( 'Y-m-d H:i:s' );
                    $accident->id_pengguna = $idpengguna;
                }   

                $accident->jenis_kecelakaan = $input['jenis-kecelakaan'];
                $accident->akibat_kecelakaan = $input['accident-result'];
                $accident->tindakan = $input['action-given'];
                $accident->penyebab_kecelakaan = $input['accident-cause'];
                $accident->rekomendasi = $input['doctor-recommendation'];
                $accident->keterangan_kecelakaan = $input['accident-explanation'];
                $accident->hari_kejadian = $input['day-accident'];
                $accident->tanggal_kejadian = $input['datetime-accident'];
                $accident->saksi = $input['witness'];
                $accident->atasan_langsung = $input['supervisor-nik'];
                $accident->telepon = $input['supervisor-phone'];
                $accident->nama_penanggung_jawab = $input['informant-name'];
                $accident->jabatan = $input['informant-job-title'];
                $accident->user_update = $idpengguna;
                
                $accident_update = $accident->save();
            }

            $update = $medicalrecord->save();

            $igd = (int) get_setting( 'igd' );
            $poli_umum = (int) get_setting( 'poli_umum' );
            $poli_kebidanan = (int) get_setting( 'poli_kebidanan' );

            if( $observation ){ // Buat entry observasi 

                $observasi = Observation::where( 'id_pemeriksaan_poli', '=', $medicalrecord->id_pemeriksaan_poli )->first();

                // Check apakah sudah ada observasi atau belum
                if( !$observasi ){
                    $observasi = new Observation();

                    $observasi->no_observasi = Observation::generate_id();
                    $observasi->id_pemeriksaan_poli = $medicalrecord->id_pemeriksaan_poli;
                    $observasi->id_peserta = $medicalrecord->id_peserta;
                    $observasi->nama_peserta = $medicalrecord->nama_peserta;
                    $observasi->nama_factory = $medicalrecord->nama_factory;
                    $observasi->nama_departemen = $medicalrecord->nama_departemen;
                    $observasi->nama_client = $medicalrecord->nama_client;
                    $observasi->umur_peserta = get_participant_age( $medicalrecord->id_peserta );
                    $observasi->status = 1;
                    $observasi->id_pengguna = $idpengguna;
                    $observasi->user_update = $idpengguna;
                    $observasi->tanggal_mulai = date( 'Y-m-d H:i:s' );

                    $observasi->save();

                    $observationdetail = new ObservationDetail();
                    $observationdetail->no_observasi_detail = ObservationDetail::generate_id( $observasi->id_observasi );
                    $observationdetail->no_observasi = $observasi->id_observasi;
                    $observationdetail->id_pengguna = $idpengguna;
                    $observationdetail->save();
                }
            }

            if( $sick_letter ){ // Buat entry surat sakit
                $sks = SickLetter::where( 'id_pemeriksaan_poli', '=', $medicalrecord->id_pemeriksaan_poli )->first();

                // Check apakah sudah ada observasi atau belum
                if( !$sks ){
                    $sks = new SickLetter();

                    $sks->no_surat_sakit = SickLetter::generate_id();
                    $sks->id_pemeriksaan_poli = $medicalrecord->id_pemeriksaan_poli;
                    $sks->id_peserta = $medicalrecord->id_peserta;
                    $sks->umur_peserta = get_participant_age( $medicalrecord->id_peserta );
                    $sks->id_pengguna = $idpengguna;
                    $sks->user_update = $idpengguna;
                    $sks->status = 1;

                    $sks->save();
                }
            }

            if( $reference_letter ){ // Buat entry surat rujukan
                $srd = ReferenceLetter::where( 'id_pemeriksaan_poli', '=', $medicalrecord->id_pemeriksaan_poli)->first();

                // Check apakah sudah ada observasi atau belum
                if( !$srd ){
                    $srd = new ReferenceLetter();

                    $srd->no_surat_rujukan = ReferenceLetter::generate_id();
                    $srd->id_pemeriksaan_poli = $medicalrecord->id_pemeriksaan_poli;
                    $srd->id_peserta = $medicalrecord->id_peserta;
                    $srd->id_pengguna = $idpengguna;
                    $srd->user_update = $idpengguna;
                    $srd->status = 1;

                    $srd->save();
                }
            }

            if( $day_off_letter ){ // Buat entry surat cuti
                $sc = DayOffLetter::where( 'id_pemeriksaan_poli', '=', $medicalrecord->id_pemeriksaan_poli )->first();

                // Check apakah sudah ada observasi atau belum
                if( !$sc ){
                    $sc = new DayOffLetter();

                    $sc->no_surat_cuti = DayOffLetter::generate_id();
                    $sc->id_pemeriksaan_poli = $medicalrecord->id_pemeriksaan_poli;
                    $sc->id_peserta = $medicalrecord->id_peserta;
                    $sc->umur_peserta = get_participant_age( $medicalrecord->id_peserta );
                    $sc->id_pengguna = $idpengguna;
                    $sc->user_update = $idpengguna;
                    $sc->status = 1;

                    $sc->save();
                }
            }

            if( $doctor_recipe ){ // Buat entry resep doktor
                $rsp = DoctorRecipe::where( 'id_pemeriksaan_poli', '=', $medicalrecord->id_pemeriksaan_poli )->first();

                // Check apakah sudah ada observasi atau belum
                if( !$rsp ){
                    $rsp = new DoctorRecipe();

                    $rsp->no_resep = DoctorRecipe::generate_id();
                    $rsp->id_pemeriksaan_poli = $medicalrecord->id_pemeriksaan_poli;
                    $rsp->id_pengguna = $idpengguna;
                    $rsp->user_update = $idpengguna;

                    $rsp->save();
                }
            }

            if( isset( $input['medicine_id'] ) && is_array( $input['medicine_id'] ) ){
                foreach( $input['medicine_id'] as $key => $value ){
                    if( $input['medicine_state'][$key] == 'add' ){
                        $medicineallergic = new MedicineAllergic();
                        
                        $medicineallergic->no_peserta_alergi = MedicineAllergic::generate_id();
                        $medicineallergic->id_peserta = $medicalrecord->id_peserta;
                        $medicineallergic->nama_peserta = get_participant_name( $medicalrecord->id_peserta );
                        $medicineallergic->nama_departemen = get_participant_department( $medicalrecord->id_peserta );
                        $medicineallergic->nama_factory = get_participant_factory( $medicalrecord->id_peserta );
                        $medicineallergic->nama_client = get_participant_client( $medicalrecord->id_peserta );
                        $medicineallergic->idobat = $value;
                        $medicineallergic->id_pengguna = $idpengguna;
                        $medicineallergic->user_update = $idpengguna;

                        $medicineallergic->save();
                    }elseif( $input['medicine_state'][$key] == 'delete' ){
                        $medicineallergic = MedicineAllergic::where( 'idobat', '=', $value )->where( 'id_peserta', '=', $medicalrecord->id_peserta )->first();

                        if( $medicineallergic ){
                            $medicineallergic->delete();
                        }
                    }
                }
            }

            if( $poliregistration->id_poli == $poli_kebidanan && $uraian == 66 ){
                $pregnant_id = $input['pregnant_id'];

                $pregnant = PregnantParticipant::find( $pregnant_id );
                if( !$pregnant ){
                    $pregnant = new PregnantParticipant();
                    $pregnant->no_peserta_hamil = PregnantParticipant::generate_id();
                    $pregnant->id_peserta = $medicalrecord->id_peserta;
                    $pregnant->status_hamil = 1;
                    $pregnant->id_pengguna = $idpengguna;
                }

                $pregnant->golongan_darah = $input['golongan_darah'] ? $input['golongan_darah'] : '';
                $pregnant->tanggal_kunjungan = date( 'Y-m-d H:i:s' ); 
                $pregnant->gravida = $input['gravida'] ? $input['gravida'] : 0; 
                $pregnant->partus = $input['partus'] ? $input['partus'] : 0; 
                $pregnant->abortus = $input['abortus'] ? $input['abortus'] : 0;
                $pregnant->hidup = $input['hidup'] ? $input['hidup'] : 0; 
                $pregnant->tanggal_hpht = $input['tanggal_hpht'] ? $input['tanggal_hpht'] : date( 'Y-m-d' ); 

                $hpht = strtotime( $pregnant->tanggal_hpht );

                $hpl_formula_3m_down = get_setting( 'hpl_formula_3m_down' );
                $hpl_formula_3m_down = $hpl_formula_3m_down ? $hpl_formula_3m_down : '+7,+9,+0';
                $hpl_formula_4m_up = get_setting( 'hpl_formula_4m_up' );
                $hpl_formula_4m_up = $hpl_formula_4m_up ? $hpl_formula_4m_up : '+7,-3,+1';
                
                if( date( 'n', $hpht ) >= 1 && date( 'n', $hpht ) <= 3){
                    $hpl_formula = $hpl_formula_3m_down;
                }else if( date( 'n', $hpht ) >= 4 &&  date( 'n', $hpht ) <= 12 ){
                    $hpl_formula = $hpl_formula_4m_up;
                }

                $formula_parts = explode( ',', $hpl_formula );
                $day = $formula_parts[0];
                $month = $formula_parts[1];
                $year = $formula_parts[2];
                $fday = 0;
                $fmonth = 0;
                $fyear = 0;

                if( strpos( $day, "-" ) !== false ){  
                    $day = (int) str_replace( "-", "", $day );
                    $fday = date( "j", $hpht ) - $day;
                }elseif( strpos( $day, "+" ) !== false ){
                    $day = str_replace( "+", "", $day );
                    $fday = date( "j", $hpht ) + $day;
                }

                if( strpos( $month, "-" ) !== false ){
                    $month = (int) str_replace( "-", "", $month );
                    $fmonth = date( "n", $hpht ) - $month;
                }elseif( strpos( $month, "+" ) !== false ){
                    $month = (int) str_replace( "+", "", $month );
                    $fmonth = date( "n", $hpht ) + $month;
                }

                if( strpos( $year, "-" ) !== false ){
                    $year = (int) str_replace( "-", "", $year );
                    $fyear = date( "Y", $hpht ) - $year;
                }elseif( strpos( $year, "+" ) !== false ){
                    $year = (int) str_replace( "+", "", $year );
                    $fyear = date( "Y", $hpht ) + $year;
                }

                $tp = mktime( 0, 0, 0, $fmonth, $fday, $fyear );
                $tp_date = date( 'Y-m-d', $tp );

                $tc = $tp - ( 3600 * 24 * 45 );
                $tc_date = date( 'Y-m-d', $tc );

                $pregnant->bb_normal = $input['bb_normal'] ? $input['bb_normal'] : ''; 
                $pregnant->tinggi_badan = $input['tinggi_badan'] ? $input['tinggi_badan'] : ''; 
                $pregnant->riwayat_komplikasi = $input['riwayat_komplikasi'] ? $input['riwayat_komplikasi'] : ''; 
                $pregnant->tanggal_cuti = $tc_date;
                $pregnant->tp = $tp_date; 
                $pregnant->save();

                $anc_id = $input['anc_id'];
                $anc = Anc::find( $anc_id );
                if( !$anc ){
                    $anc = new Anc();
                    $anc->no_pemeriksaan_anc = Anc::generate_id();
                    $anc->id_pemeriksaan_poli = $medicalrecord->id_pemeriksaan_poli;
                    $anc->id_peserta_hamil = $pregnant->id_peserta_hamil;
                    $anc->id_peserta = $medicalrecord->id_peserta;
                    $anc->id_pengguna = $idpengguna;
                }

                $anc->tgl_pemeriksaan_anc = date( 'Y-m-d H:i:s' ); 
                $anc->kunjungan_ke = get_anc_visit( $medicalrecord->id_pemeriksaan_poli, $medicalrecord->id_peserta ); 
                $anc->status_tt = $input['status_tt'] ? $input['status_tt'] : '';  
                $anc->berat_badan = $input['berat_badan'] ? $input['berat_badan'] : '';  
                $anc->td_atas = $input['td_atas'] ? $input['td_atas'] : '';  
                $anc->td_bawah = $input['td_bawah'] ? $input['td_bawah'] : '';  
                $anc->nilai_gizi = $input['nilai_gizi'] ? $input['nilai_gizi'] : '';  
                $anc->denyut_janin = $input['denyut_janin'] ? $input['denyut_janin'] : '';  
                $anc->presentasi = ( isset( $input['presentasi'] ) && $input['presentasi'] ) ? $input['presentasi'] : '';  
                $anc->injeksi_tt = $input['injeksi_tt'] ? $input['injeksi_tt'] : '';  
                $anc->tablet_fe = ( isset( $input['tablet_fe'] ) && $input['tablet_fe'] ) ? $input['tablet_fe'] : '';  
                $anc->pemeriksaan_hb = ( isset( $input['pemeriksaan_hb_hasil'] ) && $input['pemeriksaan_hb_hasil'] ) ? $input['pemeriksaan_hb_hasil'] : ''; 
                $anc->pemeriksaan_urin = ( isset( $input['pemeriksaan_urin_hasil'] ) && $input['pemeriksaan_urin_hasil'] ) ? $input['pemeriksaan_urin_hasil'] : ''; 
                $anc->djj_plus = ( isset( $input['djj_plus'] ) && $input['djj_plus'] ) ? $input['djj_plus'] : ''; 
                $anc->tm = ( isset( $input['tm'] ) && $input['tm'] ) ? $input['tm'] : '';
                $anc->tfu = ( isset( $input['tfu'] ) && $input['tfu'] ) ? $input['tfu'] : ''; 
                $anc->kesimpulan = ( isset( $input['kesimpulan'] ) && $input['kesimpulan'] ) ? $input['kesimpulan'] : ''; 
                $anc->keterangan_kehamilan = ( isset( $input['keterangan_kehamilan'] ) && $input['keterangan_kehamilan'] ) ? $input['keterangan_kehamilan'] : NULL; 
                $anc->user_update = $idpengguna;
                $anc->save();
            }

            // Update pendaftaran poli
            $poliregistration->tgl_selesai = date( 'Y-m-d H:i:s' );
            $poliregistration->status = 0;
            $poliregistration->save();

        } catch (\Exception $e) {           
            die_dump( $e->getMessage() );
        }

        //return redirect()->route( 'medical-record.show', [ 'id' => $id ] ); 
        return redirect( 'medical-record/?submitted=true&success=1' );
    }

    public function print_medrec( $id ){
        die_dump( $id );
    }
}
