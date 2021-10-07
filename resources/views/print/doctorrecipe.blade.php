<html>
<head>
<title>Data Resep Dokter</title>
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/print.css')}}">
<script type="text/javascript">
window.print();
</script>
</head>
<body>
<div id="header" class="text-center">
	<h2>{{ get_company_name() }}</h2>
	<h5>{{ get_company_address() }}</h5>
</div>
<h4>Data Resep Dokter</h4>
<table class="table table-bordered table-striped list-table" id="list-items">
	<thead>
		<tr>
			<th class="column-no-title">No.</th>
			<th class="column-date-title">Tanggal Berobat</th>
			<th class="column-medical-record-title">Poli</th>
			<th class="column-patient-nik-title">NIK Pasien</th>
			<th class="column-patient-name-title">Name Pasien</th>
			<th class="column-diagnosa-title">Diagnosa</th>
			<th class="column-diagnosa-title">Dokter Periksa</th>
			<th class="column-doctorrecipe-title">Resep Dokter</th>
			<th class="column-medicineout-title">Obat Keluar</th>
		<tr>
	<thead>
	<tbody>
		<?php
			$date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        	$date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

 			if( $date_from && $date_to ) :
                $datas = App\DoctorRecipe::where( 'created_at', '>=', $date_from . " 00:00:00" )->where( 'created_at', '<=', $date_to . " 23:59:59" )->orderBy( 'id_resep', 'desc' )->get();
            elseif( $date_from ) :
                $datas = App\DoctorRecipe::where( 'created_at', '>=', $date_from . " 00:00:00" )->orderBy( 'id_resep', 'desc' )->get();
            elseif( $date_to ) :
                $datas = App\DoctorRecipe::where( 'created_at', '<=', $date_to . " 23:59:59" )->orderBy( 'id_resep', 'desc' )->get();
            else :
                $datas = App\DoctorRecipe::orderBy( 'id_resep', 'desc' )->get();
            endif;

			if( count( $datas ) ){ 
				$i = 1;
				foreach ( $datas as $data ) {
					$medrec = App\MedicalRecord::find( $data->id_pemeriksaan_poli );
					$poliregistration = App\PoliRegistration::find( $medrec->id_pendaftaran_poli );
					$participant = App\Participant::find( $medrec->id_peserta );

					$details = App\DoctorRecipeDetail::where( 'id_resep', '=', $data->id_resep )->get();
					$out = App\MedicineOut::where( 'id_resep', '=', $data->id_resep )->first();
					$outs = App\MedicineOutDetail::where( 'id_pengeluaran_obat', '=', $out->id_pengeluaran_obat )->get();
		?>
		<tr class="item" id="item-{{ $data->id_resep }}">
			<td class="column-no">{{ $i }}</td>
			<td class="column-date">{{ date( 'd-m-Y', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
			<td class="column-medical-record">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
			<td class="column-patient-nik">{{ $participant->nik_peserta }}</td>
			<td class="column-name">{{ $participant->nama_peserta }}</td>
			<td class="column-diagnosa">{{ $medrec->diagnosa_dokter }}</td>
			<td class="column-diagnosa">{{ $medrec->dokter_rawat }}</td>
			<td class="column-docterrecipe">
			@foreach( $details as $d )
			- {{ get_medicine_name( $d->id_obat ) }} ( {{ $d->jumlah_obat }} )<br />
			@endforeach
			</td>
			<td class="column-medicineout">
			@foreach( $outs as $out )
			- {{ get_medicine_name( $out->id_obat ) }} ( {{ $out->jumlah_obat }} )<br />
			@endforeach
			</td>
		<tr>
		<?php
					$i++;
				}
			}else{
		?>
		<tr class="no-data">
			<td colspan="7">Tidak ada data ditemukan.</td>
		</tr>
		<?php		
			}
		?>
	</tbody>
</table>
</body>
</html>