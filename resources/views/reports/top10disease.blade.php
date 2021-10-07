@extends('layouts.app')

@section('page_title')
Smart Clinic System - Laporan Top {{ $count }} Penyakit
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('plugins/bootstrap-select/js/bootstrap-select.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('plugins/multi-select/js/jquery.quicksearch.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('plugins/multi-select/js/jquery.multi-select.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
@stop

@section('styles')
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.structure.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.theme.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('plugins/multi-select/css/multi-select.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/easy-autocomplete.min.css')}}" />
@stop

@section('content')
<div id="participant">
	<div class="content-title"><h1>Laporan Top {{ $count }} Penyakit</h1></div>

	<div class="narrow">
		<form enctype="multipart/form-data" id="import-livestock-form" method="get" class="wp-upload-form" action="{{ url( 'report/top10disease' ) }}">
			<div class="row">
				<div class="col-xs-5">
					<div class="form-group" id="elm-date-from">
						<label for="date-from">Date From</label>
						<input type="text" name="date-from" id="date-from" class="form-control disabled-this" placeholder="Ketikkan tanggal awal" value="{{ $date_from }}" />
					</div>
					<div class="form-group" id="elm-date-to">
						<label for="date-to">Date To</label>
						<input type="text" name="date-to" id="date-to" class="form-control disabled-this" placeholder="Ketikkan tanggal akhir" value="{{ $date_to }}" />
					</div>

					<div class="form-group" id="elm-date-to">
						<label for="count">Jumlah Data</label>
						<input type="number" name="count" id="count" class="form-control disabled-this" placeholder="Jumlah data yang ditampilkan" value="{{ $count }}" />
					</div>
					
					<div class="form-group">
						<input type="submit" name="submit" id="submit" class="btn btn-primary" value="Generate Report" />
					</div>
				</div>
			</div>
			
			@php
				$date_from =  ( !empty( $date_from ) ) ? $date_from : '';
				$date_to = ( !empty( $date_to ) ) ? $date_to : '';

				$date_from_formatted = ( !empty( $date_from ) ) ? date( 'd M Y', strtotime( $date_from ) ) : '';
				$date_to_formatted = ( !empty( $date_to ) ) ? date( 'd M Y', strtotime( $date_to ) ) : date( 'd M Y' );
			@endphp
			<div id="response-update">
				<div id="print-header" class="text-center">
					<h2>{{ get_company_name() }}</h2>
					<h5>{{ get_company_address() }}</h5>
				</div>

				<h2 style="text-align: center">LAPORAN TOP {{ $count }} PENYAKIT</h2>
				<h3 style="text-align: center">PERIODE: {{ $date_from_formatted }} - {{ $date_to_formatted }}</h3>

				<div style="max-width:500px;margin:0 auto; padding: 50px 0px;">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th style="width:50px;">No. </th>
								<th style="width:125px;">Kode ICD</th>
								<th style="width:250px;">Nama ICD</th>
								<th style="width:75px;">Jumlah</th>
							</tr>
						</thead>
						<tbody>
							@php 
								$i = 1; 
							@endphp
							@foreach( $datas as $data )
							<tr>
								<td>{{ $i }}</td>
								<td>{{ $data->iddiagnosa }}</td>
								<td>{{ get_diagnosis_name( $data->iddiagnosa ) }}</td>
								<td>{{ $data->count }}</td>
							</tr>
							@php 
								$i++; 
							@endphp
							@endforeach
						</tbody>
					</table>
				</div>

				<div class="form-group">
					@php
					$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					$print_url = str_replace( url( 'report/top10disease' ), url( 'print/top10disease' ), $actual_link );
					@endphp
					<a href="{{ $print_url }}" class="btn btn-primary" target="_blank">Print</a>
				</div>
			</div>
		</form>
		<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
	</div>
</div>
<script type="text/javascript" src="{{ URL::asset( 'assets/js/jquery.form.min.js' ) }}"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#collapseSix').addClass('in');

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

	$('#date-from, #date-to').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true
    });
});
</script>
@stop