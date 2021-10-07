
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Penggunaan Ambulance
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
<div class="content-title"><h1>Data Penggunaan Ambulance</h1></div>

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
	<div class="row-select-wrapper">
		<select id="view" name="view">
			<option value="out"{{ selected( 'out', $view, true ) }}>Ambulance Out</option>
			<option value="in"{{ selected( 'in', $view, true ) }}>Ambulance In</option>
		</select>
		<span>View</span>
	</div>
	

	<div class="search-wrapper">
		<form method="get" action="{{ route( 'ambulance.index' ) }}">
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
	<div class="table-wrapper full-width">
		<table class="table table-bordered table-striped list-table" id="list-items">
			<thead>
				<tr>
					<th class="column_no_title">No.</th>
					<th class="columnno_ambulance_out_title">No Ambulance Out</th>
					<th class="columnno_ambulance_in_title">No Ambulance In</th>
					<th class="column_tanggal_title">Tanggal</th>
					<th class="column_id_peserta_title">ID Peserta</th>
					<th class="column_nik_peserta_title">NIK Peserta</th>
					<th class="column-name_title">Nama Peserta</th>
					<th class="column_jam_datang_title">Jam Datang</th>
					<th class="column_jam_pulang_title">Jam Pulang</th>
					<th class="column_lokasi_penjemputan_title">Lokasi Penjemputan</th>
					<th class="column_lokasi_pengiriman_title">Lokasi Pengiriman</th>
					<th class="column_km_out_title">KM Out</th>
					<th class="column_km_in_title">KM In</th>
					<th class="column_driver_title">Driver</th>
					<th class="column_catatan_title">Catatan</th>
					<th class="column_action_title">Action</th>
				<tr>
			<thead>
			<tbody>
				<?php
					if( count( $ambulances ) ){ 
						
						foreach ( $ambulances as $ambulance ) {
				?>
				<tr class="item" id="item-{{ $ambulance['id_ambulance_out'] }}">
					<td class="column_no">{{ $i }}</td>
					<td class="columnno_ambulance_out">{{ $ambulance['no_ambulance_out'] }}</td>
					<td class="columnno_ambulance_in">{{ $ambulance['no_ambulance_in'] }}</td>
					<td class="column_tanggal">{{ $ambulance['tanggal'] }}</td>
					<td class="column_id_peserta">{{ get_participant_code( $ambulance['id_peserta'] ) }}</td>
					<td class="column_nik_peserta">{{ get_participant_nik( $ambulance['id_peserta'] ) }}</td>
					<td class="column-name">{{ get_participant_name( $ambulance['id_peserta'] ) }}</td>
					<td class="column_jam_datang">{{ $ambulance['jam_datang'] }}</td>
					<td class="column_jam_pulang">{{ $ambulance['jam_pulang'] }}</td>
					<td class="column_lokasi_penjemputan">{{ $ambulance['lokasi_penjemputan'] }}</td>
					<td class="column_lokasi_pengiriman">{{ $ambulance['lokasi_pengiriman'] }}</td>
					<td class="column_km_out">{{ $ambulance['km_out'] }}</td>
					<td class="column_km_in">{{ $ambulance['km_in'] }}</td>
					<td class="column_driver">{{ $ambulance['driver'] }}</td>
					<td class="column_catatan">{{ $ambulance['catatan'] }}</td>
					<td class="column-action">
						<div class="action-item first">
							<a href="#" title="Edit" class="edit" data-id="{{ $ambulance['id_ambulance_out'] }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
						</div>
						<div class="action-item last">
							<a href="#" title="Delete" class="delete" data-id="{{ $ambulance['id_ambulance_out'] }}"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>
						</div>
					</td>
				<tr>
				<?php
							$i++;
						}
					}else{
				?>
				<tr class="no-data">
					<td colspan="16">Tidak ada data ditemukan.</td>
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
		
		<ul class="pagination center clearfix full-width">
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

	<div class="download-item full-width hide">
		<a href="#" class="btn">Download</a>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
        <div class="modal-dialog ambulance">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                
                    <span id="modal-icon"></span>

                    <form id="add-item" action="{{ route( 'ambulance.store' ) }}" method="post" class="form-horizontal">
                    	<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    	
                    	<div class="row">
                    		<div class="col-xs-6" id="ambulance_out">
                    			<h4>Ambulance Out</h4>

                    			<div class="form-group">
		                    		<label class="control-label col-xs-4" for="no_ambulance_out">No Ambulance Out</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control required" placeholder="" name="no_ambulance_out" id="no_ambulance_out" disabled />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="nik_peserta">NIK Peserta</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control required" placeholder="" name="nik_peserta" id="nik_peserta" />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="nama_peserta">Nama Peserta</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control" placeholder="" name="nama_peserta" id="nama_peserta" disabled />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="nama_factory">Factory</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control" placeholder="" name="nama_factory" id="nama_factory" disabled />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="nama_departemen">Unit Kerja</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control" placeholder="" name="nama_departemen" id="nama_departemen" disabled />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="nama_client">Client</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control" placeholder="" name="nama_client" id="nama_client" disabled />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="lokasi_jemput">Lokasi Jemput</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control" placeholder="" name="lokasi_jemput" id="lokasi_jemput" />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="lokasi_kirim">Lokasi Kirim</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control" placeholder="" name="lokasi_kirim" id="lokasi_kirim" />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="tanggal_keluar">Tanggal</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control required" placeholder="" name="tanggal_keluar" id="tanggal_keluar" />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="km_out">KM Out</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control" placeholder="" name="km_out" id="km_out" />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="catatan">Catatan</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control" placeholder="" name="catatan" id="catatan" />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>
                    		</div>
                    		<div class="col-xs-6" id="ambulance_in">
                    			<h4>Ambulance In</h4>

                    			<div class="form-group">
		                    		<label class="control-label col-xs-4" for="no_ambulance_in">No Ambulance In</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control" placeholder="" name="no_ambulance_in" id="no_ambulance_in" disabled />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="km_in">KM In</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control" placeholder="" name="km_in" id="km_in" disabled />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="driver">Driver</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control required" placeholder="" name="driver" id="driver" disabled />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="tanggal_masuk">Tanggal Masuk</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control required" placeholder="" name="tanggal_masuk" id="tanggal_masuk" disabled />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-4" for="catatan_2">Catatan</label>
		                    		<div class="col-xs-8">
		 			 					<input type="text" class="form-control" placeholder="" name="catatan_2" id="catatan_2" disabled />
										<div class="error-placement"></div>
		                    		</div>
		                    	</div>
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
                    	<input type="hidden" name="id_peserta" value="" id="id_peserta" />
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

    var participant_suggestion = function(e){
        var code = (e.keyCode ? e.keyCode : e.which);

        if(code == 13) {
            e.preventDefault();

            var $value = $('#nik_peserta').val();

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
                                $('#nama_peserta').val(json.nama_peserta);
                                $('#id_peserta').val(json.id_peserta);
                                $('#nama_departemen').val(json.unit_kerja);
                                $('#nama_factory').val(json.pabrik);
                                $('#nama_client').val(json.perusahaan);
                                $("#nik_peserta").val(json.nik_peserta);
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
                                        var selectedItemValue = $("#nik_peserta").getSelectedItemData();
                                    },
                                    onClickEvent: function() {
                                        var selectedItemValue = $("#nik_peserta").getSelectedItemData();
                                    },
                                    onHideListEvent: function() {
                                        
                                    },
                                    onChooseEvent: function(){
                                        var selectedItemValue = $("#nik_peserta").getSelectedItemData();

                                       	$('#nama_peserta').val(selectedItemValue.nama_peserta);
		                                $('#id_peserta').val(selectedItemValue.id_peserta);
		                                $('#nama_departemen').val(selectedItemValue.unit_kerja);
		                                $('#nama_factory').val(selectedItemValue.pabrik);
		                                $('#nama_client').val(selectedItemValue.perusahaan);
                                        $("#nik_peserta").val(selectedItemValue.nik_peserta);

                                        $('#nik_peserta').unbind();
                                        $('#nik_peserta').keyup(function(a){
                                            participant_suggestion(a);
                                        });
                                    }
                                }
                            };

                            $("#nik_peserta").easyAutocomplete(options);

                            var b = jQuery.Event("keyup", { keyCode: 32, which: 32});
                            $("#nik_peserta").focus();
                            $("#nik_peserta").triggerHandler(b);
                            $("#nik_peserta").trigger('change');
                        }
                    }else{
                        alert('Data tidak ditemukan.');
                    }
                }
            });
        }
    }

	$('#collapseFive').addClass('in');
	
	$('#add-item').validate({
		errorPlacement: function(error, element) { 
			var selector = $(element.context).attr('id');

     		error.appendTo($('#' + selector).parent().find('.error-placement'));
   		},
		submitHandler: function(form) {
	       	$state = $('#state').val();
	       	$url = $(form).attr('action');
	       	$type = 'POST';
	       	$id = $('#id').val();
	       	$formData = $(form).serialize();

	       	if($state == 'add'){
	       	}else if($state == 'edit'){
		        $url = $url + '/' + $id;
		        $type = 'PATCH';
	       	}

		    $.ajax({
	            url: $url,
	            type: $type,
	            data: $formData,
	            dataType: 'json',
	            beforeSend: function() {
	               	
	            },      
	            complete: function() {
	            	
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$(form).find('input[type=text]').val('');

	            		if($state == 'add'){
		            		$count_item = $('#list-items tbody tr.item').length;

		            		if($count_item < 1){
		            			$('#list-items tbody tr.no-data').remove();
		            			$count_item = 1;
		            		}else{
		            			$count_item = parseInt($('#list-items tbody > tr.item').last().find('td.column-no').html());
		            			$count_item++;
		            		}

		            		$html = '<tr class="item" id="item-' + json.id_ambulance_out + '">\
										<td class="column_no">' + $count_item + '</td>\
										<td class="columnno_ambulance_out">' + json.ambulance_out + '</td>\
										<td class="columnno_ambulance_in">' + json.ambulance_in + '</td>\
										<td class="column_tanggal">' + json.tanggal + '</td>\
										<td class="column_id_peserta">' + json.kode_peserta + '</td>\
										<td class="column_nik_peserta">' + json.nik_peserta + '</td>\
										<td class="column_nama_peserta">' + json.nama_peserta + '</td>\
										<td class="column_jam_datang">' + json.jam_datang + '</td>\
										<td class="column_jam_pulang">' + json.jam_pulang + '</td>\
										<td class="column_lokasi_penjemputan">' + json.lokasi_penjemputan + '</td>\
										<td class="column_lokasi_pengiriman">' + json.lokasi_pengiriman + '</td>\
										<td class="column_km_out">' + json.km_out + '</td>\
										<td class="column_km_in">' + json.km_in + '</td>\
										<td class="column_driver">' + json.driver + '</td>\
										<td class="column_catatan">' + json.catatan + '</td>\
										<td class="column-action">\
											<div class="action-item first">\
												<a href="#" title="Edit" class="edit" data-id="' + json.id_ambulance_out + '"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>\
											</div>\
											<div class="action-item last">\
												<a href="#" title="Delete" class="delete" data-id="' + json.id_ambulance_out + '"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
											</div>\
										</td>\
									<tr>';

							$('#list-items tbody').append($html);

							alert(json.message);

							$('#modal-add-item').modal('hide');
						}else if($state == 'edit'){
							$count_item = $('tr#item-' + $id).find('.column_no').text();

							$html = '<td class="column_no">' + $count_item + '</td>\
									<td class="columnno_ambulance_out">' + json.ambulance_out + '</td>\
									<td class="columnno_ambulance_in">' + json.ambulance_in + '</td>\
									<td class="column_tanggal">' + json.tanggal + '</td>\
									<td class="column_id_peserta">' + json.kode_peserta + '</td>\
									<td class="column_nik_peserta">' + json.nik_peserta + '</td>\
									<td class="column_nama_peserta">' + json.nama_peserta + '</td>\
									<td class="column_jam_datang">' + json.jam_datang + '</td>\
									<td class="column_jam_pulang">' + json.jam_pulang + '</td>\
									<td class="column_lokasi_penjemputan">' + json.lokasi_penjemputan + '</td>\
									<td class="column_lokasi_pengiriman">' + json.lokasi_pengiriman + '</td>\
									<td class="column_km_out">' + json.km_out + '</td>\
									<td class="column_km_in">' + json.km_in + '</td>\
									<td class="column_driver">' + json.driver + '</td>\
									<td class="column_catatan">' + json.catatan + '</td>\
									<td class="column-action">\
										<div class="action-item first">\
											<a href="#" title="Edit" class="edit" data-id="' + json.id_ambulance_out + '"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>\
										</div>\
										<div class="action-item last">\
											<a href="#" title="Delete" class="delete" data-id="' + json.id_ambulance_out + '"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
										</div>\
									</td>';

							$('tr#item-' + $id).html($html);

							alert(json.message);
		            		
							$('#modal-add-item').modal('hide');
						}
	            	}else{
	            		alert(json.message);
	            	}
	            }
	        });

		    return false;
		}
	});

	$('#add-item').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
            e.preventDefault();
            return false;
        }
    });

	$('#modal-add-item').on('hidden.bs.modal', function(e){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');
		$('#add-item').find('input[type=text]').val('');
		$('#ambulance_in input[type=text]').attr('disabled', true);
		$('#state').val('add');
		$('#id').val('');
		$('#id_peserta').val('');
	}).on('shown.bs.modal', function(e){
		$id = $('#id').val();

		if($id != ''){
			$url = $('#add-item').attr('action');

			$.ajax({
	            url: $url + '/' + $id,
	            type: 'GET',
	            dataType: 'json',
	            beforeSend: function() {
	               	
	            },      
	            complete: function() {
	            	
	            },          
	            success: function(json) {
	            	$('#no_ambulance_out').val(json.no_ambulance_out);
					$('#nik_peserta').val(json.nik_peserta);
					$('#nama_peserta').val(json.nama_peserta);
					$('#nama_factory').val(json.nama_factory);
					$('#nama_departemen').val(json.nama_departemen);
					$('#nama_client').val(json.nama_client);
					$('#lokasi_jemput').val(json.lokasi_jemput);
					$('#lokasi_kirim').val(json.lokasi_kirim);
					$('#tanggal_keluar').val(json.tanggal_keluar);
					$('#km_out').val(json.km_out);
					$('#catatan').val(json.catatan);
					$('#no_ambulance_in').val(json.no_ambulance_in);
					$('#km_in').val(json.km_in);
					$('#driver').val(json.driver);
					$('#tanggal_masuk').val(json.tanggal_masuk);
					$('#catatan_2').val(json.catatan_2);
					$('#id_peserta').val(json.id_peserta);
	            }
	        });

	        $('#ambulance_in input[type=text]').not('#no_ambulance_in').removeAttr('disabled');
		}else{
			$url = $('#add-item').attr('action');

			$.ajax({
	            url: $url + '/latest_id',
	            type: 'GET',
	            dataType: 'json',
	            beforeSend: function() {
	               	
	            },      
	            complete: function() {
	            	
	            },          
	            success: function(json) {
	            	$('#no_ambulance_out').val(json.no_ambulance_out);
	            	$('#no_ambulance_in').val(json.no_ambulance_in);
	            }
	        });
		}

		$('#tanggal_masuk, #tanggal_keluar').datetimepicker({
	        dateFormat : 'yy-mm-dd',
	        timeFormat: "HH:mm:ss",
	    });

		$('#nik_peserta').keyup(function(e){
            participant_suggestion(e);
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

	$('#list-items .item .column-action a.delete').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$confirm = confirm('Anda yakin ingin menghapus penggunaan ambulance ini?');

		if($confirm){
			$url = $('#add-item').attr('action');
			$url = $url + '/' + $id;

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
		$view = $('#view').find('option:selected').val();
		$page = $('#page').val();
		$s = $('#s').val();
		$action = '{{ route( 'ambulance.index' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s + '&view=' + $view;

		window.location.href= $url;
	});

	$('#view').change(function(){
		$view = $(this).find('option:selected').val();
		$rows = $('#rows').find('option:selected').val();
		$page = $('#page').val();
		$s = $('#s').val();
		$action = '{{ route( 'ambulance.index' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s + '&view=' + $view;

		window.location.href= $url;
	});
});
</script>
@stop