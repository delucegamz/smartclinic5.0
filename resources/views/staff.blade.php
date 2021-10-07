
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Karyawan
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui-timepicker-addon.js')}}"></script>
@stop

@section('styles')
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.structure.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.theme.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui-timepicker-addon.css')}}">
@stop

@section('content')
<div class="content-title"><h1>Data Karyawan</h1></div>

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
	

	<div class="search-wrapper">
		<form method="get" action="{{ route( 'staff.index' ) }}">
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
					<th class="column-nik-title">NIK</th>
					<th class="column-name-title">Nama</th>
					<th class="column-sex-title">Jenis Kelamin</th>
					<th class="column-phone-title">No Telp.</th>
					<th class="column-position-title">Jabatan</th>
					<th class="column-action-title">Action</th>
				<tr>
			<thead>
			<tbody>
				<?php
					if( count( $datas ) ){ 
						
						foreach ( $datas as $staff ) {
				?>
				<tr class="item" id="item-{{ $staff->id_karyawan }}">
					<td class="column-no">{{ $i }}</td>
					<td class="column-nik">{{ $staff->nik_karyawan }}</td>
					<td class="column-name">{{ $staff->nama_karyawan }}</td>
					<td class="column-sex">{{ $staff->jenis_kelamin }}</td>
					<td class="column-phone">{{ ( $staff->no_telepon ? $staff->no_telepon : '-' ) }}</td>
					<td class="column-position">{{ ( $staff->id_jabatan ? get_job_title_name( $staff->id_jabatan ) : '-' ) }}</td>
					<td class="column-action">
						<a href="#" title="Edit" class="edit" data-id="{{ $staff->id_karyawan }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
						<!-- <div class="action-item first">
							<a href="#" title="Edit" class="edit" data-id="{{ $staff->id_karyawan }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
						</div>
						<div class="action-item last">
							<a href="#" title="Delete" class="delete" data-id="{{ $staff->id_karyawan }}"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>
						</div> -->
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

	<div id="pagination" class="full-width">
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
	</div>

	<div class="download-item hide">
		<a href="#" class="btn">Download</a>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                
                    <span id="modal-icon"></span>

                    <form id="add-item" action="{{ route( 'staff.store' ) }}" method="post" class="form-horizontal">
                    	<input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}"/>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="kode_karyawan">ID</label>
                    		<div class="col-xs-7">
 			 					<input type="text" class="form-control" placeholder="ID Karyawan" name="kode_karyawan" id="kode_karyawan" disabled />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="nik_karyawan">NIK</label>
                    		<div class="col-xs-7">
 			 					<input type="text" class="form-control" placeholder="NIK Karyawan" name="nik_karyawan" id="nik_karyawan" disabled />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="nama_karyawan">Nama</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control required" placeholder="Nama Karyawan" name="nama_karyawan" id="nama_karyawan" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="id_jabatan">Jabatan</label>
                    		<div class="col-xs-10">
 			 					<select class="form-control required" placeholder="Jabatan" name="id_jabatan" id="id_jabatan">
 			 						<option value="">- Pilih Jabatan -</option>
 			 						@if( $jobtitles )
 			 							@foreach( $jobtitles as $jt )
 			 								<option value="{{ $jt->id_jabatan }}">{{ $jt->nama_jabatan }}</option>
 			 							@endforeach
 			 						@endif
 			 					</select>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="jenis_kelamin">Jenis Kelamin</label>
                    		<div class="col-xs-10">
 			 					<select class="form-control required" placeholder="Jenis Kelamin" name="jenis_kelamin" id="jenis_kelamin">
 			 						<option value="Laki-Laki">Laki-Laki</option>
 			 						<option value="Perempuan">Perempuan</option>
 			 					</select>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="status_kawin">Status Pernikahan</label>
                    		<div class="col-xs-10">
 			 					<select class="form-control" placeholder="Status Pernikahan" name="status_kawin" id="status_kawin">
 			 						<option value="">- Pilih Status -</option>
 			 						<option value="Lajang">Lajang</option>
 			 						<option value="Menikah">Menikah</option>
 			 						<option value="Janda">Janda</option>
 			 						<option value="Duda">Duda</option>
 			 					</select>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="jumlah_anak">Jumlah Anak</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Jumlah Anak" name="jumlah_anak" id="jumlah_anak" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="t_badan">Tinggi Badan</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Tinggi Badan" name="t_badan" id="t_badan" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="b_badan">Berat Badan</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Berat Badan" name="b_badan" id="b_badan" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="tempat_lahir">Tempat Lahir</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Tempat Lahir" name="tempat_lahir" id="tempat_lahir" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="tanggal_lahir">Tanggal Lahir</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Tanggal Lahir" name="tanggal_lahir" id="tanggal_lahir" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="alamat">Alamat</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Alamat" name="alamat" id="alamat" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="kota">Kota</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Kota" name="kota" id="kota" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="propinsi">Propinsi</label>
                    		<div class="col-xs-10">
 			 					<select class="form-control" placeholder="Propinsi" name="propinsi" id="propinsi">
 			 						<option value="">- Pilih Propinsi -</option>
 			 						@if( $provinces )
 			 							@foreach( $provinces as $pv )
 			 								<option value="{{ $pv->id_propinsi }}">{{ $pv->nama_propinsi }}</option>
 			 							@endforeach
 			 						@endif
 			 					</select>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="kode_pos">Kode Pos</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Kode Pos" name="kode_pos" id="kode_pos" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="no_telepon">No. Telepon</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="No. Telepon" name="no_telepon" id="no_telepon" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="email">Email</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Email" name="email" id="email" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="agama">Agama</label>
                    		<div class="col-xs-10">
 			 					<select class="form-control" placeholder="Agama" name="agama" id="agama">
 			 						<option value="">- Pilih Agama -</option>
 			 						<option value="Islam">Islam</option>
 			 						<option value="Kristen Katolik">Kristen Katolik</option>
 			 						<option value="Kristen Protestan">Kristen Protestan</option>
 			 						<option value="Hindu">Hindu</option>
 			 						<option value="Budha">Budha</option>
 			 						<option value="Lainnya">Lainnya</option>
 			 					</select>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="bank">Bank</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Bank" name="bank" id="bank" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="no_rekening">No. Rekening</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Nomor Rekening" name="no_rekening" id="no_rekening" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="jenis_id">Jenis ID</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Jenis ID" name="jenis_id" id="jenis_id" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="no_id">No. ID</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="No. ID" name="no_id" id="no_id" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="no_kk">No. KK</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Nomor Kartu Keluarga" name="no_kk" id="no_kk" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="no_bpjs">No. BPJS</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="No. BPJS" name="no_bpjs" id="no_bpjs" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="no_jamsostek">No. Jamsostek</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="No. Jamsostek" name="no_jamsostek" id="no_jamsostek" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="status">Status</label>
                    		<div class="col-xs-10">
 			 					<select class="form-control" placeholder="Status" name="status" id="status">
 			 						<option value="1">Aktif</option>
 			 						<option value="0">Tidak Aktif</option>
 			 					</select>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<!-- <div class="form-group">
                    		<label class="control-label col-xs-2" for="foto_karyawan">Foto Karyawan</label>
                    		<div class="col-xs-10">
 			 					<input type="file" class="form-control" placeholder="Foto Karyawan" name="foto_karyawan" id="foto_karyawan" />
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

	$('#collapseFour').addClass('in');

	$('#tanggal_lahir').datepicker({
		dateFormat : 'yy-mm-dd',
        changeYear: true,
        changeMonth: true,
        yearRange: '-60:+0'
	});	
	
	$('#add-item').validate({
		errorPlacement: function(error, element) { 
			var selector = $(element.context).attr('id');

     		error.appendTo($('#' + selector).parents('div[class*="col-xs"]').find('.error-placement'));
   		},
		submitHandler: function(form) {
	       	$state = $('#state').val();
	       	$url = $(form).attr('action');
	       	$type = 'POST';
	       	$id = $('#id').val();
	       	$rows = $('#rows').val();
	       	$formData = $(form).serialize();
	       	$formData = $formData + '&rows=' + $rows;

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
	            		$(form).find('select').find('option').removeAttr('selected').eq(0).attr('selected',true);
	            		$('#s').val('');

	            		$('#form-message').html(json.message);
	            		$('#form-alert').addClass('alert-success').removeClass('hide');

	            		$('#list-items tbody').html(json.html);
	            		$('#pagination').html(json.pagination);

	            		$.ajax({
				            url: $(form).attr('action') + '/latest_id',
				            type: 'GET',
				            dataType: 'json',
				            beforeSend: function() {
				               	
				            },      
				            complete: function() {
				            	
				            },          
				            success: function(json) {
				            	$('#kode_karyawan').val(json.kode_karyawan);
				            	$('#nik_karyawan').val(json.nik_karyawan);
				            }
				        });

				        $('#state').val('add');
				        $('#id').val('');
	            	}else{
	            		$('#form-message').html(json.message);
	            		$('#form-alert').addClass('alert-danger').removeClass('hide');
	            	}
	            }
	        });

		    return false;
		}
	});

	$('#modal-add-item').on('hidden.bs.modal', function(e){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');
		$('#add-item').find('input[type=text]').val('');
		$('#add-item').find('select').find('option').removeAttr('selected').eq(0).attr('selected',true);
		$('#state').val('add');
		$('#id').val('');
	}).on('shown.bs.modal', function(e){
		$id = $('#id').val();
		$url = $('#add-item').attr('action');

		if($id != ''){
			$.ajax({
	            url: $url + '/' + $id,
	            type: 'GET',
	            dataType: 'json',
	            beforeSend: function() {
	               	
	            },      
	            complete: function() {
	            	
	            },          
	            success: function(json) {
	            	$('#kode_karyawan').val(json.staff.kode_karyawan); 
					$('#nik_karyawan').val(json.staff.nik_karyawan); 
					$('#nama_karyawan').val(json.staff.nama_karyawan); 
					$('#id_jabatan').val(json.staff.id_jabatan); 
					$('#jenis_kelamin').val(json.staff.jenis_kelamin); 
					$('#status_kawin').val(json.staff.status_kawin); 
					$('#jumlah_anak').val(json.staff.jumlah_anak); 
					$('#t_badan').val(json.staff.t_badan); 
					$('#b_badan').val(json.staff.b_badan); 
					$('#tempat_lahir').val(json.staff.tempat_lahir); 
					$('#tanggal_lahir').val(json.staff.tanggal_lahir);
					$('#alamat').val(json.staff.alamat);
					$('#kota').val(json.staff.kota);
					$('#propinsi').val(json.staff.provinsi);
					$('#kode_pos').val(json.staff.kode_pos);
					$('#no_telepon').val(json.staff.no_telepon);
					$('#email').val(json.staff.email);
					$('#agama').val(json.staff.agama);
					$('#bank').val(json.staff.bank);
					$('#no_rekening').val(json.staff.no_rekening);
					$('#jenis_id').val(json.staff.jenis_id);
					$('#no_id').val(json.staff.no_id);
					$('#no_kk').val(json.staff.no_KK);
					$('#no_bpjs').val(json.staff.no_bpjs);
					$('#no_jamsostek').val(json.staff.no_jamsostek);
					$('#status').val(json.staff.status);
	            }
	        });
		}else{
			$.ajax({
	            url: $url + '/latest_id',
	            type: 'GET',
	            dataType: 'json',
	            beforeSend: function() {
	               	
	            },      
	            complete: function() {
	            	
	            },          
	            success: function(json) {
	            	$('#kode_karyawan').val(json.kode_karyawan);
	            	$('#nik_karyawan').val(json.nik_karyawan);
	            }
	        });
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

		$confirm = confirm('Anda yakin ingin menghapus poli ini?');

		if($confirm){
			$url = $('#add-item').attr('action');
			$url = $url + '/' + $id;

			$.ajax({
	            url: $url,
	            type: 'delete',
	            data: {},
	            dataType: 'json',
	            beforeSend: function() {
	               
	            },      
	            complete: function() {
	            
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$('#s').val('');
	            		$('#rows').find('option').removeAttr('selected').eq(0).attr('selected', true);
	            		$('#list-items tbody').html(json.html);
	            		$('#pagination').html(json.pagination);
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
		$action = '{{ route( 'staff.index' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s;

		window.location.href= $url;
	});
});
</script>
@stop