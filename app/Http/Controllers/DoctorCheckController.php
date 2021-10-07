<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\PoliRegistration;
use App\Participant;
use App\Poli;
use DB;
use Response;
use Auth;


class DoctorCheckController extends Controller
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

        $s = ( isset( $_GET['s'] ) && $_GET['s'] != '' ) ? filter_var( $_GET['s'], FILTER_SANITIZE_STRING ) : '';

        if( $rows == 'all' ){
            if( empty( $s ) )
    	        $datas = PoliRegistration::all();
            else{
            	$participants = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

            	$ids = array();
            	foreach ( $participants as $p ) {
            		$ids[] = $p->id_peserta;
            	}

                $datas = PoliRegistration::whereIn( 'id_peserta',  $ids )->get();
            }

        }else{
            if( empty( $s ) )
                $datas = PoliRegistration::paginate( $rows );
            else{
            	$participants = Participant::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

            	$ids = array();
            	foreach ( $participants as $p ) {
            		$ids[] = $p->id_peserta;
            	}  

                $datas = PoliRegistration::whereIn( 'id_peserta',  $ids )->paginate( $rows );
            }
           
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $poli = Poli::all();

    	return view( 'doctorcheck', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'poli' => $poli ]);
    }
}
