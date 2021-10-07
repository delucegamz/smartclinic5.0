<html>
<head>
<title>Jumlah Karyawan Berdasarkan Departemen</title>
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
<h4>Laporan Peserta Berdasarkan Departemen</h4>
<table class="table table-bordered table-striped">
	<thead>
	<tr>
		<th>Nama Departemen</th>
		<th>Jumlah</th>
	</tr>
	</thead>
	<tbody>
	<?php
		$departments = App\Department::orderBy( 'nama_departemen', 'asc' )->get();

	    $i = 1;
	    foreach( $departments as $d ){
	    	$count = App\Participant::where( 'id_departemen', '=', $d->id_departemen )->count();
	?>
		<tr>
			<td>{{ $d->nama_departemen  }}</td>
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