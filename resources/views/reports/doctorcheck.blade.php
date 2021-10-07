
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Pemeriksaan
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui-timepicker-addon.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/chosen.jquery.js')}}"></script>
@stop

@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/easy-autocomplete.min.css')}}" />
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.structure.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.theme.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui-timepicker-addon.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap-chosen.css')}}">
@stop

@section('content')
<div class="content-top-action clearfix" style="width:100%">
    <form method="get" action="{{ url( 'report/registration' ) }}">
        <div class="row-select-wrapper">
            <span>From</span>
            <input type="text" name="date-from" id="date-from" placeholder="Dari Tanggal" value="{{ $date_from }}" />
        </div>

        <div class="row-select-wrapper">
            <span>To</span>
            <input type="text" name="date-to" id="date-to" placeholder="Hingga Tanggal" value="{{ $date_to }}" />
        </div>

        <div class="row-select-wrapper">
            <span>Row</span>
            <select id="rows" name="rows">
                <option value="10"{{ selected( 10, $rows, true ) }}>10</option>
                <option value="20"{{ selected( 20, $rows, true ) }}>20</option>
                <option value="50"{{ selected( 50, $rows, true ) }}>50</option>
                <option value="all"{{ selected( 'all', $rows, true ) }}>All</option>
            </select>
        </div>

        <div class="row-select-wrapper">
            <button class="btn" type="submit">GO</button>
        </div>
    </form>
</div>

<div class="entry-content">
	<div class="table-responsive" style="width:100%">
		<div class="table-wrapper" style="width:auto">
			<table class="table table-bordered table-striped list-table" id="list-items">
				<thead>
					<tr>
						<th class="column-no-title">No.</th>
						<th class="column-no-pendaftaran-title">No. Pendaftaran</th>
						<th class="column-waktu-pendaftaran-title">Waktu Pendaftaran</th>
						<th class="column-no-antrian-title">No. Antrian</th>
						<th class="column-nik-title">NIK</th>
						<th class="column-nama-pasien-title">Nama Pasien</th>
						<th class="column-umur-title">Umur</th>
						<th class="column-jenis-kelamin-title">Jenis Kelamin</th>
						<th class="column-unit-kerja-title">Unit Kerja</th>
						<th class="column-pabrik-title">Pabrik</th>
						<th class="column-perusahaan-title">Perusahaan</th>
						<th class="column-nama-poli-title">Nama Poli</th>
						<th class="column-catatan-title">Catatan</th>
						<th class="column-action-title">Action</th>
					<tr>
				<thead>
				<tbody>
					<?php 
						if( count( $datas ) ){
							//die_dump( $datas );
							foreach ( $datas as $poliregistration ) {
					?>
					<tr class="item" id="item-{{ $poliregistration->id_pendaftaran }}">
						<td class="column-no">{{ $i }}</td>
						<td class="column-no-pendaftaran">{{ $poliregistration->no_daftar }}</td>
						<td class="column-waktu-pendaftaran">{{ date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
						<td class="column-no-antrian">{{ $poliregistration->no_antrian }}</td>
						<td class="column-nik">{{ get_participant_nik( $poliregistration->id_peserta ) }}</td>
						<td class="column-nama-pasien">{{ get_participant_name( $poliregistration->id_peserta ) }}</td>
						<td class="column-umur">{{ get_participant_age( $poliregistration->id_peserta ) }}</td>
						<td class="column-jenis-kelamin">{{ get_participant_sex( $poliregistration->id_peserta ) }}</td>
						<td class="column-unit-kerja">{{ get_participant_department( $poliregistration->id_peserta ) }}</td>
						<td class="column-pabrik">{{ get_participant_factory( $poliregistration->id_peserta ) }}</td>
						<td class="column-perusahaan">{{ get_participant_client( $poliregistration->id_peserta ) }}</td>
						<td class="column-nama-poli">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
						<td class="column-catatan">{{ $poliregistration->catatan_pendaftaran }}</td>
						<td class="column-action">
							<a href="{{ url( 'medical-record' ) }}/{{ $poliregistration->id_pendaftaran }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
						</td>
					<tr>
					<?php
								$i++;
							}
						}else{
					?>
					<tr class="no-data">
						<td colspan="15">Tidak ada data ditemukan.</td>
					</tr>
					<?php		
						}
					?>
				</tbody>
			</table>
		</div>
	</div>

	@if ( $rows != 'all' )
	<div class="navigation clearfix">
	@if ( $datas->lastPage() > 1 )
        <ul class="pagination left clearfix">
        	@if ( $datas->currentPage() != 1 )
			<li class="pagination-item pagination-prev{{ ( $datas->currentPage() == 1 ) ? ' disabled' : '' }}">
		     	<a href="{{ $datas->url( $datas->currentPage() - 1 ) }}&rows={{ $rows }}&date-from={{ $date_from }}&date-to={{ $date_to }}" aria-label="Previous">
		        	<span aria-hidden="true"><i class="fa fa-chevron-left"></i> Prev Page</span>
		      	</a>
		    </li>
		    @endif
		</ul>
		
		<ul class="pagination center clearfix">
        @for ($i = 1; $i <= $datas->lastPage(); $i++)
            <?php
            $half_total_links = floor( 7 / 2 );
            $from = $datas->currentPage() - $half_total_links;
            $to = $datas->currentPage() + $half_total_links;
            if ( $datas->currentPage() < $half_total_links ) {
               $to += $half_total_links - $datas->currentPage();
            }
            if ( $datas->lastPage() - $datas->currentPage() < $half_total_links ) {
                $from -= $half_total_links - ( $datas->lastPage() - $datas->currentPage() ) - 1;
            }
            ?>
            @if ( $from < $i && $i < $to )
                <li class="pagination-item{{ ( $datas->currentPage() == $i ) ? ' active' : '' }}">
                    <a href="{{ $datas->url( $i ) }}&rows={{ $rows }}&date-from={{ $date_from }}&date-to={{ $date_to }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        </ul>
        <ul class="pagination right clearfix">
        	 @if ( $datas->currentPage() != $datas->lastPage() )
			<li class="pagination-item pagination-next{{ ( $datas->currentPage() == $datas->lastPage() ) ? ' disabled' : '' }}">
		      	<a href="{{ $datas->url( $datas->currentPage() + 1 ) }}&rows={{ $rows }}&date-from={{ $date_from }}&date-to={{ $date_to }}" aria-label="Next">
		        	<span aria-hidden="true">Next Page <i class="fa fa-chevron-right"></i></span>
		      	</a>
		    </li>
		    @endif
		</ul>
	@endif
	</div>
	@endif

	<div class="download-item" style="width:100%">
		<a href="{{ url( 'export/doctorcheck' ) }}?date_from={{ $date_from }}&date_to={{ $date_to }}" class="btn">Download</a>
	</div>
</div><!-- /.entry-content  -->

<script type="text/javascript">
$(document).ready(function(){
	$('#collapseSix').addClass('in');

    $('#date-to, #date-from').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true,
        yearRange: '-60:+0'
    });
});
</script>
@stop