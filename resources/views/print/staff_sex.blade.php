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

	$male_items = array();
	$male_staffes = App\Staff::where( 'jenis_kelamin', '=', 'Laki-Laki' )->get(); $i = 1;
	foreach ( $male_staffes as $s ) {
		$male_items[$i-1] = array(
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

	$female_items = array();
	$female_staffes = App\Staff::where( 'jenis_kelamin', '=', 'Perempuan' )->get(); $i = 1;
	foreach ( $female_staffes as $s ) {
		$female_items[$i-1] = array(
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
<title>Data Karyawan Berdasarkan Jenis Kelamin</title>
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

<h4>Laki-Laki</h4>
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
	@foreach( $male_items as $item )
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

<h4>Perempuan</h4>
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
	@foreach( $female_items as $item )
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