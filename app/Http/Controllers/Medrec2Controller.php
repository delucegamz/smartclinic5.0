<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Medrec2;
use App\ViewMedrec;
use App\Participant;
use App\Diagnosis;
use App\Http\Requests;
use App\Poli;
use Carbon\Carbon;
use Cache;
use DB;
use Excel;

class Medrec2Controller extends Controller
{
    public function index(Request $request)
    {
		$start_date = $request->input('start_date');
		$end_date = $request->input('end_date');
		$nik_peserta = $request->input('nik_peserta');
		$filter_by = $request->input('filter_by');
		$nama_poli = $request->input('nama_poli');
		$kode_diagnosa = $request->input('kode_diagnosa');
		$per_page = $request->input('per_page', 10);

    	$halaman = 'medrec2';

		$diagnoses = Cache::remember('diagnoses', Carbon::now()->addHour(), function () {
			return Diagnosis::orderBy('nama_diagnosa')->get(['kode_diagnosa', 'nama_diagnosa']);
		});

        $medrec_list = ViewMedrec::
			when($nik_peserta, function ($query) use ($nik_peserta) {
				return $query->where('nik_peserta', 'LIKE', '%'.$nik_peserta.'%');
			})
			->when($filter_by == 'poli' && $nama_poli, function ($query) use ($nama_poli) {
				return $query->where('nama_poli', $nama_poli);
			})
			->when($filter_by == 'diagnosa' && $kode_diagnosa, function ($query) use ($kode_diagnosa) {
				return $query->where('iddiagnosa', $kode_diagnosa);
			})
			->when($start_date, function ($query) use ($start_date) {
				$start_date = Carbon::createFromFormat('Y-m-d', $start_date)->toDateString();
				return $query->whereDate('created_at', '>=', $start_date);
			})
			->when($end_date, function ($query) use ($end_date) {
				$end_date = Carbon::createFromFormat('Y-m-d', $end_date)->toDateString();
				return $query->whereDate('created_at', '<=', $end_date);
			})
			->paginate($per_page);

    	return view('reports/medrec2', [
			'halaman'       => $halaman,
			'medrec_list'   => $medrec_list,
			'start_date'    => $start_date,
			'filter_by'     => $filter_by,
			'nama_poli'     => $nama_poli,
			'kode_diagnosa' => $kode_diagnosa,
			'end_date'      => $end_date,
			'nik_peserta'   => $nik_peserta,
			'polies'        => Poli::all(),
			'diagnoses'     => $diagnoses,
			'per_page'      => $per_page,
		]);
	}

	public function export(Request $request)
	{
		$start_date = $request->input('start_date');
		$end_date = $request->input('end_date');
		$nik_peserta = $request->input('nik_peserta');
		$filter_by = $request->input('filter_by');
		$nama_poli = $request->input('nama_poli');
		$kode_diagnosa = $request->input('kode_diagnosa');

		$medrec_list = ViewMedrec::
			when($nik_peserta, function ($query) use ($nik_peserta) {
				return $query->where('nik_peserta', 'LIKE', '%'.$nik_peserta.'%');
			})
			->when($filter_by == 'poli' && $nama_poli, function ($query) use ($nama_poli) {
				return $query->where('nama_poli', $nama_poli);
			})
			->when($filter_by == 'diagnosa' && $kode_diagnosa, function ($query) use ($kode_diagnosa) {
				return $query->where('iddiagnosa', $kode_diagnosa);
			})
			->when($start_date, function ($query) use ($start_date) {
				$start_date = Carbon::createFromFormat('Y-m-d', $start_date)->toDateString();
				return $query->whereDate('created_at', '>=', $start_date);
			})
			->when($end_date, function ($query) use ($end_date) {
				$end_date = Carbon::createFromFormat('Y-m-d', $end_date)->toDateString();
				return $query->whereDate('created_at', '<=', $end_date);
			})->get();

		return Excel::create('rekam_medis', function ($excel) use ($medrec_list) {
			$excel->sheet('Sheet', function($sheet) use ($medrec_list) {
				$sheet->loadView('excel.medrec', [
					'medrec_list' => $medrec_list,
				]);
			});
		})->download('xlsx');
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
