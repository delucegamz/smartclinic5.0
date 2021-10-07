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

	$active_items = array();
	$active_staffes = App\Staff::where( 'status', '=', 1 )->get(); $i = 1;
	foreach ( $active_staffes as $s ) {
		$active_items[$i-1] = array(
			'No' => $i,
			'Kode Karyawan' => $s->kode_karyawan,
			'NIK Karyawan' => $s->nik_karyawan,
			'Nama Karyawan' => $s->nama_karyawan,
			'Jabatan' => get_job_title_name( $s->id_jabatan ),
			'Jenis Kelamin' => $s->jenis_kelamin,
			'Alamat' => ucwords( strtolower( $s->alamat ) ),
			'Kota' => ucwords( strtolower( $s->kota ) ),
			'No Telp.' => $s->no_telepon,
			'Agama' => $s->agama,
			'Status' => $s->status ? 'Aktif' : 'Tidak Aktif'
    	);

		$i++;
	}

	$nonactive_items = array();
	$nonactive_staffes = App\Staff::where( 'status', '=', 0 )->get(); $i = 1;
	foreach ( $nonactive_staffes as $s ) {
		$nonactive_items[$i-1] = array(
			'No' => $i,
			'Kode Karyawan' => $s->kode_karyawan,
			'NIK Karyawan' => $s->nik_karyawan,
			'Nama Karyawan' => $s->nama_karyawan,
			'Jabatan' => get_job_title_name( $s->id_jabatan ),
			'Jenis Kelamin' => $s->jenis_kelamin,
			'Alamat' => ucwords( strtolower( $s->alamat ) ),
			'Kota' => ucwords( strtolower( $s->kota ) ),
			'No Telp.' => $s->no_telepon,
			'Agama' => $s->agama,
			'Status' => $s->status ? 'Aktif' : 'Tidak Aktif'
    	);

		$i++;
	}
?>
<html>
<head>
<title>Data Karyawan Berdasarkan Status</title>
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

<h4>Aktif</h4>
<table class="table table-bordered table-striped">
	<thead>
	<tr>
	@foreach( $titles as $key => $value )
	<th>{{ $key }}</th>
	@endforeach
	</tr>
	</thead>
	<tbody>
	<?php $count = 0; ?>
	@foreach( $active_items as $item )
		<tr>
		@foreach( $titles as $key => $value )
		<td>{{ $item[$key] }}</td>
		@endforeach
		</tr>
	<?php $count++; ?>
	@endforeach
		<tr>
			<td colspan="9" align="center" class="text-center"><strong>Total</strong></td>
			<td colspan="2" align="center" class="text-center"><strong>{{ $count }}</strong></td>
		</tr>
	</tbody>
</table>

<h4>Non Aktif</h4>
<table class="table table-bordered table-striped">
	<thead>
	<tr>
	@foreach( $titles as $key => $value )
	<th>{{ $key }}</th>
	@endforeach
	</tr>
	</thead>
	<tbody>
	<?php $count = 0; ?>
	@foreach( $nonactive_items as $item )
		<tr>
		@foreach( $titles as $key => $value )
		<td>{{ $item[$key] }}</td>
		@endforeach
		</tr>
	<?php $count++; ?>
	@endforeach
	<tr>
		<td colspan="9" align="center" class="text-center"><strong>Total</strong></td>
		<td colspan="2" align="center" class="text-center"><strong>{{ $count }}</strong></td>
	</tr>
	</tbody>
</table>
</body>
</html>