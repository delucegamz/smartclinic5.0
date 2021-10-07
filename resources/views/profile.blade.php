
@extends( 'layouts.app' )

@section( 'page_title' )
Smart Clinic System - Profil Anda
@stop

@section( 'styles' )
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap-chosen.css')}}">
@stop

@section( 'scripts' )
<script type="text/javascript" src="{{URL::asset('assets/js/chosen.jquery.js')}}"></script>
@stop

@section( 'content' )
<div class="content-title"><h1>Profil Anda</h1></div>

@if( Session::has( 'message' ) )
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="alert alert-success">
                {{ Session::get( 'message' ) }}
            </div>
        </div>
    </div>
</div>
@endif

@if( Session::has( 'error' ) )
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="alert alert-danger">
                {{ Session::get( 'error' ) }}
            </div>
        </div>
    </div>
</div>
@endif

<div class="entry-content">
	<form id="client-form" class="form-horizontal" action="{{ url( 'user/update_profile' ) }}" method="post" enctype="multipart/form-data">
		<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
		
		<div class="form-group">
    		<label class="control-label col-xs-2" for="idpengguna">ID</label>
    		<div class="col-xs-7">
 				<input type="text" class="form-control" placeholder="ID Pengguna" name="idpengguna" id="idpengguna" value="{{ $datas->idpengguna }}" disabled />
				<div class="error-placement"></div>
    		</div>
    	</div>

    	<div class="form-group">
    		<label class="control-label col-xs-2" for="nik_karyawan">NIK Karyawan</label>
    		<div class="col-xs-7">
 				<input type="text" class="form-control required" placeholder="NIK Karyawan" name="nik_karyawan" id="nik_karyawan" value="{{ $staff->nik_karyawan }}" disabled />
				<div class="error-placement"></div>
    		</div>
    	</div>
    	
    	<div class="form-group">
    		<label class="control-label col-xs-2" for="nama_karyawan">Nama</label>
    		<div class="col-xs-10">
 				<input type="text" class="form-control" placeholder="Nama Karyawan" name="nama_karyawan" id="nama_karyawan" value="{{ $staff->nama_karyawan }}" disabled />
				<div class="error-placement"></div>
    		</div>
    	</div>

    	<div class="form-group">
    		<label class="control-label col-xs-2" for="email">Email</label>
    		<div class="col-xs-10">
 				<input type="text" class="form-control email" placeholder="Alamat Email" name="email" id="email" value="{{ $datas->email }}" />
				<div class="error-placement"></div>
    		</div>
    	</div>

    	<div class="form-group">
    		<label class="control-label col-xs-2" for="username">Username</label>
    		<div class="col-xs-7">
 				<input type="text" class="form-control required" placeholder="Username" name="username" id="username" value="{{ $datas->username }}" />
				<div class="error-placement"></div>
    		</div>
    	</div>

    	<div class="form-group">
    		<label class="control-label col-xs-2" for="password_1">Password</label>
    		<div class="col-xs-7">
 				<input type="password" class="form-control" placeholder="Password" name="password_1" id="password_1" />
				<div class="error-placement"></div>
    		</div>
    	</div>

    	<div class="form-group">
    		<label class="control-label col-xs-2" for="password_2">&nbsp;</label>
    		<div class="col-xs-7">
 				<input type="password" class="form-control" placeholder="Konfirmasi Password" name="password_2" id="password_2" />
				<div class="error-placement"></div>
    		</div>
    	</div>

    	<div class="form-group">
    		<label class="control-label col-xs-2" for="password_1">Foto</label>
    		<div class="col-xs-7">
 				<input type="file" class="form-control" placeholder="Upload foto anda dalam format .png, .jpg, .jpeg" name="foto" id="foto" />
				<div class="error-placement"></div>
    		</div>
    	</div>

    	<div class="form-group last">
    		<div class="col-xs-2">&nbsp;</div>
    		<div class="col-xs-7">
    			<input type="submit" value="Update" class="btn btn-lg btn-primary" />
    		</div>
    	</div>
	</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#collapseFour').addClass('in');

	

	$('#client-form').validate({
		errorPlacement: function(error, element) { 
			var selector = $(element.context).attr('id');

     		error.appendTo($('#' + selector).parents('div[class*="col-xs"]').find('.error-placement'));
   		}
   	});

   	$('#password_2').rules('add', {
		equalTo: $('#password_1')
	});

	$('#foto').rules('add', {
		extension: 'png|jpg|jpeg'
	});
});
</script>
@stop