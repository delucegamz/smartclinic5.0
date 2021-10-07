
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Observasi
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
<div class="content-title"><img src="{{URL::asset('assets/images/title-medical-record.png')}}" alt="Observasi" /></div>

<div class="content-top-action clearfix" style="width:100%">
	<div class="row-select-wrapper">
		<select id="rows" name="rows">
			<option value="10"{{ selected( 10, $rows, true ) }}>10</option>
			<option value="20"{{ selected( 20, $rows, true ) }}>20</option>
			<option value="50"{{ selected( 50, $rows, true ) }}>50</option>
			<option value="all"{{ selected( 'all', $rows, true ) }}>All</option>
		</select>
		<span>Row</span>
	</div>
	

	<div class="search-wrapper">
		<form method="get" action="{{ route( 'observation.index' ) }}">
			<div class="input-group">
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
						<th class="column-no-observation-title">No. Observasi</th>
						<th class="column-no-medrec-title">No. Pemeriksaan</th>
						<th class="column-date">Tanggal</th>
						<th class="column-nik-title">NIK</th>
						<th class="column-nama-pasien-title">Nama Pasien</th>
						<th class="column-umur-title">Umur</th>
						<th class="column-jenis-kelamin-title">Jenis Kelamin</th>
						<th class="column-unit-kerja-title">Unit Kerja</th>
						<th class="column-pabrik-title">Pabrik</th>
						<th class="column-perusahaan-title">Perusahaan</th>
						<th class="column-action-title">Action</th>
					<tr>
				<thead>
				<tbody>
					<?php
						if( count( $datas ) ){ 
							foreach ( $datas as $data ) { 
								$medrec = App\MedicalRecord::find( $data->id_pemeriksaan_poli );
								$poliregistration = App\PoliRegistration::find( $medrec->id_pendaftaran_poli );
					?>
					<tr class="item" id="item-{{ $data->id_observasi }}">
						<td class="column-no">{{ $i }}</td>
						<td class="column-no-observation">{{ $data->no_observasi }}</td>
						<td class="column-no-medrec"><a href="{{ url( 'medical-record/' . $data->id_pemeriksaan_poli ) }}">{{ $medrec->no_pemeriksaan_poli }}</a></td>
						<td class="column-date">{{ date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
						<td class="column-nik">{{ get_participant_nik( $data->id_peserta ) }}</td>
						<td class="column-nama-pasien">{{ get_participant_name( $data->id_peserta ) }}</td>
						<td class="column-umur">{{ get_participant_age( $data->id_peserta ) }}</td>
						<td class="column-jenis-kelamin">{{ get_participant_sex( $data->id_peserta ) }}</td>
						<td class="column-unit-kerja">{{ get_participant_department( $data->id_peserta ) }}</td>
						<td class="column-pabrik">{{ get_participant_factory( $data->id_peserta ) }}</td>
						<td class="column-perusahaan">{{ get_participant_client( $data->id_peserta ) }}</td>
						<td class="column-action">
							<div class="action-item first full-width">
								<a href="{{ url( 'observation/' . $data->id_observasi  ) }}" title="Lihat" class="edit" data-id="{{ $data->id_observasi }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
							</div>
						</td>
					<tr>
					<?php
								$i++;
							}
						}else{
					?>
					<tr class="no-data">
						<td colspan="12">Tidak ada data ditemukan.</td>
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
		     	<a href="{{ $datas->url( $datas->currentPage() - 1 ) }}&rows={{ $rows }}&s={{ $s }}" aria-label="Previous">
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
                    <a href="{{ $datas->url( $i ) }}&rows={{ $rows }}&s={{ $s }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        </ul>
        <ul class="pagination right clearfix">
        	 @if ( $datas->currentPage() != $datas->lastPage() )
			<li class="pagination-item pagination-next{{ ( $datas->currentPage() == $datas->lastPage() ) ? ' disabled' : '' }}">
		      	<a href="{{ $datas->url( $datas->currentPage() + 1 ) }}&rows={{ $rows }}&s={{ $s }}" aria-label="Next">
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

	$('#rows').change(function(){
		$rows = $(this).find('option:selected').val();
		$page = $('#page').val();
		$s = $('#s').val();
		$action = '{{ route( 'observation.index' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s;

		window.location.href= $url;
	});

});
</script>
@stop