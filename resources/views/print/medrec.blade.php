<html>
<head>
<title>Data Rekam Medis</title>
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

@if( $participant )
<?php $participant = App\Participant::where( 'id_peserta', '=', $participant )->first(); ?>
<div class="patient-detail">
	<h4 class="patient-name">Data Rekam Medis {{ $participant->nama_peserta }}</h4>
    @if( $date_from && $date_to )
    <h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
    @endif
	<span class="patient-label">NIK</span><span class="colon">:</span><span class="patient-value">{{ $participant->nik_peserta }}</span><br />
	<span class="patient-label">Departemen</span><span class="colon">:</span><span class="patient-value">{{ get_department_name( $participant->id_departemen ) }}</span><br />
	<span class="patient-label">Factory</span><span class="colon">:</span><span class="patient-value">{{ get_participant_factory( $participant->id_peserta ) }}</span><br />
	<span class="patient-label">Client</span><span class="colon">:</span><span class="patient-value">{{ get_participant_client( $participant->id_peserta ) }}</span><br />
</div><br />
@else
<h4>Data Rekam Medis</h4>
@if( $date_from && $date_to )
<h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
@endif
@endif

<table class="table table-bordered table-striped list-table" id="list-items">
	<thead>
		<tr>
			<th class="column-no-title">NO</th>
			<th class="column-medout-title">REGISTRASI</th>
			<th class="column-recipe-title">TGL</th>
			@if( !$participant )
			<th class="column-nik-title">NIK</th>
			<th class="column-nik-title">NO MEDREK</th>
			<th class="column-name-title">NAMA</th>
			<th class="column-name-title">JK</th>
			<th class="column-name-title">UMUR</th>
            <th class="column-name-title">DEPT</th>
            <th class="column-name-title">PABRIK</th>
			@endif
			<th class="column-note-title">ICDX</th>
			<th class="column-note-title">DIAGNOSA</th>
			<th class="column-note-title">DOKTER</th>
			<th class="column-note-title">POLI</th>
			<th class="column-note-title">TL</th>
			<th class="column-note-title">IN</th>
			<th class="column-note-title">OUT</th>
			<th class="column-note-title">BL</th>
		<tr>
	<thead>
	<tbody>
		<?php
			if( count( $datas ) ){ 
				foreach ( $datas as $data ) {
		?>
		<tr class="item">
			<td class="column-no">{{ $data['NO'] }}</td>
			<td class="column-medout">{{ $data['REGISTRASI'] }}</td>
			<td class="column-recipe">{{ $data['TGL'] }}</td> 
			@if( !$participant )
			<td class="column-date">{{ $data['NIK'] }}</td>
			<td class="column-amount">{{ $data['NO MEDREK'] }}</td>
			<td class="column-date">{{ $data['NAMA'] }}</td>
			<td class="column-amount">{{ $data['JK'] }}</td>
			<td class="column-amount">{{ $data['UMUR'] }}</td>
			<td class="column-date">{{ $data['DEPT'] }}</td>
			<td class="column-amount">{{ $data['PABRIK'] }}</td>
			@endif
			<td class="column-amount">{{ $data['ICD'] }}</td>
			<td class="column-note">{{ $data['DIAGNOSA'] }}</td>
			<td class="column-note">{{ $data['DOKTER'] }}</td>
			<td class="column-note">{{ $data['POLI'] }}</td>
			<td class="column-amount">{{ $data['TL'] }}</td>
			<td class="column-note">{{ $data['IN'] }}</td>
			<td class="column-note">{{ $data['OUT'] }}</td>
			<td class="column-note">{{ $data['BL'] }}</td>
		<tr>
		<?php
		
				}
			}else{
		?>
		<tr class="no-data">
			@if( !$participant )
			<td colspan="11">Tidak ada data ditemukan.</td>
			@else
			<td colspan="7">Tidak ada data ditemukan.</td>
			@endif
		</tr>
		<?php		
			}
		?>
	</tbody>
</table>
</body>
</html>