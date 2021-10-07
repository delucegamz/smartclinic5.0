<html>
<head>
<title>Data Pengunaan Ambulance</title>
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
<?php
$title = '';
if( $view == 'out' ){
	$title = 'Ambulance Keluar';
}elseif( $view == 'in' ){
	$title = 'Ambulance Masuk';
}elseif( $view == 'participant' ){
	$title = 'Penggunaan Ambulance Oleh ' . $participant;
}
?>
<h4>Data {{ $title }}</h4>
@if( $date_from && $date_to )
<h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
@endif
<table class="table table-bordered table-striped list-table" id="list-items">
	<thead>
		<tr>
			<th class="column_no_title">No.</th>
			<th class="columnno_ambulance_out_title">No Ambulance Out</th>
			<th class="columnno_ambulance_in_title">No Ambulance In</th>
			<th class="column_tanggal_title">Tanggal</th>
			<th class="column_id_peserta_title">ID Peserta</th>
			<th class="column_nik_peserta_title">NIK Peserta</th>
			<th class="column-name_title">Nama Peserta</th>
			<th class="column_jam_datang_title">Jam Datang</th>
			<th class="column_jam_pulang_title">Jam Pulang</th>
			<th class="column_lokasi_penjemputan_title">Lokasi Penjemputan</th>
			<th class="column_lokasi_pengiriman_title">Lokasi Pengiriman</th>
			<th class="column_km_out_title">KM Out</th>
			<th class="column_km_in_title">KM In</th>
			<th class="column_driver_title">Driver</th>
			<th class="column_catatan_title">Catatan</th>
		<tr>
	<thead>
	<tbody>
		<?php
			$count = count( $ambulances );
			if( count( $ambulances ) ){ 
				$i = 1;
				foreach ( $ambulances as $ambulance ) {
		?>
		<tr class="item" id="item-{{ $ambulance['id_ambulance_out'] }}">
			<td class="column_no">{{ $i }}</td>
			<td class="columnno_ambulance_out">{{ $ambulance['no_ambulance_out'] }}</td>
			<td class="columnno_ambulance_in">{{ $ambulance['no_ambulance_in'] }}</td>
			<td class="column_tanggal">{{ $ambulance['tanggal'] }}</td>
			<td class="column_id_peserta">{{ get_participant_code( $ambulance['id_peserta'] ) }}</td>
			<td class="column_nik_peserta">{{ get_participant_nik( $ambulance['id_peserta'] ) }}</td>
			<td class="column-name">{{ get_participant_name( $ambulance['id_peserta'] ) }}</td>
			<td class="column_jam_datang">{{ $ambulance['jam_datang'] }}</td>
			<td class="column_jam_pulang">{{ $ambulance['jam_pulang'] }}</td>
			<td class="column_lokasi_penjemputan">{{ $ambulance['lokasi_penjemputan'] }}</td>
			<td class="column_lokasi_pengiriman">{{ $ambulance['lokasi_pengiriman'] }}</td>
			<td class="column_km_out">{{ $ambulance['km_out'] }}</td>
			<td class="column_km_in">{{ $ambulance['km_in'] }}</td>
			<td class="column_driver">{{ $ambulance['driver'] }}</td>
			<td class="column_catatan">{{ $ambulance['catatan'] }}</td>
		<tr>
		<?php
					$i++;
				}
			}else{
		?>
		<tr class="no-data">
			<td colspan="15">Tidak ada data ditemukan.</td>
		</tr>
		<?php		
			}
		?>
		<tr>
			<td colspan="13" align="center" class="text-center"><strong>Total</strong></td>
			<td colspan="2" align="center" class="text-center"><strong>{{ $count }}</strong></td>
		</tr>
	</tbody>
</table>
</body>
</html>