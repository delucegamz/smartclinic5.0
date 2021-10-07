<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Response;
use Auth;
use Excel;
use App\Medicine;
use App\MedicineIn;
use App\MedicineInDetail;
use App\MedicineOut;
use App\MedicineOutDetail;
use App\DoctorRecipe;
use App\DoctorRecipeDetail;
use App\MedicalRecord;
use App\PoliRegistration;
use App\Participant;
use App\AmbulanceIn;
use App\AmbulanceOut;
use App\ReferenceLetter;
use App\SickLetter;
use App\DayOffLetter;
use App\Department;
use App\Factory;
use App\Client;
use App\Staff;
use App\User;
use App\Observation;
use App\ObservationDetail;
use App\Poli;
use App\Anc;
use App\PregnantParticipant;


class ExportController extends Controller{

	public function medicinein(){
		Excel::create( 'Data Obat Masuk ' . date( 'd-m-Y' ), function( $excel ) {

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) {
		    	$datas = MedicineIn::orderBy( 'id_pembelian_obat', 'desc' )->get();

		    	$items = array(); $i = 1; $old_count = 1; $k = 1;
				foreach ( $datas as $data ) {	
					$details = MedicineInDetail::where( 'id_obat_masuk', '=', $data->id_pembelian_obat )->get();

					$count = count( $details );

					$j = 1;
					foreach( $details as $item ){
	                    $medicine = Medicine::find( $item->id_obat );

	                    if( $j == 1 ){
	                    	$items[] = array(
	                    		'No' => $i,
								'No Faktur' => $data->no_pembelian_obat,
								'Supplier' => ( $data->idsupplier ? $data->idsupplier : '-' ),
								'Tanggal Faktur' => ( $data->tanggal_obat_masuk ? $data->tanggal_obat_masuk : '-' ),
								'Tanggal Proses' => ( $data->tanggal_proses ? $data->tanggal_proses : '-' ),
								'Jumlah Pembelian' => ( $data->jumlah_pembelian ? $data->jumlah_pembelian : 0 ),
								'Total Harga' => ( $data->total_harga ? $data->total_harga : 0 ),
								'Kode Obat' => $medicine->kode_obat,
								'Nama Obat' => $medicine->nama_obat,
								'Jumlah Obat' => $item->jumlah_obat,
								'Harga Satuan' => $medicine->satuan
	                    	);

	                    	$old = $old_count + 1;
	                    	$old_count = $old + $count - 1;

	                    	$sheet->mergeCells( 'A' . $old . ':A' . $old_count );
	                    	$sheet->mergeCells( 'B' . $old . ':B' . $old_count );
	                    	$sheet->mergeCells( 'C' . $old . ':C' . $old_count );
	                    	$sheet->mergeCells( 'D' . $old . ':D' . $old_count );
	                    	$sheet->mergeCells( 'E' . $old . ':E' . $old_count );
	                    	$sheet->mergeCells( 'F' . $old . ':F' . $old_count );
	                    	$sheet->mergeCells( 'G' . $old . ':G' . $old_count );
	                    }else{
	                    	$items[] = array(
	                    		'No' => '',
								'No Faktur' => '',
								'Supplier' => '',
								'Tanggal Faktur' => '',
								'Tanggal Proses' => '',
								'Jumlah Pembelian' => '',
								'Total Harga' => '',
								'Kode Obat' => $medicine->kode_obat,
								'Nama Obat' => $medicine->nama_obat,
								'Jumlah Obat' => $item->jumlah_obat,
								'Harga Satuan' => $medicine->satuan
	                    	);
	                    }

	                    $j++; $k++;
	                }

	               	$i++;
				}

				$sheet->cells( 'A1:K' . $k, function( $cells ) {
					 $cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:K' . $k, 'thin' );
				$sheet->fromArray( $items );
		    });

		})->export('xls');

		// $datas = MedicineIn::orderBy( 'id_pembelian_obat', 'desc' )->get();
		// return view( 'exports.medicinein', [ 'datas' => $datas ] );
	}

	public function medicineout(){
		Excel::create( 'Data Obat Keluar ' . date( 'd-m-Y' ), function( $excel ) {

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) {
		    	$datas = MedicineOut::orderBy( 'id_pengeluaran_obat', 'desc' )->get();

		    	$items = array(); $i = 1; $old_count = 1; $k = 1;
				foreach ( $datas as $data ) {	
					$details = MedicineOutDetail::where( 'id_pengeluaran_obat', '=', $data->id_pengeluaran_obat )->get();

					$count = count( $details );

					$j = 1;
					foreach( $details as $item ){
	                    $medicine = Medicine::find( $item->id_obat );

	                    if( $j == 1 ){
	                    	$items[] = array(
	                    		'No' => $i,
								'ID Obat Keluar' => $data->no_pengeluaran_obat,
								'ID Resep' => ( $data->id_resep ? get_recipe_no( $data->id_resep ) : '-' ),
								'Tanggal' => ( $data->tanggal_pengeluaran_obat ? $data->tanggal_pengeluaran_obat : '-' ),
								'Jumlah' => ( $data->jumlah_pengeluaran_obat ? $data->jumlah_pengeluaran_obat : 0 ),
								'Catatan' => ( $data->catatan ? $data->catatan : 0 ),
								'Kode Obat' => $medicine->kode_obat,
								'Nama Obat' => $medicine->nama_obat,
								'Jumlah Obat' => $item->jumlah_obat
	                    	);

	                    	$old = $old_count + 1;
	                    	$old_count = $old + $count - 1;

	                    	$sheet->mergeCells( 'A' . $old . ':A' . $old_count );
	                    	$sheet->mergeCells( 'B' . $old . ':B' . $old_count );
	                    	$sheet->mergeCells( 'C' . $old . ':C' . $old_count );
	                    	$sheet->mergeCells( 'D' . $old . ':D' . $old_count );
	                    	$sheet->mergeCells( 'E' . $old . ':E' . $old_count );
	                    	$sheet->mergeCells( 'F' . $old . ':F' . $old_count );
	                    }else{
	                    	$items[] = array(
	                    		'No' => '',
								'ID Obat Keluar' => '',
								'ID Resep' => '',
								'Tanggal' => '',
								'Jumlah' => '',
								'Catatan' => '',
								'Kode Obat' => $medicine->kode_obat,
								'Nama Obat' => $medicine->nama_obat,
								'Jumlah Obat' => $item->jumlah_obat
	                    	);
	                    }

	                    $j++; $k++;
	                }

	               	$i++;
				}

				$sheet->cells( 'A1:I' . $k, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:I' . $k, 'thin' );
				$sheet->fromArray( $items );
		    });

		})->export('xls');

		// $datas = MedicineIn::orderBy( 'id_pembelian_obat', 'desc' )->get();
		// return view( 'exports.medicinein', [ 'datas' => $datas ] );
	}

	public function doctorrecipe(){
		Excel::create( 'Data Resep Dokter ' . date( 'd-m-Y' ), function( $excel ) {

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) {
		    	$datas = DoctorRecipe::orderBy( 'id_resep', 'desc' )->get();

		    	$items = array(); $i = 1; $old_count = 1; $k = 1;
				foreach ( $datas as $data ) {	
					$details = DoctorRecipeDetail::where( 'id_resep', '=', $data->id_resep )->get();

					$count = count( $details );

					$j = 1;
					foreach( $details as $item ){
	                    $medicine = Medicine::find( $item->id_obat );
	                    $medrec = MedicalRecord::find( $data->id_pemeriksaan_poli );
						$poliregistration = PoliRegistration::find( $medrec->id_pendaftaran_poli );
						$participant = Participant::find( $medrec->id_peserta );

	                    if( $j == 1 ){
	                    	$items[] = array(
	                    		'No' => $i,
								'ID Resep' => $data->no_resep,
								'Tanggal Berobat' => $poliregistration->tgl_daftar,
								'No. Medrec' => $participant->no_medrec,
								'NIK Pasien' => $participant->nik_peserta,
								'Nama Pasien' => $participant->nama_peserta,
								'Diagnosa' => $medrec->diagnosa_dokter,
								'Dokter' => $medrec->dokter_rawat,
								'Kode Obat' => $medicine->kode_obat,
								'Nama Obat' => $medicine->nama_obat,
								'Jumlah Obat' => $item->jumlah_obat
	                    	);

	                    	$old = $old_count + 1;
	                    	$old_count = $old + $count - 1;

	                    	$sheet->mergeCells( 'A' . $old . ':A' . $old_count );
	                    	$sheet->mergeCells( 'B' . $old . ':B' . $old_count );
	                    	$sheet->mergeCells( 'C' . $old . ':C' . $old_count );
	                    	$sheet->mergeCells( 'D' . $old . ':D' . $old_count );
	                    	$sheet->mergeCells( 'E' . $old . ':E' . $old_count );
	                    	$sheet->mergeCells( 'F' . $old . ':F' . $old_count );
	                    	$sheet->mergeCells( 'G' . $old . ':G' . $old_count );
	                    	$sheet->mergeCells( 'H' . $old . ':H' . $old_count );
	                    }else{
	                    	$items[] = array(
	                    		'No' => '',
								'ID Resep' => '',
								'Tanggal Berobat' => '',
								'No. Medrec' => '',
								'NIK Pasien' => '',
								'Nama Pasien' => '',
								'Diagnosa' => '',
								'Dokter' => '',
								'Kode Obat' => $medicine->kode_obat,
								'Nama Obat' => $medicine->nama_obat,
								'Jumlah Obat' => $item->jumlah_obat
	                    	);
	                    }

	                    $j++; $k++;
	                }

	               	$i++;
				}

				$sheet->cells( 'A1:K' . $k, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:K' . $k, 'thin' );
				$sheet->fromArray( $items );
		    });

		})->export('xls');

		// $datas = MedicineIn::orderBy( 'id_pembelian_obat', 'desc' )->get();
		// return view( 'exports.medicinein', [ 'datas' => $datas ] );
	}

	public function medicinestock(){
		if( !current_user_can( 'laporan_stock_obat' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

		$medicinegroup = isset( $_GET['medicinegroup'] ) ? absint( $_GET['medicinegroup'] ) : '';

        if( $medicinegroup )
			$datas = Medicine::where( 'id_golongan_obat', '=', $medicinegroup )->get();
		else
			$datas = Medicine::all();

        $items = array(); $i = 1;
        foreach( $datas as $medicine ){
        	$items[] = array(
        		'No' => $i,
        		'Kode Obat' => $medicine->kode_obat,
        		'Nama Obat' => $medicine->nama_obat,
        		'Golongan Obat' => get_medicine_group_name( $medicine->id_golongan_obat ),
        		'Stock Obat' => $medicine->stock_obat ? $medicine->stock_obat : 0
        	);

        	$i++;
        }

        Excel::create( 'Data Stock Obat ' . date( 'd-m-Y' ), function( $excel ) use ( $items, $i ){

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) use ( $items, $i ){
	    		$sheet->cells( 'A1:E' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:E' . $i, 'thin' );
		    	$sheet->fromArray( $items );
		    });

		})->export('xls');
	}

	public function ambulance(){
		if( !current_user_can( 'laporan_ambulance' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

		$date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        $view = ( isset( $_GET['view'] ) && $_GET['view'] != '' ) ? filter_var( $_GET['view'], FILTER_SANITIZE_STRING ) : 'out';
		
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
        }else{
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
        }

		$ambulances = array(); $i = 1;
        if( $view == 'out' ){
            foreach( $datas as $data ){
                $ambulances[] = array(
                	'No' => $i,
                    'No Ambulance Out' => $data->no_ambulance_out,
                    'No Ambulance In' => '-',
                    'Peserta' => get_participant_name( $data->id_peserta ) . ' / ' . get_participant_nik( $data->id_peserta ),
                    'Tanggal' => date( 'd-m-Y', strtotime( $data->tanggal_keluar ) ),
                    'Jam Keluar' => date( 'H:i:s', strtotime( $data->tanggal_keluar ) ),
                    'Jam Pulang' => '-',
                    'Lokasi Jemput' => $data->lokasi_jemput,
                    'Lokasi Kirim' => $data->lokasi_kirim,
                    'KM Out' => $data->km_out,
                    'KM In' => '-',
                    'Driver' => '-',
                    'Catatan' => '-'
                );

                $i++;
            }
        }else{
            foreach( $datas as $data ){
                $out = AmbulanceOut::where( 'id_ambulance_out', '=', $data->id_ambulance_out )->first();

                $ambulances[] = array(
                	'No' => $i,
                    'No Ambulance Out' => $out->no_ambulance_out,
                    'No Ambulance In' => $data->no_ambulance_in,
                    'Peserta' => get_participant_name( $out->id_peserta ) . ' / ' . get_participant_nik( $out->id_peserta ),
                    'Tanggal' => date( 'd-m-Y', strtotime( $out->tanggal_keluar ) ),
                    'Jam Keluar' => date( 'H:i:s', strtotime( $out->tanggal_keluar ) ),
                    'Jam Pulang' => date( 'H:i:s', strtotime( $out->tanggal_masuk ) ),
                    'Lokasi Jemput' => $out->lokasi_jemput,
                    'Lokasi Kirim' => $out->lokasi_kirim,
                    'KM Out' => $out->km_out,
                    'KM In' => $data->km_in,
                    'Driver' => $data->driver,
                    'Catatan' => $data->catatan
                );

                $i++;
            }
        }

        Excel::create( 'Data Ambulance ' . ucwords( $view ) . ' ' . date( 'd-m-Y' ), function( $excel ) use ( $ambulances, $i ){

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) use ( $ambulances, $i ){
				$sheet->cells( 'A1:L' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:L' . $i, 'thin' );
				$sheet->fromArray( $ambulances );
		    });

		})->export('xls');
	}

	public function doctorcheck(){
		if( !current_user_can( 'laporan_pemeriksaan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

		$date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        $datas = NULL;

        $ids = array();

        $medrecs = MedicalRecord::all();
        foreach( $medrecs as $medrec ){
            $ids[] = $medrec->id_pendaftaran_poli;
        }

		if( $date_from && $date_to ){
            $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                 ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                 ->whereIn( 'id_pendaftaran', $ids )
                                 ->orderBy( 'tgl_daftar', 'asc' )
                                 ->get();
        }elseif( $date_from ){
            $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                 ->whereIn( 'id_pendaftaran', $ids )
                                 ->orderBy( 'tgl_daftar', 'asc' )
                                 ->get();
        }elseif( $date_to ){
            $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                 ->whereIn( 'id_pendaftaran', $ids )
                                 ->orderBy( 'tgl_daftar', 'asc' )
                                 ->get();
        }else{
            $datas = PoliRegistration::whereIn( 'id_pendaftaran', $ids )->orderBy( 'tgl_daftar', 'asc' )->get();
        }

        $items = array(); $i = 1;
        foreach( $datas as $poliregistration ){
        	$medrec = MedicalRecord::where( 'id_pendaftaran_poli', '=', $poliregistration->id_pendaftaran )->first();

        	$items[] = array(
        		'No' => $i,
        		'No Pendaftaran' => $poliregistration->no_daftar,
        		'Waktu Pendaftaran' => date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ),
        		'Waktu Pemeriksaan' => date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_selesai ) ),
        		'NIK Peserta' => get_participant_nik( $poliregistration->id_peserta ),
        		'Nama Pasien' => get_participant_name( $poliregistration->id_peserta ),
        		'Umur' => get_participant_age( $poliregistration->id_peserta ),
        		'Jenis Kelamin' => get_participant_sex( $poliregistration->id_peserta ),
        		'Unit Kerja' => get_participant_department( $poliregistration->id_peserta ),
        		'Pabrik' => get_participant_factory( $poliregistration->id_peserta ),
        		'Perusahaan' => get_participant_client( $poliregistration->id_peserta ),
        		'Poli' => get_poli_name( $poliregistration->id_poli ),
        		'Keluhan' => $medrec->keluhan,
        		'Diagnosa' => $medrec->diagnosa_dokter,
        		'Dokter Periksa' => $medrec->dokter_rawat,
        		'Catatan Pemeriksaan' => $medrec->catatan_pemeriksaan
        	);

			$i++;
        }

        Excel::create( 'Data Pemeriksaan ' . date( 'd-m-Y' ), function( $excel ) use ( $items, $i ){

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) use ( $items, $i ){
				$sheet->cells( 'A1:P' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:P' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });

		})->export('xls');
	}

	public function poliregistration(){
		if( !current_user_can( 'laporan_pendaftaran' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

		$date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        $datas = NULL;

		if( $date_from && $date_to ){
            $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                 ->where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                 ->orderBy( 'tgl_daftar', 'asc' )
                                 ->get();
        }elseif( $date_from ){
            $datas = PoliRegistration::where( 'tgl_daftar', '>=', $date_from . ' 00:00:00' )
                                 ->orderBy( 'tgl_daftar', 'asc' )
                                 ->get();
        }elseif( $date_to ){
            $datas = PoliRegistration::where( 'tgl_daftar', '<=', $date_to . ' 23:59:59' )
                                 ->orderBy( 'tgl_daftar', 'asc' )
                                 ->get();
        }else{
            $datas = PoliRegistration::orderBy( 'tgl_daftar', 'asc' )->get();
        }

        $items = array(); $i = 1;
        foreach( $datas as $poliregistration ){
        	$items[] = array(
        		'No' => $i,
        		'No Pendaftaran' => $poliregistration->no_daftar,
        		'Waktu Pendaftaran' => date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ),
        		'NIK Peserta' => get_participant_nik( $poliregistration->id_peserta ),
        		'Nama Pasien' => get_participant_name( $poliregistration->id_peserta ),
        		'Umur' => get_participant_age( $poliregistration->id_peserta ),
        		'Jenis Kelamin' => get_participant_sex( $poliregistration->id_peserta ),
        		'Unit Kerja' => get_participant_department( $poliregistration->id_peserta ),
        		'Pabrik' => get_participant_factory( $poliregistration->id_peserta ),
        		'Perusahaan' => get_participant_client( $poliregistration->id_peserta ),
        		'Poli' => get_poli_name( $poliregistration->id_poli ),
        		'Catatan Pendaftaran' => $poliregistration->catatan_pendaftaran
        	);

			$i++;
        }

        Excel::create( 'Data Pendaftaran ' . date( 'd-m-Y' ), function( $excel ) use ( $items, $i ){

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) use ( $items, $i ){
				$sheet->cells( 'A1:L' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:L' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });

		})->export('xls');
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

        Excel::create( 'Data Surat ' . $letter . ' ' . date( 'd-m-Y' ), function( $excel ) use ( $items, $i, $view ){

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) use ( $items, $i, $view ){
		    	if( $view == 'day-off' ){
		    		$sheet->cells( 'A1:N' . $i, function( $cells ) {
						$cells->setValignment( 'center' );
					});
					$sheet->setBorder( 'A1:N' . $i, 'thin' );
		    	}elseif( $view == 'reference' ){
		    		$sheet->cells( 'A1:Q' . $i, function( $cells ) {
						$cells->setValignment( 'center' );
					});
					$sheet->setBorder( 'A1:Q' . $i, 'thin' );
		    	}elseif( $view == 'sick' ){
		    		$sheet->cells( 'A1:M' . $i, function( $cells ) {
						$cells->setValignment( 'center' );
					});
					$sheet->setBorder( 'A1:M' . $i, 'thin' );
		    	}

				$sheet->fromArray( $items );
		    });

		})->export('xls');
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

        $all_poli = Poli::all(); $poli_items = array();

        foreach( $all_poli as $poli ){
        	$poli_items[$poli->id_poli] = array(
        		'name' => $poli->nama_poli,
        		'datas' => array()
        	);
        }

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

        	$item = array(
        		'NO' => $i,
        		'TGL' => date( 'd/m/Y', strtotime( $p->tgl_daftar ) ),
        		'REGISTRASI' => $o->no_pemeriksaan_poli,
        		'PABRIK' => get_participant_factory( $o->id_peserta ),
        		'DEPT' => get_participant_department( $o->id_peserta ),
        		'NIK' => get_participant_nik( $o->id_peserta ),
        		'NO MEDREC' => get_participant_medrec_no( $o->id_peserta ),
        		'NAMA' => get_participant_name( $o->id_peserta ),
        		'JK' => get_participant_sex( $o->id_peserta ) == 'Laki-laki' ? 'L' : 'P',
        		'ICD' => $o->iddiagnosa,
        		'DIAGNOSA' => get_diagnosis_name( $o->iddiagnosa ),
        		'DOKTER' => $o->dokter_rawat,
        		'POLI' => get_poli_name( $p->id_poli ),
        		'TL' => $tl,
        		'IN' => date( 'H:i:s', strtotime( $p->tgl_daftar ) ),
        		'OUT' => date( 'H:i:s', strtotime( $p->tgl_selesai ) ),
        		'BL' => get_visit( $o->id_peserta ) > 1 ? 'Lama' : 'Baru'
        	);

        	$count_p = count($poli_items[$p->id_poli]['datas']);
        	$count_p++;

        	$item_2 = array(
        		'NO' => $count_p,
        		'TGL' => date( 'd/m/Y', strtotime( $p->tgl_daftar ) ),
        		'REGISTRASI' => $o->no_pemeriksaan_poli,
        		'PABRIK' => get_participant_factory( $o->id_peserta ),
        		'DEPT' => get_participant_department( $o->id_peserta ),
        		'NIK' => get_participant_nik( $o->id_peserta ),
        		'NO MEDREC' => get_participant_medrec_no( $o->id_peserta ),
        		'NAMA' => get_participant_name( $o->id_peserta ),
        		'JK' => get_participant_sex( $o->id_peserta ) == 'Laki-laki' ? 'L' : 'P',
        		'ICD' => $o->iddiagnosa,
        		'DIAGNOSA' => get_diagnosis_name( $o->iddiagnosa ),
        		'DOKTER' => $o->dokter_rawat,
        		'POLI' => get_poli_name( $p->id_poli ),
        		'TL' => $tl,
        		'IN' => date( 'H:i:s', strtotime( $p->tgl_daftar ) ),
        		'OUT' => date( 'H:i:s', strtotime( $p->tgl_selesai ) ),
        		'BL' => get_visit( $o->id_peserta ) > 1 ? 'Lama' : 'Baru'
        	);

        	$items[] = $item;

        	$poli_items[$p->id_poli]['datas'][] = $item_2;

        	$i++;
        }

        //die_dump($poli_items);

        Excel::create( 'Data Rekam Medis ' . date( 'd-m-Y' ), function( $excel ) use ( $items, $i, $poli_items ){

		    $excel->sheet( 'ALL', function( $sheet ) use ( $items, $i ){
	    		$sheet->cells( 'A1:Q' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:Q' . $i, 'thin' );
		    	$sheet->fromArray( $items );
		    });

		    foreach( $poli_items as $key => $value ){
		    	$excel->sheet( $value['name'], function( $sheet ) use ( $value ){
		    		$e = count( $value['datas'] );
		    		$e += 1;
		    		$sheet->cells( 'A1:Q' . $e, function( $cells ) {
						$cells->setValignment( 'center' );
					});
					$sheet->setBorder( 'A1:Q' . $e, 'thin' );
			    	$sheet->fromArray( $value['datas'] );
			    });
		    }

		})->export('xls');
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

        $items = array(); $i = 1;
        foreach( $others as $o ){
        	$od = ObservationDetail::where( 'no_observasi', '=', $o->id_observasi )->first();

        	$items[] = array(
        		'No.' => $i,
				'Kode' => $o->no_observasi,
				'Nama' => get_participant_name( $o->id_peserta ),
				'Departemen' => get_participant_department( $o->id_peserta ),
				'Factory' => get_participant_factory( $o->id_peserta ),
				'Client' => get_participant_client( $o->id_peserta ),
				'Umur' => get_participant_age( $o->id_peserta ),
				'Tanggal Mulai' => date( 'd/m/Y H:i:s', strtotime( $o->tanggal_mulai ) ),
				'Tanggal Selesai' => date( 'd/m/Y H:i:s', strtotime( $o->tanggal_selesai ) ),
				'Diagnosa Akhir' => $o->diagnosa_akhir,
				'Kesimpulan' => $o->kesimpulan_observasi,
				'Keterangan dan Tindak Lanjut' => $o->hasil_observasi,
				'Keadaan Umum' => $od->keadaan_umum,
				'Eye Opening' => get_eye_opening( $od->k_mata ),
				'Respon Verbal' => get_verbal_response( $od->k_bicara ),
				'Respon Motorik' => get_motoric_response( $od->k_motorik ),
				'Tensi Darah' => $od->td_bawah . ' / ' . $od->td_atas,
				'Suhu' => $od->suhu,
				'Denyut Nadi' => $od->nadi,
				'Nafas' => $od->jalan_nafas,
				'Surat Sakit' => is_sick_letter( $o->id_pemeriksaan_poli ) ? 'Ya' : 'Tidak',
				'Surat Rujukan' => is_reference_letter( $o->id_pemeriksaan_poli ) ? 'Ya' : 'Tidak',
				'Surat Cuti' => is_dayoff_letter( $o->id_pemeriksaan_poli ) ? 'Ya' : 'Tidak',
				'Resep Dokter' => is_doctor_recipe( $o->id_pemeriksaan_poli ) ? 'Ya' : 'Tidak',
				
        	);

        	$i++;
        }

        Excel::create( 'Data Observasi Pasien ' . date( 'd-m-Y' ), function( $excel ) use ( $items, $i ){

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) use ( $items, $i ){
	    		$sheet->cells( 'A1:X' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:X' . $i, 'thin' );
		    	$sheet->fromArray( $items );
		    });

		})->export('xls');
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

        $items = array(); $i = 1;
        foreach( $others as $o ){
        	$pregnant = PregnantParticipant::find( $o->id_peserta_hamil ); 
        	$medrec = MedicalRecord::find( $o->id_pemeriksaan_poli );
            $diagnosa = '';
            $diagnosa .= 'G' . ( $pregnant->gravida ? $pregnant->gravida : 0 );
            $diagnosa .= 'P' . ( $pregnant->partus ? $pregnant->partus : 0 );
            $diagnosa .= 'A' . ( $pregnant->abortus ? $pregnant->abortus : 0 );

            // $tptime = strtotime( $pregnant->tp . ' 00:00:00'  ); $weeks = '';
            // if( $tptime > time() ){
            //     $weeks = datediffInWeeks( $pregnant->tanggal_hpht, date( 'Y-m-d' ) );
            // }else{
            //     $weeks = datediffInWeeks( $pregnant->tanggal_hpht, $pregnant->tp );
            // }

            $weeks = $o->keterangan_kehamilan;

            if( $weeks != '' ){
                $diagnosa .= ' ' . $weeks . ' MGG';
            }

            $tm = '';
            if( $weeks <= 12 ){
                $tm = 'I';
            }elseif( $weeks <= 13 && $weeks <= 24 ){
                $tm = 'II';
            }elseif( $weeks >= 25 ){
                $tm = 'III';
            }

            $medrec = MedicalRecord::find( $o->id_pemeriksaan_poli );

            $ket = '';
            if( is_dayoff_letter( $o->id_pemeriksaan_poli ) ) $ket .= 'Cuti;';
            if( !empty( $o->pemeriksaan_hb ) ) $ket .= 'Hb' . $o->pemeriksaan_hb . 'gr;';
            if( !empty( $o->pemeriksaan_urin ) ) $ket .= 'Urin' . $o->pemeriksaan_urin . '%;';

            $tfu = '';
            if( $o->tfu ) $tfu .= $o->tfu . ',';
            if( $o->presentasi ) $tfu .= $o->presentasi . ',';
            if( $o->djj_plus ) $tfu .= 'DJJ+';

            $therapy = ''; $jml = '';
            if( is_doctor_recipe( $o->id_pemeriksaan_poli ) ){
                $dr = DoctorRecipe::where( 'id_pemeriksaan_poli', $o->id_pemeriksaan_poli )->first();
                if( $dr ){
                    $mo = MedicineOut::where( 'id_resep', $dr->id_resep )->first();
                    if( $mo ){
                        $mods = MedicineOutDetail::where( 'id_pengeluaran_obat', $mo->id_pengeluaran_obat )->get();

                        if( $mods && count( $mods ) ){
                            $modi = 1;
                            foreach( $mods as $mod ){
                                if( $modi != count( $mods ) ){ 
                                    $therapy .= get_medicine_name( $mod->id_obat ) . ',';
                                    $jml .= $mod->jumlah_obat . ',';
                                }else{
                                    $therapy .= get_medicine_name( $mod->id_obat );
                                    $jml .= $mod->jumlah_obat;
                                } 

                                $modi++;
                            }
                        }      
                    }
                }
            }else{
                $therapy = 'Lanjut';
                $jml = '-';
            }

        	$items[] = array(
        		'No.' => $i,
				'Tgl' => date( 'd/m/Y', strtotime( $o->created_at ) ),
				'Nama' => get_participant_name( $o->id_peserta ),
				'Umur' => get_participant_age( $o->id_peserta ),
				'NIK' => get_participant_nik( $o->id_peserta ),
				'Dept' => get_participant_department( $o->id_peserta ),
				'HPHT' => date( 'd/m/Y', strtotime( $pregnant->tanggal_hpht ) ),
				'HTP' => date( 'd/m/Y', strtotime( $pregnant->tp ) ),
				'Alamat' => get_participant_address( $o->id_peserta ),
				'BB(Kg)' => $o->berat_badan,
				'TD' => $o->td_bawah . ' / ' . $o->td_atas,
				'TFU' => $tfu,
				'Diagnosa' => $diagnosa,
				'TM' => $tm,
				'Therapy' => $therapy,
				'JML' => $jml,
				'Ket' => $ket,
				'Bidan' => 'Bd. ' . $medrec->dokter_rawat,
        	);

        	$i++;
        }

        Excel::create( 'Data Pemeriksaan ANC ' . date( 'd-m-Y' ), function( $excel ) use ( $items, $i ){

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) use ( $items, $i ){
	    		$sheet->cells( 'A1:R' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:R' . $i, 'thin' );
		    	$sheet->fromArray( $items );
		    });

		})->export('xls');
	}

	public function visit(){
		if( !current_user_can( 'laporan_kunjungan' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

		$view = isset( $_GET['view'] ) ? $_GET['view']  : '';
        $participant = isset( $_GET['participant'] ) ? $_GET['participant']  : '';
        $department = isset( $_GET['department'] ) ? $_GET['department']  : '';
        $client = isset( $_GET['client'] ) ? $_GET['client']  : '';
        $factory = isset( $_GET['factory'] ) ? $_GET['factory']  : '';
        $date_from = isset( $_GET['date-form'] ) ? $_GET['date-form']  : '';
        $date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

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

        
        $items = array(); $i = 1;
        foreach( $datas as $poliregistration ){
        	$participant = get_participant( $poliregistration->id_peserta );
        	$items[] = array(
        		'No' => $i,
        		'No Pendaftaran' => $poliregistration->no_daftar,
        		'Waktu Pendaftaran' => date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ),
        		'NIK Peserta' => $participant->nik_peserta,
        		'Nama Pasien' => $participant->nama_peserta,
        		'Umur' => get_age_by_mysql_date( $participant->tanggal_lahir ),
        		'Jenis Kelamin' => ucwords( $participant->jenis_kelamin ),
        		'Unit Kerja' => get_participant_department_alt( $poliregistration->id_peserta, $participant ),
        		'Pabrik' => get_participant_factory( $poliregistration->id_peserta, $participant ),
        		'Perusahaan' => get_participant_client( $poliregistration->id_peserta, $participant ),
        		'Poli' => get_poli_name( $poliregistration->id_poli ),
        		'Catatan Pendaftaran' => $poliregistration->catatan_pendaftaran
        	);

			$i++;
        }

        Excel::create( 'Data Kunjungan ' . date( 'd-m-Y' ), function( $excel ) use ( $items, $i ){

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) use ( $items, $i ){
				$sheet->cells( 'A1:L' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:L' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });

		})->export('xls');
	}

	public function participant_sex(){
		$sexes = DB::table( 'm_peserta' )
	                ->select( DB::raw( 'DISTINCT( jenis_kelamin )' ) )
	                ->get();

	    Excel::create( 'Data Peserta Berdasarkan Jenis Kelamin ' . date( 'd-m-Y' ), function( $excel ) use( $sexes ) {
	    	foreach( $sexes as $s ){
			    $excel->sheet( ( $s->jenis_kelamin ? ucwords( $s->jenis_kelamin, '-' ) : 'Belum Ter-set' ), function( $sheet ) use ( $s ) {
			    	$items = array();

			    	$items[0] = array(
		    			'No' => '',
		    			'Kode Peserta' => '',
		    			'No Medrec' => '',
		    			'NIK Peserta' => '',
		    			'Nama Peserta' => '',
		    			'Unit Kerja' => '',
		    			'Pabrik' => '',
		    			'Perusahaan' => '',
		    			'Jenis Kelamin' => '',
		    			'Tempat Lahir' => '',
		    			'Tanggal Lahir' => '',
		    			'Alamat' => '',
		    			'Kota' => '',
		    			'Provinsi' => '',
		    			'Kode Pos' => '',
		    			'Tanggal Aktif' => '',
		    			'Tanggal Non Aktif' => '',
		    			'Status' => '',
		    			'Status Kawin' => '',
		    			'Jumlah Anak' => ''
		    		);

			    	$participants = Participant::where( 'jenis_kelamin', '=', $s->jenis_kelamin )->get(); $i = 1;
			    	foreach ( $participants as $p ) {
			    		$items[$i-1] = array(
			    			'No' => $i,
			    			'Kode Peserta' => $p->kode_peserta,
			    			'No Medrec' => $p->no_medrec,
			    			'NIK Peserta' => $p->nik_peserta,
			    			'Nama Peserta' => $p->nama_peserta,
			    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
			    			'Pabrik' => get_participant_factory( $p->id_peserta ),
			    			'Perusahaan' => get_participant_client( $p->id_peserta ),
			    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
			    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
			    			'Tanggal Lahir' => $p->tanggal_lahir,
			    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
			    			'Kota' => ucwords( strtolower( $p->kota ) ),
			    			'Provinsi' => get_province_name( $p->provinsi ),
			    			'Kode Pos' => $p->kodepos,
			    			'Tanggal Aktif' => $p->tanggal_aktif,
			    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
			    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
			    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
			    			'Jumlah Anak' => $p->jumlah_anak
			    		);

						$i++;
			    	}

					$sheet->cells( 'A1:T' . $i, function( $cells ) {
						$cells->setValignment( 'center' );
					});
					$sheet->setBorder( 'A1:T' . $i, 'thin' );
					$sheet->fromArray( $items );
			    });
			}

		})->export('xls');
	}

	public function participant_pregnant(){
		$results = DB::table( 'm_peserta_hamil' )
	                ->select( 'id_peserta' )
	                ->get();

	    $ids = array();
	    foreach( $results as $res ){
	    	$ids[] = $res->id_peserta;
	    }

	    Excel::create( 'Data Peserta Hamil ' . date( 'd-m-Y' ), function( $excel ) use( $ids ) {
	    	
		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) use ( $ids ) {
		    	$items = array();

		    	$items[0] = array(
	    			'No' => '',
	    			'Kode Peserta' => '',
	    			'No Medrec' => '',
	    			'NIK Peserta' => '',
	    			'Nama Peserta' => '',
	    			'Unit Kerja' => '',
	    			'Pabrik' => '',
	    			'Perusahaan' => '',
	    			'Jenis Kelamin' => '',
	    			'Tempat Lahir' => '',
	    			'Tanggal Lahir' => '',
	    			'Alamat' => '',
	    			'Kota' => '',
	    			'Provinsi' => '',
	    			'Kode Pos' => '',
	    			'Tanggal Aktif' => '',
	    			'Tanggal Non Aktif' => '',
	    			'Status' => '',
	    			'Status Kawin' => '',
	    			'Jumlah Anak' => ''
	    		);

		    	$participants = Participant::whereIn( 'id_peserta', $ids )->get(); $i = 1;

		    	foreach ( $participants as $p ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Peserta' => $p->kode_peserta,
		    			'No Medrec' => $p->no_medrec,
		    			'NIK Peserta' => $p->nik_peserta,
		    			'Nama Peserta' => $p->nama_peserta,
		    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
		    			'Pabrik' => get_participant_factory( $p->id_peserta ),
		    			'Perusahaan' => get_participant_client( $p->id_peserta ),
		    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
		    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
		    			'Tanggal Lahir' => $p->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
		    			'Kota' => ucwords( strtolower( $p->kota ) ),
		    			'Provinsi' => get_province_name( $p->propinsi ),
		    			'Kode Pos' => $p->kodepos,
		    			'Tanggal Aktif' => $p->tanggal_aktif,
		    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
		    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
		    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
		    			'Jumlah Anak' => $p->jumlah_anak
		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:T' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:T' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });
			
		})->export('xls');
	}

	public function participant_tb(){
		$results = DB::table( 'm_peserta_tb' )
	                ->select( 'id_peserta' )
	                ->get();

	    $ids = array();
	    foreach( $results as $res ){
	    	$ids[] = $res->id_peserta;
	    }

	    Excel::create( 'Data Peserta TB ' . date( 'd-m-Y' ), function( $excel ) use( $ids ) {
	    	
		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) use ( $ids ) {
		    	$items = array();

		    	$items[0] = array(
	    			'No' => '',
	    			'Kode Peserta' => '',
	    			'No Medrec' => '',
	    			'NIK Peserta' => '',
	    			'Nama Peserta' => '',
	    			'Unit Kerja' => '',
	    			'Pabrik' => '',
	    			'Perusahaan' => '',
	    			'Jenis Kelamin' => '',
	    			'Tempat Lahir' => '',
	    			'Tanggal Lahir' => '',
	    			'Alamat' => '',
	    			'Kota' => '',
	    			'Provinsi' => '',
	    			'Kode Pos' => '',
	    			'Tanggal Aktif' => '',
	    			'Tanggal Non Aktif' => '',
	    			'Status' => '',
	    			'Status Kawin' => '',
	    			'Jumlah Anak' => ''
	    		);

		    	$participants = Participant::whereIn( 'id_peserta', $ids )->get(); $i = 1;

		    	foreach ( $participants as $p ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Peserta' => $p->kode_peserta,
		    			'No Medrec' => $p->no_medrec,
		    			'NIK Peserta' => $p->nik_peserta,
		    			'Nama Peserta' => $p->nama_peserta,
		    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
		    			'Pabrik' => get_participant_factory( $p->id_peserta ),
		    			'Perusahaan' => get_participant_client( $p->id_peserta ),
		    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
		    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
		    			'Tanggal Lahir' => $p->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
		    			'Kota' => ucwords( strtolower( $p->kota ) ),
		    			'Provinsi' => get_province_name( $p->propinsi ),
		    			'Kode Pos' => $p->kodepos,
		    			'Tanggal Aktif' => $p->tanggal_aktif,
		    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
		    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
		    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
		    			'Jumlah Anak' => $p->jumlah_anak
		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:T' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:T' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });
			
		})->export('xls');
	}

	public function participant_factory(){
		$factories = Factory::all();

	    Excel::create( 'Data Peserta Berdasarkan Factory ' . date( 'd-m-Y' ), function( $excel ) use( $factories ) {
	    	$items = array();

	    	$items[0] = array(
    			'No' => '',
    			'Kode Peserta' => '',
    			'No Medrec' => '',
    			'NIK Peserta' => '',
    			'Nama Peserta' => '',
    			'Unit Kerja' => '',
    			'Pabrik' => '',
    			'Perusahaan' => '',
    			'Jenis Kelamin' => '',
    			'Tempat Lahir' => '',
    			'Tanggal Lahir' => '',
    			'Alamat' => '',
    			'Kota' => '',
    			'Provinsi' => '',
    			'Kode Pos' => '',
    			'Tanggal Aktif' => '',
    			'Tanggal Non Aktif' => '',
    			'Status' => '',
    			'Status Kawin' => '',
    			'Jumlah Anak' => ''
    		);

	    	foreach( $factories as $f ){
			    $excel->sheet( $f->nama_factory, function( $sheet ) use ( $f, $items ) {
			    
			    	$ids = array();
					$departments = Department::where( 'nama_factory', '=', $f->id_factory )->get();
		        	foreach( $departments as $d ){
		        		$ids[] = $d->id_departemen;
		        	}

			    	$participants = Participant::whereIn( 'id_departemen', $ids )->get(); $i = 1;
			    	foreach ( $participants as $p ) {
			    		$items[$i-1] = array(
			    			'No' => $i,
			    			'Kode Peserta' => $p->kode_peserta,
			    			'No Medrec' => $p->no_medrec,
			    			'NIK Peserta' => $p->nik_peserta,
			    			'Nama Peserta' => $p->nama_peserta,
			    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
			    			'Pabrik' => get_participant_factory( $p->id_peserta ),
			    			'Perusahaan' => get_participant_client( $p->id_peserta ),
			    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
			    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
			    			'Tanggal Lahir' => $p->tanggal_lahir,
			    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
			    			'Kota' => ucwords( strtolower( $p->kota ) ),
			    			'Provinsi' => get_province_name( $p->propinsi ),
			    			'Kode Pos' => $p->kodepos,
			    			'Tanggal Aktif' => $p->tanggal_aktif,
			    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
			    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
			    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
			    			'Jumlah Anak' => $p->jumlah_anak
			    		);

						$i++;
			    	}

					$sheet->cells( 'A1:T' . $i, function( $cells ) {
						$cells->setValignment( 'center' );
					});
					$sheet->setBorder( 'A1:T' . $i, 'thin' );
					$sheet->fromArray( $items );
			    });
			}

			$excel->sheet( 'Belum Ter-set', function( $sheet, $items ) {
		    	$participants = Participant::where( 'id_departemen', '=', '' )->orWhereNull( 'id_departemen' )->get(); $i = 1;
		    	foreach ( $participants as $p ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Peserta' => $p->kode_peserta,
		    			'No Medrec' => $p->no_medrec,
		    			'NIK Peserta' => $p->nik_peserta,
		    			'Nama Peserta' => $p->nama_peserta,
		    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
		    			'Pabrik' => get_participant_factory( $p->id_peserta ),
		    			'Perusahaan' => get_participant_client( $p->id_peserta ),
		    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
		    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
		    			'Tanggal Lahir' => $p->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
		    			'Kota' => ucwords( strtolower( $p->kota ) ),
		    			'Provinsi' => get_province_name( $p->propinsi ),
		    			'Kode Pos' => $p->kodepos,
		    			'Tanggal Aktif' => $p->tanggal_aktif,
		    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
		    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
		    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
		    			'Jumlah Anak' => $p->jumlah_anak
		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:T' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:T' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });
			
		})->export('xls');
	}

	public function participant_department(){
		$items = array();

    	$items[0] = array(
			'No' => '',
			'Kode Peserta' => '',
			'No Medrec' => '',
			'NIK Peserta' => '',
			'Nama Peserta' => '',
			'Unit Kerja' => '',
			'Pabrik' => '',
			'Perusahaan' => '',
			'Jenis Kelamin' => '',
			'Tempat Lahir' => '',
			'Tanggal Lahir' => '',
			'Alamat' => '',
			'Kota' => '',
			'Provinsi' => '',
			'Kode Pos' => '',
			'Tanggal Aktif' => '',
			'Tanggal Non Aktif' => '',
			'Status' => '',
			'Status Kawin' => '',
			'Jumlah Anak' => ''
		);

		$departments = Department::all();
        
	    Excel::create( 'Data Peserta Berdasarkan Departemen ' . date( 'd-m-Y' ), function( $excel ) use( $departments, $items ) {
	    	
	    	foreach( $departments as $d ){
			    $excel->sheet( str_replace( array( "\\", "/", "?" ), "", $d->nama_departemen ), function( $sheet ) use ( $d, $items ) {
			    	$participants = Participant::where( 'id_departemen', '=', $d->id_departemen )->get(); $i = 1;
			    	foreach ( $participants as $p ) {
			    		$items[$i-1] = array(
			    			'No' => $i,
			    			'Kode Peserta' => $p->kode_peserta,
			    			'No Medrec' => $p->no_medrec,
			    			'NIK Peserta' => $p->nik_peserta,
			    			'Nama Peserta' => $p->nama_peserta,
			    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
			    			'Pabrik' => get_participant_factory( $p->id_peserta ),
			    			'Perusahaan' => get_participant_client( $p->id_peserta ),
			    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
			    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
			    			'Tanggal Lahir' => $p->tanggal_lahir,
			    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
			    			'Kota' => ucwords( strtolower( $p->kota ) ),
			    			'Provinsi' => get_province_name( $p->propinsi ),
			    			'Kode Pos' => $p->kodepos,
			    			'Tanggal Aktif' => $p->tanggal_aktif,
			    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
			    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
			    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
			    			'Jumlah Anak' => $p->jumlah_anak
			    		);

						$i++;
			    	}

					$sheet->cells( 'A1:T' . $i, function( $cells ) {
						$cells->setValignment( 'center' );
					});
					$sheet->setBorder( 'A1:T' . $i, 'thin' );
					$sheet->fromArray( $items );
			    });
			}

			$excel->sheet( 'Belum Ter-set', function( $sheet ) use ( $items ){
		    	$participants = Participant::where( 'id_departemen', '=', '' )->orWhereNull( 'id_departemen' )->get(); $i = 1;
		    	foreach ( $participants as $p ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Peserta' => $p->kode_peserta,
		    			'No Medrec' => $p->no_medrec,
		    			'NIK Peserta' => $p->nik_peserta,
		    			'Nama Peserta' => $p->nama_peserta,
		    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
		    			'Pabrik' => get_participant_factory( $p->id_peserta ),
		    			'Perusahaan' => get_participant_client( $p->id_peserta ),
		    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
		    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
		    			'Tanggal Lahir' => $p->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
		    			'Kota' => ucwords( strtolower( $p->kota ) ),
		    			'Provinsi' => get_province_name( $p->propinsi ),
		    			'Kode Pos' => $p->kodepos,
		    			'Tanggal Aktif' => $p->tanggal_aktif,
		    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
		    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
		    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
		    			'Jumlah Anak' => $p->jumlah_anak
		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:T' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:T' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });
			
		})->export('xls');
	}

	public function participant_client(){
		$items = array();

    	$items[0] = array(
			'No' => '',
			'Kode Peserta' => '',
			'No Medrec' => '',
			'NIK Peserta' => '',
			'Nama Peserta' => '',
			'Unit Kerja' => '',
			'Pabrik' => '',
			'Perusahaan' => '',
			'Jenis Kelamin' => '',
			'Tempat Lahir' => '',
			'Tanggal Lahir' => '',
			'Alamat' => '',
			'Kota' => '',
			'Provinsi' => '',
			'Kode Pos' => '',
			'Tanggal Aktif' => '',
			'Tanggal Non Aktif' => '',
			'Status' => '',
			'Status Kawin' => '',
			'Jumlah Anak' => ''
		);

		$clients = Client::all();

	    Excel::create( 'Data Peserta Berdasarkan Client ' . date( 'd-m-Y' ), function( $excel ) use( $clients, $items ) {
	    	
	    	foreach( $clients as $c ){
			    $excel->sheet( $c->nama_client, function( $sheet ) use ( $c, $items ) {
			    	$ids = array();
					$departments = Department::where( 'nama_client', '=', $c->id_client )->get();
		        	foreach( $departments as $d ){
		        		$ids[] = $d->id_departemen;
		        	}

			    	$participants = Participant::whereIn( 'id_departemen', $ids )->get(); $i = 1;
			    	foreach ( $participants as $p ) {
			    		$items[$i-1] = array(
			    			'No' => $i,
			    			'Kode Peserta' => $p->kode_peserta,
			    			'No Medrec' => $p->no_medrec,
			    			'NIK Peserta' => $p->nik_peserta,
			    			'Nama Peserta' => $p->nama_peserta,
			    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
			    			'Pabrik' => get_participant_factory( $p->id_peserta ),
			    			'Perusahaan' => get_participant_client( $p->id_peserta ),
			    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
			    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
			    			'Tanggal Lahir' => $p->tanggal_lahir,
			    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
			    			'Kota' => ucwords( strtolower( $p->kota ) ),
			    			'Provinsi' => get_province_name( $p->propinsi ),
			    			'Kode Pos' => $p->kodepos,
			    			'Tanggal Aktif' => $p->tanggal_aktif,
			    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
			    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
			    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
			    			'Jumlah Anak' => $p->jumlah_anak
			    		);

						$i++;
			    	}

					$sheet->cells( 'A1:T' . $i, function( $cells ) {
						$cells->setValignment( 'center' );
					});
					$sheet->setBorder( 'A1:T' . $i, 'thin' );
					$sheet->fromArray( $items );
			    });
			}

			$excel->sheet( 'Belum Ter-set', function( $sheet ) use( $items ) {
		    	$participants = Participant::where( 'id_departemen', '=', '' )->orWhereNull( 'id_departemen' )->get(); $i = 1;
		    	foreach ( $participants as $p ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Peserta' => $p->kode_peserta,
		    			'No Medrec' => $p->no_medrec,
		    			'NIK Peserta' => $p->nik_peserta,
		    			'Nama Peserta' => $p->nama_peserta,
		    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
		    			'Pabrik' => get_participant_factory( $p->id_peserta ),
		    			'Perusahaan' => get_participant_client( $p->id_peserta ),
		    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
		    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
		    			'Tanggal Lahir' => $p->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
		    			'Kota' => ucwords( strtolower( $p->kota ) ),
		    			'Provinsi' => get_province_name( $p->propinsi ),
		    			'Kode Pos' => $p->kodepos,
		    			'Tanggal Aktif' => $p->tanggal_aktif,
		    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
		    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
		    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
		    			'Jumlah Anak' => $p->jumlah_anak
		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:T' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:T' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });
			
		})->export('xls');
	}

	public function participant_status(){
		$items = array();

    	$items[0] = array(
			'No' => '',
			'Kode Peserta' => '',
			'No Medrec' => '',
			'NIK Peserta' => '',
			'Nama Peserta' => '',
			'Unit Kerja' => '',
			'Pabrik' => '',
			'Perusahaan' => '',
			'Jenis Kelamin' => '',
			'Tempat Lahir' => '',
			'Tanggal Lahir' => '',
			'Alamat' => '',
			'Kota' => '',
			'Provinsi' => '',
			'Kode Pos' => '',
			'Tanggal Aktif' => '',
			'Tanggal Non Aktif' => '',
			'Status' => '',
			'Status Kawin' => '',
			'Jumlah Anak' => ''
		);

	    Excel::create( 'Data Peserta Berdasarkan Status Aktif ' . date( 'd-m-Y' ), function( $excel ) use( $items ) {
	    	
		    $excel->sheet( 'Aktif', function( $sheet ) use ( $items ) {
		    	$participants = Participant::where( 'status_aktif', '=', 1 )->get(); $i = 1;
		    	foreach ( $participants as $p ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Peserta' => $p->kode_peserta,
		    			'No Medrec' => $p->no_medrec,
		    			'NIK Peserta' => $p->nik_peserta,
		    			'Nama Peserta' => $p->nama_peserta,
		    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
		    			'Pabrik' => get_participant_factory( $p->id_peserta ),
		    			'Perusahaan' => get_participant_client( $p->id_peserta ),
		    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
		    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
		    			'Tanggal Lahir' => $p->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
		    			'Kota' => ucwords( strtolower( $p->kota ) ),
		    			'Provinsi' => get_province_name( $p->provinsi ),
		    			'Kode Pos' => $p->kodepos,
		    			'Tanggal Aktif' => $p->tanggal_aktif,
		    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
		    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
		    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
		    			'Jumlah Anak' => $p->jumlah_anak
		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:T' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:T' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });

			$excel->sheet( 'Tidak Aktif', function( $sheet ) use ( $items ) {
		    	$participants = Participant::where( 'status_aktif', '=', 0 )->get(); $i = 1;
		    	foreach ( $participants as $p ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Peserta' => $p->kode_peserta,
		    			'No Medrec' => $p->no_medrec,
		    			'NIK Peserta' => $p->nik_peserta,
		    			'Nama Peserta' => $p->nama_peserta,
		    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
		    			'Pabrik' => get_participant_factory( $p->id_peserta ),
		    			'Perusahaan' => get_participant_client( $p->id_peserta ),
		    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
		    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
		    			'Tanggal Lahir' => $p->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
		    			'Kota' => ucwords( strtolower( $p->kota ) ),
		    			'Provinsi' => get_province_name( $p->provinsi ),
		    			'Kode Pos' => $p->kodepos,
		    			'Tanggal Aktif' => $p->tanggal_aktif,
		    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
		    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
		    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
		    			'Jumlah Anak' => $p->jumlah_anak
		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:T' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:T' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });
			

		})->export('xls');
	}

	public function participant_data(){
		$items = array();

    	$items[0] = array(
			'No' => '',
			'Kode Peserta' => '',
			'No Medrec' => '',
			'NIK Peserta' => '',
			'Nama Peserta' => '',
			'Unit Kerja' => '',
			'Pabrik' => '',
			'Perusahaan' => '',
			'Jenis Kelamin' => '',
			'Tempat Lahir' => '',
			'Tanggal Lahir' => '',
			'Alamat' => '',
			'Kota' => '',
			'Provinsi' => '',
			'Kode Pos' => '',
			'Tanggal Aktif' => '',
			'Tanggal Non Aktif' => '',
			'Status' => '',
			'Status Kawin' => '',
			'Jumlah Anak' => ''
		);

		$filter = ( isset( $_GET['filter'] ) && $_GET['filter'] != '' ) ? $_GET['filter'] : 0;

	    Excel::create( 'Data Peserta Berdasarkan Kelengkapan Data ' . date( 'd-m-Y' ), function( $excel ) use( $items, $filter ) {
	    	
	    	if( $filter == 0 ){
			    $excel->sheet( 'Lengkap', function( $sheet ) use ( $items ) {
			    	$participants = Participant::where( 'id_departemen', '!=', '' )->get(); $i = 1;
			    	foreach ( $participants as $p ) {
			    		$items[$i-1] = array(
			    			'No' => $i,
			    			'Kode Peserta' => $p->kode_peserta,
			    			'No Medrec' => $p->no_medrec,
			    			'NIK Peserta' => $p->nik_peserta,
			    			'Nama Peserta' => $p->nama_peserta,
			    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
			    			'Pabrik' => get_participant_factory( $p->id_peserta ),
			    			'Perusahaan' => get_participant_client( $p->id_peserta ),
			    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
			    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
			    			'Tanggal Lahir' => $p->tanggal_lahir,
			    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
			    			'Kota' => ucwords( strtolower( $p->kota ) ),
			    			'Provinsi' => get_province_name( $p->provinsi ),
			    			'Kode Pos' => $p->kodepos,
			    			'Tanggal Aktif' => $p->tanggal_aktif,
			    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
			    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
			    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
			    			'Jumlah Anak' => $p->jumlah_anak
			    		);

						$i++;
			    	}

					$sheet->cells( 'A1:T' . $i, function( $cells ) {
						$cells->setValignment( 'center' );
					});
					$sheet->setBorder( 'A1:T' . $i, 'thin' );
					$sheet->fromArray( $items );
			    });
			}

			$excel->sheet( 'Tidak Lengkap', function( $sheet ) use ( $items ) {
		    	$participants = Participant::where( 'id_departemen', '=', NULL )->get(); $i = 1;
		    	foreach ( $participants as $p ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Peserta' => $p->kode_peserta,
		    			'No Medrec' => $p->no_medrec,
		    			'NIK Peserta' => $p->nik_peserta,
		    			'Nama Peserta' => $p->nama_peserta,
		    			'Unit Kerja' => get_participant_department( $p->id_peserta ),
		    			'Pabrik' => get_participant_factory( $p->id_peserta ),
		    			'Perusahaan' => get_participant_client( $p->id_peserta ),
		    			'Jenis Kelamin' => ucwords( $p->jenis_kelamin ),
		    			'Tempat Lahir' => ucwords( strtolower( $p->tempat_lahir ) ),
		    			'Tanggal Lahir' => $p->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $p->alamat ) ),
		    			'Kota' => ucwords( strtolower( $p->kota ) ),
		    			'Provinsi' => get_province_name( $p->provinsi ),
		    			'Kode Pos' => $p->kodepos,
		    			'Tanggal Aktif' => $p->tanggal_aktif,
		    			'Tanggal Non Aktif' => $p->tanggal_nonaktif,
		    			'Status' => $p->status_aktif ? 'Aktif' : 'Tidak Aktif',
		    			'Status Kawin' => $p->status_kawin ? $p->status_kawin : 'Belum Kawin',
		    			'Jumlah Anak' => $p->jumlah_anak
		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:T' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:T' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });
			

		})->export('xls');
	}

	public function staff_status(){
		Excel::create( 'Data Karyawan Berdasarkan Status ' . date( 'd-m-Y' ), function( $excel ) {
	    	
		    $excel->sheet( 'Aktif', function( $sheet ) {
		    	$items = array();

		    	$items[0] = array(
	    			'No' => '',
	    			'Kode Karyawan' => '',
	    			'NIK Karyawan' => '',
	    			'Nama Karyawan' => '',
	    			'Jabatan' => '',
	    			'Jenis Kelamin' => '',
	    			'Status Kawin' => '',
	    			'Jumlah Anak' => '',
	    			'Tinggi Badan' => '',
	    			'Berat Badan' => '',
	    			'Tempat Lahir' => '',
	    			'Tanggal Lahir' => '',
	    			'Alamat' => '',
	    			'Kota' => '',
	    			'Provinsi' => '',
	    			'Kode Pos' => '',
	    			'No Telp.' => '',
	    			'Email' => '',
	    			'Agama' => '',
	    			'Bank' => '',
	    			'No Rekening' => '',
	    			'Jenis ID' => '',
	    			'No ID' => '',
	    			'No KK' => '',
	    			'No BPJS' => '',
	    			'No Jamsostek' => '',
	    			'Status' => ''
		    	);

		    	$staffes = Staff::where( 'status', '=', 1 )->get(); $i = 1;
		    	foreach ( $staffes as $s ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Karyawan' => $s->kode_karyawan,
		    			'NIK Karyawan' => $s->nik_karyawan,
		    			'Nama Karyawan' => $s->nama_karyawan,
		    			'Jabatan' => get_job_title_name( $s->id_jabatan ),
		    			'Jenis Kelamin' => $s->jenis_kelamin,
		    			'Status Kawin' => $s->status_kawin,
		    			'Jumlah Anak' => $s->jumlah_anak,
		    			'Tinggi Badan' => $s->t_badan,
		    			'Berat Badan' => $s->b_badan,
		    			'Tempat Lahir' => $s->tempat_lahir,
		    			'Tanggal Lahir' => $s->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $s->alamat ) ),
		    			'Kota' => ucwords( strtolower( $s->kota ) ),
		    			'Provinsi' => get_province_name( $s->provinsi ),
		    			'Kode Pos' => $s->kode_pos,
		    			'No Telp.' => $s->no_telepon,
		    			'Email' => $s->email,
		    			'Agama' => $s->agama,
		    			'Bank' => $s->bank,
		    			'No Rekening' => $s->no_rekening,
		    			'Jenis ID' => $s->jenis_id,
		    			'No ID' => $s->no_id,
		    			'No KK' => $s->no_kk,
		    			'No BPJS' => $s->no_bpjs,
		    			'No Jamsostek' => $s->no_jamsostek,
		    			'Status' => $s->status ? 'Aktif' : 'Tidak Aktif'
 		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:AA' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:AA' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });

			$excel->sheet( 'Tidak Aktif', function( $sheet ) {
		    	$items = array();

		    	$items[0] = array(
	    			'No' => '',
	    			'Kode Karyawan' => '',
	    			'NIK Karyawan' => '',
	    			'Nama Karyawan' => '',
	    			'Jabatan' => '',
	    			'Jenis Kelamin' => '',
	    			'Status Kawin' => '',
	    			'Jumlah Anak' => '',
	    			'Tinggi Badan' => '',
	    			'Berat Badan' => '',
	    			'Tempat Lahir' => '',
	    			'Tanggal Lahir' => '',
	    			'Alamat' => '',
	    			'Kota' => '',
	    			'Provinsi' => '',
	    			'Kode Pos' => '',
	    			'No Telp.' => '',
	    			'Email' => '',
	    			'Agama' => '',
	    			'Bank' => '',
	    			'No Rekening' => '',
	    			'Jenis ID' => '',
	    			'No ID' => '',
	    			'No KK' => '',
	    			'No BPJS' => '',
	    			'No Jamsostek' => '',
	    			'Status' => ''
		    	);

		    	$staffes = Staff::where( 'status', '=', 0 )->get(); $i = 1;
		    	foreach ( $staffes as $s ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Karyawan' => $s->kode_karyawan,
		    			'NIK Karyawan' => $s->nik_karyawan,
		    			'Nama Karyawan' => $s->nama_karyawan,
		    			'Jabatan' => get_job_title_name( $s->id_jabatan ),
		    			'Jenis Kelamin' => $s->jenis_kelamin,
		    			'Status Kawin' => $s->status_kawin,
		    			'Jumlah Anak' => $s->jumlah_anak,
		    			'Tinggi Badan' => $s->t_badan,
		    			'Berat Badan' => $s->b_badan,
		    			'Tempat Lahir' => $s->tempat_lahir,
		    			'Tanggal Lahir' => $s->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $s->alamat ) ),
		    			'Kota' => ucwords( strtolower( $s->kota ) ),
		    			'Provinsi' => get_province_name( $s->provinsi ),
		    			'Kode Pos' => $s->kode_pos,
		    			'No Telp.' => $s->no_telepon,
		    			'Email' => $s->email,
		    			'Agama' => $s->agama,
		    			'Bank' => $s->bank,
		    			'No Rekening' => $s->no_rekening,
		    			'Jenis ID' => $s->jenis_id,
		    			'No ID' => $s->no_id,
		    			'No KK' => $s->no_kk,
		    			'No BPJS' => $s->no_bpjs,
		    			'No Jamsostek' => $s->no_jamsostek,
		    			'Status' => $s->status ? 'Aktif' : 'Tidak Aktif'
 		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:AA' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:AA' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });
			
			
		})->export('xls');
	}

	public function staff_jobtitle(){
		$jobtitles = DB::table( 'm_karyawan' )
	                ->select( DB::raw( 'DISTINCT( id_jabatan )' ) )
	                ->get();

		Excel::create( 'Data Karyawan Berdasarkan Jabatan ' . date( 'd-m-Y' ), function( $excel ) use ( $jobtitles ) {

			foreach( $jobtitles as $j ){
	    	
			    $excel->sheet( get_job_title_name( $j->id_jabatan ), function( $sheet ) use ( $j ) {
			    	$items = array();

			    	$items[0] = array(
		    			'No' => '',
		    			'Kode Karyawan' => '',
		    			'NIK Karyawan' => '',
		    			'Nama Karyawan' => '',
		    			'Jabatan' => '',
		    			'Jenis Kelamin' => '',
		    			'Status Kawin' => '',
		    			'Jumlah Anak' => '',
		    			'Tinggi Badan' => '',
		    			'Berat Badan' => '',
		    			'Tempat Lahir' => '',
		    			'Tanggal Lahir' => '',
		    			'Alamat' => '',
		    			'Kota' => '',
		    			'Provinsi' => '',
		    			'Kode Pos' => '',
		    			'No Telp.' => '',
		    			'Email' => '',
		    			'Agama' => '',
		    			'Bank' => '',
		    			'No Rekening' => '',
		    			'Jenis ID' => '',
		    			'No ID' => '',
		    			'No KK' => '',
		    			'No BPJS' => '',
		    			'No Jamsostek' => '',
		    			'Status' => ''
			    	);

			    	$staffes = Staff::where( 'id_jabatan', '=', $j->id_jabatan )->get(); $i = 1;
			    	foreach ( $staffes as $s ) {
			    		$items[$i-1] = array(
			    			'No' => $i,
			    			'Kode Karyawan' => $s->kode_karyawan,
			    			'NIK Karyawan' => $s->nik_karyawan,
			    			'Nama Karyawan' => $s->nama_karyawan,
			    			'Jabatan' => get_job_title_name( $s->id_jabatan ),
			    			'Jenis Kelamin' => $s->jenis_kelamin,
			    			'Status Kawin' => $s->status_kawin,
			    			'Jumlah Anak' => $s->jumlah_anak,
			    			'Tinggi Badan' => $s->t_badan,
			    			'Berat Badan' => $s->b_badan,
			    			'Tempat Lahir' => $s->tempat_lahir,
			    			'Tanggal Lahir' => $s->tanggal_lahir,
			    			'Alamat' => ucwords( strtolower( $s->alamat ) ),
			    			'Kota' => ucwords( strtolower( $s->kota ) ),
			    			'Provinsi' => get_province_name( $s->provinsi ),
			    			'Kode Pos' => $s->kode_pos,
			    			'No Telp.' => $s->no_telepon,
			    			'Email' => $s->email,
			    			'Agama' => $s->agama,
			    			'Bank' => $s->bank,
			    			'No Rekening' => $s->no_rekening,
			    			'Jenis ID' => $s->jenis_id,
			    			'No ID' => $s->no_id,
			    			'No KK' => $s->no_kk,
			    			'No BPJS' => $s->no_bpjs,
			    			'No Jamsostek' => $s->no_jamsostek,
			    			'Status' => $s->status ? 'Aktif' : 'Tidak Aktif'
	 		    		);

						$i++;
			    	}

					$sheet->cells( 'A1:AA' . $i, function( $cells ) {
						$cells->setValignment( 'center' );
					});
					$sheet->setBorder( 'A1:AA' . $i, 'thin' );
					$sheet->fromArray( $items );
			    });

			}

		})->export('xls');
	}

	public function staff_sex(){
		Excel::create( 'Data Karyawan Berdasarkan Jenis Kelamin ' . date( 'd-m-Y' ), function( $excel ) {
	    	
		    $excel->sheet( 'Laki-Laki', function( $sheet ) {
		    	$items = array();

		    	$items[0] = array(
	    			'No' => '',
	    			'Kode Karyawan' => '',
	    			'NIK Karyawan' => '',
	    			'Nama Karyawan' => '',
	    			'Jabatan' => '',
	    			'Jenis Kelamin' => '',
	    			'Status Kawin' => '',
	    			'Jumlah Anak' => '',
	    			'Tinggi Badan' => '',
	    			'Berat Badan' => '',
	    			'Tempat Lahir' => '',
	    			'Tanggal Lahir' => '',
	    			'Alamat' => '',
	    			'Kota' => '',
	    			'Provinsi' => '',
	    			'Kode Pos' => '',
	    			'No Telp.' => '',
	    			'Email' => '',
	    			'Agama' => '',
	    			'Bank' => '',
	    			'No Rekening' => '',
	    			'Jenis ID' => '',
	    			'No ID' => '',
	    			'No KK' => '',
	    			'No BPJS' => '',
	    			'No Jamsostek' => '',
	    			'Status' => ''
		    	);

		    	$staffes = Staff::where( 'jenis_kelamin', '=', 'Laki-Laki' )->get(); $i = 1;
		    	foreach ( $staffes as $s ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Karyawan' => $s->kode_karyawan,
		    			'NIK Karyawan' => $s->nik_karyawan,
		    			'Nama Karyawan' => $s->nama_karyawan,
		    			'Jabatan' => get_job_title_name( $s->id_jabatan ),
		    			'Jenis Kelamin' => $s->jenis_kelamin,
		    			'Status Kawin' => $s->status_kawin,
		    			'Jumlah Anak' => $s->jumlah_anak,
		    			'Tinggi Badan' => $s->t_badan,
		    			'Berat Badan' => $s->b_badan,
		    			'Tempat Lahir' => $s->tempat_lahir,
		    			'Tanggal Lahir' => $s->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $s->alamat ) ),
		    			'Kota' => ucwords( strtolower( $s->kota ) ),
		    			'Provinsi' => get_province_name( $s->provinsi ),
		    			'Kode Pos' => $s->kode_pos,
		    			'No Telp.' => $s->no_telepon,
		    			'Email' => $s->email,
		    			'Agama' => $s->agama,
		    			'Bank' => $s->bank,
		    			'No Rekening' => $s->no_rekening,
		    			'Jenis ID' => $s->jenis_id,
		    			'No ID' => $s->no_id,
		    			'No KK' => $s->no_kk,
		    			'No BPJS' => $s->no_bpjs,
		    			'No Jamsostek' => $s->no_jamsostek,
		    			'Status' => $s->status ? 'Aktif' : 'Tidak Aktif'
 		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:AA' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:AA' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });

			$excel->sheet( 'Perempuan', function( $sheet ) {
		    	$items = array();

		    	$items[0] = array(
	    			'No' => '',
	    			'Kode Karyawan' => '',
	    			'NIK Karyawan' => '',
	    			'Nama Karyawan' => '',
	    			'Jabatan' => '',
	    			'Jenis Kelamin' => '',
	    			'Status Kawin' => '',
	    			'Jumlah Anak' => '',
	    			'Tinggi Badan' => '',
	    			'Berat Badan' => '',
	    			'Tempat Lahir' => '',
	    			'Tanggal Lahir' => '',
	    			'Alamat' => '',
	    			'Kota' => '',
	    			'Provinsi' => '',
	    			'Kode Pos' => '',
	    			'No Telp.' => '',
	    			'Email' => '',
	    			'Agama' => '',
	    			'Bank' => '',
	    			'No Rekening' => '',
	    			'Jenis ID' => '',
	    			'No ID' => '',
	    			'No KK' => '',
	    			'No BPJS' => '',
	    			'No Jamsostek' => '',
	    			'Status' => ''
		    	);

		    	$staffes = Staff::where( 'jenis_kelamin', '=', 'Perempuan' )->get(); $i = 1;
		    	foreach ( $staffes as $s ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Karyawan' => $s->kode_karyawan,
		    			'NIK Karyawan' => $s->nik_karyawan,
		    			'Nama Karyawan' => $s->nama_karyawan,
		    			'Jabatan' => get_job_title_name( $s->id_jabatan ),
		    			'Jenis Kelamin' => $s->jenis_kelamin,
		    			'Status Kawin' => $s->status_kawin,
		    			'Jumlah Anak' => $s->jumlah_anak,
		    			'Tinggi Badan' => $s->t_badan,
		    			'Berat Badan' => $s->b_badan,
		    			'Tempat Lahir' => $s->tempat_lahir,
		    			'Tanggal Lahir' => $s->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $s->alamat ) ),
		    			'Kota' => ucwords( strtolower( $s->kota ) ),
		    			'Provinsi' => get_province_name( $s->provinsi ),
		    			'Kode Pos' => $s->kode_pos,
		    			'No Telp.' => $s->no_telepon,
		    			'Email' => $s->email,
		    			'Agama' => $s->agama,
		    			'Bank' => $s->bank,
		    			'No Rekening' => $s->no_rekening,
		    			'Jenis ID' => $s->jenis_id,
		    			'No ID' => $s->no_id,
		    			'No KK' => $s->no_kk,
		    			'No BPJS' => $s->no_bpjs,
		    			'No Jamsostek' => $s->no_jamsostek,
		    			'Status' => $s->status ? 'Aktif' : 'Tidak Aktif'
 		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:AA' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:AA' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });
			
			
		})->export('xls');
	}

	public function staff_user(){
		Excel::create( 'Data Karyawan Yang Menjadi Pengguna Sistem ' . date( 'd-m-Y' ), function( $excel ) {

		    $excel->sheet( date( 'd-m-Y' ), function( $sheet ) {
		    	$items = array();

		    	$items[0] = array(
	    			'No' => '',
	    			'Kode Karyawan' => '',
	    			'NIK Karyawan' => '',
	    			'Nama Karyawan' => '',
	    			'Jabatan' => '',
	    			'Jenis Kelamin' => '',
	    			'Status Kawin' => '',
	    			'Jumlah Anak' => '',
	    			'Tinggi Badan' => '',
	    			'Berat Badan' => '',
	    			'Tempat Lahir' => '',
	    			'Tanggal Lahir' => '',
	    			'Alamat' => '',
	    			'Kota' => '',
	    			'Provinsi' => '',
	    			'Kode Pos' => '',
	    			'No Telp.' => '',
	    			'Email' => '',
	    			'Agama' => '',
	    			'Bank' => '',
	    			'No Rekening' => '',
	    			'Jenis ID' => '',
	    			'No ID' => '',
	    			'No KK' => '',
	    			'No BPJS' => '',
	    			'No Jamsostek' => '',
	    			'Status' => ''
		    	);

		    	$users = User::all();

				$ids = array();
				foreach( $users as $user ){
					$ids[] = $user->id_karyawan;
				}

		    	$staffes = Staff::whereIn( 'id_karyawan', $ids )->get(); $i = 1;
		    	foreach ( $staffes as $s ) {
		    		$items[$i-1] = array(
		    			'No' => $i,
		    			'Kode Karyawan' => $s->kode_karyawan,
		    			'NIK Karyawan' => $s->nik_karyawan,
		    			'Nama Karyawan' => $s->nama_karyawan,
		    			'Jabatan' => get_job_title_name( $s->id_jabatan ),
		    			'Jenis Kelamin' => $s->jenis_kelamin,
		    			'Status Kawin' => $s->status_kawin,
		    			'Jumlah Anak' => $s->jumlah_anak,
		    			'Tinggi Badan' => $s->t_badan,
		    			'Berat Badan' => $s->b_badan,
		    			'Tempat Lahir' => $s->tempat_lahir,
		    			'Tanggal Lahir' => $s->tanggal_lahir,
		    			'Alamat' => ucwords( strtolower( $s->alamat ) ),
		    			'Kota' => ucwords( strtolower( $s->kota ) ),
		    			'Provinsi' => get_province_name( $s->provinsi ),
		    			'Kode Pos' => $s->kode_pos,
		    			'No Telp.' => $s->no_telepon,
		    			'Email' => $s->email,
		    			'Agama' => $s->agama,
		    			'Bank' => $s->bank,
		    			'No Rekening' => $s->no_rekening,
		    			'Jenis ID' => $s->jenis_id,
		    			'No ID' => $s->no_id,
		    			'No KK' => $s->no_kk,
		    			'No BPJS' => $s->no_bpjs,
		    			'No Jamsostek' => $s->no_jamsostek,
		    			'Status' => $s->status ? 'Aktif' : 'Tidak Aktif'
 		    		);

					$i++;
		    	}

				$sheet->cells( 'A1:AA' . $i, function( $cells ) {
					$cells->setValignment( 'center' );
				});
				$sheet->setBorder( 'A1:AA' . $i, 'thin' );
				$sheet->fromArray( $items );
		    });

			

		})->export('xls');
	}
}