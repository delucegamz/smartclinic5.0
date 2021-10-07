
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Obat Masuk
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
<div class="content-title"><h1>Data Obat Masuk</h1></div>

<div class="content-top-action clearfix full-width">
	<div class="row-select-wrapper">
		<select id="rows" name="rows">
			<option value="10"{{ selected( 10, $rows, true ) }}>10</option>
			<option value="20"{{ selected( 20, $rows, true ) }}>20</option>
			<option value="50"{{ selected( 50, $rows, true ) }}>50</option>
			<option value="all"{{ selected( 'all', $rows, true ) }}>All</option>
		</select>
		<span>Row</span>
	</div>
</div>

<div class="entry-content">
	<div class="table-wrapper full-width">
		<table class="table table-bordered table-striped list-table" id="list-items">
			<thead>
				<tr>
					<th class="column-no-title">No.</th>
					<th class="column-facture-title">No Faktur</th>
					<th class="column-supplier-title">Nama Supplier</th>
					<th class="column-date-title">Tanggal Faktur</th>
					<th class="column-date-process-title">Tanggal Proses</th>
					<th class="column-amount-title">Jumlah Pembelian</th>
					<th class="column-price-title">Total Harga</th>
					<th class="column-action-title">Action</th>
				<tr>
			<thead>
			<tbody>
				<?php
					if( count( $datas ) ){ 
						
						foreach ( $datas as $data ) {
							
				?>
				<tr class="item" id="item-{{ $data->id_pembelian_obat }}">
					<td class="column-no">{{ $i }}</td>
					<td class="column-facture">{{ $data->no_pembelian_obat }}</td>
					<td class="column-supplier">{{ $data->idsupplier ? $data->idsupplier : '-' }}</td>
					<td class="column-date">{{ $data->tanggal_obat_masuk ? $data->tanggal_obat_masuk : '-' }}</td>
					<td class="column-date-process">{{ $data->tanggal_proses ? $data->tanggal_proses : '-' }}</td>
					<td class="column-amount">{{ $data->jumlah_pembelian ? $data->jumlah_pembelian : 0 }}</td>
					<td class="column-price">Rp. {{ $data->total_harga ? number_format( $data->total_harga, 0, ',', '.' ) : '-' }}</td>
					<td class="column-action">
						<a href="#" title="Edit" class="edit" data-id="{{ $data->id_pembelian_obat }}" data-code="{{ $data->id_pembelian_obat }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
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

		<div class="add-item">
			<a href="#modal-add-item" class="add-item-link" data-toggle="modal" data-target="#modal-add-item">Tambah Item</a>
		</div>
	</div>

	@if ( $rows != 'all' )
	<div class="navigation clearfix">
	@if ( $datas->lastPage() > 1 )
        <ul class="pagination left clearfix">
        	@if ( $datas->currentPage() != 1 )
			<li class="pagination-item pagination-prev{{ ( $datas->currentPage() == 1 ) ? ' disabled' : '' }}">
		     	<a href="{{ $datas->url( $datas->currentPage() - 1 ) }}&rows={{ $rows }}" aria-label="Previous">
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
                    <a href="{{ $datas->url( $i ) }}&rows={{ $rows }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        </ul>
        <ul class="pagination right clearfix">
        	 @if ( $datas->currentPage() != $datas->lastPage() )
			<li class="pagination-item pagination-next{{ ( $datas->currentPage() == $datas->lastPage() ) ? ' disabled' : '' }}">
		      	<a href="{{ $datas->url( $datas->currentPage() + 1 ) }}&rows={{ $rows }}" aria-label="Next">
		        	<span aria-hidden="true">Next Page <i class="fa fa-chevron-right"></i></span>
		      	</a>
		    </li>
		    @endif
		</ul>
	@endif
	</div>
	@endif

	<!--<div class="download-item hide">
		<a href="#" class="btn">Download</a>
	</div>-->

	<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
        <div class="modal-dialog" style="width:640px;">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                
                    <span id="modal-icon"></span>

                    <form id="add-item" action="{{ route( 'medicine-in.store' ) }}" method="post" class="form-horizontal">
                    	<input name="_token" id="_token" type="hidden" value="{{ csrf_token() }}"/>

                    	<div class="form-group">
                    		<label class="control-label col-xs-4" for="no_pembelian_obat">No. Faktur</label>
                    		<div class="col-xs-8">
                    			<input type="text" name="no_pembelian_obat" id="no_pembelian_obat" class="form-control" disabled />
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-4" for="supplier">Supplier</label>
                    		<div class="col-xs-8">
                    			<input type="text" name="supplier" id="supplier" class="form-control required" />
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-4" for="tanggal_obat_masuk">Tanggal Obat Masuk</label>
                    		<div class="col-xs-8">
                    			<input type="text" name="tanggal_obat_masuk" id="tanggal_obat_masuk" class="form-control required" />
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-4" for="jumlah_pembelian">Jumlah Obat</label>
                    		<div class="col-xs-8">
                    			<input type="text" name="jumlah_pembelian" id="jumlah_pembelian" class="form-control required" />
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-4" for="total_harga">Total Harga</label>
                    		<div class="col-xs-8">
                    			<input type="text" name="total_harga" id="total_harga" class="form-control required" />
                    		</div>
                    	</div>

                    	<fieldset>
                    		<legend>Detail Pembelian</legend>

                    		<div id="medicine-allergic" class="form-inline">
				    			<div class="form-group" style="margin-left:0px;margin-right:10px;">
									<input type="text" name="medicine-name" id="medicine-name" class="form-control" placeholder="Ketikan Nama/Kode Obat" />
				    			</div>
				    			<div class="form-group" style="margin-left:0px;margin-right:10px;">
									<input type="text" name="medicine-amount" id="medicine-amount" class="form-control" placeholder="Jumlah" />
				    			</div>
				    			<div class="form-group" style="margin-left:0px;margin-right:10px;vertical-align:top;">
									<input type="button" class="btn btn-save-medicine" id="btn-save-medicine" value="Tambahkan" style="vertical-align:top;" />
									<input type="hidden" name="medicine-id" id="medicine-id" value="" />
				    			</div>
				    		</div>

				    		<div id="medicine-allergic-list">

					    		<div class="table-wrapper no-margin full-width" id="list-allergic-medicine">
					    			<div class="alert hide" id="form-alert"><span id="form-message"></span> <a href="#" class="close">&times;</a></div>

									<table class="table table-bordered table-striped list-table" id="list-medicines">
										<thead>
											<tr>
												<th class="column-code-title">Kode Obat</th>
												<th class="column-name-title">Nama Obat</th>
												<th class="column-amount-title">Jumlah</th>
												<th class="column-price-title">Harga</th>
												<th class="column-total-title">Total</th>
												<th class="column-action-title">Action</th>
											<tr>
										<thead>
										<tbody>
										
											<tr class="no-data">
												<td colspan="6">Tidak ada data ditemukan.</td>
											</tr>
										
										</tbody>
									</table>
								</div>

								
							</div>
                    	</fieldset>

						<div class="form-submit" style="padding-top:20px;">
							<input type="submit" value="SIMPAN" class="btn btn-save-medicine-allergic" />
						</div>


                    	<input type="hidden" name="id" value="" id="id" />
                    	<input type="hidden" name="state" value="add" id="state" />
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div><!-- /.entry-content  -->

<script type="text/javascript">
$(document).ready(function(){
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        }
    });

    $('#tanggal_obat_masuk').datepicker({
        dateFormat : 'yy-mm-dd',
        changeYear: true,
        changeMonth: true,
        yearRange: '-10:+0'
    });

	$('#collapseSeven').addClass('in');

	$('#add-item').submit(function(e){
    	
    }).on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
            e.preventDefault();
            return false;
        }
    }).validate();

	$('#modal-add-item').on('hidden.bs.modal', function(e){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');
		$('#add-item').find('input[type=text]').val('');
		$('#state').val('add');
		$('#id').val('');
	}).on('shown.bs.modal', function(e){
		$id = $('#id').val();
		$state = $('#state').val();

		if($state == 'add'){
			$html = '<tr class="no-data">\
						<td colspan="6">Tidak ada data ditemukan.</td>\
					</tr>';

			$.ajax({
	            url: 'medicine-in/latest_id',
	            type: 'GET',
	            dataType: 'json',
	            beforeSend: function() {
	                
	            },      
	            complete: function() {
	                
	            },          
	            success: function(json) {
	            	$('#no_pembelian_obat').val(json.latest_id);
	            }
	        });

			$('#list-medicines tbody').html($html);
		}else if($state == 'edit'){
			$.ajax({
	            url: 'medicine-in/' + $id,
	            type: 'GET',
	            dataType: 'json',
	            beforeSend: function() {
	                
	            },      
	            complete: function() {
	                
	            },          
	            success: function(json) {
	            	$('#no_pembelian_obat').val(json.no_pembelian_obat);
	            	$('#supplier').val(json.idsupplier);
	            	$('#tanggal_obat_masuk').val(json.tanggal_obat_masuk);
	            	$('#jumlah_pembelian').val(json.jumlah_pembelian);
	            	$('#total_harga').val(json.total_harga);

	            	if( json.html != '' ){
		            	$('#list-medicines tbody').html(json.html);
		            }
	            }
	        });
		}

        var options = {
	        url: function(phrase) {
	            return '{{ route( 'medicine.index' ) }}/search_med_by_code_or_name';
	        },
	        getValue: function(element) {
	            return element.display_name;
	        },
	        ajaxSettings: {
	            dataType: "json",
	            method: "POST",
	            data: { val : '' }
	        },
	        preparePostData: function(data) {
	            data.val = $("#medicine-name").val();
	            return data;
	        },
	        requestDelay: 200,
	        list: {
	            maxNumberOfElements: 10,
	            onSelectItemEvent: function() {
	                var selectedItemValue = $("#medicine-name").getSelectedItemData();
	            },
	            onClickEvent: function() {
	                var selectedItemValue = $("#medicine-name").getSelectedItemData();
	            },
	            onHideListEvent: function() {
	                
	            },
	            onChooseEvent: function(){
	                var selectedItemValue = $("#medicine-name").getSelectedItemData();

	                $('#medicine-name').val(selectedItemValue.nama_obat);
	                $('#medicine-id').val(selectedItemValue.id_obat);
	            }
	        }
	    };

	    $("#medicine-name").easyAutocomplete(options);
	});

	$('#btn-save-medicine').click(function(){
    	$medicine_id = $('#medicine-id').val();
    	$amount = $('#medicine-amount').val();

    	if($medicine_id == ''){
    		alert('Harap pilih obat yang akan ditambahkan ke list pembelian obat.');

    		return false;
    	}

    	if($amount == ''){
    		alert('Harap masukkan jumlah obat yang akan ditambahkan ke list pembelian obat.');

    		return false;
    	}

    	$.ajax({
            url: '{{ url( 'medicine' ) }}/' + $medicine_id,
            type: 'GET',
            data: {},
            dataType: 'json',
            beforeSend: function() {
               	
            },      
            complete: function() {
            	
            },          
            success: function(json) {
            	if(json.success == 'true'){
            		if($('#list-medicines tbody tr#item-' + json.id_obat).not('.hide').length > 0){
            			alert("Obat sudah ada di dalam list pembelian obat.");
            			return false;
            		}

            		$count_item = $('#list-medicines tbody tr.item').length;
            		
            		$('#list-medicines tbody tr.no-data').remove();
            			
					$html = '<tr class="item" id="item-' + json.id_obat + '">\
								<td class="column-code">' + json.kode_obat + '</td>\
								<td class="column-name">' + json.nama_obat + '</td>\
								<td class="column-amount">' + $amount + '</td>\
								<td class="column-price">' + json.satuan + '</td>\
								<td class="column-total">' + ( $amount * json.satuan ) + '</td>\
								<td class="column-action">\
									<a href="#" title="Delete" class="delete" data-id="' + json.id_obat + '"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
									<input type="hidden" name="medicine_id[]" value="' + json.id_obat + '" class="medicine_id" />\
									<input type="hidden" name="medicine_state[]" value="add" class="medicine_state" />\
									<input type="hidden" name="medicine_amount[]" value="' + $amount + '" class="medicine_amount" />\
								</td>\
							<tr>';


					$('#list-medicines tbody').append($html);

					$('#medicine-name').val('').focus();
	                $('#medicine-id').val('');
	                $('#medicine-amount').val('');
            	}else{
            		alert(json.message);
            	}
            }
        });

    	return false
    });

	$('#list-items .item .column-action a.edit').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$('#state').val('edit');
		$('#id').val($id);

		$('#modal-add-item').modal('show');

		return false;
	});

	$('#rows').change(function(){
		$rows = $(this).find('option:selected').val();
		$page = $('#page').val();
		$s = $('#s').val();
		$action = '{{ url( 'medicine-in' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s;

		window.location.href= $url;
	});

	$('#list-medicines .column-action a.delete').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('#list-medicines tr#item-' + $id);

		$confirm = confirm('Anda yakin ingin menghapus obat dari daftar pembelian obat?');

		if($confirm){
			$item.addClass('hide');
			$item.find('.medicine_state').val('delete');
		}

		return false;
	});
});
</script>
@stop