<html>
<head>
<title>Jumlah Karyawan Berdasarkan Kelengkapan Data</title>
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
<h4>Laporan Peserta Berdasarkan Kelengkapan Data</h4>
<table class="table table-bordered table-striped">
	<thead>
	<tr>
		<th>Status</th>
		<th>Jumlah</th>
	</tr>
	</thead>
	<tbody>
		<?php
			$count_complete = App\Participant::where( 'id_departemen', '!=', '' )->count();
		?>
		<tr>
			<td>Lengkap</td>
			<td>{{ $count_complete }}</td>
		</tr>
		<?php
			$count_uncomplete = App\Participant::where( 'id_departemen', '=', '' )->orWhereNull( 'id_departemen' )->count();
		?>
		<tr>
			<td>Belum Lengkap</td>
			<td>{{ $count_uncomplete }}</td>
		</tr>
	</tbody>
</table>
</script>
</body>
</html>