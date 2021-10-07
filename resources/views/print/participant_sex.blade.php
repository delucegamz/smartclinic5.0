<html>
<head>
<title>Jumlah Karyawan Berdasarkan Jenis Kelamin</title>
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
<h4>Laporan Peserta Berdasarkan Jenis Kelamin</h4>
<table class="table table-bordered table-striped">
	<thead>
	<tr>
		<th>Jenis Kelamin</th>
		<th>Jumlah</th>
	</tr>
	</thead>
	<tbody>
	<?php
		$sexes = DB::table( 'm_peserta' )
	        ->select( DB::raw( 'DISTINCT( jenis_kelamin )' ) )
	        ->get();

	    $i = 1;
	    foreach( $sexes as $s ){
	    	$count = App\Participant::where( 'jenis_kelamin', '=', $s->jenis_kelamin )->count();
	?>
		<tr>
			<td>{{ $s->jenis_kelamin ? $s->jenis_kelamin : 'Belum Ter-set' }}</td>
			<td>{{ $count }}</td>
		</tr>
	<?php
		}
	?>
	</tbody>
</table>
</script>
</body>
</html>