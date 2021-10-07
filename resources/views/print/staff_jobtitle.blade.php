<?php
	$titles = array(
		'No' => '',
		'Kode Karyawan' => '',
		'NIK Karyawan' => '',
		'Nama Karyawan' => '',
		'Jabatan' => '',
		'Jenis Kelamin' => '',
		'Alamat' => '',
		'Kota' => '',
		'No Telp.' => '',
		'Agama' => '',
		'Status' => ''
	);
?>
<html>
<head>
<title>Data Karyawan Berdasarkan Jabatan</title>
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
	$jobtitles = App\JobTitle::all();
?>
@foreach( $jobtitles as $j )
<h4>{{ $j->nama_jabatan }}</h4>
<table class="table table-bordered table-striped">
	<thead>
	<tr>
	@foreach( $titles as $key => $value )
	<th>{{ $key }}</th>
	@endforeach
	</tr>
	</thead>
	<tbody>
	<?php $items = App\Staff::where( 'id_jabatan', '=', $j->id_jabatan )->get(); $i = 1; $count = count( $items ); ?>
	@foreach( $items as $s )
		<tr>
			<td>{{ $i }}</td>
			<td>{{ $s->kode_karyawan }}</td>
			<td>{{ $s->nik_karyawan }}</td>
			<td>{{ $s->nama_karyawan }}</td>
			<td>{{ get_job_title_name( $s->id_jabatan ) }}</td>
			<td>{{ $s->jenis_kelamin }}</td>
			<td>{{ ucwords( strtolower( $s->alamat ) ) }}</td>
			<td>{{ ucwords( strtolower( $s->kota ) ) }}</td>
			<td>{{ $s->no_telepon }}</td>
			<td>{{ $s->agama }}</td>
			<td>{{ $s->status ? 'Aktif' : 'Tidak Aktif' }}</td>
		</tr>
	<?php $i++; ?>
	@endforeach
		<tr>
			<td colspan="9" align="center" class="text-center"><strong>Total</strong></td>
			<td colspan="2" align="center" class="text-center"><strong>{{ $count }}</strong></td>
		</tr>
	</tbody>
</table>
@endforeach
</body>
</html>