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
<?php
	$results = DB::table( 'm_peserta_hamil' )
                ->select( 'id_peserta' )
                ->get();

    $ids = array();
    foreach( $results as $res ){
    	$ids[] = $res->id_peserta;
    }
?>
<h4>Peserta Hamil</h4>
<table class="table table-bordered table-striped">
	<thead>
	<tr>
	@foreach( $titles as $key => $value )
	<th>{{ $key }}</th>
	@endforeach
	</tr>
	</thead>
	<tbody>
	<?php $items = App\Participant::whereIn( 'id_peserta', $ids )->get(); $i = 1; ?>
	@foreach( $items as $p )
		<tr>
			<td>{{ $i }}</td>
			<td>{{ $p->kode_peserta }}</td>
			<td>{{ $p->no_medrec }}</td>
			<td>{{ $p->nik_peserta }}</td>
			<td>{{ $p->nama_peserta }}</td>
			<td>{{ get_participant_department( $p->id_peserta ) }}</td>
			<td>{{ get_participant_factory( $p->id_peserta ) }}</td>
			<td>{{ get_participant_client( $p->id_peserta ) }}</td>
			<td>{{ ucwords( $p->jenis_kelamin ) }}</td>
			<td>{{ ucwords( strtolower( $p->tempat_lahir ) ) }}</td>
			<td>{{ $p->tanggal_lahir }}</td>
			<td>{{ ucwords( strtolower( $p->alamat ) ) }}</td>
			<td>{{ ucwords( strtolower( $p->kota ) ) }}</td>
			<td>{{ get_province_name( $p->provinsi ) }}</td>
			<td>{{ $p->kodepos }}</td>
			<td>{{ $p->tanggal_aktif }}</td>
			<td>{{ $p->tanggal_nonaktif }}</td>
			<td>{{ $p->status_aktif ? 'Aktif' : 'Tidak Aktif' }}</td>
			<td>{{ $p->status_kawin ? $p->status_kawin : 'Belum Kawin' }}</td>
			<td>{{ $p->jumlah_anak }}</td>
		</tr>
	<?php $i++; ?>
	@endforeach
	</tbody>
</table>
</body>
</html>