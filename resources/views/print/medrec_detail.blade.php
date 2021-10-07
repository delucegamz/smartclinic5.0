<?php
	$id_pemeriksaan = ( isset( $_GET['id'] ) && $_GET['id'] != '' ) ? absint( $_GET['id'] ) : NULL;

	if( !$id_pemeriksaan ) die( 'Tidak dapat menemukan data yang dicari.' );

	$medrec = App\MedicalRecord::find( $id_pemeriksaan );

	if( !$medrec ) die( 'Tidak dapat menemukan data yang dicari.' );

	$poliregistration = App\PoliRegistration::find( $medrec->id_pendaftaran_poli );
?>
<html>
<head>
<title>Data Detail Rekam Medis</title>
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
<h4>Data Detail Rekam Medis</h4>
<table class="table list-table" id="list-items" border="0">
	<tbody>
		<tr>
			<td>No Rekam Medis</td>
			<td>:</td>
			<td>{{ get_participant_medrec_no( $medrec->id_peserta ) }}</td>
		</tr>
		<tr>
			<td>No Pemeriksaan</td>
			<td>:</td>
			<td>{{ $medrec->no_pemeriksaan_poli }}</td>
		</tr>
		<tr>
			<td>Tanggal Pemeriksaan</td>
			<td>:</td>
			<td>{{ $poliregistration->tgl_selesai }}</td>
		</tr>
		<tr>
			<td>Nama Pasien</td>
			<td>:</td>
			<td>{{ get_participant_name( $medrec->id_peserta ) }}</td>
		</tr>
		<tr>
			<td>NIK Peserta</td>
			<td>:</td>
			<td>{{ get_participant_nik( $medrec->id_peserta ) }}</td>
		</tr>
		<tr>
			<td>Unit Kerja</td>
			<td>:</td>
			<td>{{ get_participant_department( $medrec->id_peserta ) }}</td>
		</tr>
		<tr>
			<td>Pabrik</td>
			<td>:</td>
			<td>{{ get_participant_factory( $medrec->id_peserta ) }}</td>
		</tr>
		<tr>
			<td>Client</td>
			<td>:</td>
			<td>{{ get_participant_client( $medrec->id_peserta ) }}</td>
		</tr>
		<tr>
			<td>Jenis Kelamin</td>
			<td>:</td>
			<td>{{ get_participant_sex( $medrec->id_peserta ) }}</td>
		</tr>
		<tr>
			<td>ICD.X</td>
			<td>:</td>
			<td>{{ $medrec->iddiagnosa }}</td>
		</tr>
		<tr>
			<td>Diagnosa</td>
			<td>:</td>
			<td>{{ $medrec->diagnosa_dokter }}</td>
		</tr>
		<tr>
			<td>Jenis Tindakan</td>
			<td>:</td>
			<td>
			@php
			$uraian = '';
   			switch( $medrec->uraian ) :
   				case 11:
   					$uraian = 'Umum';
   					break;
   				case 22:
   					$uraian = 'Kecelakaan Kerja';
   					break;
   				case 33:
   					$uraian = 'Kecelakaan Lalu Lintas';
   					break;
   				case 44:
   					$uraian = 'Kontrol Kecelakaan Kerja';
   					break;
   				case 55:
   					$uraian = 'Kontrol Pasca Rawat Inap';
   					break;
   				case 66:
   					$uraian = 'ANC';
   					break;
   				default: $uraian = 'Umum'; break;
   			endswitch;
			@endphp
			{{ $uraian }}
			</td>
		</tr>
		<tr>
			<td>Dokter Rawat</td>
			<td>:</td>
			<td>{{ $medrec->dokter_rawat }}</td>
		</tr>
		<tr>
			<td>Keluhan</td>
			<td>:</td>
			<td>{{ $medrec->keluhan }}</td>
		</tr>
		<tr>
			<td>Catatan Pemeriksaan</td>
			<td>:</td>
			<td>{{ $medrec->catatan_pemeriksaan }}</td>
		</tr>
		<tr>
			<td>Suspek Tuberculosis</td>
			<td>:</td>
			<td>{{ $medrec->tb == 1 ? 'Ya' : 'Tidak' }}</td>
		</tr>
		<tr>
			<td>Penyakit Akibat Hubungan Kerja</td>
			<td>:</td>
			<td>{{ $medrec->pahk == 1 ? 'Ya' : 'Tidak' }}</td>
		</tr>
	</tbody>
</table>
</body>
</html>