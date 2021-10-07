<html>
<head>
<title>Jumlah Karyawan Berdasarkan Client</title>
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
<h4>Laporan Peserta Berdasarkan Client</h4>
<table class="table table-bordered table-striped">
	<thead>
	<tr>
		<th>Nama Client</th>
		<th>Jumlah</th>
	</tr>
	</thead>
	<tbody>
	<?php
		$clients = App\Client::all();

	    $i = 1;
	    foreach( $clients as $c ){
	    	$ids = array();
			$departments = App\Department::where( 'nama_client', '=', $c->id_client )->get();
        	foreach( $departments as $d ){
        		$ids[] = $d->id_departemen;
        	}

	    	$count = App\Participant::whereIn( 'id_departemen', $ids )->count();
	?>
		<tr>
			<td>{{ $c->nama_client }}</td>
			<td>{{ $count }}</td>
		</tr>
	<?php
		}

		$count_unset = App\Participant::where( 'id_departemen', '=', '' )->orWhereNull( 'id_departemen' )->count();
	?>
		<tr>
			<td>Belum Ter-set</td>
			<td>{{ $count_unset }}</td>
		</tr>
	</tbody>
</table>
</script>
</body>
</html>