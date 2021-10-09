<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medrec2;
use App\Http\Requests;
use DB;


class Medrec2Controller extends Controller
{
    public function index()
    {
    	
    	$halaman = 'medrec2';
    	// $medrec_list = Medrec2::all();

    	$medrec_list = DB::table('t_pemeriksaan_poli')
            ->join('m_peserta', function ($join) {
            	$join->on('t_pemeriksaan_poli.id_peserta', '=', 'm_peserta.id_peserta');
            })
            ->join('m_diagnosa', function ($join) {
            	$join->on('t_pemeriksaan_poli.iddiagnosa', '=', 'm_diagnosa.kode_diagnosa');
            })
            ->get();
    	return view('reports/medrec2', compact('medrec_list'));
	}
}
