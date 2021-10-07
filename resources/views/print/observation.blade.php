<html>
<head>
<title>Data Observasi</title>
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
	<h4 class="patient-name">Data Observasi {{ $participant->nama_peserta }}</h4>
    @if( $date_from && $date_to )
    <h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
    @endif
	<span class="patient-label">NIK</span><span class="colon">:</span><span class="patient-value">{{ $participant->nik_peserta }}</span><br />
	<span class="patient-label">Departemen</span><span class="colon">:</span><span class="patient-value">{{ get_department_name( $participant->id_departemen ) }}</span><br />
	<span class="patient-label">Factory</span><span class="colon">:</span><span class="patient-value">{{ get_participant_factory( $participant->id_peserta ) }}</span><br />
	<span class="patient-label">Client</span><span class="colon">:</span><span class="patient-value">{{ get_participant_client( $participant->id_peserta ) }}</span><br />
</div><br />
@else
<h4>Data Observasi</h4>
@if( $date_from && $date_to )
<h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
@endif
@endif

<div class="table-wrapper">
<table class="table table-bordered table-striped list-table" id="list-items">
	<thead>
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">Kode</th>
			<th rowspan="2">Nama</th>
			<th rowspan="2">Departemen</th>
            <th rowspan="2">Factory</th>
            <th rowspan="2">Client</th>
			<th rowspan="2">Umur</th>
			<th rowspan="2">Tanggal Mulai</th>
			<th rowspan="2">Tanggal Selesai</th>
            <th colspan="8">Hasil Pemeriksaan</th>
			<th rowspan="2">Diagnosa Akhir</th>
			<th rowspan="2">Kesimpulan</th>
			<th rowspan="2">Keterangan dan Tindak Lanjut</th>
            <th rowspan="2">Surat Sakit</th>
            <th rowspan="2">Surat Rujukan</th>
            <th rowspan="2">Surat Cuti</th>
            <th rowspan="2">Resep Dokter</th>
		</tr>
        <tr>
            <th>Keadaan Umum</th>
            <th>Eye Opening</th>
            <th>Respon Verbal</th>
            <th>Respon Motorik</th>
            <th>Tensi Darah</th>
            <th>Suhu</th>
            <th>Denyut Nadi</th>
            <th>Nafas</th>
        </tr>
	<thead>
	<tbody>
	@if( count( $datas ) )
		@php $i = 1; @endphp
		@foreach( $datas as $o )
	        @php $od = App\ObservationDetail::where( 'no_observasi', '=', $o->id_observasi )->first(); @endphp
		<tr class="item" id="item-{{ $o->id_observasi }}">
			<td>{{ $i }}</td>
			<td>{{ $o->no_observasi }}</td>
			<td>{{ get_participant_name( $o->id_peserta ) }}</td>
			<td>{{ get_participant_department( $o->id_peserta ) }}</td>
	        <td>{{ get_participant_factory( $o->id_peserta ) }}</td>
	        <td>{{ get_participant_client( $o->id_peserta ) }}</td>
			<td>{{ get_participant_age( $o->id_peserta ) }}</td>
			<td>{{ date( 'd/m/Y H:i:s', strtotime( $o->tanggal_mulai ) ) }}</td>
			<td>{{ date( 'd/m/Y H:i:s', strtotime( $o->tanggal_selesai ) ) }}</td>
	        <td>{{ $od->keadaan_umum }}</td>
	        <td>{{ get_eye_opening( $od->k_mata ) }}</td>
	        <td>{{ get_verbal_response( $od->k_bicara ) }}</td>
	        <td>{{ get_motoric_response( $od->k_motorik ) }}</td>
	        <td>{{ $od->td_bawah }} / {{ $od->td_atas }}</td>
	        <td>{{ $od->suhu }}</td>
	        <td>{{ $od->nadi }}</td>
	        <td>{{ $od->jalan_nafas }}</td>
			<td>{{ $o->diagnosa_akhir }}</td>
			<td>{{ $o->kesimpulan_observasi }}</td>
	        <td>{{ $o->hasil_observasi }}</td>
	        <td>{{ ( is_sick_letter( $o->id_pemeriksaan_poli ) ? '&#10004;' : '&times;' ) }}</td>
	        <td>{{ ( is_reference_letter( $o->id_pemeriksaan_poli ) ? '&#10004;' : '&times;' ) }}</td>
	        <td>{{ ( is_dayoff_letter( $o->id_pemeriksaan_poli ) ? '&#10004;' : '&times;' ) }}</td>
	        <td>{{ ( is_doctor_recipe( $o->id_pemeriksaan_poli ) ? '&#10004;' : '&times;' ) }}</td>
		<tr>
			@php $i++ @endphp
		@endforeach
	@else
		<tr class="no-data">
			<td colspan="23">Tidak ada data ditemukan.</td>
		</tr>
	@endif
	</tbody>
</table>
</div>

</body>
</html>