<html>
<head>
<title>Data Client</title>
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
<h4>Data Client</h4>
<table class="table table-bordered table-striped list-table" id="list-clients">
	<thead>
		<tr>
			<th class="column-no-title">No.</th>
			<th class="column-code-title" style="width:80px;">ID Client</th>
			<th class="column-name-title">Nama Client</th>
			<th class="column-address-title">Alamat</th>
			<th class="column-zip-title">Kode Pos</th>
			<th class="column-phone-1-title">Telp. 1</th>
			<th class="column-phone-2-title">Telp. 2</th>
			<th class="column-fax-title">Fax</th>
			<th class="column-email-title">Email</th>
		<tr>
	<thead>
	<tbody>
		<?php
			$datas = App\Client::all();

			if( count( $datas ) ){ 
				
				$i = 1;
				foreach ( $datas as $client ) {
		?>
		<tr class="item" id="item-{{ $client->id_client }}">
			<td class="column-no">{{ $i }}</td>
			<td class="column-code">{{ $client->kode_client }}</td>
			<td class="column-name">{{ $client->nama_client }}</td>
			<td class="column-address">
				{{ ( $client->alamat_client ? $client->alamat_client : '-' ) }}
				{{ ( $client->kota ? ', ' . $client->kota : '' ) }}
				{{ ( $client->propinsi ? ', ' . $client->propinsi : '' ) }}
			</td>
			<td class="column-zip">{{ $client->kode_pos ? $client->kode_pos : '-' }}</td>
			<td class="column-phone-1">{{ $client->telepon_1 ? $client->telepon_1 : '-' }}</td>
			<td class="column-phone-2">{{ $client->telepon_2 ? $client->telepon_2 : '-' }}</td>
			<td class="column-fax">{{ $client->fax ? $client->fax : '-' }}</td>
			<td class="column-email">{{ $client->email ? $client->email : '-' }}</td>
		<tr>
		<?php
					$i++;
				}
			}else{
		?>
		<tr class="no-data">
			<td colspan="9">Tidak ada data ditemukan.</td>
		</tr>
		<?php		
			}
		?>
	</tbody>
</table>
</script>
</body>
</html>