
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Import Data Peserta
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
	<div class="content-title"><h1>Import Data Peserta</h1></div>

	<div class="narrow">
		<p>Halo! Unggah berkas comma separated value (.csv) Anda dan kami akan mengimpor data peserta ke database</p>
		<p>Pilih berkas (.csv) untuk mengunggah, lalu klik Unggah berkas dan impor.</p>

		<form enctype="multipart/form-data" id="import-livestock-form" method="post" class="wp-upload-form" action="">
			<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
			<?php
				$upload_max = parse_size( ini_get( 'upload_max_filesize' ) );

				$upload_max_mb = ceil( $upload_max / 1024000 );
			?>
			<div class="row">
				<div class="col-xs-5">
					<div class="form-group">
						<label for="type">Tipe</label>
						<select name="type" id="type" class="form-control">
							<option value="add-new">Tambah Baru</option>
							<option value="deactivate">Nonaktifkan Peserta</option>
						</select>
					</div>
					<div class="form-group">
						<label for="factory">Factory</label>
						<select name="factory" id="factory" class="form-control">
							<option value="">- Pilih Factory -</option>
							@foreach( $factories as $factory )
							<option value="{{ $factory->id_factory }}">{{ $factory->nama_factory }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="factory">Client</label>
						<select name="client" id="client" class="form-control">
							<option value="">- Pilih Client -</option>
							@foreach( $clients as $client )
							<option value="{{ $client->id_client }}">{{ $client->nama_client }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="upload">Pilih sebuah berkas dari komputer Anda:</label> (Ukuran maksimal: <?php echo $upload_max_mb; ?> MB)
						<input type="file" id="upload" name="import" size="25" class="required form-control" />
						<input type="hidden" name="action" value="save" />
						<input type="hidden" name="max_file_size" value="<?php echo $upload_max; ?>" />
					</div>
					<div class="form-group">
						<input type="submit" name="submit" id="submit" class="btn btn-primary" value="Unggah berkas dan impor" />
					</div>
				</div>
			</div>
			
			<div class="progress">
		        <div class="bar"></div >
		        <div class="percent">0%</div >
		    </div>

		    <div id="loader"><img src="{{ URL::asset('assets/images/ajax-loader.gif') }}" />PROCESSING</div>

		    <div id="status-response"></div>

			<div id="response-update">

			</div>
		</form>
	</div>
</div>
<script type="text/javascript" src="{{ URL::asset( 'assets/js/jquery.form.min.js' ) }}"></script>
<script type="text/javascript">
function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

$(document).ready(function(){
	var bar = $('.bar');
	var percent = $('.percent');
	var status = $('#status-response');
	var progress = $('.progress');
	var loader = $('#loader');
	var type = $('#type').val();

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

	$('#import-livestock-form').validate({
		rules: {
			factory : "required",
			client : "required",
		    import: {
		      	required: true,
		      	extension: "xls|xlsx"
		    }
		},
		submitHandler: function(form){
		   	$(form).ajaxSubmit({
		        url : '{{ url( 'participant/action-import' ) }}',
		        type : "POST",
		        data : $('#import-livestock-form').serialize(),
		        dataType : 'json',
		        resetForm : true,
		        target : '#response-update',
		      	beforeSubmit: function(formData, jqForm, options) {
			        status.empty().show();
			        progress.show();
			        loader.show();
			        var percentVal = '0%';
			        bar.width(percentVal);
			        percent.html(percentVal);  
			    },
			    uploadProgress: function(event, position, total, percentComplete) {
			        var percentVal = percentComplete + '%';
			        bar.width(percentVal);
			        percent.html(percentVal);
			    },
			    resetForm: false,
			    clearForm: false,
			    success: function(xhr){
		            var percentVal = '100%';
			        bar.width(percentVal);
			        percent.html(percentVal);
			        loader.hide();

			       	//$('#response-update').html(xhr.message);

			       	do_import(xhr.datas, xhr.count, 1);

		      	}
		    });

		    return false;
		}
	});

	if(type == 'add-new'){
		$("#factory").rules("add", "required");
		$("#client").rules("add", "required");
	}

	$('#type').change(function(){
		var value = $(this).find('option:selected').val();

		if(value == 'add-new'){
			$("#factory").rules("add", "required");
			$("#client").rules("add", "required");
		}else{
			$("#factory").rules("remove", "required");
			$("#client").rules("remove", "required");
		}
	});

	var do_import = function($datas, $count, $i){
	    if($i > $count){
	    	loader.hide();

	    	return false;
	    } 	

	    $.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('input[name="_token"]').val()
	        }
	    });

	    $.ajax({
	        url : '{{ url( 'participant/do-import' ) }}',
	        type: 'post',
	        data: {
	        	datas : $datas[$i-1],
	        	type : $('#type').val(),
	        	factory : $('#factory').val(),
	        	client : $('#client').val()
	        },
	        dataType: 'json',
	        beforeSend: function() {
	            
	        },      
	        complete: function() {
	            
	        },          
	        success: function(json) { 
	            percent_num = ($i / $count) * 100;
	            percentVal = number_format(percent_num, 2, '.', ',');
	            bar.width(percentVal + '%');
	            percent.html($i + '/' + $count);
	            $('#response-update').append(json.message);

	            $i += 1;
	            do_import($datas, $count, $i);
	        }
	    });
	}

});

</script>
@stop