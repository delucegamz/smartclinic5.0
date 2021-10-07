
@extends( 'layouts.app' )

@section( 'page_title' )
Smart Clinic System - Data Departemen
@stop

@section( 'styles' )
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap-chosen.css')}}">
@stop

@section( 'scripts' )
<script type="text/javascript" src="{{URL::asset('assets/js/chosen.jquery.js')}}"></script>
@stop


@section('content')
<div class="content-title"><img src="{{URL::asset('assets/images/title-department.png')}}" alt="Daftar Departemen" /></div>

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
		<form method="get" action="{{ route( 'department.index' ) }}">
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
					<th class="column-code-title">Kode Departemen</th>
					<th class="column-name-title">Nama Departemen</th>
					<th class="column-factory-title">Nama Factory</th>
					<th class="column-client-title">Nama Client</th>
					<th class="column-action-title">Action</th>
				<tr>
			<thead>
			<tbody>
				<?php
					if( count( $datas ) ){ 
						
						foreach ( $datas as $department ) {
				?>
				<tr class="item" id="item-{{ $department->id_departemen }}">
					<td class="column-no">{{ $i }}</td>
					<td class="column-code">{{ $department->kode_departemen }}</td>
					<td class="column-name">{{ $department->nama_departemen }}</td>
					<td class="column-factory">{{ get_factory_name( $department->nama_factory ) }}</td>
					<td class="column-client">{{ get_client_name( $department->nama_client ) }}</td>
					<td class="column-action">
						<div class="action-item first">
							<a href="#" title="Edit" class="edit" data-id="{{ $department->id_departemen }}" data-code="{{ $department->kode_departemen }}" data-factory="{{ $department->nama_factory }}" data-client="{{ $department->nama_client }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
						</div>
						<div class="action-item last">
							<a href="#" title="Delete" class="delete" data-id="{{ $department->id_departemen }}"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>
						</div>
					</td>
				<tr>
				<?php
							$i++;
						}
					}else{
				?>
				<tr class="no-data">
					<td colspan="4">Tidak ada data ditemukan.</td>
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

                    <form id="add-item" action="{{ route( 'department.store' ) }}" method="post" class="form-horizontal">
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
 			 						<input type="text" class="form-control required" placeholder="Nama Departemen" aria-describedby="name-icon" name="name" id="name" />
								</div>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="factory">Factory</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-industry"></i></span>
									<select class="form-control required chosen-select" placeholder="Pilih Factory" name="factory" id="factory">
										<option value="">- Pilih Factory -</option>
										<?php foreach( $factories as $factory ) : ?>
										<option value="{{ $factory->id_factory }}">{{ $factory->nama_factory }}</option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="client">Client</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-building"></i></span>
									<select class="form-control required chosen-select" placeholder="Pilih Client" name="client" id="client">
										<option value="">- Pilih Client -</option>
										<?php foreach( $clients as $client ) : ?>
										<option value="{{ $client->id_client }}">{{ $client->nama_client }}</option>
										<?php endforeach; ?>
									</select>
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

<script type="text/javascript">
$(document).ready(function(){
	$('#collapseTwo').addClass('in');
	
	$('#add-item').validate({
		rules: {
			code : {
				required : true,
				maxlength : 7
			},
			name : {
				required : true,
				maxlength: 30
			},
			factory : {
				required : true
			},
			client : {
				required : true
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
	       	$type = 'POST';
	       	$code = $('#code').val();
	       	$name = $('#name').val();
	       	$client = $('#client').val();
	       	$factory = $('#factory').val();
	       	$id = $('#id').val();

	       	if($state == 'add'){
	       		$formData = {
		           	code: $code,
		            name: $name,
		            client: $client,
		            factory: $factory
		        };
	       	}else if($state == 'edit'){
	       		$formData = {
		           	code: $code,
		            name: $name,
		            client: $client,
		            factory: $factory
		        };

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
	               	$('.chosen-select').attr('disabled', true).trigger('chosen:updated');
	            },      
	            complete: function() {
	            	$('#name').removeAttr('disabled');
	            	$('.chosen-select').removeAttr('disabled').trigger('chosen:updated');
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$(form).find('input[type=text]').val('');
	            		$('.chosen-select').val('').trigger('chosen:updated')

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

		            		$html = '<tr class="item" id="item-' + json.id_departemen + '">\
								<td class="column-no">' + $count_item + '</td>\
								<td class="column-code">' + json.kode_departemen + '</td>\
								<td class="column-name">' + json.nama_departemen + '</td>\
								<td class="column-factory">' + json.nama_factory + '</td>\
								<td class="column-client">' + json.nama_client + '</td>\
								<td class="column-action">\
									<div class="action-item first">\
										<a href="#" title="Edit" data-id="' + json.id_departemen + '" data-code="' + json.kode_departemen + '" class="edit" data-client="' + json.client + '" data-factory="' + json.factory + '"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>\
									</div>\
									<div class="action-item last">\
										<a href="#" title="Delete" data-id="' + json.id_departemen + '" class="delete"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
									</div>\
								</td>\
							<tr>';

							$('#list-items tbody').append($html);

							setTimeout(function(){
								$('#modal-add-item').modal('hide');
							}, 1000);
						}else if($state == 'edit'){
							$('tr#item-' + $id).find('td.column-name').html(json.nama_departemen);
							$('tr#item-' + $id).find('td.column-code').html(json.kode_departemen);
							$('tr#item-' + $id).find('td.column-factory').html(json.nama_factory);
							$('tr#item-' + $id).find('td.column-client').html(json.nama_client);
							$('tr#item-' + $id).find('a.edit').attr('data-code',json.kode_departemen);
							$('tr#item-' + $id).find('a.edit').attr('data-client',json.client);
							$('tr#item-' + $id).find('a.edit').attr('data-factory',json.factory);

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
		$('#state').val('add');
		$('#id').val('');
		$('.chosen-select').val('').trigger("chosen:updated");
	}).on('shown.bs.modal', function(e){
		$('.chosen-select').chosen();

		$id = $('#id').val();

		if($id != ''){
			$code = $('#hidden-code').val();
			$('#code').val($code);
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
		}
	});

	$('#list-items .item .column-action a.edit').live('click', function(){
		$id = $(this).attr('data-id');
		$code = $(this).attr('data-code');
		$factory = $(this).attr('data-factory');
		$client = $(this).attr('data-client');
		$item = $('tr#item-' + $id);
		$name = $item.find('.column-name').html();

		$('#state').val('edit');
		$('#id').val($id);
		$('#hidden-code').val($code);
		$('#name').val($name);
		$('#client').find('option[value="' + $client + '"]').attr('selected',true).trigger('chosen:updated');
		$('#factory').find('option[value="' + $factory + '"]').attr('selected',true).trigger('chosen:updated');

		$('#modal-add-item').modal('show');

		return false;
	});

	$('#list-items .item .column-action a.delete').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$confirm = confirm('Anda yakin ingin menghapus departemen ini?');

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
		$action = '{{ route( 'department.index' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s;

		window.location.href= $url;
	});
});
</script>
@stop