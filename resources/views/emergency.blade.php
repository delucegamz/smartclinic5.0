
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Pendaftaran Poli
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
<div class="content-title"><img src="{{URL::asset('assets/images/title-igd.png')}}" alt="Daftar Poli" /></div>

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
		<form method="get" action="{{ route( 'poliregistration.index' ) }}">
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
                                if ( $poliregistration->catatan_pendaftaran == 11 ){
                                    $catatan = 'Umum';
                                }elseif ( $poliregistration->catatan_pendaftaran == 33 ){
                                    $catatan = 'Kecelakaan Lalu Lintas';
                                }elseif ( $poliregistration->catatan_pendaftaran == 22 ){
                                    $catatan = 'Kecelakaan Kerja';
                                }else{
                                    $catatan = '-';
                                }
                               
					?>
					<tr class="item" id="item-{{ $poliregistration->id_pendaftaran }}">
						<td class="column-no">{{ $i }}</td>
						<td class="column-no-pendaftaran">{{ $poliregistration->no_daftar }}</td>
						<td class="column-waktu-pendaftaran">{{ date( 'Y-m-d H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
						<td class="column-no-antrian">{{ $poliregistration->no_antrian }}</td>
						<td class="column-nik">{{ get_participant_nik( $poliregistration->id_peserta ) }}</td>
						<td class="column-nama-pasien">{{ get_participant_name( $poliregistration->id_peserta ) }}</td>
						<td class="column-umur">{{ get_participant_age( $poliregistration->id_peserta ) }}</td>
						<td class="column-jenis-kelamin">{{ get_participant_sex( $poliregistration->id_peserta ) }}</td>
						<td class="column-unit-kerja">{{ get_participant_department( $poliregistration->id_peserta ) }}</td>
						<td class="column-pabrik">{{ get_participant_factory( $poliregistration->id_peserta ) }}</td>
						<td class="column-perusahaan">{{ get_participant_client( $poliregistration->id_peserta ) }}</td>
						<td class="column-nama-poli">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
						<td class="column-catatan">{{ $catatan }}</td>
						<td class="column-action">
							<div class="action-item first">
								<a href="#" title="Edit" class="edit" data-id="{{ $poliregistration->id_pendaftaran }}" data-code="{{ $poliregistration->kode_poli }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
							</div>
							<div class="action-item last">
								<a href="#" title="Delete" class="delete" data-id="{{ $poliregistration->id_pendaftaran }}"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>
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

			<div class="add-item">
				<a href="#modal-add-item" class="add-item-link" data-toggle="modal" data-target="#modal-add-item">Tambah Item</a>
			</div>
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

	<div class="modal fade emergency" tabindex="-1" role="dialog" id="modal-add-item">
        <div class="modal-dialog" style="width:600px">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                
                    <span id="modal-icon"></span>

                    <form id="add-item" action="{{ route( 'poliregistration.store' ) }}" method="post" class="form-horizontal">
                    	<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="tanggal">Tanggal</label>
                    		<div class="col-xs-7">
            					<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
 			 						<input type="text" class="form-control" placeholder="Tanggal" aria-describedby="code-icon" name="tanggal" id="tanggal" value="" />
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="code">No. Pendaftaran</label>
                    		<div class="col-xs-7">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
 			 						<input type="text" class="form-control" placeholder="Kode" aria-describedby="code-icon" name="code" id="code" disabled />
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2">Catatan</label>
                    		<div class="col-xs-10">
                    			<div class="checbox-wrapper">
                                    <label>
                                        <i class="fa fa-dot-circle-o" data-action="11"></i> Umum
                                    </label>

                                    <label>
                                        <i class="fa fa-circle-thin" data-action="33"></i> Kecelakaan Lalu Lintas
                                    </label>

                                    <label>
                                        <i class="fa fa-circle-thin" data-action="22"></i> Kecelakaan Kerja
                                    </label>

                                    <input type="hidden" name="uraian" id="uraian" value="11" />
                                </div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="medrec">NIK/No Kartu</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-file-text"></i></span>
 			 						<input type="text" class="form-control required" placeholder="NIK/No Kartu" aria-describedby="name-icon" name="medrec" id="medrec" />
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="nama">Nama</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-user"></i></span>
 			 						<input type="text" class="form-control" placeholder="Nama Peserta" aria-describedby="name-icon" name="nama" id="nama" disabled />
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="age">Umur</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
 			 						<input type="text" class="form-control" placeholder="Umur Peserta" aria-describedby="name-icon" name="age" id="age" disabled />
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="sex">Jenis Kelamin</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-venus-mars"></i></span>
 			 						<select name="sex" id="sex" class="form-control" disabled>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="laki-laki">Laki-Laki</option>
                                        <option value="perempuan">Perempuan</option>
                                    </select>
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="department">Unit Kerja</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
 			 						<select name="department" id="department" class="form-control" disabled>
                                        <option value="">Pilih Unit Kerja</option>
                                        <?php foreach( $departments as $d ): ?>
                                        <option value="{{ $d->id_departemen }}" data-client="{{ get_client_name( $d->nama_client ) }}" data-factory="{{ get_factory_name( $d->nama_factory ) }}">{{ $d->nama_departemen }}</option>
                                        <?php endforeach; ?>
                                    </select>
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="factory">Pabrik</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-industry"></i></span>
 			 						<input type="text" class="form-control" placeholder="Pabrik" aria-describedby="name-icon" name="factory" id="factory" disabled />
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="client">Perusahaan</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-building"></i></span>
 			 						<input type="text" class="form-control" placeholder="Perusahaan" aria-describedby="name-icon" name="client" id="client" disabled />
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<!-- <div class="form-group">
                    		<label class="control-label col-xs-2" for="catatan">Catatan</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-comment"></i></span>
 			 						<textarea class="form-control" placeholder="Catatan" aria-describedby="name-icon" name="catatan" id="catatan" rows="3"></textarea>
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div> -->
                    	<div class="form-group last">
                    		<div class="col-xs-2">&nbsp;</div>
                    		<div class="col-xs-10">
                    			<input type="submit" value="Simpan" id="add-submit" class="btn" />

                    			<div class="alert hide" id="form-alert"><span id="form-message"></span> <a href="#" class="close">&times;</a></div>
                    		</div>
                    	</div>
                    	<input type="hidden" name="id" value="" id="id" />
                    	<input type="hidden" name="hidden-code" value="" id="hidden-code" />
                    	<input type="hidden" name="state" value="add" id="state" />
                        <input type="hidden" name="id_peserta" value="" id="id_peserta" />
                        <input type="hidden" name="poli" id="poli" value="1" />
                    </form>

                    <!-- <input type="text" value="" placeholder="NIK/No Kartu" name="card_no" id="card_no" class="form-control" /> -->
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

            var $value = $('#medrec').val();

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
                                $('#nama').val(json.nama_peserta);
                                $('#id_peserta').val(json.id_peserta);
                                $('#age').val(json.umur);
                                $('#sex').find('option[value="' + json.sex + '"]').attr('selected', true);
                                $('#department').find('option[value="' + json.department + '"]').attr('selected', true);
                                $('#factory').val(json.pabrik);
                                $('#client').val(json.perusahaan);
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
                                        var selectedItemValue = $("#medrec").getSelectedItemData();
                                    },
                                    onClickEvent: function() {
                                        var selectedItemValue = $("#medrec").getSelectedItemData();
                                    },
                                    onHideListEvent: function() {
                                        
                                    },
                                    onChooseEvent: function(){
                                        var selectedItemValue = $("#medrec").getSelectedItemData();

                                        $('#nama').val(selectedItemValue.nama_peserta);
                                        $('#id_peserta').val(selectedItemValue.id_peserta);
                                        $('#age').val(selectedItemValue.umur);
                                        $('#sex').find('option[value="' + selectedItemValue.sex + '"]').attr('selected', true);
                                        $('#department').find('option[value="' + selectedItemValue.department + '"]').attr('selected', true);
                                        $('#factory').val(selectedItemValue.pabrik);
                                        $('#client').val(selectedItemValue.perusahaan);
                                        $("#medrec").val(selectedItemValue.nik_peserta);

                                        $('#medrec').unbind();
                                        $('#medrec').keyup(function(a){
                                            participant_suggestion(a);
                                        });
                                    }
                                }
                            };

                            $("#medrec").easyAutocomplete(options);

                            var b = jQuery.Event("keyup", { keyCode: 32, which: 32});
                            $("#medrec").focus();
                            $("#medrec").triggerHandler(b);
                            $("#medrec").trigger('change');
                        }
                    }else{
                        if(json.success == 'resigned'){
                            alert(json.message);
                        }else if(json.success == 'notfound'){
                            alert(json.message);

                            var x = confirm( 'Apakah anda ingin menambahkan peserta ini sebagai peserta baru?' );

                            if( x ){
                                $('#nama').removeAttr('disabled');
                                $('#department').removeAttr('disabled')
                                $('#sex').removeAttr('disabled');
                                $('#age').removeAttr('disabled').datepicker({
                                    dateFormat : 'yy-mm-dd',
                                    changeMonth : true,
                                    changeYear : true,
                                    yearRange: "-70:+0"
                                });
                            }
                        }else{
                            alert(json.message);
                        }
                    }
                }
            });
        }
    }

	$('#collapseFive').addClass('in');

    $('#tanggal').datetimepicker({
        dateFormat : 'yy-mm-dd',
        timeFormat: "HH:mm:ss"
    });
	
	$("#add-item").submit(function(e) {
	    e.preventDefault();
	}).validate({
        errorPlacement: function(error, element) { 
            var selector = $(element.context).attr('id');

            error.appendTo($('#' + selector).parents('div[class*="col-xs"]').find('.error-placement'));
        },
		submitHandler: function(form) {
	       	$url = $(form).attr('action');
	       	$type = 'POST';
	       	$state = $('#state').val();
	       	$id = $('#id').val();
	       	$formData = $(form).serialize();
            $nik = $('#card_no').val();
            $formData = $formData + '&nik=' + $nik;

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
	               
	            },      
	            complete: function() {
	            	
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$(form).find('input[type=text]').val('');
	            		$(form).find('select').find('option').removeAttr('selected');

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

                            if(json.catatan == '11'){
                                $catatan = 'Umum';
                            }else if(json.catatan == '33'){
                                $catatan = 'Kecelakaan Lalu Lintas';
                            }else if(json.catatan == '22'){
                                $catatan = 'Kecelakaan Kerja';
                            }else{
                                $catatan = '-';
                            }

		            		$html = '<tr class="item" id="item-' + json.id_pendaftaran + '">\
								<td class="column-no">' + $count_item + '</td>\
								<td class="column-no-pendaftaran">' + json.no_daftar + '</td>\
								<td class="column-waktu-pendaftaran">' + json.tanggal_daftar + '</td>\
								<td class="column-no-antrian">' + json.no_antrian + '</td>\
								<td class="column-nik">' + json.nik_peserta + '</td>\
								<td class="column-nama-pasien">' + json.nama_peserta + '</td>\
								<td class="column-umur">' + json.umur_peserta + '</td>\
								<td class="column-jenis-kelamin">' + json.jenis_kelamin + '</td>\
								<td class="column-unit-kerja">' + json.unit_kerja + '</td>\
								<td class="column-pabrik">' + json.pabrik + '</td>\
								<td class="column-perusahaan">' + json.perusahaan + '</td>\
								<td class="column-nama-poli">' + json.nama_poli + '</td>\
								<td class="column-catatan">' + $catatan + '</td>\
								<td class="column-action">\
									<div class="action-item first">\
										<a href="#" title="Edit" data-id="' + json.id_pendaftaran + '" data-code="' + json.kode_poli + '" class="edit"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>\
									</div>\
									<div class="action-item last">\
										<a href="#" title="Delete" data-id="' + json.id_pendaftaran + '" class="delete"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
									</div>\
								</td>\
							<tr>';

							$('#list-items tbody').append($html);

                            $('#add-item').find('input[type=text]').val('');
                            $('#add-item').find('textarea').val('');
                            $('#add-item').find('select').find('option').removeAttr('selected');
                            $('#card_no').val('');
                            $('#nama').attr('disabled', true);
                            $('#state').val('add');
                            $('#id').val('');
                            $('#id_peserta').val('');

                            $.ajax({
                                url: $url + '/latest_id',
                                type: 'GET',
                                dataType: 'json',
                                beforeSend: function() {
                                    
                                },      
                                complete: function() {
                                    
                                },          
                                success: function(json) {
                                    $('#code').val(json.latest_id);
                                }
                            });

                            $.ajax({
                                url: $url + '/ordering_no',
                                type: 'GET',
                                dataType: 'json',
                                beforeSend: function() {
                                    
                                },      
                                complete: function() {
                                    
                                },          
                                success: function(json) {
                                    $('#no_antrian').val(json.latest_id);
                                }
                            });

                            $('#tanggal').datetimepicker("setDate", new Date());
						}else if($state == 'edit'){
                            if(json.catatan == '11'){
                                $catatan = 'Umum';
                            }else if(json.catatan == '33'){
                                $catatan = 'Kecelakaan Lalu Lintas';
                            }else if(json.catatan == '22'){
                                $catatan = 'Kecelakaan Kerja';
                            }else{
                                $catatan = '-';
                            }

                            $('tr#item-' + $id).find('td.column-no-antrian').html(json.no_antrian); // Nomor antrian
							$('tr#item-' + $id).find('td.column-waktu-pendaftaran').html(json.tanggal_daftar); // Nomor antrian
							$('tr#item-' + $id).find('td.column-nik').html(json.nik_peserta); // Nomor medrec
							$('tr#item-' + $id).find('td.column-nama-pasien').html(json.nama_peserta); // Nama Peserta
							$('tr#item-' + $id).find('td.column-umur').html(json.umur_peserta);// Umur Peserta
							$('tr#item-' + $id).find('td.column-jenis-kelamin').html(json.jenis_kelamin);// Jenis Kelamin
							$('tr#item-' + $id).find('td.column-unit-kerja').html(json.unit_kerja);// Unit Kerja
							$('tr#item-' + $id).find('td.column-pabrik').html(json.pabrik);// Pabrik
							$('tr#item-' + $id).find('td.column-perusahaan').html(json.perusahaan);// Perusahaan
							$('tr#item-' + $id).find('td.column-nama-poli').html(json.nama_poli);// Poli
							$('tr#item-' + $id).find('td.column-catatan').html($catatan);// Catatan

		            		setTimeout(function(){
								$('#modal-add-item').modal('hide');
							}, 1000);
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

    $('#add-item').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
            e.preventDefault();
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
        $('#add-item').find('select').find('option').removeAttr('selected');
        $('#card_no').val('');
        $('#nama').attr('disabled', true);
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
                    $('#tanggal').val(json.tanggal_daftar);
                    $('#code').val(json.no_daftar);
                    $('#no_antrian').val(json.no_antrian);
                    $('#medrec').val(json.nik_peserta);
                    $('#card_no').val(json.nik_peserta);
                    $('#nama').val(json.nama_peserta)
                    $('#age').val(json.umur_peserta)
                    $('#sex').val(json.jenis_kelamin);
                    $('#department').val(json.unit_kerja);
                    $('#factory').val(json.pabrik);
                    $('#client').val(json.perusahaan);
                    $('#poli').find('option[value="' + json.nama_poli + '"]').attr('selected', true);
                    $('#catatan').val(json.catatan);
                    $('#id_peserta').val(json.id_peserta);
                    $('#uraian').val(json.catatan);
                    $('.checbox-wrapper label i.fa').removeClass('fa-dot-circle-o').addClass('fa-circle-thin');
                    $('.checbox-wrapper label i.fa[data-action=' + json.catatan + ']').removeClass('fa-circle-thin').addClass('fa-dot-circle-o');
                }
            });
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
	            	$('#code').val(json.latest_id);
	            }
	        });

	        $.ajax({
	            url: $url + '/ordering_no',
	            type: 'GET',
	            dataType: 'json',
	            beforeSend: function() {
	               	
	            },      
	            complete: function() {
	            	
	            },          
	            success: function(json) {
	            	$('#no_antrian').val(json.latest_id);
	            }
	        });

            $('#tanggal').datetimepicker("setDate", new Date());
		}

        var options = {
            url: function(phrase) {
                $url = $('#add-item').attr('action');

                return $url + '/search_id_card';
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
                data.val = $("#medrec").val();
                return data;
            },
            requestDelay: 200,
            list: {
                maxNumberOfElements: 15,
                onSelectItemEvent: function() {
                    var selectedItemValue = $("#medrec").getSelectedItemData();
                },
                onClickEvent: function() {
                    var selectedItemValue = $("#medrec").getSelectedItemData();
                },
                onHideListEvent: function() {
                    
                },
                onChooseEvent: function(){
                    var selectedItemValue = $("#medrec").getSelectedItemData();

                    $('#nama').val(selectedItemValue.nama_peserta);
                    $('#id_peserta').val(selectedItemValue.id_peserta);
                    $('#age').val(selectedItemValue.umur);
                    $('#sex').val(selectedItemValue.jenis_kelamin);
                    $('#department').val(selectedItemValue.unit_kerja);
                    $('#factory').val(selectedItemValue.pabrik);
                    $('#client').val(selectedItemValue.perusahaan);
                },
                match: {
                    enabled: true
                }
            }
        };

        $('#medrec').keyup(function(e){
            participant_suggestion(e);
        });

        $('.checbox-wrapper label').click(function(){
            var $this = $(this),
                $fa = $(this).find('i.fa'),
                $val = $fa.attr('data-action');

            if($fa.hasClass('fa-circle-thin')){
                $('#uraian').val($val);
                $('.checbox-wrapper label i.fa').removeClass('fa-dot-circle-o').addClass('fa-circle-thin');
                $fa.removeClass('fa-circle-thin').addClass('fa-dot-circle-o');
            }

            return false;
        });
	});

	$('#list-items .item .column-action a.edit').live('click', function(){
		$id = $(this).attr('data-id');

		$('#state').val('edit');
		$('#id').val($id);

		$('#modal-add-item').modal('show');

		return false;
	});

	$('#list-items .item .column-action a.delete').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$confirm = confirm('Anda yakin ingin menghapus entri pendaftan ini?');

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
		$action = '{{ route( 'poliregistration.index' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s;

		window.location.href= $url;
	});
});
</script>
@stop