
@extends( 'layouts.app' )

@section( 'page_title' )
Smart Clinic System - Data Client
@stop

@section( 'styles' )
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap-chosen.css')}}">
@stop

@section( 'scripts' )
<script type="text/javascript" src="{{URL::asset('assets/js/chosen.jquery.js')}}"></script>
@stop

@section( 'content' )
<div class="content-title"><img src="{{URL::asset('assets/images/title-client.png')}}" alt="Daftar Client" /></div>

<div class="entry-content">
	<form id="client-form" class="form-horizontal" action="{{ route( 'client.store' ) }}" method="post">
		<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
		<div class="form-group">
			<label class="control-label col-xs-2" for="name">Nama Client</label>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input type="text" name="name" id="name" class="form-control" placeholder="Nama Client" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2" for="address">Alamat Client</label>
			<div class="col-xs-5">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-building"></i></span>
					<textarea name="address" id="address" class="form-control" placeholder="Alamat Client" rows="3"></textarea>
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2">&nbsp;</label>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
					<select name="province" id="province" class="form-control chosen-select" data-placeholder="Pilih Propinsi">
						<option value="">- Pilih Propinsi -</option>
						<?php
							if( count( $provinces ) ) : 
								
								foreach ( $provinces as $province ) :
						?>
						<option value="{{ $province->nama_propinsi }}">{{ $province->nama_propinsi }}</option>
						<?php
								endforeach;
							endif;
						?>
					</select>
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2">&nbsp;</label>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-street-view"></i></span>
					<input type="text" name="city" id="city" class="form-control" placeholder="Kota" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2">&nbsp;</label>
			<div class="col-xs-2">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					<input type="text" name="zip_code" id="zip_code" class="form-control" placeholder="Kode Pos" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2">No. Telepon</label>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-phone"></i></span>
					<input type="text" name="phone_1" id="phone_1" class="form-control" placeholder="No Telepon 1" />
				</div>
				<div class="error-placement"></div>
			</div>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-phone"></i></span>
					<input type="text" name="phone_2" id="phone_2" class="form-control" placeholder="No Telepon 2" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2" for="fax">Fax</label>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-fax"></i></span>
					<input type="text" name="fax" id="fax" class="form-control" placeholder="Fax" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2" for="email">Email</label>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
					<input type="text" name="email" id="email" class="form-control" placeholder="Email" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-2">&nbsp;</div>
			<div class="col-xs-8">
				<div class="alert hide" id="form-alert"><span id="form-message"></span> <a href="#" class="close">&times;</a></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2">&nbsp;</label>
			<div class="col-xs-8">
				<input type="submit" name="submit" value="Tambah" class="btn form-control btn-add" id="btn-add" />  
			</div>
		</div>
		<div class="form-group last">
			<label class="control-label col-xs-2">&nbsp;</label>
			<div class="col-xs-8">
				<input type="submit" name="submit" value="Simpan" class="btn form-control btn-save" id="btn-save" disabled />  
			</div>
		</div>
		<input type="hidden" name="id" value="" id="id" />
    	<input type="hidden" name="state" value="add" id="state" />
	</form>

	<div class="row">
		<div class="col-sm-12 col-md-8 col-md-offset-2">
			<div class="table-wrapper no-margin full-width">
				<table class="table table-bordered table-striped list-table" id="list-items">
					<thead>
						<tr>
							<th class="column-no-title">No.</th>
							<th class="column-code-title" style="width:80px;">ID Client</th>
							<th class="column-name-title">Nama Client</th>
							<th class="column-address-title">Alamat</th>
							<th class="column-action-title">Action</th>
						<tr>
					<thead>
					<tbody>
						<?php
							if( count( $datas ) ){ 
								
								foreach ( $datas as $client ) {
						?>
						<tr class="item" id="item-{{ $client->id_client }}">
							<td class="column-no">{{ $i }}</td>
							<td class="column-code">{{ $client->kode_client }}</td>
							<td class="column-name">{{ $client->nama_client }}</td>
							<td class="column-address">{{ $client->alamat_client }}</td>
							<td class="column-action">
								<div class="action-item first">
									<a href="#" title="Edit" class="edit" data-id="{{ $client->id_client }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
								</div>
								<div class="action-item last">
									<a href="#" title="Delete" class="delete" data-id="{{ $client->id_client }}"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>
								</div>
							</td>
						<tr>
						<?php
									$i++;
								}
							}else{
						?>
						<tr class="no-data">
							<td colspan="5">Tidak ada data ditemukan.</td>
						</tr>
						<?php		
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#collapseTwo').addClass('in');

	$('#province').chosen();

	$('#client-form').validate({
		rules: {
			name : {
				required : true,
				maxlength : 50
			},
			address : {
				required : true,
				maxlength : 255
			},
			city : {
				maxlength : 100
			},
			zip_code : {
				number : true,
				maxlength : 6
			},
			phone_1 : {
				maxlength : 20
			},
			phone_2 : {
				maxlength : 20
			},
			fax : {
				maxlength : 20
			},
			email : {
				email : true,
				maxlength : 100
			}
		},
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
	       	$formData = $(form).serialize();

	       	if($state == 'add'){
	       		$type = 'POST';
	       	}else if($state == 'edit'){
	       		$id = $('#id').val();
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
	               	$(form).find('textarea').attr('disabled', true);
	               	$(form).find('select').attr('disabled', true);
	            },      
	            complete: function() {
	            	$(form).find('input[type=text]').removeAttr('disabled');
	            	$(form).find('textarea').removeAttr('disabled');
	            	$('#province').removeAttr('disabled');
	            	$('#province').trigger('chosen:updated');
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$(form).find('input[type=text]').val('');
	            		$(form).find('textarea').val('');
	            		$('#province').find('option').removeAttr('selected').eq(0).attr('selected', true);
	            		
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

		            		$html = '<tr class="item" id="item-' + json.id_client + '">\
								<td class="column-no">' + $count_item + '</td>\
								<td class="column-code">' + json.client_code + '</td>\
								<td class="column-name">' + json.client_name + '</td>\
								<td class="column-address">' + json.client_address + '</td>\
								<td class="column-action">\
									<div class="action-item first">\
										<a href="#" title="Edit" data-id="' + json.id_client + '" class="edit"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>\
									</div>\
									<div class="action-item last">\
										<a href="#" title="Delete" data-id="' + json.id_client + '" class="delete"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
									</div>\
								</td>\
							<tr>';

							$('#list-items tbody').append($html);
						}else if($state == 'edit'){
							$('tr#item-' + $id).find('td.column-name').html(json.client_name);
							$('tr#item-' + $id).find('td.column-address').html(json.client_address);

							$('#btn-save').attr('disabled', true);
							$('#btn-add').removeAttr('disabled');
							$('#state').val('add');
							$('#id').val('');
						}

						setTimeout(function(){
							$('#form-alert').removeClass('alert-success').addClass('hide');
						}, 5000);

						set_sidebar_height();
	            	}else{
	            		$('#form-message').html(json.message);
	            		$('#form-alert').addClass('alert-danger').removeClass('hide');
	            	}
	            }
	        });

		    return false;
		}
	});

	$('#list-items .item .column-action a.edit').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$url = $('#client-form').attr('action');
		$url = $url + '/' + $id;

		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

		$.ajax({
            url: $url,
            type: 'GET',
            data: {
            	id : $id
            },
            dataType: 'json',
            beforeSend: function() {
               	
            },      
            complete: function() {
            	
            },          
            success: function(json) {
            	if(json.success == 'true'){
            		$('#name').val(json.nama_client);
            		$('#address').val(json.alamat_client);
            		$('#province').find('option[value="' + json.propinsi + '"]').attr('selected',true);
            		$('#city').val(json.kota);
            		$('#zip_code').val(json.kode_pos);
            		$('#phone_1').val(json.telepon_1);
            		$('#phone_2').val(json.telepon_2);
            		$('#fax').val(json.fax);
            		$('#email').val(json.email);

            		$('#state').val('edit');
					$('#id').val($id);

					$('#btn-save').removeAttr('disabled');
					$('#btn-add').attr('disabled', true);

					$("#province").trigger("chosen:updated");
            	}else{
            		$('#form-message').html(json.message);
	            	$('#form-alert').addClass('alert-danger').removeClass('hide');

	            	setTimeout(function(){
						$('#form-alert').removeClass('alert-danger').addClass('hide');
					}, 5000);
            	}
            }
        });

		return false;
	});

	$('#list-items .item .column-action a.delete').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$confirm = confirm('Anda yakin ingin menghapus client ini?');

		if($confirm){
			$url = $('#client-form').attr('action');
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
	            		set_sidebar_height();
						regenerate_column_no();
	            		alert(json.message);
	            	}else{
	            		alert(json.message);
	            	}
	            }
	        });
		}



		return false;
	});
});
</script>
@stop