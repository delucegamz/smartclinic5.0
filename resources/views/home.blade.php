@extends('layouts.app')

@section('page_title')
Smart Clinic System - Dashboard
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/chart.bundle.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.canvasjs.min.js')}}"></script>
@stop

@section('content')
<div id="home">
	<div class="row">
		<div class="col-xs-6 home-col home-col-1">
			<div id="chart-1-wrapper">

				<div class="row">
					<div class="col-xs-6"><h4>Laporan Harian Kunjungan Pasien Dalam Satu Pekan</h4></div>
					<div class="col-xs-6"><h4 id="periode-name">Periode 1 - 7 September 2016</h4></div>
				</div>

				<div id="chart-1" style="width:390px;height:200px"></div>
			</div>

			<div class="row">
				<div class="col-xs-6">
					<h4>Jumlah <br />Pendaftaran</h4>

					<div id="chart-2-wrapper">
						<div id="chart-2" style="width:193px;height:193px"></div>
					</div>
				</div>
				<div class="col-xs-6">
					<h4>Proses <br />Pemeriksaan</h4>

					<div id="chart-3-wrapper">
						<div id="chart-3" style="width:193px;height:193px"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-3 home-col home-col-2">
			<div class="home-chart">
				<h4>Laporan Mingguan Kunjungan Pasien Periode <?php echo date( 'F Y' ); ?></h4>

				<?php
			    	$start_date = date( "Y-m-01 00:00:00" );
					$end_date = date( "Y-m-31 23:59:59" );
					$poli_reg_total = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();
			    ?>

			    
			    <span class="progress-label">Tanggal 1-7</span>
				<div class="progress">
					<?php
				    	$start_date = date( "Y-m-01 00:00:00" );
						$end_date = date( "Y-m-07 23:59:59" );
						$poli_reg = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

						$width = $poli_reg_total ? ( $poli_reg / $poli_reg_total ) * 100 : 20;
				    ?>
					
				  	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $poli_reg; ?>" aria-valuemin="0" aria-valuemax="<?php echo $poli_reg_total; ?>" style="min-width: 20%; width: <?php echo $width; ?>%;">
				  	<?php echo $poli_reg; ?>
				  	</div>
				</div>

				<span class="progress-label">Tanggal 8-14</span>
				<div class="progress">
					<?php
				    	$start_date = date( "Y-m-08 00:00:00" );
						$end_date = date( "Y-m-14 23:59:59" );
						$poli_reg = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();
				   
						$width = $poli_reg_total ? ( $poli_reg / $poli_reg_total ) * 100 : 20;
				    ?>
					
				  	<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $poli_reg; ?>" aria-valuemin="0" aria-valuemax="<?php echo $poli_reg_total; ?>" style="min-width: 20%; width: <?php echo $width; ?>%;">
				    <?php echo $poli_reg; ?>
				  	</div>
				</div>

				<span class="progress-label">Tanggal 15-21</span>
				<div class="progress">
					<?php
				    	$start_date = date( "Y-m-15 00:00:00" );
						$end_date = date( "Y-m-21 23:59:59" );
						$poli_reg = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();
				   
						$width = $poli_reg_total ? ( $poli_reg / $poli_reg_total ) * 100 : 20;
				    ?>
				  	<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?php echo $poli_reg; ?>" aria-valuemin="0" aria-valuemax="<?php echo $poli_reg_total; ?>" style="min-width: 20%; width: <?php echo $width; ?>%;">
				    <?php echo $poli_reg; ?>
				  	</div>
				</div>

				<span class="progress-label">Tanggal 22-31</span>
				<div class="progress">
					<?php
				    	$start_date = date( "Y-m-22 00:00:00" );
						$end_date = date( "Y-m-31 23:59:59" );
						$poli_reg = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();
				    
						$width = $poli_reg_total ? ( $poli_reg / $poli_reg_total ) * 100 : 20;
				    ?>
					
				  	<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="<?php echo $poli_reg_total; ?>" style="min-width: 20%; width: <?php echo $width; ?>%;">
				    <?php echo $poli_reg; ?>
				  	</div>
				</div>
			</div>

			<div class="home-chart">
				<h4>Kunjungan Poli</h4>

				<?php
			    	$start_date = date( "Y-m-01 00:00:00" );
					$end_date = date( "Y-m-31 23:59:59" );
					
					$polis = DB::table( 't_pendaftaran' )
                     ->select( DB::raw( 'count(id_pendaftaran) as count, id_poli' ) )
                     ->where( 'tgl_daftar', '>=', $start_date )
                     ->where( 'tgl_daftar', '<=', $end_date )
                     ->groupBy( 'id_poli' )
                     ->limit( 5 )
                     ->orderBy( 'count', 'desc' )
                     ->get();

                     $count = DB::table( 't_pendaftaran' )
                     ->select( DB::raw( 'count(id_pendaftaran) as count' ) )
                     ->where( 'tgl_daftar', '>=', $start_date )
                     ->where( 'tgl_daftar', '<=', $end_date )
                     ->first();

                    $i = 1;
                    foreach( $polis as $poli ):
                    	if( $i == 1 ){
                    		$class = ' progress-bar-success';
                    	}elseif( $i == 2 ){
                    		$class = ' progress-bar-info';
                    	}elseif( $i == 3 ){
                    		$class = ' progress-bar-warning';
                    	}elseif( $i == 4 ){
                    		$class = ' progress-bar-danger';
                    	}elseif( $i == 5 ){
                    		$class = '';
                    	}else{
                    		$class = '';
                    	}

                    	$width =  ( $poli->count / $count->count ) * 100;
			    ?>

				<span class="progress-label">{{ get_poli_name( $poli->id_poli ) }}</span>
				<div class="progress">
				  	<div class="progress-bar{{ $class }}" role="progressbar" aria-valuenow="{{ $poli->count }}" aria-valuemin="0" aria-valuemax="{{ $count->count }}" style="min-width: 20%; width: {{ $width }}%;">
				    {{ $poli->count }}
				  	</div>
				</div>

				<?php
						$i++;
					endforeach;
				?>
			</div>
		</div>
		<div class="col-xs-3 home-col home-col-3">
			<div class="home-chart">
				<h4><span>5</span> Diagnosa Tertinggi Periode <?php echo date( 'F Y' ); ?></h4>

				<?php
			    	$start_date = date( "Y-m-01 00:00:00" );
					$end_date = date( "Y-m-31 23:59:59" );
					
					$diagnosis = DB::table( 't_pemeriksaan_poli' )
                     ->select( DB::raw( 'count(id_pemeriksaan_poli) as count, iddiagnosa' ) )
                     ->where( 'created_at', '>=', $start_date )
                     ->where( 'created_at', '<=', $end_date )
                     ->where( 'iddiagnosa', '<>', NULL )
                     ->groupBy( 'iddiagnosa' )
                     ->limit( 5 )
                     ->orderBy( 'count', 'desc' )
                     ->get();

                     //debug_var($diagnosis);

                     $count = DB::table( 't_pemeriksaan_poli' )
                     ->select( DB::raw( 'count(id_pemeriksaan_poli) as count' ) )
                     ->where( 'created_at', '>=', $start_date )
                     ->where( 'created_at', '<=', $end_date )
                     ->first();

                    $i = 1;
                    foreach( $diagnosis as $d ):
                    	if( $i == 1 ){
                    		$class = ' progress-bar-success';
                    	}elseif( $i == 2 ){
                    		$class = ' progress-bar-info';
                    	}elseif( $i == 3 ){
                    		$class = ' progress-bar-warning';
                    	}elseif( $i == 4 ){
                    		$class = ' progress-bar-danger';
                    	}elseif( $i == 5 ){
                    		$class = '';
                    	}else{
                    		$class = '';
                    	}

                    	$width =  ( $d->count / $count->count ) * 100;

                    	$dd = App\Diagnosis::where( 'kode_diagnosa', '=', $d->iddiagnosa )->first();
			    ?>
				<span class="progress-label">{{  ( ( $dd && isset( $dd->nama_diagnosa ) ) ? $dd->nama_diagnosa : 'Tidak Terdiagnosa' ) }}</span>
				<div class="progress">
				  	<div class="progress-bar{{ $class }}" role="progressbar" aria-valuenow="{{ $d->count }}" aria-valuemin="0" aria-valuemax="{{ $count->count }}" style="min-width: 20%; width: {{ $width }}%;">
				    {{ $d->count }}
				  	</div>
				</div>
				<?php
						$i++;
					endforeach;
				?>

			</div>

			<div class="home-chart">
				<h4><span>5</span> Kunjungan Tertinggi per Departemen Periode <?php echo date( 'F Y' ); ?></h4>
				<?php
			    	$start_date = date( "Y-m-01 00:00:00" );
					$end_date = date( "Y-m-31 23:59:59" );
					
					$diagnosis = DB::table( 't_pemeriksaan_poli' )
                     ->select( DB::raw( 'count(id_pemeriksaan_poli) as count, nama_departemen' ) )
                     ->where( 'created_at', '>=', $start_date )
                     ->where( 'created_at', '<=', $end_date )
                     ->where( 'nama_departemen', '<>', '' )
                     ->where( 'nama_departemen', '<>', NULL )
                     ->where( 'nama_departemen', '<>', '-' )
                     ->groupBy( 'nama_departemen' )
                     ->limit( 5 )
                     ->orderBy( 'count', 'desc' )
                     ->get();

                     //debug_var($diagnosis);

                     $count = DB::table( 't_pemeriksaan_poli' )
                     ->select( DB::raw( 'count(id_pemeriksaan_poli) as count' ) )
                     ->where( 'created_at', '>=', $start_date )
                     ->where( 'created_at', '<=', $end_date )
                     ->first();

                    $i = 1;
                    foreach( $diagnosis as $d ):
                    	if( $i == 1 ){
                    		$class = ' progress-bar-success';
                    	}elseif( $i == 2 ){
                    		$class = ' progress-bar-info';
                    	}elseif( $i == 3 ){
                    		$class = ' progress-bar-warning';
                    	}elseif( $i == 4 ){
                    		$class = ' progress-bar-danger';
                    	}elseif( $i == 5 ){
                    		$class = '';
                    	}else{
                    		$class = '';
                    	}

                    	$width =  ( $d->count / $count->count ) * 100;
			    ?>
				<span class="progress-label">{{ $d->nama_departemen }}</span>
				<div class="progress">
				  	<div class="progress-bar" role="progressbar" aria-valuenow="{{ $d->count }}" aria-valuemin="0" aria-valuemax="{{ $count->count }}" style="min-width: 20%; width: {{ $width }}%;">
				    {{ $d->count }}
				  	</div>
				</div>
				<?php
						$i++;
					endforeach;
				?>
			</div>
		</div>
	</div>
</div>
<div id="legality" style="margin-top: 40px;">
	<div class="row" style="margin-bottom: 20px;">
		<div class="col-xs-6 home-cart">
			<h4>Legalitas yang Hampir Habis (< 6 bulan)</h4>

			<ul class="list-group">
				@php
					$results = DB::table( 't_legalitas' )
				                 ->select( DB::raw( 'DISTINCT nama_legalitas, COUNT( id_t_legalitas ) AS count' ) )
				                 ->orderBy( 'nama_legalitas', 'ASC' )
				                 ->groupBy( 'nama_legalitas' )
				                 ->where( 'status', '=', 2 )
				                 ->get();

				    foreach( $results as $r ) :
				@endphp
                <li class="list-group-item">{{ $r->nama_legalitas }} <a class="badge bg-pink" href="{{ url( 'legality-detail' ) }}?status=2&name={{ urlencode( $r->nama_legalitas ) }}">{{ $r->count }}</a></li>
                @php endforeach; @endphp
            </ul>
		</div>
		<div class="col-xs-6 home-cart">
			<h4>Legalitas yang Hampir Habis (< 3 bulan)</h4>

			<ul class="list-group">
               @php
					$results = DB::table( 't_legalitas' )
				                 ->select( DB::raw( 'DISTINCT nama_legalitas, COUNT( id_t_legalitas ) AS count' ) )
				                 ->orderBy( 'nama_legalitas', 'ASC' )
				                 ->groupBy( 'nama_legalitas' )
				                 ->where( 'status', '=', 3 )
				                 ->get();

				    foreach( $results as $r ) :
				@endphp
                <li class="list-group-item">{{ $r->nama_legalitas }} <a class="badge bg-pink" href="{{ url( 'legality-detail' ) }}?status=3&name={{ urlencode( $r->nama_legalitas ) }}">{{ $r->count }}</a></li>
                @php endforeach; @endphp
            </ul>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
<?php
	$date = date( 'j' );

	$label = ''; $data = ''; $periode = '';
	if( $date >= 1 && $date <= 7 ){
		// value 1
		$start_date = date( "Y-m-01 00:00:00" );
		$end_date = date( "Y-m-01 23:59:59" );
		$poli_reg_1 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-02 00:00:00" );
		$end_date = date( "Y-m-02 23:59:59" );
		$poli_reg_2 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-03 00:00:00" );
		$end_date = date( "Y-m-03 23:59:59" );
		$poli_reg_3 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-04 00:00:00" );
		$end_date = date( "Y-m-04 23:59:59" );
		$poli_reg_4 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-05 00:00:00" );
		$end_date = date( "Y-m-05 23:59:59" );
		$poli_reg_5 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-06 00:00:00" );
		$end_date = date( "Y-m-06 23:59:59" );
		$poli_reg_6 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-07 00:00:00" );
		$end_date = date( "Y-m-07 23:59:59" );
		$poli_reg_7 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();
	
		$periode = '1 - 7 ' . date( 'F Y' );
		$label = "{ x: 1, y: $poli_reg_1 },{ x: 2, y: $poli_reg_2 },{ x: 3, y: $poli_reg_3 },{ x: 4, y: $poli_reg_4 },{ x: 5, y: $poli_reg_5 },{ x: 6, y: $poli_reg_6 },{ x: 7, y: $poli_reg_7 }";
	}elseif( $date >= 8 && $date <= 14 ){
		$periode = '8 - 14 ' . date( 'F Y' );

		// value 1
		$start_date = date( "Y-m-08 00:00:00" );
		$end_date = date( "Y-m-08 23:59:59" );
		$poli_reg_1 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-09 00:00:00" );
		$end_date = date( "Y-m-09 23:59:59" );
		$poli_reg_2 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-10 00:00:00" );
		$end_date = date( "Y-m-10 23:59:59" );
		$poli_reg_3 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-11 00:00:00" );
		$end_date = date( "Y-m-11 23:59:59" );
		$poli_reg_4 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-12 00:00:00" );
		$end_date = date( "Y-m-12 23:59:59" );
		$poli_reg_5 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();


		$start_date = date( "Y-m-13 00:00:00" );
		$end_date = date( "Y-m-13 23:59:59" );
		$poli_reg_6 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-14 00:00:00" );
		$end_date = date( "Y-m-14 23:59:59" );
		$poli_reg_7 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();
	
		$label = "{ x: 8, y: $poli_reg_1 },{ x: 9, y: $poli_reg_2 },{ x: 10, y: $poli_reg_3 },{ x: 11, y: $poli_reg_4 },{ x: 12, y: $poli_reg_5 },{ x: 13, y: $poli_reg_6 },{ x: 14, y: $poli_reg_7 }";
	}elseif( $date >= 15 && $date <= 21 ){
		// value 1
		$start_date = date( "Y-m-15 00:00:00" );
		$end_date = date( "Y-m-15 23:59:59" );
		$poli_reg_1 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-16 00:00:00" );
		$end_date = date( "Y-m-16 23:59:59" );
		$poli_reg_2 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-17 00:00:00" );
		$end_date = date( "Y-m-17 23:59:59" );
		$poli_reg_3 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-18 00:00:00" );
		$end_date = date( "Y-m-18 23:59:59" );
		$poli_reg_4 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-19 00:00:00" );
		$end_date = date( "Y-m-19 23:59:59" );
		$poli_reg_5 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();


		$start_date = date( "Y-m-20 00:00:00" );
		$end_date = date( "Y-m-20 23:59:59" );
		$poli_reg_6 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-21 00:00:00" );
		$end_date = date( "Y-m-21 23:59:59" );
		$poli_reg_7 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$label = "{ x: 15, y: $poli_reg_1 },{ x: 16, y: $poli_reg_2 },{ x: 17, y: $poli_reg_3 },{ x: 18, y: $poli_reg_4 },{ x: 19, y: $poli_reg_5 },{ x: 20, y: $poli_reg_6 },{ x: 21, y: $poli_reg_7 }";
		$periode = '15 - 21 ' . date( 'F Y' );
	}elseif( $date >= 22 && $date <= 31 ){
		// value 1
		$start_date = date( "Y-m-22 00:00:00" );
		$end_date = date( "Y-m-22 23:59:59" );
		$poli_reg_1 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-23 00:00:00" );
		$end_date = date( "Y-m-23 23:59:59" );
		$poli_reg_2 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-24 00:00:00" );
		$end_date = date( "Y-m-24 23:59:59" );
		$poli_reg_3 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-25 00:00:00" );
		$end_date = date( "Y-m-25 23:59:59" );
		$poli_reg_4 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-26 00:00:00" );
		$end_date = date( "Y-m-26 23:59:59" );
		$poli_reg_5 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-27 00:00:00" );
		$end_date = date( "Y-m-27 23:59:59" );
		$poli_reg_6 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$start_date = date( "Y-m-28 00:00:00" );
		$end_date = date( "Y-m-28 23:59:59" );
		$poli_reg_7 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

		$is_leap_year = date( 'L' );
		$is_30_days = ( date( 't' ) == 30 ) ? true : false;
		$is_31_days = ( date( 't' ) == 31 ) ? true : false;

		if( $is_31_days ){
			$start_date = date( "Y-m-29 00:00:00" );
			$end_date = date( "Y-m-29 23:59:59" );
			$poli_reg_8 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

			$start_date = date( "Y-m-30 00:00:00" );
			$end_date = date( "Y-m-30 23:59:59" );
			$poli_reg_9 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

			$start_date = date( "Y-m-31 00:00:00" );
			$end_date = date( "Y-m-31 23:59:59" );
			$poli_reg_10 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

			$label = "{ x: 22, y: $poli_reg_1 },{ x: 23, y: $poli_reg_2 },{ x: 24, y: $poli_reg_3 },{ x: 25, y: $poli_reg_4 },{ x: 26, y: $poli_reg_5 },{ x: 27, y: $poli_reg_6 },{ x: 28, y: $poli_reg_7 },{ x: 29, y: $poli_reg_8 },{ x: 30, y: $poli_reg_9 },{ x: 31, y: $poli_reg_10 }";
			$periode = '22 - 31 ' . date( 'F Y' );
		}elseif( $is_30_days ){
			$start_date = date( "Y-m-29 00:00:00" );
			$end_date = date( "Y-m-29 23:59:59" );
			$poli_reg_8 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

			$start_date = date( "Y-m-30 00:00:00" );
			$end_date = date( "Y-m-30 23:59:59" );
			$poli_reg_9 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

			$label = "{ x: 22, y: $poli_reg_1 },{ x: 23, y: $poli_reg_2 },{ x: 24, y: $poli_reg_3 },{ x: 25, y: $poli_reg_4 },{ x: 26, y: $poli_reg_5 },{ x: 27, y: $poli_reg_6 },{ x: 28, y: $poli_reg_7 },{ x: 29, y: $poli_reg_8 },{ x: 30, y: $poli_reg_9 }";
			$periode = '22 - 30 ' . date( 'F Y' );
		}elseif( $is_leap_year ){
			$start_date = date( "Y-m-29 00:00:00" );
			$end_date = date( "Y-m-29 23:59:59" );
			$poli_reg_8 = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )->where( 'tgl_daftar', '<=', $end_date )->count();

			$label = "{ x: 22, y: $poli_reg_1 },{ x: 23, y: $poli_reg_2 },{ x: 24, y: $poli_reg_3 },{ x: 25, y: $poli_reg_4 },{ x: 26, y: $poli_reg_5 },{ x: 27, y: $poli_reg_6 },{ x: 28, y: $poli_reg_7 },{ x: 29, y: $poli_reg_8 }";
			$periode = '22 - 30 ' . date( 'F Y' );
		}else{
			$label = "{ x: 22, y: $poli_reg_1 },{ x: 23, y: $poli_reg_2 },{ x: 24, y: $poli_reg_3 },{ x: 25, y: $poli_reg_4 },{ x: 26, y: $poli_reg_5 },{ x: 27, y: $poli_reg_6 },{ x: 28, y: $poli_reg_7 }";
			$periode = '22 - 38 ' . date( 'F Y' );
		}
		
	}
	
?>
	$('#periode-name').text('Periode <?php echo $periode; ?>');

	$(function () {
		$("#chart-1").CanvasJSChart({
			axisX: {
				interval: 10
			},
			backgroundColor: null,
			axisY:{
			   lineColor: "#fff",
			   labelFontColor: "#fff",
			   labelFontSize: 14,
			   gridColor: "#fff"
			},
			axisX:{
			   lineColor: "#fff",
			   labelFontColor: "#fff",
			   labelFontSize: 14,
			},
			data: [
			{
				type: "line", //try changing to column, area
				lineColor: "#fff",
				dataPoints: [
					  {{ $label }}
				],
				toolTipContent: "{x}/{{ date( 'm' ) }} - {y} Kunjungan",
			}
			]
		});
	});

<?php
	$start_date = date( "Y-m-d 00:00:00" );
	$end_date = date( "Y-m-d 23:59:59" );

	$regs = App\PoliRegistration::where( 'tgl_daftar', '>=', $start_date )
									 ->where( 'tgl_daftar', '<=', $end_date )
									 ->get();
	$count_reg = count( $regs );
?>
	$("#chart-2").CanvasJSChart({
		theme: "theme2",
		backgroundColor: "#f5f6f7",
		legend:{
	        verticalAlign: "bottom",
	        horizontalAlign: "center",
	        fontSize: 12
	    },
		data: [
		{
			type: "doughnut",
			showInLegend: true,
			dataPoints: [
				{  y: {{ $count_reg }}, indexLabel: "{{$count_reg}}", legendText: "{{$count_reg}} Pendaftaran", }
			]
		}
		]
	});
});
<?php
	$ids = array();
	foreach ( $regs as $reg ) {
		$ids[] = $reg->id_pendaftaran;
	}

	$count_check = App\MedicalRecord::whereIn( 'id_pendaftaran_poli', $ids )->count();
?>
	$("#chart-3").CanvasJSChart({
		theme: "theme2",
		backgroundColor: "#f5f6f7",
		legend:{
	        verticalAlign: "bottom",
	        horizontalAlign: "center",
	        fontSize: 12
	    },
		data: [
		{
			type: "doughnut",
			showInLegend: true,
			dataPoints: [
				{  y: {{ $count_check }}, indexLabel: "{{$count_check}}", legendText: "{{$count_check}} Pemeriksaan", }
			]
		}
		]
	});
</script>
@stop