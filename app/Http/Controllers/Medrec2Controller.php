<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medrec2;
use App\ViewMedrec;
use App\Participant;
use App\Diagnosis;
use App\Http\Requests;
use DB;


class Medrec2Controller extends Controller
{
    public function index()
    {
    	
    	$halaman = 'medrec2';
    	// $medrec_list = Medrec2::all();

    	$medrec_list = ViewMedrec::all();

    	$jumlah_pasien = $medrec_list-> count();

    	return view('reports/medrec2', compact('halaman', 'medrec_list','jumlah_pasien'));
	}
}
