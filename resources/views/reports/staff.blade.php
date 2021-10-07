@extends('layouts.app')

@section('page_title')
Smart Clinic System - Laporan Karyawan
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
					<h3>Berdasarkan Status</h3>

					<div id="chart-1" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/staff/status' ) }}" class="btn" target="_blank">Print</a>
						<a href="{{ url( 'export/staff/status?export=xlsx' ) }}" class="btn">Download Excel</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="report-container theme-blue">
				<div class="report-inside">
					<h3>Berdasarkan Jabatan</h3>

					<div id="chart-2" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/staff/jobtitle' ) }}" class="btn" target="_blank">Print</a>
						<a href="{{ url( 'export/staff/jobtitle?export=xlsx' ) }}" class="btn">Download Excel</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<div class="report-container theme-orange">
				<div class="report-inside">
					<h3>Berdasarkan Jenis Kelamin</h3>

					<div id="chart-3" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/staff/sex' ) }}" class="btn" target="_blank">Print</a>
						<a href="{{ url( 'export/staff/sex' ) }}" class="btn">Download</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="report-container theme-lime-green">
				<div class="report-inside">
					<h3>Pengguna Sistem</h3>

					<div id="chart-4" class="pie-chart"></div>

					<div class="download-item" style="width:100%">
						<a href="{{ url( 'print/staff/user' ) }}" class="btn" target="_blank">Print</a>
						<a href="{{ url( 'export/staff/user' ) }}" class="btn">Download</a>
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
		$staff_aktif = App\Staff::where( 'status', '=', 1 )->count();
		$staff_nonaktif = App\Staff::where( 'status', '=', 0 )->count();
		$staff_semua = App\Staff::count();
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
				{  y: {{$staff_aktif}}, indexLabel: "Aktif ({{$staff_aktif}})" },
				{  y: {{$staff_nonaktif}}, indexLabel: "Non-Aktif ({{$staff_nonaktif}})" }
			]
		}
		]
	});

	$("#chart-2").CanvasJSChart({
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
				$jobtitles = DB::table( 'm_karyawan' )
	                ->select( DB::raw( 'DISTINCT( id_jabatan )' ) )
	                ->get();

	            $i = 1;
	            foreach( $jobtitles as $jt ){
	            	$count = App\Staff::where( 'id_jabatan', '=', $jt->id_jabatan )->count();
			?>
				{  y: {{$count}}, indexLabel: "{{ get_job_title_name( $jt->id_jabatan ) }} ({{$count}})" }{{ ( $i != count( $jobtitles ) ? ',' : '' )}}
			<?php
					$i++;
				}
			?>
			]
		}
		]
	});

	<?php
		$staff_m = App\Staff::where( 'jenis_kelamin', '=', 'Laki-Laki' )->count();
		$staff_f = App\Staff::where( 'jenis_kelamin', '=', 'Perempuan' )->count();
	?>
	$("#chart-3").CanvasJSChart({
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
				{  y: {{$staff_m}}, indexLabel: "Laki-Laki ({{$staff_m}})" },
				{  y: {{$staff_f}}, indexLabel: "Perempuan ({{$staff_f}})" }
			]
		}
		]
	});

	<?php
		$users = App\User::all();

		$ids = array();
		foreach( $users as $user ){
			$ids[] = $user->id_karyawan;
		}
	?>

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
				$jobtitles = DB::table( 'm_karyawan' )
	                ->select( DB::raw( 'DISTINCT( id_jabatan )' ) )
	                ->get();

	            $i = 1;
	            foreach( $jobtitles as $jt ){
	            	$count = App\Staff::whereIn( 'id_karyawan', $ids )->where( 'id_jabatan', '=', $jt->id_jabatan )->count();
			?>
				{  y: {{$count}}, indexLabel: "{{ get_job_title_name( $jt->id_jabatan ) }} ({{$count}})" }{{ ( $i != count( $jobtitles ) ? ',' : '' )}}
			<?php
					$i++;
				}
			?>
			]
		}
		]
	});

});
</script>
@stop