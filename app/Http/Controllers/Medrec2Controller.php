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

             			->limit(10) -> get();

    	$jumlah_pasien = count($medrec_list);

    	return view('reports/medrec2', compact('halaman', 'medrec_list','jumlah_pasien'));
	}

	public function cari(Request $request)
	{
		$kata_kunci = $request->input('kata_kunci');
		$query	= ViewMedrec::where('nik_peserta','LIKE','%'.$kata_kunci .'%');
		$medrec_list = $query->get();
		
		$jumlah_pasien = $medrec_list->count();
		return view('reports/medrec2', compact('medrec_list','kata_kunci','pagination','jumlah_pasien'));
	}

	public function caridate(Request $request)
	{
		$fromdate = $request->input('fromdate');
		$todate = $request->input('todate');

		$query	= DB::table('v_medrec')
				->where('created_at','>=',$fromdate)
				->where('created_at','<=',$todate)
				->get();

		$medrec_list = $query->get();

		
		
		
		return view('reports/medrec2', compact('data','fromdate','todade'));
	}


}
