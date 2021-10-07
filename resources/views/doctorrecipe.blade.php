
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Resep Dokter
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
<div class="content-title"><h1>Data Resep Dokter</h1></div>

<div class="content-top-action clearfix full-width">
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
		<span style="margin-right:10px;">Filter</span>
		<select id="filter" name="filter">
			<option value="today"{{ selected( 'today', $filter, true ) }}>Resep Hari Ini</option>
			<option value="recorded"{{ selected( 'recorded', $filter, true ) }}>Sudah Direkam</option>
			<option value="not-recorded"{{ selected( 'not-recorded', $filter, true ) }}>Belum Direkam</option>
		</select>
	</div>
	

	<div class="search-wrapper">
		<form method="get" action="{{ url( 'doctor-recipe' ) }}">
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
					<th class="column-no-title">No.</th>
					<th class="column-medical-record-title">Poli</th>
					<th class="column-patient-id-title">ID Pasien</th>
					<th class="column-patient-nik-title">NIK Pasien</th>
					<th class="column-patient-name-title">Name Pasien</th>
					<th class="column-patient-sex-title">Jenis Kelamin</th>
					<th class="column-patient-age-title">Umur</th>
					<th class="column-date-title">Tanggal Berobat</th>
					<th class="column-action-title">Action</th>
				<tr>
			<thead>
			<tbody>
				<?php
					if( count( $datas ) ){
						
						foreach ( $datas as $data ) {
							$medrec = App\MedicalRecord::find( $data->id_pemeriksaan_poli ); 

							if( !$medrec ) continue;

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
		     	<a href="{{ $datas->url( $datas->currentPage() - 1 ) }}&rows={{ $rows }}&s={{ $s }}&filter={{ $filter }}" aria-label="Previous">
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
                    <a href="{{ $datas->url( $i ) }}&rows={{ $rows }}&s={{ $s }}&filter={{ $filter }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        </ul>
        <ul class="pagination right clearfix">
        	 @if ( $datas->currentPage() != $datas->lastPage() )
			<li class="pagination-item pagination-next{{ ( $datas->currentPage() == $datas->lastPage() ) ? ' disabled' : '' }}">
		      	<a href="{{ $datas->url( $datas->currentPage() + 1 ) }}&rows={{ $rows }}&s={{ $s }}&filter={{ $filter }}" aria-label="Next">
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

                    <form id="add-item" action="{{ route( 'doctor-recipe.store' ) }}" method="post" class="form-horizontal">
                    	<input name="_token" id="_token" type="hidden" value="{{ csrf_token() }}"/>

                    	<div id="medicine-allergic">
                    		<fieldset class="medicine-out-wrapper">
	                    		<legend>List Obat Resep</legend>

	                    		<div class="form-inline">
					    			<div class="form-group" style="margin-left:0px;margin-right:10px;">
										<input type="text" name="medicine-name-1" id="medicine-name-1" class="form-control" placeholder="Ketikan Nama/Kode Obat" />
					    			</div>
					    			<div class="form-group" style="margin-left:0px;margin-right:10px;">
										<input type="text" name="medicine-amount-1" id="medicine-amount-1" class="form-control" placeholder="Jumlah" />
					    			</div>
					    			<div class="form-group" style="margin-left:0px;margin-right:10px;vertical-align:top;">
										<input type="button" class="btn btn-save-medicine" id="btn-save-medicine-1" value="Tambahkan" style="vertical-align:top;" />
										<input type="hidden" name="medicine-id-1" id="medicine-id-1" value="" />
					    			</div>
					    		</div>

					    		<div class="table-wrapper no-margin full-width" class="list-allergic-medicine">
					    			<div class="alert hide" id="form-alert-1"><span id="form-message-1"></span> <a href="#" class="close">&times;</a></div>

									<table class="table table-bordered table-striped list-table" id="list-recipe-medicine">
										<thead>
											<tr>
												<th class="column-no-title">No.</th>
												<th class="column-group-title">Golongan Obat</th>
												<th class="column-code-title">Kode Obat</th>
												<th class="column-name-title">Nama Obat</th>
												<th class="column-name-title">Jumlah</th>
												<th class="column-action-title">Action</th>
											</tr>
										</thead>
										<tbody>
										
											<tr class="no-data">
												<td colspan="6">Tidak ada data ditemukan.</td>
											</tr>
										
										</tbody>
									</table>
								</div>
	                    	</fieldset>

	                    	<fieldset class="medicine-out-wrapper last">
	                    		<legend>List Obat Keluar</legend>

	                    		<div class="form-inline">
					    			<div class="form-group" style="margin-left:0px;margin-right:10px;">
										<input type="text" name="medicine-name-2" id="medicine-name-2" class="form-control" placeholder="Ketikan Nama/Kode Obat" />
					    			</div>
					    			<div class="form-group" style="margin-left:0px;margin-right:10px;">
										<input type="text" name="medicine-amount-2" id="medicine-amount-2" class="form-control" placeholder="Jumlah" />
					    			</div>
					    			<div class="form-group" style="margin-left:0px;margin-right:10px;vertical-align:top;">
										<input type="button" class="btn btn-save-medicine" id="btn-save-medicine-2" value="Tambahkan" style="vertical-align:top;" />
										<input type="hidden" name="medicine-id-2" id="medicine-id-2" value="" />
					    			</div>
					    		</div>

					    		<div class="table-wrapper no-margin full-width" class="list-allergic-medicine">
					    			<div class="alert hide" id="form-alert-2"><span id="form-message-2"></span> <a href="#" class="close">&times;</a></div>

									<table class="table table-bordered table-striped list-table" id="list-out-medicine">
										<thead>
											<tr>
												<th class="column-no-title">No.</th>
												<th class="column-group-title">Golongan Obat</th>
												<th class="column-code-title">Kode Obat</th>
												<th class="column-name-title">Nama Obat</th>
												<th class="column-name-title">Jumlah</th>
												<th class="column-action-title">Action</th>
											</tr>
										</thead>
										<tbody>
										
											<tr class="no-data">
												<td colspan="6">Tidak ada data ditemukan.</td>
											</tr>
										
										</tbody>
									</table>
								</div>
	                    	</fieldset>
                    	</div>
                    	
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

	$('#collapseSeven').addClass('in');

	$('#modal-add-item').on('hidden.bs.modal', function(e){
		$('#form-alert-1').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-alert-2').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message-1').html('');
		$('#form-message-2').html('');
		$('#add-item').find('input[type=text]').val('');
		$('#state').val('add');
		$('#id').val('');
	}).on('shown.bs.modal', function(e){
		$id = $('#id').val();

		$('#list-out-medicine tbody').html('');
		$('#list-recipe-medicine tbody').html('');

        $.ajax({
            url: 'doctor-recipe/' + $id,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                
            },      
            complete: function() {
                
            },          
            success: function(json) {
            	$('#list-recipe-medicine tbody').html(json.html);
            }
        });

        $.ajax({
            url: 'medicine-out/' + $id + '?search_by_recipe=1',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                
            },      
            complete: function() {
                
            },          
            success: function(json) {
            	$('#list-out-medicine tbody').html(json.html);
            }
        });

        var options_1 = {
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
	            data.val = $("#medicine-name-1").val();
	            return data;
	        },
	        requestDelay: 200,
	        list: {
	            maxNumberOfElements: 10,
	            onSelectItemEvent: function() {
	                var selectedItemValue = $("#medicine-name-1").getSelectedItemData();
	            },
	            onClickEvent: function() {
	                var selectedItemValue = $("#medicine-name-1").getSelectedItemData();
	            },
	            onHideListEvent: function() {
	                
	            },
	            onChooseEvent: function(){
	                var selectedItemValue = $("#medicine-name-1").getSelectedItemData();

	                $('#medicine-name-1').val(selectedItemValue.nama_obat);
	                $('#medicine-id-1').val(selectedItemValue.id_obat);
	            }
	        }
	    };

	    $("#medicine-name-1").easyAutocomplete(options_1);

	    var options_2 = {
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
	            data.val = $("#medicine-name-2").val();
	            return data;
	        },
	        requestDelay: 200,
	        list: {
	            maxNumberOfElements: 10,
	            onSelectItemEvent: function() {
	                var selectedItemValue = $("#medicine-name-2").getSelectedItemData();
	            },
	            onClickEvent: function() {
	                var selectedItemValue = $("#medicine-name-2").getSelectedItemData();
	            },
	            onHideListEvent: function() {
	                
	            },
	            onChooseEvent: function(){
	                var selectedItemValue = $("#medicine-name-2").getSelectedItemData();

	                $('#medicine-name-2').val(selectedItemValue.nama_obat);
	                $('#medicine-id-2').val(selectedItemValue.id_obat);
	            }
	        }
	    };

	    $("#medicine-name-2").easyAutocomplete(options_2);
	});

	$('#btn-save-medicine-1').click(function(){
    	$medicine_id = $('#medicine-id-1').val();
    	$amount = $('#medicine-amount-1').val();
    	$id = $('#id').val();

    	if($medicine_id == ''){
    		alert('Harap pilih obat yang akan ditambahkan ke list resep obat.');

    		return false;
    	}

    	if($amount == ''){
    		alert('Harap masukkan jumlah obat yang akan ditambahkan ke list resep obat.');

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
            		if($('#list-recipe-medicine tbody tr.item-' + json.id_obat).not('.hide').length > 0){
            			alert("Obat sudah ada di dalam list resep obat.");
            			return false;
            		}

            		$count_item = $('#list-recipe-medicine tbody tr.item').length;

            		$count_item++;
            		
            		$('#list-recipe-medicine tbody tr.no-data').remove();
            			
					$html = '<tr class="item item-' + json.id_obat + '">\
								<td class="column-no">' + $count_item + '</td>\
								<td class="column-group">' + json.jenis_obat + '</td>\
								<td class="column-code">' + json.kode_obat + '</td>\
								<td class="column-name">' + json.nama_obat + '</td>\
								<td class="column-amount">' + $amount + '</td>\
								<td class="column-action">\
									<a href="#" title="Delete" class="delete" data-id="' + json.id_obat + '"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
									<input type="hidden" name="medicine_id_1[]" value="' + json.id_obat + '" class="medicine_id_1" />\
									<input type="hidden" name="medicine_state_1[]" value="add" class="medicine_state_1" />\
									<input type="hidden" name="medicine_amount_1[]" value="' + $amount + '" class="medicine_amount_1" />\
								</td>\
							</tr>';


					$('#list-recipe-medicine tbody').append($html);

					$('#medicine-name-1').val('').focus();
	                $('#medicine-id-1').val('');
	                $('#medicine-amount-1').val('');


	                if($('#list-out-medicine tbody tr.item-' + json.id_obat).length > 0){
            			//alert("Obat sudah ada di dalam list resep obat.");
            			return false;
            		}

            		$count_item = $('#list-out-medicine tbody tr.item').length;

            		$count_item++;
            		
            		$('#list-out-medicine tbody tr.no-data').remove();
            			
					$html = '<tr class="item item-' + json.id_obat + '">\
								<td class="column-no">' + $count_item + '</td>\
								<td class="column-group">' + json.jenis_obat + '</td>\
								<td class="column-code">' + json.kode_obat + '</td>\
								<td class="column-name">' + json.nama_obat + '</td>\
								<td class="column-amount">' + $amount + '</td>\
								<td class="column-action">\
									<a href="#" title="Delete" class="delete" data-id="' + json.id_obat + '"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
									<input type="hidden" name="medicine_id_2[]" value="' + json.id_obat + '" class="medicine_id_2" />\
									<input type="hidden" name="medicine_state_2[]" value="add" class="medicine_state_2" />\
									<input type="hidden" name="medicine_amount_2[]" value="' + $amount + '" class="medicine_amount_2" />\
								</td>\
							</tr>';


					$('#list-out-medicine tbody').append($html);
            	}else{
            		alert(json.message);
            	}
            }
        });


    	return false
    });

	$('#btn-save-medicine-2').click(function(){
    	$medicine_id = $('#medicine-id-2').val();
    	$amount = $('#medicine-amount-2').val();
    	$id = $('#id').val();

    	if($medicine_id == ''){
    		alert('Harap pilih obat yang akan ditambahkan ke list obat keluar.');

    		return false;
    	}

    	if($amount == ''){
    		alert('Harap masukkan jumlah obat yang akan ditambahkan ke list obat keluar.');

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
            		if($('#list-out-medicine tbody tr.item-' + json.id_obat).not('.hide').length > 0){
            			alert("Obat sudah ada di dalam list resep obat.");
            			return false;
            		}

            		$count_item = $('#list-out-medicine tbody tr.item').length;

            		$count_item++;
            		
            		$('#list-out-medicine tbody tr.no-data').remove();
            			
					$html = '<tr class="item item-' + json.id_obat + '">\
								<td class="column-no">' + $count_item + '</td>\
								<td class="column-group">' + json.jenis_obat + '</td>\
								<td class="column-code">' + json.kode_obat + '</td>\
								<td class="column-name">' + json.nama_obat + '</td>\
								<td class="column-amount">' + $amount + '</td>\
								<td class="column-action">\
									<a href="#" title="Delete" class="delete" data-id="' + json.id_obat + '"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
									<input type="hidden" name="medicine_id_2[]" value="' + json.id_obat + '" class="medicine_id_2" />\
									<input type="hidden" name="medicine_state_2[]" value="add" class="medicine_state_2" />\
									<input type="hidden" name="medicine_amount_2[]" value="' + $amount + '" class="medicine_amount_2" />\
								</td>\
							</tr>';


					$('#list-out-medicine tbody').append($html);

					$('#medicine-name-2').val('').focus();
	                $('#medicine-id-2').val('');
	                $('#medicine-amount-2').val('');
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

	$('.row-select-wrapper select').change(function(){
		$rows = $('#rows').find('option:selected').val();
		$filter = $('#filter').find('option:selected').val();
		$page = $('#page').val();
		$s = $('#s').val();
		$action = '{{ url( 'doctor-recipe' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s + '&filter=' + $filter;

		window.location.href= $url;
	});

	$('#list-recipe-medicine .column-action a.delete').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('#list-recipe-medicine tr.item-' + $id);
		$item2 = $('#list-out-medicine tr.item-' + $id);

		$confirm = confirm('Anda yakin ingin menghapus obat dari daftar resep obat?');

		if($confirm){
			$item.find('.medicine_state_1').val('delete');
			$item.addClass('hide');

			$item2.find('.medicine_state_2').val('delete');
			$item2.addClass('hide');
		}

		return false;
	});

	$('#list-out-medicine .column-action a.delete').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('#list-out-medicine tr.item-' + $id);

		$confirm = confirm('Anda yakin ingin menghapus obat dari daftar obat keluar?');

		if($confirm){
			$item.find('.medicine_state_2').val('delete');
			$item.addClass('hide');
		}

		return false;
	});
});
</script>
@stop