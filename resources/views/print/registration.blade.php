<html>
<head>
<title>Data Pendaftaran</title>
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
if( $filter == 'all' ){
	$title = '';
}elseif( $filter == 'belum-direkam' ){
	$title = 'yang Belum Direkam';
}elseif( $filter == 'tidak-direkam' ){
	$title = 'yang Tidak Terekam';
}elseif( $filter == 'sudah-direkam' ){
	$title = 'yang Sudah Direkam';
}
?>
<h4>Data Pendaftaran {{ $title }}</h4>
@if( $date_from && $date_to )
<h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
@endif
<table class="table table-bordered table-striped list-table" id="list-items">
	<thead>
		<tr>
			<th class="column-no-title">No.</th>
			<th class="column-no-pendaftaran-title">No. Pendaftaran</th>
			<th class="column-waktu-pendaftaran-title">Waktu Pendaftaran</th>
			<th class="column-nik-title">NIK</th>
			<th class="column-nama-pasien-title">Nama Pasien</th>
			<th class="column-jenis-kelamin-title">Jenis Kelamin</th>
			<th class="column-unit-kerja-title">Unit Kerja</th>
			<th class="column-pabrik-title">Pabrik</th>
			<th class="column-perusahaan-title">Perusahaan</th>
			<th class="column-nama-poli-title">Nama Poli</th>
			<th class="column-catatan-title">Catatan</th>
		<tr>
	<thead>
	<tbody>
		<?php 
			$count = count( $datas );
			if( count( $datas ) ){
				$i = 1;
				foreach ( $datas as $poliregistration ) {
		?>
		<tr class="item" id="item-{{ $poliregistration->id_pendaftaran }}">
			<td class="column-no">{{ $i }}</td>
			<td class="column-no-pendaftaran">{{ $poliregistration->no_daftar }}</td>
			<td class="column-waktu-pendaftaran">{{ date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
			<td class="column-nik">{{ get_participant_nik( $poliregistration->id_peserta ) }}</td>
			<td class="column-nama-pasien">{{ get_participant_name( $poliregistration->id_peserta ) }}</td>
			<td class="column-jenis-kelamin">{{ get_participant_sex( $poliregistration->id_peserta ) }}</td>
			<td class="column-unit-kerja">{{ get_participant_department( $poliregistration->id_peserta ) }}</td>
			<td class="column-pabrik">{{ get_participant_factory( $poliregistration->id_peserta ) }}</td>
			<td class="column-perusahaan">{{ get_participant_client( $poliregistration->id_peserta ) }}</td>
			<td class="column-nama-poli">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
			<td class="column-catatan">{{ $poliregistration->catatan_pendaftaran }}</td>
		<tr>
		<?php
					$i++;
				}
			}else{
		?>
		<tr class="no-data">
			<td colspan="11">Tidak ada data ditemukan.</td>
		</tr>
		<?php		
			}
		?>
		<tr>
			<td colspan="9" align="center" class="text-center"><strong>Total</strong></td>
			<td colspan="2" align="center" class="text-center"><strong>{{ $count }}</strong></td>
		</tr>
	</tbody>
</table>
</body>
</html>