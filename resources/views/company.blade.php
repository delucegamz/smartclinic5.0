
@extends( 'layouts.app' )

@section( 'page_title' )
Smart Clinic System - Data Organisasi
@stop

@section( 'styles' )
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap-chosen.css')}}">
@stop

@section( 'scripts' )
<script type="text/javascript" src="{{URL::asset('assets/js/chosen.jquery.js')}}"></script>
@stop

@section( 'content' )
<div class="content-title"><h1>Data Perusahaan</h1></div>

<div class="entry-content">
	<form id="client-form" class="form-horizontal" action="{{ route( 'company.store' ) }}" method="post">
		<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
		<div class="form-group">
			<label class="control-label col-xs-2" for="name">Nama Perusahaan</label>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-user"></i></span>
					<input type="text" name="name" id="name" class="form-control" placeholder="Nama Perusahaan" value="{{ $company->nama_organisasi }}" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2" for="address">Alamat Client</label>
			<div class="col-xs-5">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-building"></i></span>
					<textarea name="address" id="address" class="form-control" placeholder="Alamat Perusahaan" rows="3">{{ $company->alamat_organisasi }}</textarea>
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
						@if( count( $provinces ) )
						@foreach ( $provinces as $province )
						<option value="{{ $province->nama_propinsi }}"{{ selected( $province->nama_propinsi, $company->provinsi_organisasi, true ) }}>{{ $province->nama_propinsi }}</option>
						@endforeach;
						@endif;
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
					<input type="text" name="city" id="city" class="form-control" placeholder="Kota" value="{{ $company->kota_organisasi }}" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2">&nbsp;</label>
			<div class="col-xs-2">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
					<input type="text" name="zip_code" id="zip_code" class="form-control" placeholder="Kode Pos" value="{{ $company->kode_pos_organisasi }}" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2">No. Telepon</label>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-phone"></i></span>
					<input type="text" name="phone_1" id="phone_1" class="form-control" placeholder="No Telepon 1" value="{{ $company->no_telepon_1 }}" />
				</div>
				<div class="error-placement"></div>
			</div>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-phone"></i></span>
					<input type="text" name="phone_2" id="phone_2" class="form-control" placeholder="No Telepon 2" value="{{ $company->no_telepon_2 }}" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2" for="fax">Fax</label>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-fax"></i></span>
					<input type="text" name="fax" id="fax" class="form-control" placeholder="Fax" value="{{ $company->no_fax }}" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2" for="email">Email</label>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
					<input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ $company->email }}" />
				</div>
				<div class="error-placement"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2" for="email">Kode Karyawan</label>
			<div class="col-xs-4">
				<div class="input-group">
  					<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
					<input type="text" name="kode_karyawan" id="kode_karyawan" class="form-control" placeholder="Kode Karyawan" value="{{ $company->kode_karyawan }}" />
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
		<div class="form-group last">
			<label class="control-label col-xs-2">&nbsp;</label>
			<div class="col-xs-8">
				<input type="submit" name="submit" value="Simpan" class="btn form-control btn-save" id="btn-save" />  
			</div>
		</div>
		<input type="hidden" name="id" value="" id="id" />
    	<input type="hidden" name="state" value="add" id="state" />
	</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#collapseOne').addClass('in');

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

	       	$url = $(form).attr('action');
	       	$formData = $(form).serialize();

		    $.ajax({
	            url: $url,
	            type: 'post',
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
	            		$('#form-message').html(json.message);
	            		$('#form-alert').addClass('alert-success').removeClass('hide');

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
});
</script>
@stop