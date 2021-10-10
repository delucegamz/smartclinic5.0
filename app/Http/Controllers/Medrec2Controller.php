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

    	/*$medrec_list = ViewMedrec::all();*/
    	/*$medrec_list = DB::table('t_pemeriksaan_poli')
        				->join('m_peserta', 't_pemeriksaan_poli.id_peserta', '=', 'm_peserta.id_peserta')
            			->join('m_diagnosa', 't_pemeriksaan_poli.iddiagnosa', '=', 'm_diagnosa.kode_diagnosa')
            			->select('t_pemeriksaan_poli.*', 'm_peserta.nik_peserta', 'm_diagnosa.nama_diagnosa')
            			->get();*/

        $medrec_list = DB::table('t_pemeriksaan_poli')

             			->join('m_peserta', function ($join) {
             				$join -> on ('t_pemeriksaan_poli.id_peserta', '=', 'm_peserta.id_peserta');
             			})
             			->join('m_diagnosa', function ($join) {
             				$join -> on ('t_pemeriksaan_poli.iddiagnosa', '=', 'm_diagnosa.kode_diagnosa');
             			})

             			->limit(300) -> get();

    	$jumlah_pasien = count($medrec_list);

    	return view('reports/medrec2', compact('halaman', 'medrec_list','jumlah_pasien'));
	}
}
