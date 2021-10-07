<html>
<head>
<title>Data Factory</title>
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
<h4>Data Factory</h4>
<table class="table table-bordered table-striped list-table" id="list-factories">
	<thead>
		<tr>
			<th class="column-no-title">No.</th>
			<th class="column-code-title">Kode Factory</th>
			<th class="column-name-title">Nama Factory</th>
		<tr>
	<thead>
	<tbody>
		<?php
			$datas = App\Factory::all();

			if( count( $datas ) ){ 
				$i = 1;
				foreach ( $datas as $factory ) {
		?>
		<tr class="item" id="item-{{ $factory->id_factory }}">
			<td class="column-no">{{ $i }}</td>
			<td class="column-code">{{ $factory->kode_factory }}</td>
			<td class="column-name">{{ $factory->nama_factory }}</td>
		<tr>
		<?php
					$i++;
				}
			}else{
		?>
		<tr class="no-data">
			<td colspan="3">Tidak ada data ditemukan.</td>
		</tr>
		<?php		
			}
		?>
	</tbody>
</table>
</script>
</body>
</html>