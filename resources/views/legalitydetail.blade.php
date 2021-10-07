
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Pemilik Legalitas
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui.min.js')}}"></script>
@stop

@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/easy-autocomplete.min.css')}}" />
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.structure.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.theme.min.css')}}">
@stop

@section('content')
<div class="content-title"><h1>Pemilik Legalitas</h1></div>

<div class="content-top-action clearfix">
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
		<form method="get" action="{{ route( 'legality-detail.index' ) }}">
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
	<div class="table-wrapper">
		<table class="table table-bordered table-striped list-table" id="list-items">
			<thead>
				<tr>
					<th class="column-no-title">No.</th>
					<th class="column-name-title">Nama Legalitas</th>
					<th class="column-owner-title">Nama Pemilik</th>
					<th class="column-expired-title">Tanggal Expired</th>
					<th class="column-status-title">Status</th>
					<th class="column-desc-title">Keterangan</th>
					<th class="column-action-title">Action</th>
				<tr>
			<thead>
			<tbody>
				<?php
					if( count( $datas ) ){ 
						
						foreach ( $datas as $data ) {
				?>
				<tr class="item" id="item-{{ $data->id_t_legalitas }}">
					<td class="column-no">{{ $i }}</td>
					<td class="column-name">{{ $data->nama_legalitas }}</td>
					<td class="column-owner">{{ $data->nama_pemilik }}</td>
					<td class="column-expired">{{ $data->exp_legalitas }}</td>
					<td class="column-status" data-status="{{ $data->status }}">
					@php	
						switch( $data->status ){
							case '1' :
								echo 'Masih berlaku';
								break;
							case '2' :
								echo 'Sudah hampir habis (< 6 bulan)';
								break;
							case '3' :
								echo 'Sudah hampir habis (< 3 bulan)';
								break;
							case '4' :
								echo 'Habis masa berlaku';
								break;
						}
					@endphp
					</td>
					<td class="column-description">{{ $data->keterangan ? $data->keterangan : '-' }}</td>
					<td class="column-action">
						<div class="action-item first">
							<a href="#" title="Edit" class="edit" data-id="{{ $data->id_t_legalitas }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
						</div>
						<div class="action-item last">
							<a href="#" title="Delete" class="delete" data-id="{{ $data->id_t_legalitas }}"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>
						</div>
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

	<div class="download-item hide">
		<a href="#" class="btn">Download</a>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                
                    <span id="modal-icon"></span>

                    <form id="add-item" action="{{ route( 'legality-detail.store' ) }}" method="post" class="form-horizontal">
                    	<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="nama_legalitas">Nama Legalitas</label>
                    		<div class="col-xs-10">
 			 					<select name="nama_legalitas" id="nama_legalitas" class="form-control required">
 			 						<option value="">- Pilih Legalitas -</option>
 			 						@foreach( $legalities as $legality )
 			 						<option value="{{ $legality->nama_legalitas }}">{{ $legality->nama_legalitas }}</option>
 			 						@endforeach
 			 					</select>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="nama_pemilik">Nama Pemilik</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control required" placeholder="Nama Pemilik" name="nama_pemilik" id="nama_pemilik" />
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="exp_legalitas">Tanggal Expired</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control required" placeholder="Tanggal Expired Legalitas" name="exp_legalitas" id="exp_legalitas" />
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="status">Status</label>
                    		<div class="col-xs-10">
 			 					<select name="status" id="status" class="form-control required">
 			 						<option value="">- Pilih Status -</option>
 			 						<option value="1">Masih berlaku</option>
 			 						<option value="2">Sudah hampir habis (< 6 bulan)</option>
 			 						<option value="3">Sudah hampir habis (< 3 bulan)</option>
 			 						<option value="4">Habis masa berlaku</option>
 			 					</select>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="keterangan">Keterangan</label>
                    		<div class="col-xs-7">
 			 					<textarea class="form-control" placeholder="Keterangan" aria-describedby="code-icon" name="keterangan" id="keterangan"></textarea>
                    		</div>
                    	</div>
                    	<div class="form-group last">
                    		<div class="col-xs-2">&nbsp;</div>
                    		<div class="col-xs-10">
                    			<input type="submit" value="Simpan" id="add-submit" class="btn" />

                    			<div class="alert hide" id="form-alert"><span id="form-message"></span> <a href="#" class="close">&times;</a></div>
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

<script type="text/javascript">
$(document).ready(function(){
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

	$('#collapseOne').addClass('in');

	$('#exp_legalitas').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true,
        yearRange: '-20:+20'
    });

    var participant_suggestion = function(e){
        var code = (e.keyCode ? e.keyCode : e.which);

        if(code == 13) {
            e.preventDefault();

            var $value = $('#nama_pemilik').val();

            $.ajax({
                url: '{{ url( 'poliregistration/search_medrec' ) }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    value : $value
                },
                beforeSend: function() {
                    
                },      
                complete: function() {
                    
                },          
                success: function(json) {
                    if(json.success == 'true'){
                        if(json.type == 'single'){
                            if(json.status == 1){
                                $('#nama_pemilik').val(json.nama_peserta);
                            }else{
                                alert('Peserta dengan nik ' + json.nik_peserta + ' sudah tidak aktif.');
                            }
                        }else{
                            var options = {
                                getValue: function(element) {
                                    return element.display_name;
                                },
                                data: json.data,
                                requestDelay: 0,
                                list: {
                                    maxNumberOfElements: 15,
                                    onSelectItemEvent: function() {
                                        var selectedItemValue = $("#nama_pemilik").getSelectedItemData();
                                    },
                                    onClickEvent: function() {
                                        var selectedItemValue = $("#nama_pemilik").getSelectedItemData();
                                    },
                                    onHideListEvent: function() {
                                        
                                    },
                                    onChooseEvent: function(){
                                        var selectedItemValue = $("#nama_pemilik").getSelectedItemData();

                                        $('#nama_pemilik').val(selectedItemValue.nama_peserta);
                                 

                                        $('#nama_pemilik').unbind();
                                        $('#nama_pemilik').keyup(function(a){
                                            participant_suggestion(a);
                                        });
                                    }
                                }
                            };

                            $("#nama_pemilik").easyAutocomplete(options);

                            var b = jQuery.Event("keyup", { keyCode: 32, which: 32});
                            $("#nama_pemilik").focus();
                            $("#nama_pemilik").triggerHandler(b);
                            $("#nama_pemilik").trigger('change');
                        }
                    }else{
                        alert(json.message);
                    }
                }
            });
        }
    }

    $('#add-item').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
            e.preventDefault();
            return false;
        }
    });
	
	$('#add-item').validate({
		rules: {
			name : {
				required : true,
				maxlength: 30
			}
		},
		submitHandler: function(form) {
			$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('input[name="_token"]').val()
	            }
	        });

	       	$state = $('#state').val();
	       	$url = $(form).attr('action');
	       	$type = 'POST';
	       	$id = $('#id').val();
	       	$formData = $(form).serialize();

	       	if($state == 'edit'){
	       		$url = $url + '/' + $id;
		        $type = 'PATCH';
	       	}

		    $.ajax({
	            url: $url,
	            type: $type,
	            data: $formData,
	            dataType: 'json',
	            beforeSend: function() {
	               	//$(form).find('input[type=text]').attr('disabled', true);
	            },      
	            complete: function() {
	            	//$('#name').removeAttr('disabled');
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$(form).find('input[type=text]').val('');
	            		$(form).find('textarea').val('');
	            		$(form).find('select').val('');

	            		$('#form-message').html(json.message);
	            		$('#form-alert').addClass('alert-success').removeClass('hide');

	            		if($state == 'add'){
		            		$count_item = $('#list-items tbody tr.item').length;

		            		if($count_item < 1){
		            			$('#list-items tbody tr.no-data').remove();
		            			$count_item = 1;
		            		}else{
		            			$count_item = parseInt($('#list-items tbody > tr.item').last().find('td.column-no').html());
		            			$count_item++;
		            		}

		            		$html = '<tr class="item" id="item-' + json.id_t_legalitas + '">\
								<td class="column-no">' + $count_item + '</td>\
								<td class="column-name">' + json.nama_legalitas + '</td>\
								<td class="column-owner">' + json.nama_pemilik + '</td>\
								<td class="column-expired">' + json.exp_legalitas + '</td>\
								<td class="column-status" data-status="' + json.status + '">' + json.status_text + '</td>\
								<td class="column-description">' + json.keterangan + '</td>\
								<td class="column-action">\
									<div class="action-item first">\
										<a href="#" title="Edit" data-id="' + json.id_t_legalitas + '" class="edit"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>\
									</div>\
									<div class="action-item last">\
										<a href="#" title="Delete" data-id="' + json.id_t_legalitas + '" class="delete"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
									</div>\
								</td>\
							<tr>';

							$('#list-items tbody').append($html);
						}else if($state == 'edit'){
							$('tr#item-' + $id).find('td.column-name').html(json.nama_legalitas);
							$('tr#item-' + $id).find('td.column-owner').html(json.nama_pemilik);
							$('tr#item-' + $id).find('td.column-expired').html(json.exp_legalitas);
							$('tr#item-' + $id).find('td.column-status').html(json.status_text);
							$('tr#item-' + $id).find('td.column-status').data('status', json.status);
							$('tr#item-' + $id).find('td.column-description').html(json.keterangan);

		        			$('#state').val('add');
		        			$('#id').val('');
						}
	            	}else{
	            		$('#form-message').html(json.message);
	            		$('#form-alert').addClass('alert-danger').removeClass('hide');
	            	}
	            }
	        });

		    return false;
		}
	});

	$('#form-alert .close').click(function(){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');

		return false;
	});

	$('#modal-add-item').on('hidden.bs.modal', function(e){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');
		$('#add-item').find('input[type=text]').val('');
		$('#add-item').find('textarea').val('');
		$('#add-item').find('select').val('');
		$('#state').val('add');
		$('#id').val('');
	}).on('shown.bs.modal', function(e){
		$url = $('#add-item').attr('action');
		$state = $('#state').val();

		$('#nama_pemilik').keyup(function(e){
            participant_suggestion(e);
        });
	});

	$('#list-items .item .column-action a.edit').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);
		$nama_legalitas = $item.find('.column-name').html();
		$nama_pemilik = $item.find('.column-owner').html();
		$keterangan = $item.find('.column-description').html();
		$exp_legalitas = $item.find('.column-expired').html();
		$status = $item.find('.column-status').data('status');

		$('#state').val('edit');
		$('#id').val($id);
		
		$('#nama_pemilik').val($nama_pemilik);
		$('#nama_legalitas').find('option[value="' + $nama_legalitas + '"]').attr('selected', true);
		$('#keterangan').val($keterangan);
		$('#exp_legalitas').val($exp_legalitas);
		$('#status').find('option[value="' + $status + '"]').attr('selected', true);

		$('#modal-add-item').modal('show');

		return false;
	});

	$('#list-items .item .column-action a.delete').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$confirm = confirm('Anda yakin ingin menghapus legalitas ini?');

		if($confirm){
			$url = $('#add-item').attr('action');
			$url = $url + '/' + $id;

			$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('input[name="_token"]').val()
	            }
	        });

			$.ajax({
	            url: $url,
	            type: 'delete',
	            data: {
	            	id: $id
	            },
	            dataType: 'json',
	            beforeSend: function() {
	               
	            },      
	            complete: function() {
	            
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$item.remove();

	            		$count = $('#list-items tbody tr.item').length;
	            		if($count < 1){
	            			$html = '<tr class="no-data">\
					<td colspan="4">Tidak ada data ditemukan.</td>\
				</tr>';

							$('#list-items tbody').html($html);
	            		}

	            		alert(json.message);
	            	}else{
	            		alert(json.message);
	            	}
	            }
	        });
		}

		return false;
	});

	$('#rows').change(function(){
		$rows = $(this).find('option:selected').val();
		$page = $('#page').val();
		$s = $('#s').val();
		$action = '{{ route( 'jobtitle.index' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s;

		window.location.href= $url;
	});
});
</script>
@stop