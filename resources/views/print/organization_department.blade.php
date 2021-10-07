<html>
<head>
<title>Data Departemen</title>
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
<h4>Data Departemen</h4>
<table class="table table-bordered table-striped list-table" id="list-departments">
	<thead>
		<tr>
			<th class="column-no-title">No.</th>
			<th class="column-code-title">Kode Departemen</th>
			<th class="column-name-title">Nama Departemen</th>
			<th class="column-factory-title">Nama Factory</th>
			<th class="column-client-title">Nama Client</th>
		<tr>
	<thead>
	<tbody>
		<?php
			$datas = App\Department::all();

			if( count( $datas ) ){ 
				$i = 1;
				foreach ( $datas as $department ) {
		?>
		<tr class="item" id="item-{{ $department->id_departemen }}">
			<td class="column-no">{{ $i }}</td>
			<td class="column-code">{{ $department->kode_departemen }}</td>
			<td class="column-name">{{ $department->nama_departemen }}</td>
			<td class="column-factory">{{ get_factory_name( $department->nama_factory ) }}</td>
			<td class="column-client">{{ get_client_name( $department->nama_client ) }}</td>
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
</script>
</body>
</html>