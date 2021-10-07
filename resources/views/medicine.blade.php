
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Obat
@stop

@section('content')
<div class="content-title"><img src="{{URL::asset('assets/images/title-medicine.png')}}" alt="Daftar Obat" /></div>

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
		<form method="get" action="{{ route( 'medicine.index' ) }}">
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
	<div class="table-wrapper" style="width:100%">
		<table class="table table-bordered table-striped list-table" id="list-items">
			<thead>
				<tr>
					<th class="column-no-title">No.</th>
					<th class="column-code-title">Kode Obat</th>
					<th class="column-name-title">Nama Obat</th>
					<th class="column-medicine-group-title">Golongan Obat</th>
					<th class="column-price-title">Harga Satuan</th>
					<th class="column-min-title">Stock Min.</th>
					<th class="column-stock-title">Stock Obat</th>
					<th class="column-action-title">Action</th>
				<tr>
			<thead>
			<tbody>
				<?php
					if( count( $datas ) ){ 
						
						foreach ( $datas as $medicine ) {
				?>
				<tr class="item" id="item-{{ $medicine->id_obat }}">
					<td class="column-no">{{ $i }}</td>
					<td class="column-code">{{ $medicine->kode_obat }}</td>
					<td class="column-name">{{ $medicine->nama_obat }}</td>
					<td class="column-medicine-group">{{ get_medicine_group_name( $medicine->id_golongan_obat ) }}</td>
					<td class="column-price">Rp. {{ number_format( $medicine->satuan, 0, ',', '.' ) }}</td>
					<td class="column-min">{{ $medicine->stock_min }}</td>
					<td class="column-stock">{{ $medicine->stock_obat }}</td>
					<td class="column-action">
						<div class="action-item first">
							<a href="#" title="Edit" class="edit" data-id="{{ $medicine->id_obat }}" data-code="{{ $medicine->kode_obat }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
						</div>
						<div class="action-item last">
							<a href="#" title="Delete" class="delete" data-id="{{ $medicine->id_obat }}"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>
						</div>
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

	<div class="download-item hide" style="width:100%">
		<a href="#" class="btn">Download</a>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
        <div class="modal-dialog" style="width:600px;">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                
                    <span id="modal-icon"></span>

                    <form id="add-item" action="{{ route( 'medicine.store' ) }}" method="post" class="form-horizontal">
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
 			 						<input type="text" class="form-control required" placeholder="Nama Obat" aria-describedby="name-icon" name="name" id="name" />
								</div>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="id_golongan_obat">Golongan Obat</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-medkit"></i></span>
 			 						<select name="id_golongan_obat" id="id_golongan_obat" class="form-control required">
 			 							<option value="">Pilih Golongan Obat</option>
 			 							<?php foreach( $groups as $g ) : ?>
 			 							<option value="{{ $g->id_golongan_obat }}">{{ $g->nama_golongan_obat }}</option>
 			 							<?php endforeach; ?> 
 			 						</select>
								</div>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="satuan">Satuan</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-tags"></i></span>
 			 						<input type="text" class="form-control required" placeholder="Satuan Obat" aria-describedby="name-icon" name="satuan" id="satuan" />
								</div>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="jenis_obat">Jenis Obat</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-stethoscope"></i></span>
 			 						<input type="text" class="form-control required" placeholder="Jenis Obat" aria-describedby="name-icon" name="jenis_obat" id="jenis_obat" />
								</div>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="stock_min">Stock Min.</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-minus-circle"></i></span>
 			 						<input type="text" class="form-control required" placeholder="Stock Minimal Obat" aria-describedby="name-icon" name="stock_min" id="stock_min" />
								</div>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="stock_obat">Stock Obat</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-plus-circle"></i></span>
 			 						<input type="text" class="form-control required" placeholder="Stock Obat" aria-describedby="name-icon" name="stock_obat" id="stock_obat" />
								</div>
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="keterangan">Keterangan</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-comment"></i></span>
 			 						<input type="text" class="form-control" placeholder="Keterangan" aria-describedby="name-icon" name="keterangan" id="keterangan" />
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
	$('#collapseSeven').addClass('in');
	
	$('#add-item').validate({
		rules: {
			code : {
				required : true,
				maxlength : 5
			},
			name : {
				required : true,
				maxlength: 30
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
	       	$type = 'POST';
	       	$id = $('#id').val();
	       	$state = $('#state').val();

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
	               	$(form).find('select').attr('disabled', true);
	            },      
	            complete: function() {
	            	$(form).find('input[type=text]').removeAttr('disabled');
	            	$(form).find('select').removeAttr('disabled');
	            	$('#code').attr('disabled', true);
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$(form).find('input[type=text]').val('');
	            		$(form).find('select').find('option').removeAttr('selected');

	            		$('#form-message').html(json.message);
	            		$('#form-alert').addClass('alert-success').removeClass('hide');

	            		$('#id').val('');
	            		$('#state').val('add');

	            		if($state == 'add'){
		            		$count_item = $('#list-items tbody tr.item').length;

		            		if($count_item < 1){
		            			$('#list-items tbody tr.no-data').remove();
		            			$count_item = 1;
		            		}else{
		            			$count_item = parseInt($('#list-items tbody > tr.item').last().find('td.column-no').html());
		            			$count_item++;
		            		}

		            		$html = '<tr class="item" id="item-' + json.id_obat + '">\
								<td class="column-no">' + $count_item + '</td>\
								<td class="column-code">' + json.kode_obat + '</td>\
								<td class="column-name">' + json.nama_obat + '</td>\
								<td class="column-medicine-group">' + json.id_golongan_obat + '</td>\
								<td class="column-price">Rp. ' + json.satuan + '</td>\
								<td class="column-min">' + json.stock_min + '</td>\
								<td class="column-stock">' + json.stock_obat + '</td>\
								<td class="column-action">\
									<div class="action-item first">\
										<a href="#" title="Edit" data-id="' + json.id_obat + '" data-code="' + json.kode_obat + '" class="edit"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>\
									</div>\
									<div class="action-item last">\
										<a href="#" title="Delete" data-id="' + json.id_obat + '" class="delete"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
									</div>\
								</td>\
							<tr>';

							$('#list-items tbody').append($html);

							setTimeout(function(){
								$('#modal-add-item').modal('hide');
							}, 1000);
						}else if($state == 'edit'){
							$('tr#item-' + $id).find('td.column-name').html(json.nama_obat);
							$('tr#item-' + $id).find('td.column-code').html(json.kode_obat);
							$('tr#item-' + $id).find('td.column-medicine-group').html(json.id_golongan_obat);
							$('tr#item-' + $id).find('td.column-price').html('Rp. ' + json.satuan);
							$('tr#item-' + $id).find('td.column-min').html(json.stock_min);
							$('tr#item-' + $id).find('td.column-stock').html(json.stock_obat);
							$('tr#item-' + $id).find('a.edit').attr('data-code',json.kode_obat);

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
	            	$('#code').val(json.kode_obat);
	            	$('#name').val(json.nama_obat);
	            	$('#id_golongan_obat').find('option').removeAttr('selected');
	            	$('#id_golongan_obat').find('option[value="' + json.id_golongan_obat + '"]').attr('selected', true);
	            	$('#satuan').val(json.satuan);
	            	$('#jenis_obat').val(json.jenis_obat);
	            	$('#stock_min').val(json.stock_min);
	            	$('#stock_obat').val(json.stock_obat);
	            	$('#keterangan').val(json.keterangan);
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

		$confirm = confirm('Anda yakin ingin menghapus obat ini?');

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
		$action = '{{ route( 'medicine.index' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s;

		window.location.href= $url;
	});
});
</script>
@stop