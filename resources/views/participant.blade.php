
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Peserta
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui.min.js')}}"></script>
@stop

@section('styles')
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.structure.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.theme.min.css')}}">
@stop

@section('content')
<div id="participant">
	<div class="content-title"><img src="{{URL::asset('assets/images/title-participant.png')}}" alt="Daftar Peserta" /></div>

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
			<form method="get" action="{{ route( 'participant.index' ) }}">
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
		<div class="table-wrapper" id="participant-wrapper">
			<table class="table table-bordered table-striped list-table" id="list-items">
				<thead>
					<tr>
						<th class="column-no-title">No.</th>
						<th class="column-code-title">ID Peserta</th>
						<th class="column-medrec-title">No. Medrec</th>
						<th class="column-nik-title">NIK</th>
						<th class="column-name-title">Nama Peserta</th>
						<th class="column-department-title">Unit Kerja</th>
						<th class="column-sex-title">Jenis Kelamin</th>
						<th class="column-active-date-title">Tanggal Aktif</th>
						<th class="column-nonactive-title">Tanggal Nok-Aktif</th>
						<th class="column-status-title">Status</th>
						<th class="column-action-title">Action</th>
					<tr>
				<thead>
				<tbody>
					<?php
						if( count( $datas ) ){ 
							
							foreach ( $datas as $participant ) {
					?>
					<tr class="item" id="item-{{ $participant->id_peserta }}">
						<td class="column-no">{{ $i }}</td>
						<td class="column-code">{{ $participant->kode_peserta }}</td>
						<td class="column-medrec">{{ $participant->no_medrec }}</td>
						<td class="column-nik">{{ $participant->nik_peserta }}</td>
						<td class="column-name">{{ $participant->nama_peserta }}</td>
						<td class="column-department">{{ get_department_name( $participant->id_departemen ) }}</td>
						<td class="column-sex">{{ $participant->jenis_kelamin }}</td>
						<td class="column-active-date">{{ $participant->tanggal_aktif }}</td>
						<td class="column-nonactive-date">{{ $participant->tanggal_nonaktif }}</td>
						<td class="column-status">{{ ( $participant->status_aktif == 1 ) ? 'Aktif' : 'Tidak Aktif' }}</td>
						<td class="column-action">
							<div class="action-item first">
								<a href="#" title="Edit" class="edit" data-id="{{ $participant->id_peserta }}" data-code="{{ $participant->kode_peserta }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
							</div>
							<div class="action-item last">
								<a href="#" title="Delete" class="delete" data-id="{{ $participant->id_peserta }}"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>
							</div>
						</td>
					<tr>
					<?php
								$i++;
							}
						}else{
					?>
					<tr class="no-data">
						<td colspan="11">Tidak ada data ditemukan.</td>
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

	                    <form id="add-item" action="{{ route( 'participant.store' ) }}" method="post" class="form-horizontal">
	                    	<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="code">Kode</label>
	                    		<div class="col-xs-7">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
	 			 						<input type="text" class="form-control required" placeholder="Kode" aria-describedby="code-icon" name="code" id="code" disabled />
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="name">Nama</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-file-text"></i></span>
	 			 						<input type="text" class="form-control required" placeholder="Nama Peserta" aria-describedby="name-icon" name="name" id="name" />
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="no_medrec">No. Medrec</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-medkit"></i></span>
	 			 						<input type="text" class="form-control required" placeholder="Nomor Medical Record" aria-describedby="name-icon" name="no_medrec" id="no_medrec" disabled />
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="nik_peserta">NIK Peserta</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-user"></i></span>
	 			 						<input type="text" class="form-control required" placeholder="NIK Peserta" aria-describedby="name-icon" name="nik_peserta" id="nik_peserta" />
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="id_departemen">Unit Kerja</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
	 			 						<select name="id_departemen" id="id_departemen" class="form-control required">
	 			 							<option value="">Pilih Unit Kerja</option>
	 			 							<?php foreach( $departments as $d ): ?>
	 			 							<option value="{{ $d->id_departemen }}">{{ $d->nama_departemen }}</option>
	 			 							<?php endforeach; ?>
	 			 						</select>
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="jenis_kelamin">Jenis Kelamin</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-mars"></i></span>
	 			 						<select name="jenis_kelamin" id="jenis_kelamin" class="form-control required">
	 			 							<option value="">Pilih Jenis Kelamin</option>
	 			 							<option value="laki-laki">Laki-Laki</option>
	 			 							<option value="perempuan">Perempuan</option>
	 			 						</select>
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="tempat_lahir">Tempat Lahir</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-hospital-o"></i></span>
	 			 						<input type="text" class="form-control required" placeholder="Tempat Lahir" aria-describedby="name-icon" name="tempat_lahir" id="tempat_lahir" />
									</div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="tanggal_lahir">Tanggal Lahir</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
	 			 						<input type="text" class="form-control required" placeholder="Tanggal Lahir" aria-describedby="name-icon" name="tanggal_lahir" id="tanggal_lahir" />
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="alamat">Alamat</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-building"></i></span>
	 			 						<input type="text" class="form-control" placeholder="Alamat Peserta" aria-describedby="name-icon" name="alamat" id="alamat" />
									</div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="kota">Kota</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
	 			 						<input type="text" class="form-control" placeholder="Kota" aria-describedby="name-icon" name="kota" id="kota" />
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="propinsi">Propinsi</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-street-view"></i></span>
	 			 						<select name="propinsi" id="propinsi" class="form-control">
	 			 							<option value="">Pilih Propinsi</option>
	 			 							<?php foreach( $provinces as $p ): ?>
	 			 							<option value="{{ $p->id_propinsi }}">{{ $p->nama_propinsi }}</option>
	 			 							<?php endforeach; ?>
	 			 						</select>
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="kodepos">Kode Pos</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
	 			 						<input type="text" class="form-control" placeholder="Kode Pos" aria-describedby="name-icon" name="kodepos" id="kodepos" />
									</div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="tanggal_aktif">Tanggal Aktif</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
	 			 						<input type="text" class="form-control required" placeholder="Tanggal Aktif" aria-describedby="name-icon" name="tanggal_aktif" id="tanggal_aktif" />
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="tanggal_nonaktif">Tanggal Non-Aktif</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
	 			 						<input type="text" class="form-control" placeholder="Tanggal Non-Aktif" aria-describedby="name-icon" name="tanggal_nonaktif" id="tanggal_nonaktif" disabled />
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="status_aktif">Status Aktif</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-exclamation-triangle"></i></span>
	 			 						<select name="status_aktif" id="status_aktif" class="form-control required">
	 			 							<option value="1">Aktif</option>
	 			 							<option value="0">Tidak Aktif</option>
	 			 						</select>
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="status_kawin">Status Kawin</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-info"></i></span>
	 			 						<select name="status_kawin" id="status_kawin" class="form-control">
	 			 							<option value="">Pilih Status Kawin</option>
	 			 							<option value="Belum Kawin">Belum Kawin</option>
	 			 							<option value="Kawin">Kawin</option>
	 			 							<option value="Janda">Janda</option>
	 			 							<option value="Duda">Duda</option>
	 			 						</select>
									</div>
									<div class="error-placement"></div>
	                    		</div>
	                    	</div>
	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="jumlah_anak">Jumlah Anak</label>
	                    		<div class="col-xs-10">
	                    			<div class="input-group">
	  									<span class="input-group-addon"><i class="fa fa-child"></i></span>
	 			 						<input type="text" class="form-control" placeholder="Jumlah Anak" aria-describedby="name-icon" name="jumlah_anak" id="jumlah_anak" />
									</div>
									<div class="error-placement"></div>
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
	                    	<input type="hidden" name="hidden-code" value="" id="hidden-code" />
	                    	<input type="hidden" name="state" value="add" id="state" />
	                    </form>
	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
	</div><!-- /.entry-content  -->
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#collapseThree').addClass('in');

	$('#tanggal_lahir, #tanggal_aktif, #tanggal_nonaktif').datepicker({
		dateFormat : 'yy-mm-dd',
		changeMonth : true,
      	changeYear : true,
      	yearRange: "-70:+0"
	});
	
	$('#add-item').validate({
		errorPlacement: function(error, element) { 
			var selector = $(element.context).attr('id');

     		error.appendTo($('#' + selector).parents('div[class*="col-xs"]').find('.error-placement'));
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
	       	$code = $('#code').val();
	       	$name = $('#name').val();
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
	               	$(form).find('input[type=text]').attr('disabled', true);
	            },      
	            complete: function() {
	            	$(form).find('input[type=text]').removeAttr('disabled');
	            	$('#code').attr('disabled', true);
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$(form).find('input[type=text]').val('');

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

		            		$html = '<tr class="item" id="item-' + json.id_peserta + '">\
								<td class="column-no">' + $count_item + '</td>\
								<td class="column-code">' + json.kode_peserta + '</td>\
								<td class="column-medrec">' + json.no_medrec + '</td>\
								<td class="column-nik">' + json.nik_peserta + '</td>\
								<td class="column-name">' + json.nama_peserta + '</td>\
								<td class="column-department">' + json.id_departemen + '</td>\
								<td class="column-sex">' + json.jenis_kelamin + '</td>\
								<td class="column-active-date">' + json.tanggal_aktif + '</td>\
								<td class="column-nonactive-date">' + json.tanggal_nonaktif  + '</td>\
								<td class="column-status">' + json.status_aktif  + '</td>\
								<td class="column-action">\
									<div class="action-item first">\
										<a href="#" title="Edit" data-id="' + json.id_peserta + '" data-code="' + json.kode_peserta + '" class="edit"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>\
									</div>\
									<div class="action-item last">\
										<a href="#" title="Delete" data-id="' + json.id_peserta + '" class="delete"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
									</div>\
								</td>\
							<tr>';

							$('#list-items tbody').append($html);

							setTimeout(function(){
								$('#modal-add-item').modal('hide');
							}, 1000);
						}else if($state == 'edit'){
							$('tr#item-' + $id).find('td.column-code').html(json.kode_peserta);
							$('tr#item-' + $id).find('td.column-medrec').html(json.no_medrec);
							$('tr#item-' + $id).find('td.column-nik').html(json.nik_peserta);
							$('tr#item-' + $id).find('td.column-code').html(json.kode_peserta);
							$('tr#item-' + $id).find('td.column-name').html(json.nama_peserta);
							$('tr#item-' + $id).find('td.column-department').html(json.id_departemen);
							$('tr#item-' + $id).find('td.column-sex').html(json.jenis_kelamin);
							$('tr#item-' + $id).find('td.column-active-date').html(json.tanggal_aktif);
							$('tr#item-' + $id).find('td.column-nonactive-date').html(json.tanggal_nonaktif);
							$('tr#item-' + $id).find('td.column-status').html(json.status_aktif);
							$('tr#item-' + $id).find('a.edit').attr('data-code',json.kode_peserta);

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

	$('#form-alert .close').click(function(){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');

		return false;
	});

	$('#modal-add-item').on('hidden.bs.modal', function(e){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');
		$('#add-item').find('input[type=text]').val('');
		$('#add-item').find('select').find('option').removeAttr('selected');
		$('#state').val('add');
		$('#id').val('');
		$('#tanggal_nonaktif').attr('disabled', true);
	}).on('shown.bs.modal', function(e){
		$id = $('#id').val();

		$('#tanggal_lahir','#tanggal_aktif','#tanggal_nonaktif').datepicker({
			'dateFormat' : 'yy-mm-dd'
		});

		if($id != ''){
			$('#tanggal_nonaktif').removeAttr('disabled');

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
	            	$('#code').val(json.kode_peserta);
					$('#nama_peserta').val(json.nama_peserta);
					$('#no_medrec').val(json.no_medrec);
					$('#nik_peserta').val(json.nik_peserta);
					$('#id_departemen').find('option').removeAttr('selected');
					$('#id_departemen').find('option[value="' + json.id_departemen + '"]').attr('selected', true);
					$('#jenis_kelamin').find('option').removeAttr('selected');
					$('#jenis_kelamin').find('option[value="' + json.jenis_kelamin + '"]').attr('selected', true);
					$('#tempat_lahir').val(json.tempat_lahir);
					$('#tanggal_lahir').val(json.tanggal_lahir);
					$('#alamat').val(json.alamat);
					$('#kota').val(json.kota);
					$('#propinsi').find('option').removeAttr('selected');
					$('#propinsi').find('option[value="' + json.propinsi + '"]').attr('selected', true);
					$('#kodepos').val(json.kodepos);
					$('#tanggal_aktif').val(json.tanggal_aktif);
					$('#tanggal_nonaktif').val(json.tanggal_nonaktif);
					$('#status_aktif').find('option').removeAttr('selected');
					$('#status_aktif').find('option[value="' + json.status_aktif + '"]').attr('selected', true);
					$('#status_kawin').find('option').removeAttr('selected');
					$('#status_kawin').find('option[value="' + json.status_kawin + '"]').attr('selected', true);
					$('#jumlah_anak').val(json.jumlah_anak);
					//$('#id_peserta').val(json.id_peserta);
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
	            url: $url + '/latest_medrec',
	            type: 'GET',
	            dataType: 'json',
	            beforeSend: function() {
	               	
	            },      
	            complete: function() {
	            	
	            },          
	            success: function(json) {
	            	$('#no_medrec').val(json.latest_medrec);
	            }
	        });

	        $('#tanggal_nonaktif').attr('disabled', true);
		}
	});

	$('#list-items .item .column-action a.edit').live('click', function(){
		$id = $(this).attr('data-id');
		$code = $(this).attr('data-code');
		$item = $('tr#item-' + $id);
		$name = $item.find('.column-name').html();

		$('#state').val('edit');
		$('#id').val($id);
		$('#hidden-code').val($code);
		$('#name').val($name);

		$('#modal-add-item').modal('show');

		return false;
	});

	$('#list-items .item .column-action a.delete').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$confirm = confirm('Anda yakin ingin menghapus peserta ini?');

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
		$action = '{{ route( 'participant.index' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s;

		window.location.href= $url;
	});
});
</script>
@stop