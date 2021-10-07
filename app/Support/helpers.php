<?php

/**
 * die_dump function
 * Dump a variable and stop the process.
 *
 */
if ( ! function_exists( 'die_dump' ) ) {

	function die_dump() {
		list( $callee ) = debug_backtrace();
		$arguments = func_get_args();
		$total_arguments = count( $arguments );
		echo '<fieldset style="background: #fefefe !important; border:2px red solid; padding:5px">';
		echo '<legend style="background:lightgrey; padding:5px;">' . $callee['file'] . ' @ line: ' . $callee['line'] . '</legend><pre>';

		$i = 0;
		foreach ( $arguments as $argument ) {
			echo '<br/><strong>Debug #' . (++$i) . ' of ' . $total_arguments . '</strong>: ';
			var_dump( $argument );
		}

		echo '</pre>';
		echo '</fieldset>';
		die();
	}

}

/**
 * debug_var function
 * Dump a variable with human readable format
 *
 */
if ( ! function_exists( 'debug_var' ) ) {

	function debug_var( $var ) {
		$before = '<div style="padding:10px 20px 10px 20px; background-color:#fbe6f2; border:1px solid #d893a1; color: #000; font-size: 12px;>' . "\n";
		$before .= '<h5 style="font-family:verdana,sans-serif; font-weight:bold; font-size:18px;">Debug Helper Output</h5>' . "\n";
		$before .= '<pre>' . "\n";

		echo $before;
		var_dump( $var );
		$after = '</pre>' . "\n";
		$after .= '</div>' . "\n";

		echo $after;
	}

}

if ( ! function_exists( 'absint' ) ) {
	function absint( $maybeint ) {
	    return abs( intval( $maybeint ) );
	}
}

function get_client_name( $id ){
	$name = App\Client::get_name( $id );

	return $name ? $name : '-';
}

function get_factory_name( $id ){
	$name = App\Factory::get_name( $id );

	return $name ? $name : '-';
}

function get_department_name( $id ){
	$name = App\Department::get_name( $id );

	return $name ? $name : '-';
}

function get_medicine_group_name( $id ){
	$name = App\MedicineGroup::get_name( $id );

	return $name ? $name : '-';
}

function get_medicine_name( $id ){
	$med = App\Medicine::find( $id );

	return $med->nama_obat ? $med->nama_obat : '-';
}

function get_poli_name( $id ){
	$name = App\Poli::get_name( $id );

	return $name ? $name : '-';
}

function get_job_title_name( $id ){
	$jobtitle = App\JobTitle::find( $id );

	return ( $jobtitle && $jobtitle->nama_jabatan ) ? $jobtitle->nama_jabatan : '-';
}

function get_participant( $id ){	
	$participant = App\Participant::get_participant( $id );

	return $participant ? $participant : null;
}

function get_participant_nik( $id ){	
	$nik = App\Participant::get_nik( $id );

	return $nik ? $nik : '-';
}

function get_participant_code( $id ){	
	$participant_code = App\Participant::get_code( $id );

	return $participant_code ? $participant_code : '-';
}

function get_participant_name( $id ){
	$name = App\Participant::get_name( $id );

	return $name ? $name : '-';
}

function get_participant_age( $id ){
	$age = App\Participant::get_age( $id );

	return $age ? $age : '-';
}	

function get_participant_sex( $id ){
	$sex = App\Participant::get_sex( $id );

	return $sex ? $sex : '-';
}

function get_participant_department( $id, $dept_id = false ){
	if( !$dept_id ){
		$department = App\Participant::get_department( $id );

		return $department ? $department : '-';
	}else{
		$participant = App\Participant::find( $id );

		return $participant->id_departemen;
	}
}

function get_participant_department_alt( $id, $participant = null ){
	if( !$participant ){
		$department = App\Participant::get_department( $id );

		return $department ? $department : '-';
	}else{
		$dept_id = $participant->id_departemen;

		$department = App\Department::get_department( $dept_id );

		$nama_departemen = $department['nama_departemen'];

		return $nama_departemen ? $nama_departemen : '-';
	}
}

function get_participant_factory( $id, $participant = null ){
	if( !$participant ){
		$factory = App\Participant::get_factory( $id );
	}else{
		$dept_id = $participant->id_departemen;

		$department = App\Department::get_department( $dept_id );

		$factory = $department['nama_factory'];
	}

	return $factory ? $factory : '-';
}	

function get_participant_client( $id, $participant = null ){	
	if( !$participant ){
		$client = App\Participant::get_client( $id );
	}else{
		$dept_id = $participant->id_departemen;

		$department = App\Department::get_department( $dept_id );

		$client = $department['nama_client'];
	}
	

	return $client ? $client : '-';
}

function get_participant_address( $id ){	
	$address = App\Participant::get_address( $id );

	return $address ? $address : '-';
}

function get_participant_medrec_no( $id ){
	$participant = App\Participant::find( $id );

	return $participant ? $participant->no_medrec : '-';
}

function get_age_by_mysql_date( $mysql_date ){
	$interval = date_diff( date_create(), date_create( $mysql_date ) );

	return $interval->format( '%Y tahun %M bulan %d hari' );
}

function get_registration_no( $id_registration ){
	$no = App\PoliRegistration::get_registration_no( $id_registration );

	return $no ? $no : '-';
}

function get_id_pemeriksaan( $id_registration, $id_participant ){
	$id = App\MedicalRecord::get_registration_no( $id_registration, $id_participant );

	return $id ? $id : '-';
}

function get_participant_medicine_allergic( $id_participant ){
	$medicineallergics = App\MedicineAllergic::where( 'id_peserta', '=', $id_participant )->get();

	$return = '';
	if( count( $medicineallergics ) ){
		$i = 1;
		foreach( $medicineallergics as $ma ){
			$medicine = App\Medicine::find( $ma->idobat );

			if( $i != count( $medicineallergics ) ){
				$return .= $medicine->nama_obat . ', ';
			}else{
				$return .= $medicine->nama_obat;
			}

			$i++;
		}
	}else{
		$return = '-';
	}

	return $return;
}

function get_diagnosis_name( $code = NULL ){
	if( !$code ) return '-';

	$diagnosis = App\Diagnosis::find( $code );

	if( !$diagnosis ) $diagnosis = App\Diagnosis::where( 'kode_diagnosa', '=', $code )->first();

	if( $diagnosis ) return $diagnosis->nama_diagnosa;
	else return '-';
}

function get_participant_last_checkup_note( $checkup_id, $id_participant ){
	$poli_check = App\MedicalRecord::where( 'id_peserta', '=', $id_participant )
									->where( 'id_pendaftaran_poli', '<', $checkup_id )
									->orderBy( 'id_pemeriksaan_poli', 'DESC' )
									->first();

	return ( $poli_check && $poli_check->catatan_pemeriksaan ) ? $poli_check->catatan_pemeriksaan : '-';
}

function selected( $value, $current_value, $echo = false ){
	if( $echo ){
		if( $value == $current_value ){
			echo ' selected="selected"';
		}
	}else{
		if( $value == $current_value ){
			return ' selected="selected"';
		}
	}
}

function get_observation_check_name( $id_observation_check ){
	$observation_check = App\ObservationAction::find( $id_observation_check );

	return $observation_check->nama_pemeriksaan_observasi ? $observation_check->nama_pemeriksaan_observasi : '-';
}

function get_visit( $id_peserta =  0 ){
	if( !$id_peserta) return 0;

	$poli_registration = App\PoliRegistration::where( 'id_peserta', '=', $id_peserta )->get();

	return $poli_registration ? count( $poli_registration ) : 0;
}

function get_recipe_no( $id_recipe = 0 ){
	if( !$id_recipe ) return '-';

	$recipe = App\DoctorRecipe::find( $id_recipe );

	if( $recipe )
		return $recipe->no_resep;
	else
		return '-';
}

function generate_pagination( $datas, $rows ){
	$html = '';

	if( $rows != 'all' ){
		$html .= '<div class="navigation clearfix">';
		if( $datas->lastPage() > 1 ){
        	$html .= '<ul class="pagination left clearfix">';
        	if( $datas->currentPage() != 1 ){
				$html .= '<li class="pagination-item pagination-prev' . ( ( $datas->currentPage() == 1 ) ? ' disabled' : '' ) . '">
					     	<a href="' . $datas->url( $datas->currentPage() - 1 ) . '&rows=' . $rows . '" aria-label="Previous">
					        	<span aria-hidden="true"><i class="fa fa-chevron-left"></i> Prev Page</span>
					      	</a>
					    </li>';
		    }
			$html .= '</ul>';
		
			$html .= '<ul class="pagination center clearfix">';

        	for( $i = 1; $i <= $datas->lastPage(); $i++ ){
	            $half_total_links = floor( 7 / 2 );
	            $from = $datas->currentPage() - $half_total_links;
	            $to = $datas->currentPage() + $half_total_links;
	            if( $datas->currentPage() < $half_total_links ) {
	               $to += $half_total_links - $datas->currentPage();
	            }
	            if( $datas->lastPage() - $datas->currentPage() < $half_total_links ) {
	                $from -= $half_total_links - ( $datas->lastPage() - $datas->currentPage() ) - 1;
	            }
	            if( $from < $i && $i < $to ){
	                $html .= '<li class="pagination-item' . ( ( $datas->currentPage() == $i ) ? ' active' : '' ) . '">
			                    <a href="' . $datas->url( $i ) . '&rows=' . $rows . '">' . $i . '</a>
			                </li>';
	            }
        	}

        	$html .= '</ul>';

        	$html .= '<ul class="pagination right clearfix">';

        	if( $datas->currentPage() != $datas->lastPage() ){
				$html .= '<li class="pagination-item pagination-next' . ( ( $datas->currentPage() == $datas->lastPage() ) ? ' disabled' : '' ) . '">
					      	<a href="' . $datas->url( $datas->currentPage() + 1 ) . '&rows=' . $rows . '" aria-label="Next">
					        	<span aria-hidden="true">Next Page <i class="fa fa-chevron-right"></i></span>
					      	</a>
					    </li>';
		    }

			$html .= '</ul>';
		}

		$html .= '</div>';
	}

	return $html;
}

function current_user_can( $role = NULL ){
	if( !$role ) return false;

	$user = Auth::user();

    $idpengguna = $user['original']['idpengguna'];

    $accrights = App\AccessRight::where( 'id_pengguna', '=', $idpengguna )->first();

    if( !$accrights ) return false;

    $access_rights = unserialize( $accrights->hak_akses );
	if( in_array( $role, $access_rights ) ){
		return true;
	}else{
		return false;
	}
    
}

function parse_size($size) {
  	$unit = preg_replace( '/[^bkmgtpezy]/i', '', $size ); // Remove the non-unit characters from the size.
  	$size = preg_replace( '/[^0-9\.]/', '', $size ); // Remove the non-numeric characters from the size.
  	if ( $unit ) {
    	// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    	return round( $size * pow( 1024, stripos( 'bkmgtpezy', $unit[0] ) ) );
  	} else {
    	return round( $size );
  	}
}

function generate_date_from_number( $numb_date = NULL ){
	if( !$numb_date ) return date( 'Y-m-d' );

	$year = substr( $numb_date, 0, 4 );
	$month = substr( $numb_date, 4, 2 );
	$day = substr( $numb_date, 6, 2 );

	return $year . '-' . $month . '-' . $day;
}

function check_marital_status( $status = '' ){
	if( !$status ) return 'Belum Kawin';

	$status_kawin = substr( $status, 0, 1 );

	if( $status_kawin == 'K' ){
		return 'Kawin';
	}else{
		return 'Belum Kawin';
	}
}

function check_child_amount( $status = '' ){
	if( !$status ) return 0;

	$jumlah_anak = substr( $status, 1, 1 );

	if( $jumlah_anak == 'K' ) return 0;
	else return $jumlah_anak;
}

function get_province_name( $province_id = 0 ){
	if( !$province_id ) return '-';

	$province = App\Province::where( 'id_propinsi', '=', $province_id )->first();

	return ( $province && isset( $province->nama_propinsi ) ) ? $province->nama_propinsi : '-';
}

function get_company_name(){
	$company = App\Company::find( 1 );

	if( !$company ) return '-';

	return $company->nama_organisasi;
}

function get_company_address(){
	$company = App\Company::find( 1 );

	if( !$company ) return '-';

	return $company->alamat_organisasi . ( $company->kota_organisasi ? ', ' . $company->kota_organisasi : '' ) . ( $company->provinsi_organisasi ? ', ' . $company->provinsi_organisasi : '' );
}

function get_staff_code(){
	$company = App\Company::find( 1 );

	if( !$company ) return '';

	return $company->kode_karyawan;
}

function check_job_title( $jobtitle_id ){
	if( !$jobtitle_id ) return '';

	$jobtitle = App\JobTitle::find( $jobtitle_id );
	if( $jobtitle->nama_jabatan == 'Dokter' ) return 'Dr.';
	elseif( $jobtitle->nama_jabatan == 'Bidan' ) return 'Bd.';
	else return ''; 

}

function get_anc_number( $medicalrecord_id, $participant_id ){
	$anc = App\Anc::where( 'id_peserta', '=', $participant_id )->where( 'id_pemeriksaan_poli', '=', $medicalrecord_id )->first();

	if( $anc )
		return $anc->no_pemeriksaan_anc;
	else
		return App\Anc::generate_id();
}

function get_anc_visit( $medicalrecord_id, $participant_id ){
	$visits = App\Anc::where( 'id_peserta', '=', $participant_id )->where( 'id_pemeriksaan_poli', '=', $medicalrecord_id )->first();

	if( $visits && isset( $visits->kunjungan_ke ) ){
		return $visits->kunjungan_ke;
	}else{
		$visits = App\Anc::where( 'id_peserta', '=', $participant_id )->count();

		$visits++;

		return $visits;
	}
}

function get_eye_opening( $eye_opening = NULL ){
	if( !$eye_opening ) return '';

	switch ( $eye_opening ) {
		case 1:
			return 'Tidak ada respon (diam)';
			break;
		case 2:
			return 'Rangsang Nyeri';
			break;
		case 3:
			return 'Dipanggil / Perintah Verbal';
			break;
		case 4:
			return 'Spontan';
			break;
		default:
			return '';
			break;
	}
}

function get_verbal_response( $verbal_response = NULL ){
	if( !$verbal_response ) return '';

	switch ( $verbal_response ) {
		case 1:
			return 'Tidak bersuara';
			break;
		case 2:
			return 'Bersuara tidak berarti (incomprehensible)';
			break;
		case 3:
			return 'Kata-kata kacau (inappropriate)';
			break;
		case 4:
			return 'Konversi / jawaban kacau';
			break;
		case 5:
			return 'Orientasi baik';
			break;
		default:
			return '';
			break;
	}
}

function get_motoric_response( $motoric_response = NULL ){
	if( !$motoric_response ) return '';

	switch ( $motoric_response ) {
		case 1:
			return 'Tidak ada respon / diam';
			break;
		case 2:
			return 'Ekstensi (desebrasi)';
			break;
		case 3:
			return 'Fleksi abnormal (dekortikasi)';
			break;
		case 4:
			return 'Reaksi pada nyeri';
			break;
		case 5:
			return 'Lokalisasi / rangsang nyeri';
			break;
		case 6:
			return 'Sesuai perintah';
			break;
		default:
			return '';
			break;
	}
}

function is_sick_letter( $medrec_id ){
	$sks = App\SickLetter::where( 'id_pemeriksaan_poli', '=', $medrec_id )->count();

	return $sks;
}

function is_reference_letter( $medrec_id ){
	$srd = App\ReferenceLetter::where( 'id_pemeriksaan_poli', '=', $medrec_id )->count();

	return $srd;
}

function is_dayoff_letter( $medrec_id ){
	$sc = App\DayOffLetter::where( 'id_pemeriksaan_poli', '=', $medrec_id )->count();

	return $sc;
}

function is_doctor_recipe( $medrec_id ){
	$rsp = App\DoctorRecipe::where( 'id_pemeriksaan_poli', '=', $medrec_id )->count();

	return $rsp;
}

function is_observation( $medrec_id ){
	$obv = App\Observation::where( 'id_pemeriksaan_poli', '=', $medrec_id )->count();

	return $obv;
}

function datediffInWeeks( $date1, $date2 ){
    if( $date1 > $date2 ) return datediffInWeeks( $date2, $date1 );
    $first = DateTime::createFromFormat( 'Y-m-d', $date1 );
    $second = DateTime::createFromFormat( 'Y-m-d', $date2 );
    return floor( $first->diff( $second )->days / 7 );
}

function get_setting( $setting_name ){
	$setting = App\Setting::where( 'setting_name', '=', $setting_name )->first();

	return $setting ? $setting->setting_value : '';
}

function update_setting( $setting_name, $setting_value ){
	$setting = App\Setting::where( 'setting_name', '=', $setting_name )->first();

	if( !$setting ){
		$setting = new App\Setting();	

		$setting->setting_name = $setting_name;
	}

	$setting->setting_value = $setting_value;
	$setting->save();
}