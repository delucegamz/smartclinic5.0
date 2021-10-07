<html>
<head>
<title>Data Surat {{ $letter }}</title>
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
<h4>Data Surat {{ $letter }}</h4>
@if( count( $items ) )
<table class="table table-bordered table-striped list-table" id="list-items">
	<thead>
		@php
			$th = $items[0];
		@endphp
		<tr>
		@foreach( $th as $key => $value )
			<th>{{ $key }}</th>
		@endforeach
		<tr>
	<thead>
	<tbody>
		@foreach( $items as $item )
		<tr>
			@foreach( $item as $key => $value )
			<td>{{ $value }}</td>
			@endforeach
		<tr>
		@endforeach
	</tbody>
</table>
@else
<p>Tidak ada data ditemukan.</p>
@endif
</body>
</html>