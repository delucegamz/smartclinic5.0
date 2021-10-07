<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\LegalityDetail;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $legality_check = (int) get_setting( 'legality_check' );

        $timenow = time();

        if( $timenow > $legality_check ){
            $results = DB::table( 't_legalitas' )->get();

            $now = date( 'Y-m-d' );
            $next3m = date( 'Y-m-d', strtotime( '+3 month', time() ) );
            $next6m = date( 'Y-m-d', strtotime( '+6 month', time() ) );

            foreach( $results as $res ){
                $legalitydetail = LegalityDetail::find( $res->id_t_legalitas );

                if( $res->exp_legalitas >= $now && $res->exp_legalitas <= $next3m ){
                    $legalitydetail->status = 3;
                }elseif( $res->exp_legalitas >= $now && $res->exp_legalitas <= $next6m ){
                    $legalitydetail->status = 2;
                }elseif( $res->exp_legalitas > $next6m ){
                    $legalitydetail->status = 1;
                }elseif( $res->exp_legalitas < $now ){
                    $legalitydetail->status = 4;
                }

                $legalitydetail->save();
            }

            $legality_check = $timenow + ( 3600 * 24 );
            update_setting( 'legality_check', $legality_check );
        }

        return view('home');
    }
}
