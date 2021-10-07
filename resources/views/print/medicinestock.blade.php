<html>
<head>
<title>Data Stock Obat</title>
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
<h4>Data Stock Obat</h4>
<table class="table table-bordered table-striped list-table" id="list-items">
	<thead>
		<tr>
			<th class="column-no-title">No.</th>
			<th class="column-code-title">Kode Obat</th>
			<th class="column-name-title">Nama Obat</th>
			<th class="column-medicine-group-title">Golongan Obat</th>
			<th class="column-stock-title">Stock Obat</th>
		<tr>
	<thead>
	<tbody>
		<?php
			$medicinegroup = isset( $_GET['medicinegroup'] ) ? absint( $_GET['medicinegroup'] ) : '';

			if( $medicinegroup )
				$datas = App\Medicine::where( 'id_golongan_obat', '=', $medicinegroup )->get();
			else
				$datas = App\Medicine::all();

			if( count( $datas ) ){ 
				$i = 1;
				foreach ( $datas as $medicine ) {
		?>
		<tr class="item" id="item-{{ $medicine->id_obat }}">
			<td class="column-no">{{ $i }}</td>
			<td class="column-code">{{ $medicine->kode_obat }}</td>
			<td class="column-name">{{ $medicine->nama_obat }}</td>
			<td class="column-medicine-group">{{ get_medicine_group_name( $medicine->id_golongan_obat ) }}</td>
			<td class="column-stock">{{ $medicine->stock_obat }}</td>
		<tr>
		<?php
					$i++;
				}
			}else{
		?>
		<tr class="no-data">
			<td colspan="5">Tidak ada data ditemukan.</td>
		</tr>
		<?php		
			}
		?>
	</tbody>
</table>
</body>
</html>