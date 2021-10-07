
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Laporan Resep Dokter
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
<div class="content-top-action clearfix full-width">
	<form method="get" action="{{ url( 'report/doctorrecipe' ) }}">
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
            <span>From</span>
            <input type="text" name="date-from" id="date-from" placeholder="Dari Tanggal" value="{{ $date_from }}" />
        </div>
        <div class="row-select-wrapper">
            <span>To</span>
            <input type="text" name="date-to" id="date-to" placeholder="Hingga Tanggal" value="{{ $date_to }}" />
        </div>
        <div class="row-select-wrapper">
            <button class="btn" type="submit">GO</button>
        </div>
	</form>
</div>

<div class="entry-content">
	<div class="table-wrapper full-width">
		<table class="table table-bordered table-striped list-table" id="list-items">
			<thead>
				<tr>
					<th class="column-no-title">No.</th>
					<th class="column-medical-record-title">Poli</th>
					<th class="column-patient-id-title">ID Pasien</th>
					<th class="column-patient-nik-title">NIK Pasien</th>
					<th class="column-patient-name-title">Name Pasien</th>
					<th class="column-patient-sex-title">Jenis Kelamin</th>
					<th class="column-patient-age-title">Umur</th>
					<th class="column-date-title">Tanggal Berobat</th>
					<th class="column-diagnosa-title">Diagnosa</th>
					<th class="column-action-title">Action</th>
				<tr>
			<thead>
			<tbody>
				<?php
					if( count( $datas ) ){ 
						
						foreach ( $datas as $data ) {
							$medrec = App\MedicalRecord::find( $data->id_pemeriksaan_poli );
							$poliregistration = App\PoliRegistration::find( $medrec->id_pendaftaran_poli );
							$participant = App\Participant::find( $medrec->id_peserta );
				?>
				<tr class="item" id="item-{{ $data->id_resep }}">
					<td class="column-no">{{ $i }}</td>
					<td class="column-medical-record">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
					<td class="column-patient-id">{{ $participant->kode_peserta }}</td>
					<td class="column-patient-nik">{{ $participant->nik_peserta }}</td>
					<td class="column-name">{{ $participant->nama_peserta }}</td>
					<td class="column-sex">{{ get_participant_sex( $participant->id_peserta ) }}</td>
					<td class="column-age">{{ get_participant_age( $participant->id_peserta ) }}</td>
					<td class="column-date">{{ date( 'd-m-Y', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
					<td class="column-diagnosa">{{ $medrec->diagnosa_dokter }}</td>
					<td class="column-action">
						<a href="#" title="Edit" class="edit" data-id="{{ $data->id_resep }}" data-code="{{ $data->id_resep }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
					</td>
				<tr>
				<?php
							$i++;
						}
					}else{
				?>
				<tr class="no-data">
					<td colspan="8">Tidak ada data ditemukan.</td>
				</tr>
				<?php		
					}
				?>
			</tbody>
		</table>

		<!--<div class="add-item">
			<a href="#modal-add-item" class="add-item-link" data-toggle="modal" data-target="#modal-add-item">Tambah Item</a>
		</div>-->
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

	<div class="download-item full-width">
		<a href="{{ url( '/print/doctorrecipe' ) }}?date-from={{ $date_from }}&date-to={{ $date_to }}" class="btn print" target="_blank">Print</a>
		<a href="{{ url( '/export/doctorrecipe' ) }}?date-from={{ $date_from }}&date-to={{ $date_to }}" class="btn">Download</a>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
        <div class="modal-dialog" style="width:640px;">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                
                    <span id="modal-icon"></span>

                    <form id="add-item" action="{{ route( 'doctor-recipe.store' ) }}" method="post" class="form-horizontal">
                    	<input name="_token" id="_token" type="hidden" value="{{ csrf_token() }}"/>

			    		<div id="medicine-allergic-list">

				    		<div class="table-wrapper no-margin full-width" id="list-allergic-medicine">
				    			<div class="alert hide" id="form-alert"><span id="form-message"></span> <a href="#" class="close">&times;</a></div>

								<table class="table table-bordered table-striped list-table" id="list-medicines">
									<thead>
										<tr>
											<th class="column-no-title">No.</th>
											<th class="column-group-title">Golongan Obat</th>
											<th class="column-code-title">Kode Obat</th>
											<th class="column-name-title">Nama Obat</th>
											<th class="column-name-title">Jumlah</th>
											<th class="column-action-title">Action</th>
										<tr>
									<thead>
									<tbody>
									
										<tr class="no-data">
											<td colspan="5">Tidak ada data ditemukan.</td>
										</tr>
									
									</tbody>
								</table>
							</div>
						</div>
						<input type="hidden" name="id" value="" id="id" />
                    	<input type="hidden" name="state" value="add" id="state" />
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div><!-- /.entry-content  -->
<style type="text/css">
#list-medicines .column-action-title,
#list-medicines .column-action{
	display: none;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        }
    });

	$('#collapseSix').addClass('in');

	$('#date-to, #date-from').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true,
        yearRange: '-60:+0'
    });

	$('#modal-add-item').on('hidden.bs.modal', function(e){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');
		$('#add-item').find('input[type=text]').val('');
		$('#state').val('add');
		$('#id').val('');
	}).on('shown.bs.modal', function(e){
		$id = $('#id').val();

		$('#list-medicines tbody').html('');

        $.ajax({
            url: '{{ url( 'doctor-recipe' ) }}/' + $id,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                
            },      
            complete: function() {
                
            },          
            success: function(json) {
            	$('#list-medicines tbody').html(json.html);
            }
        });
	});

	$('#list-items .item .column-action a.edit').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$('#state').val('edit');
		$('#id').val($id);

		$('#modal-add-item').modal('show');

		return false;
	});
});
</script>
@stop