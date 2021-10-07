@extends('layouts.app')

@section('page_title')
Smart Clinic System - Laporan Organisasi
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/datatables.min.js')}}"></script>
@stop

@section('styles')
<script type="text/javascript" src="{{URL::asset('assets/css/datatables.css')}}"></script>
@stop

@section('content')
<div class="entry-content" style="margin-bottom: 60px;">
	<div class="content-title"><h1>Daftar Client</h1></div>

	<div class="table-wrapper no-margin full-width">
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

		<div class="download-item full-width">
			<a href="{{ url( 'print/organization/client' ) }}" class="btn print" target="_blank">Print</a>
			<!--<a href="{{ url( 'export/organization/client' ) }}" class="btn">Download</a>-->
		</div>
	</div>
</div>

<div class="entry-content" style="margin-bottom: 60px;">
	<div class="content-title"><h1>Daftar Pabrik</h1></div>

	<div class="table-wrapper no-margin full-width">
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

		<div class="download-item full-width">
			<a href="{{ url( 'print/organization/factory' ) }}" class="btn print" target="_blank">Print</a>
			<!--<a href="{{ url( 'export/organization/factory' ) }}" class="btn">Download</a>-->
		</div>
	</div>
</div>

<div class="entry-content">
	<div class="content-title"><h1>Daftar Unit Kerja</h1></div>

	<div class="table-wrapper no-margin full-width">
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

		<div class="download-item full-width">
			<a href="{{ url( 'print/organization/department' ) }}" class="btn print" target="_blank">Print</a>
			<!--<a href="{{ url( 'export/organization/department' ) }}" class="btn">Download</a>-->
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#collapseSix').addClass('in');
});
</script>	
@stop