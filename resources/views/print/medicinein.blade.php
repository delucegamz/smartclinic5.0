<html>
<head>
<title>Data Obat Masuk</title>
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
<h4>Data Obat Masuk</h4>
<table class="table table-bordered table-striped list-table" id="list-items">
	<thead>
		<tr>
			<th class="column-no-title">No.</th>
			<th class="column-facture-title">No Faktur</th>
			<th class="column-supplier-title">Nama Supplier</th>
			<th class="column-date-title">Tanggal Faktur</th>
			<th class="column-date-process-title">Tanggal Proses</th>
			<th class="column-amount-title">Jumlah Pembelian</th>
			<th class="column-price-title">Total Harga</th>
		</tr>
	<thead>
	<tbody>
		<?php
			$date_from = isset( $_GET['date-from'] ) ? $_GET['date-from']  : '';
        	$date_to = isset( $_GET['date-to'] ) ? $_GET['date-to']  : '';

        	if( $date_from && $date_to )
                $datas = App\MedicineIn::where( 'tanggal_obat_masuk', '>=', $date_from )->where( 'tanggal_obat_masuk', '<=', $date_to )->orderBy( 'id_pembelian_obat', 'desc' )->get();
            elseif( $date_from )
                $datas = App\MedicineIn::where( 'tanggal_obat_masuk', '>=', $date_from )->orderBy( 'id_pembelian_obat', 'desc' )->get();
            elseif( $date_to )
                $datas = App\MedicineIn::where( 'tanggal_obat_masuk', '<=', $date_to )->orderBy( 'id_pembelian_obat', 'desc' )->get();
            else
                $datas = App\MedicineIn::orderBy( 'id_pembelian_obat', 'desc' )->get();

			if( count( $datas ) ){ 
				$i = 1;
				foreach ( $datas as $data ) {
		?>
		<tr class="item" id="item-{{ $data->id_pembelian_obat }}">
			<td class="column-no">{{ $i }}</td>
			<td class="column-facture">{{ $data->no_pembelian_obat }}</td>
			<td class="column-supplier">{{ $data->idsupplier ? $data->idsupplier : '-' }}</td>
			<td class="column-date">{{ $data->tanggal_obat_masuk ? $data->tanggal_obat_masuk : '-' }}</td>
			<td class="column-date-process">{{ $data->tanggal_proses ? $data->tanggal_proses : '-' }}</td>
			<td class="column-amount">{{ $data->jumlah_pembelian ? $data->jumlah_pembelian : 0 }}</td>
			<td class="column-price">Rp. {{ $data->total_harga ? number_format( $data->total_harga, 0, ',', '.' ) : '-' }}</td>
		</tr>
		<?php
					$i++;
				}
			}else{
		?>
		<tr class="no-data">
			<td colspan="7">Tidak ada data ditemukan.</td>
		</tr>
		<?php		
			}
		?>
	</tbody>
</table>
</body>
</html>