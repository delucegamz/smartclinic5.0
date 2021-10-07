<html>
<head>
<title>Data Obat Keluar</title>
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
<h4>Data Obat Keluar</h4>
<table class="table table-bordered table-striped list-table" id="list-items">
	<thead>
		<tr>
			<th class="column-no-title">No.</th>
			<th class="column-medout-title">No. Faktur</th>
			<th class="column-recipe-title">No. Resep</th>
			<th class="column-date-title">Tanggal Faktur</th>
			<th class="column-amount-title">Jumlah Pengeluaran</th>
			<th class="column-amount-title">Detail Pengeluaran</th>
			<th class="column-note-title">Catatan</th>
		<tr>
	<thead>
	<tbody>
		<?php
			$date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        	$date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        	if( $date_from && $date_to )
                $datas = App\MedicineOut::where( 'tanggal_pengeluaran_obat', '>=', $date_from )->where( 'tanggal_pengeluaran_obat', '<=', $date_to )->orderBy( 'id_pengeluaran_obat', 'desc' )->get();
            elseif( $date_from )
                $datas = App\MedicineOut::where( 'tanggal_pengeluaran_obat', '>=', $date_from )->orderBy( 'id_pengeluaran_obat', 'desc' )->get();
            elseif( $date_to )
                $datas = App\MedicineOut::where( 'tanggal_pengeluaran_obat', '<=', $date_to )->orderBy( 'id_pengeluaran_obat', 'desc' )->get();
            else
                $datas = App\MedicineOut::orderBy( 'id_pengeluaran_obat', 'desc' )->get();

			if( count( $datas ) ){ 
				$i = 1;
				foreach ( $datas as $data ) {
					$outs = App\MedicineOutDetail::where( 'id_pengeluaran_obat', '=', $data->id_pengeluaran_obat )->get();
					
		?>
		<tr class="item" id="item-{{ $data->id_pengeluaran_obat }}">
			<td class="column-no">{{ $i }}</td>
			<td class="column-medout">{{ $data->no_pengeluaran_obat }}</td>
			<td class="column-recipe">{{ $data->id_resep ? get_recipe_no( $data->id_resep ) : '-' }}</td> 
			<td class="column-date">{{ $data->tanggal_pengeluaran_obat ? $data->tanggal_pengeluaran_obat : '-' }}</td>
			<td class="column-amount">{{ $data->jumlah_pengeluaran_obat ? $data->jumlah_pengeluaran_obat : 0 }}</td>
			<td class="column-amount">
			@foreach( $outs as $out )
			- {{ get_medicine_name( $out->id_obat ) }} ( {{ $out->jumlah_obat }} )<br />
			@endforeach
			</td>
			<td class="column-note">{{ $data->catatan_pengeluaran_obat ? $data->catatan_pengeluaran_obat : '-' }}</td>
		<tr>
		<?php
					$i++;
				}
			}else{
		?>
		<tr class="no-data">
			<td colspan="6">Tidak ada data ditemukan.</td>
		</tr>
		<?php		
			}
		?>
	</tbody>
</table>
</body>
</html>