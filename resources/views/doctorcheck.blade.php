
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Pemeriksaan Dokter
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui-timepicker-addon.js')}}"></script>
@stop

@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/easy-autocomplete.min.css')}}" />
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.structure.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.theme.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui-timepicker-addon.css')}}">
@stop

@section('content')

@if( isset( $_GET['submitted'] ) && $_GET['submitted'] == 'true' )
<div class="alert {{ ( $_GET['success'] ) ? 'alert-success' : 'alert-danger' }}">
    @if( $_GET['success'] )
    Rekam medis berhasil diperbarui.
    @else
    Telah terjadi kesalahan ketika memperbarui data rekam medis.
    @endif
    <a href="#" class="close" data-dismiss="alert">&times;</a>
</div>
@endif

<div class="content-title"><img src="{{URL::asset('assets/images/title-medical-record.png')}}" alt="Pemeriksaan Dokter" /></div>

<div class="content-top-action clearfix" style="width:100%">
	
	

	<div class="search-wrapper" style="float:none;width:100%;">
		<form method="get" action="{{ route( 'medical-record.index' ) }}">
			<div class="row-select-wrapper">
				<span style="margin-right:10px;">Row</span>
				<select id="rows" name="rows">
					<option value="10"{{ selected( 10, $rows, true ) }}>10</option>
					<option value="20"{{ selected( 20, $rows, true ) }}>20</option>
					<option value="50"{{ selected( 50, $rows, true ) }}>50</option>
					<option value="all"{{ selected( 'all', $rows, true ) }}>All</option>
				</select>
			</div>

			<div class="row-select-wrapper">
				<span style="margin-right:10px;">Poli</span>
				<select id="poli" name="poli">
					<option value="">Pilih Poli</option>
					@foreach( $polis as $p )
					<option value="{{ $p->id_poli }}"{{ selected( $p->id_poli, $poli, true ) }}>{{ $p->nama_poli }}</option>
					@endforeach
				</select>
			</div>

			<div class="row-select-wrapper">
				<span style="margin-right:10px;">Filter</span>
				<select id="filter" name="filter">
					<option value="pendaftaran-today"{{ selected( 'pendaftaran-today', $filter, true ) }}>Pendaftaran Hari Ini</option>
					<option value="belum-direkam"{{ selected( 'belum-direkam', $filter, true ) }}>Belum Direkam</option>
					<option value="tidak-direkam"{{ selected( 'tidak-direkam', $filter, true ) }}>Tidak Direkam</option>
					<option value="sudah-direkam"{{ selected( 'sudah-direkam', $filter, true ) }}>Sudah Direkam</option>
				</select>
			</div>

			<div class="row-select-wrapper date-from">
	            <span>From</span>
	            <input type="text" name="date-from" id="date-from" placeholder="Dari Tanggal" value="{{ $date_from }}" style="width:63px" />
	        </div>
	        <div class="row-select-wrapper date-to">
	            <span>To</span>
	            <input type="text" name="date-to" id="date-to" placeholder="Hingga Tanggal" value="{{ $date_to }}" style="width:63px" />
	        </div>

	        <div class="row-select-wrapper date-to">
	        	<input type="submit" value="GO" class="btn" />
	        </div>

			<div class="input-group" style="float:right;width:200px;">
				<input type="text" class="form-control" placeholder="Search" name="s" value="{{ $s }}" id="s" />
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit" id="search-button">&nbsp;</button>
				</span>
			</div><!-- /input-group -->
			<input type="hidden" name="rows" value="{{ $rows }}" />
			<input type="hidden" name="page" value="{{ $page }}" id="page" />
		</form>
	</div>
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
							foreach ( $datas as $poliregistration ) {
								$id_pemeriksaan = get_id_pemeriksaan( $poliregistration->id_pendaftaran, $poliregistration->id_peserta );
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
							<div class="action-item first full-width">
								<a href="{{ route( 'medical-record.index' ) }}/{{ $id_pemeriksaan }}" title="Lihat" class="edit" data-id="{{ $poliregistration->id_pendaftaran }}" data-code="{{ $poliregistration->kode_poli }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
							</div>
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

			<!-- <div class="add-item">
				<a href="#modal-add-item" class="add-item-link" data-toggle="modal" data-target="#modal-add-item">Tambah Item</a>
			</div> -->
		</div>
	</div>

	@if ( $rows != 'all' )
	<div class="navigation clearfix">
	@if ( $datas->lastPage() > 1 )
        <ul class="pagination left clearfix">
        	@if ( $datas->currentPage() != 1 )
			<li class="pagination-item pagination-prev{{ ( $datas->currentPage() == 1 ) ? ' disabled' : '' }}">
		     	<a href="{{ $datas->url( $datas->currentPage() - 1 ) }}&rows={{ $rows }}&poli={{ $poli }}&filter={{ $filter }}&s={{ $s }}&date-to={{ $date_to }}&date-from={{ $date_from }}" aria-label="Previous">
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
                    <a href="{{ $datas->url( $i ) }}&rows={{ $rows }}&poli={{ $poli }}&filter={{ $filter }}&s={{ $s }}&date-to={{ $date_to }}&date-from={{ $date_from }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        </ul>
        <ul class="pagination right clearfix">
        	 @if ( $datas->currentPage() != $datas->lastPage() )
			<li class="pagination-item pagination-next{{ ( $datas->currentPage() == $datas->lastPage() ) ? ' disabled' : '' }}">
		      	<a href="{{ $datas->url( $datas->currentPage() + 1 ) }}&rows={{ $rows }}&poli={{ $poli }}&filter={{ $filter }}&s={{ $s }}&date-to={{ $date_to }}&date-from={{ $date_from }}" aria-label="Next">
		        	<span aria-hidden="true">Next Page <i class="fa fa-chevron-right"></i></span>
		      	</a>
		    </li>
		    @endif
		</ul>
	@endif
	</div>
	@endif

	<div class="download-item hide" style="width:100%">
		<a href="#" class="btn">Download</a>
	</div>
</div><!-- /.entry-content  -->

<script type="text/javascript">
$(document).ready(function(){
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

	$('#collapseFive').addClass('in');

	$('#date-to, #date-from').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true,
        yearRange: '-60:+0'
    });

	// $('#rows').change(function(){
	// 	$rows = $(this).find('option:selected').val();
	// 	$poli = $('#poli').find('option:selected').val();
	// 	$filter = $('#filter').find('option:selected').val();
	// 	$page = $('#page').val();
	// 	$s = $('#s').val();
	// 	$action = '{{ route( 'medical-record.index' ) }}';

	// 	$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s + '&poli=' + $poli + '&filter=' + $filter;

	// 	window.location.href= $url;
	// });

	// $('#poli').change(function(){
	// 	$rows = $('#rows').find('option:selected').val();
	// 	$poli = $(this).find('option:selected').val();
	// 	$filter = $('#filter').find('option:selected').val();
	// 	$page = $('#page').val();
	// 	$s = $('#s').val();
	// 	$action = '{{ route( 'medical-record.index' ) }}';

	// 	$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s + '&poli=' + $poli + '&filter=' + $filter;

	// 	window.location.href= $url;
	// });

	// $('#filter').change(function(){
	// 	$filter = $('#filter').find('option:selected').val();
	// 	$rows = $('#rows').find('option:selected').val();
	// 	$poli = $('#poli').find('option:selected').val();
	// 	$page = $('#page').val();
	// 	$s = $('#s').val();
	// 	$action = '{{ route( 'medical-record.index' ) }}';

	// 	$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s + '&poli=' + $poli + '&filter=' + $filter;

	// 	window.location.href= $url;
	// });

});
</script>
@stop