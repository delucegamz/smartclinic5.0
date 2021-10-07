@extends('layouts.app')

@section('page_title')
Smart Clinic System - Laporan Peserta
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.canvasjs.min.js')}}"></script>
@stop

@section('content')
<div id="report-wrapper">
	<div class="row">
		<div class="col-xs-6">
			<div class="report-container theme-purple">
				<div class="report-inside">
					<h3>Jumlah Peserta Berdasarkan Jenis Kelamin</h3>

					<div id="chart-1" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/participant/sex' ) }}" class="btn print" target="_blank">Print</a>
						<a href="{{ url( 'export/participant/sex' ) }}" class="btn">Download</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="report-container theme-blue">
				<div class="report-inside">
					<h3>Jumlah Peserta Hamil</h3>

					<div id="chart-2" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/participant/pregnant' ) }}" class="btn print" target="_blank">Print</a>
						<a href="{{ url( 'export/participant/pregnant' ) }}" class="btn">Download</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="report-container theme-orange">
				<div class="report-inside">
					<h3>Jumlah Peserta Tuberculosis</h3>

					<div id="chart-3" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/participant/tb' ) }}" class="btn print" target="_blank">Print</a>
						<a href="{{ url( 'export/participant/tb' ) }}" class="btn">Download</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="report-container theme-lime-green">
				<div class="report-inside">
					<h3>Jumlah Peserta Berdasarkan Factory</h3>

					<div id="chart-4" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/participant/factory' ) }}" class="btn print" target="_blank">Print</a>
						<a href="{{ url( 'export/participant/factory' ) }}" class="btn">Download</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="report-container theme-red">
				<div class="report-inside">
					<h3>Jumlah Peserta Berdasarkan Departemen</h3>

					<div id="chart-5" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/participant/department' ) }}" class="btn print" target="_blank">Print</a>
						<a href="{{ url( 'export/participant/department' ) }}" class="btn">Download</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="report-container">
				<div class="report-inside">
					<h3>Jumlah Peserta Berdasarkan Client</h3>

					<div id="chart-6" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/participant/client' ) }}" class="btn print" target="_blank">Print</a>
						<a href="{{ url( 'export/participant/client' ) }}" class="btn">Download</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="report-container theme-dark-blue">
				<div class="report-inside">
					<h3>Jumlah Peserta Status Aktif</h3>

					<div id="chart-7" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/participant/status' ) }}" class="btn print" target="_blank">Print</a>
						<a href="{{ url( 'export/participant/status' ) }}" class="btn">Download</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="report-container theme-grey">
				<div class="report-inside">
					<h3>Jumlah Peserta Berdasarkan Kelengkapan Data</h3>

					<div id="chart-8" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/participant/data' ) }}" class="btn print" target="_blank">Print</a>
						<a href="{{ url( 'export/participant/data?filter=1' ) }}" class="btn">Download Not Complete</a>
						<a href="{{ url( 'export/participant/data?filter=0' ) }}" class="btn">Download All</a>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#collapseSix').addClass('in');

	<?php
		$total_participant = App\Participant::count();
	?>
	$("#chart-1").CanvasJSChart({
		theme: "theme2",
		backgroundColor: "#f5f6f7",
		data: [
		{
			type: "pie",
			showInLegend: true,
			toolTipContent: "{y} - #percent %",
			yValueFormatString: "# Orang",
			legendText: "{indexLabel}",
			dataPoints: [
			<?php
				$sexes = DB::table( 'm_peserta' )
	                ->select( DB::raw( 'DISTINCT( jenis_kelamin )' ) )
	                ->get();

	            $i = 1;
	            foreach( $sexes as $s ){
	            	$count = App\Participant::where( 'jenis_kelamin', '=', $s->jenis_kelamin )->count();
			?>
				{  y: {{$count}}, indexLabel: "{{ $s->jenis_kelamin ? ucwords( $s->jenis_kelamin, '-' ) : 'Belum Ter-set' }} ({{$count}})" }{{ ( $i != count( $sexes ) ? ',' : '' )}}
			<?php
					$i++;
				}
			?>
			]
		}
		]
	});

	<?php
		$count = DB::table( 'm_peserta_hamil' )
	                ->select( 'id_peserta_hamil' )
	                ->count();

	    $fake_count = $count ? $count : 100;
	?>
	$("#chart-2").CanvasJSChart({
		theme: "theme2",
		backgroundColor: "#f5f6f7",
		legend:{
	        verticalAlign: "center",
	        horizontalAlign: "center",
	        fontSize: 20
	    },
		data: [
		{
			type: "doughnut",
			showInLegend: true,
			dataPoints: [
				{  y: {{ $count }}, indexLabel: "{{$count}}", legendText: "{{$count}} orang", }
			]
		}
		]
	});

	<?php
		$count = DB::table( 'm_peserta_tb' )
	                ->select( 'id_peserta_tb' )
	                ->count();

	    $fake_count = $count ? $count : 100;
	?>
	$("#chart-3").CanvasJSChart({
		theme: "theme2",
		backgroundColor: "#f5f6f7",
		legend:{
	        verticalAlign: "center",
	        horizontalAlign: "center",
	        fontSize: 20
	    },
		data: [
		{
			type: "doughnut",
			showInLegend: true,
			dataPoints: [
				{  y: {{ $count }}, indexLabel: "{{$count}}", legendText: "{{$count}} orang", }
			]
		}
		]
	});

	$("#chart-4").CanvasJSChart({
		theme: "theme2",
		backgroundColor: "#f5f6f7",
		data: [
		{
			type: "pie",
			showInLegend: true,
			toolTipContent: "{y} - #percent %",
			yValueFormatString: "# Orang",
			legendText: "{indexLabel}",
			dataPoints: [
			<?php
				$factories = App\Factory::all();

	            $i = 1; $total_count = 0;
	            foreach( $factories as $f ){
	            	$ids = array();

	            	$departments = App\Department::where( 'nama_factory', '=', $f->id_factory )->get();
	            	foreach( $departments as $d ){
	            		$ids[] = $d->id_departemen;
	            	}

	            	$count = App\Participant::whereIn( 'id_departemen', $ids )->count();
			?>
				{  y: {{$count}}, indexLabel: "{{ $f->nama_factory }} ({{$count}})" },
			<?php
					$i++; $total_count += $count;
				}

				if( $total_count < $total_participant ){
					$count = $total_participant - $total_count;
			?>
				{  y: {{$count}}, indexLabel: "Belum Ter-set ({{$count}})" },
			<?php		
				}
			?>
			]
		}
		]
	});

	$("#chart-5").CanvasJSChart({
		theme: "theme2",
		backgroundColor: "#f5f6f7",
		data: [
		{
			type: "pie",
			showInLegend: true,
			toolTipContent: "{y} - #percent %",
			yValueFormatString: "# Orang",
			legendText: "{indexLabel}",
			dataPoints: [
			<?php
				$departments = App\Department::all();

	            $i = 1; $total_count = 0;
	            foreach( $departments as $d ){
	            	$count = App\Participant::where( 'id_departemen', '=', $d->id_departemen )->count();
			?>
				{  y: {{$count}}, indexLabel: "{{ $d->nama_departemen }} ({{$count}})" },
			<?php
					$i++; $total_count += $count;
				}

				if( $total_count < $total_participant ){
					$count = $total_participant - $total_count;
			?>
				{  y: {{$count}}, indexLabel: "Belum Ter-set ({{$count}})" },
			<?php		
				}
			?>
			]
		}
		]
	});

	$("#chart-6").CanvasJSChart({
		theme: "theme2",
		backgroundColor: "#f5f6f7",
		data: [
		{
			type: "pie",
			showInLegend: true,
			toolTipContent: "{y} - #percent %",
			yValueFormatString: "# Orang",
			legendText: "{indexLabel}",
			dataPoints: [
			<?php
				$clients = App\Client::all();

	            $i = 1; $total_count = 0;
	            foreach( $clients as $c ){
	            	$ids = array();

	            	$departments = App\Department::where( 'nama_client', '=', $c->id_client )->get();
	            	foreach( $departments as $d ){
	            		$ids[] = $d->id_departemen;
	            	}

	            	$count = App\Participant::whereIn( 'id_departemen', $ids )->count();
			?>
				{  y: {{$count}}, indexLabel: "{{ $c->nama_client }} ({{$count}})" },
			<?php
					$i++; $total_count += $count;
				}

				if( $total_count < $total_participant ){
					$count = $total_participant - $total_count;
			?>
				{  y: {{$count}}, indexLabel: "Belum Ter-set ({{$count}})" },
			<?php		
				}
			?>
			]
		}
		]
	});

	<?php 
		$count_user_active = App\Participant::where( 'status_aktif', '=', 1 )->count();
		$count_user_nonactive = App\Participant::where( 'status_aktif', '=', 0 )->count();
	?>

	$("#chart-7").CanvasJSChart({
		theme: "theme2",
		backgroundColor: "#f5f6f7",
		data: [
		{
			type: "pie",
			showInLegend: true,
			toolTipContent: "{y} - #percent %",
			yValueFormatString: "# Orang",
			legendText: "{indexLabel}",
			dataPoints: [
				{  y: {{ $count_user_active }}, indexLabel: "Aktif ({{ $count_user_active }})" },
				{  y: {{ $count_user_nonactive }}, indexLabel: "Tidak Aktif ({{ $count_user_nonactive }})" },
			]
		}
		]
	});

	<?php 
		$count_user_complete = App\Participant::where( 'id_departemen', '!=', '' )->count();
		$count_user_uncomplete = App\Participant::where( 'id_departemen', '=', NULL )->count();
	?>

	$("#chart-8").CanvasJSChart({
		theme: "theme2",
		backgroundColor: "#f5f6f7",
		data: [
		{
			type: "pie",
			showInLegend: true,
			toolTipContent: "{y} - #percent %",
			yValueFormatString: "# Orang",
			legendText: "{indexLabel}",
			dataPoints: [
				{  y: {{ $count_user_complete }}, indexLabel: "Sudah Lengkap ({{ $count_user_complete }})" },
				{  y: {{ $count_user_uncomplete }}, indexLabel: "Belum Lengkap ({{ $count_user_uncomplete }})" },
			]
		}
		]
	});

});
</script>
@stop