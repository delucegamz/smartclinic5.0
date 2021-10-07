
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Laporan Obat Keluar
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
	<form method="get" action="{{ url( 'report/medicineout' ) }}">
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
					<th class="column-medout-title">No. Faktur</th>
					<th class="column-recipe-title">No. Resep</th>
					<th class="column-date-title">Tanggal Faktur</th>
					<th class="column-amount-title">Jumlah Pengeluaran</th>
					<th class="column-note-title">Catatan</th>
					<th class="column-action-title">Action</th>
				<tr>
			<thead>
			<tbody>
				<?php
					if( count( $datas ) ){ 
						
						foreach ( $datas as $data ) {
							
				?>
				<tr class="item" id="item-{{ $data->id_pengeluaran_obat }}">
					<td class="column-no">{{ $i }}</td>
					<td class="column-medout">{{ $data->no_pengeluaran_obat }}</td>
					<td class="column-recipe">{{ $data->id_resep ? get_recipe_no( $data->id_resep ) : '-' }}</td> 
					<td class="column-date">{{ $data->tanggal_pengeluaran_obat ? $data->tanggal_pengeluaran_obat : '-' }}</td>
					<td class="column-amount">{{ $data->jumlah_pengeluaran_obat ? $data->jumlah_pengeluaran_obat : 0 }}</td>
					<td class="column-note">{{ $data->catatan_pengeluaran_obat ? $data->catatan_pengeluaran_obat : '-' }}</td>
					<td class="column-action">
						<a href="#" title="Edit" class="edit" data-id="{{ $data->id_pengeluaran_obat }}" data-code="{{ $data->id_pengeluaran_obat }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
					</td>
				<tr>
				<?php
							$i++;
						}
					}else{
				?>
				<tr class="no-data">
					<td colspan="7">Tidak ada data ditemukan.</td>
				</tr>
				<?php		
					}
				?>
			</tbody>
		</table>
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
		<a href="{{ url( '/print/medicineout' ) }}?date-from={{ $date_from }}&date_to={{ $date_to }}" class="btn print" target="_blank">Print</a>
		<a href="{{ url( '/export/medicineout' ) }}?date-from={{ $date_from }}&date_to={{ $date_to }}" class="btn">Download</a>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
        <div class="modal-dialog" style="width:640px;">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                
                    <span id="modal-icon"></span>

                    <form id="add-item" action="{{ route( 'medicine-out.store' ) }}" method="post" class="form-horizontal">
                    	<input name="_token" id="_token" type="hidden" value="{{ csrf_token() }}"/>

                    	<div class="form-group">
                    		<label class="control-label col-xs-4" for="no_pengeluaran_obat">Kode</label>
                    		<div class="col-xs-8">
                    			<input type="text" name="no_pengeluaran_obat" id="no_pengeluaran_obat" class="form-control" disabled />
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-4" for="resep">Resep</label>
                    		<div class="col-xs-8">
                    			<input type="text" name="resep" id="resep" class="form-control" />
                    			<input type="hidden" name="id_resep" id="id_resep" value="" />
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-4" for="tanggal_pengeluaran_obat">Tanggal Obat Keluar</label>
                    		<div class="col-xs-8">
                    			<input type="text" name="tanggal_pengeluaran_obat" id="tanggal_pengeluaran_obat" class="form-control required" />
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-4" for="jumlah_pengeluaran_obat">Jumlah Obat</label>
                    		<div class="col-xs-8">
                    			<input type="text" name="jumlah_pengeluaran_obat" id="jumlah_pengeluaran_obat" class="form-control required" />
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-4" for="catatan_pengeluaran_obat">Catatan</label>
                    		<div class="col-xs-8">
                    			<textarea name="catatan_pengeluaran_obat" id="catatan_pengeluaran_obat" class="form-control"></textarea>
                    		</div>
                    	</div>

                    	<fieldset>
                    		<legend>Detail Pengeluaran</legend>

				    		<div id="medicine-allergic-list">

					    		<div class="table-wrapper no-margin full-width" id="list-allergic-medicine">
					    			<div class="alert hide" id="form-alert"><span id="form-message"></span> <a href="#" class="close">&times;</a></div>

									<table class="table table-bordered table-striped list-table" id="list-medicines">
										<thead>
											<tr>
												<th class="column-code-title">Kode Obat</th>
												<th class="column-name-title">Nama Obat</th>
												<th class="column-amount-title">Jumlah</th>
												<th class="column-action-title">Action</th>
											<tr>
										<thead>
										<tbody>
										
											<tr class="no-data">
												<td colspan="4">Tidak ada data ditemukan.</td>
											</tr>
										
										</tbody>
									</table>
								</div>

								
							</div>
                    	</fieldset>

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

	$('#date-to, #date-from').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true,
        yearRange: '-60:+0'
    });

	$('#collapseSix').addClass('in');

	$('#modal-add-item').on('hidden.bs.modal', function(e){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');
		$('#add-item').find('input[type=text]').val('');
		$('#state').val('add');
		$('#id').val('');
	}).on('shown.bs.modal', function(e){
		$id = $('#id').val();
		$state = $('#state').val();

		
		$.ajax({
            url: '{{ url( 'medicine-out' ) }}/' + $id,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                
            },      
            complete: function() {
                
            },          
            success: function(json) {
            	$('#no_pengeluaran_obat').val(json.no_pengeluaran_obat);
            	$('#resep').val(json.resep);
            	$('#id_resep').val(json.id_resep);
            	$('#tanggal_pengeluaran_obat').val(json.tanggal_pengeluaran_obat);
            	$('#jumlah_pengeluaran_obat').val(json.jumlah_pengeluaran_obat);
            	$('#catatan_pengeluaran_obat').val(json.catatan_pengeluaran_obat);
            	$('#add-item').find('input[type=text]').attr('disabled', true);
            	$('#add-item').find('textarea').attr('disabled', true);

            	if( json.html != '' ){
	            	$('#list-medicines tbody').html(json.html);
	            }
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