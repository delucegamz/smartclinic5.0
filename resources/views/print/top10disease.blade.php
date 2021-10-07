<?php
	$titles = array(
		'No' => '',
		'Kode ICD' => '',
		'Nama ICD' => '',
		'Jumlah' => ''
	);
?>
<html>
<head>
<title>LAPORAN TOP {{ $count }} PENYAKIT</title>
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
	$date_from =  ( !empty( $date_from ) ) ? $date_from : '';
	$date_to = ( !empty( $date_to ) ) ? $date_to : '';

	$date_from_formatted = ( !empty( $date_from ) ) ? date( 'd M Y', strtotime( $date_from ) ) : '';
	$date_to_formatted = ( !empty( $date_to ) ) ? date( 'd M Y', strtotime( $date_to ) ) : date( 'd M Y' );

	$current_user = Auth::user();
    $idpengguna = $current_user['original']['idpengguna'];
    $user = App\User::find( $idpengguna );
	$staff = App\Staff::where( 'id_karyawan', '=', $user->id_karyawan )->first();
?>
<h2 style="text-align: center; margin: 30px 0 5px;">LAPORAN TOP {{ $count }} PENYAKIT</h2>
<h3 style="text-align: center; margin: 0 0 30px;">PERIODE: {{ $date_from_formatted }} - {{ $date_to_formatted }}</h3>
<h5 style="text-align: center; margin: 0 0 10px;">DATE PRINTED : {{ date( 'd-m-Y' ) }}</h5>
<h5 style="text-align: center; margin: 0 0 30px;">PRINTED BY : {{ $staff->nama_karyawan }}</h5>
<table class="table table-bordered table-striped">
	<thead>
	<tr>
	@foreach( $titles as $key => $value )
	<th>{{ $key }}</th>
	@endforeach
	</tr>
	</thead>
	<tbody>
	@php 
		$i = 1; 
	@endphp
	@foreach( $datas as $data )
	<tr>
		<td>{{ $i }}</td>
		<td>{{ $data->iddiagnosa }}</td>
		<td>{{ get_diagnosis_name( $data->iddiagnosa ) }}</td>
		<td>{{ $data->count }}</td>
	</tr>
	@php 
		$i++; 
	@endphp
	@endforeach
	</tbody>
</table>
</body>
</html>