<html>
<head>
<title>Jumlah Karyawan Berdasarkan Status Aktif</title>
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
<h4>Laporan Peserta Berdasarkan Status Aktif</h4>
<table class="table table-bordered table-striped">
	<thead>
	<tr>
		<th>Status</th>
		<th>Jumlah</th>
	</tr>
	</thead>
	<tbody>
		<?php
			$count_active = App\Participant::where( 'status_aktif', '=', 1 )->count();
		?>
		<tr>
			<td>Aktif</td>
			<td>{{ $count_active }}</td>
		</tr>
		<?php
			$count_nonactive = App\Participant::where( 'status_aktif', '=', 0 )->count();
		?>
		<tr>
			<td>Tidak Aktif</td>
			<td>{{ $count_nonactive }}</td>
		</tr>
	</tbody>
</table>
</script>
</body>
</html>