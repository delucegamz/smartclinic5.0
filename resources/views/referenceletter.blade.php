
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Surat Rujukan
@stop

@section('content')
<div class="content-title"><h1>Data Surat Rujukan</h1></div>

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
		<form method="get" action="{{ route( 'reference-letter.index' ) }}">
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
					<th class="column-medical-record-title">No Pemeriksaan</th>
					<th class="column-patient-id-title">ID Pasien</th>
					<th class="column-patient-nik-title">NIK Pasien</th>
					<th class="column-patient-name-title">Name Pasien</th>
					<th class="column-patient-sex-title">Jenis Kelamin</th>
					<th class="column-patinet-age-title">Umur</th>
					<th class="column-action-title">Action</th>
				<tr>
			<thead>
			<tbody>
				<?php 
					if( count( $datas ) ){ 
						
						foreach ( $datas as $data ) { 
							$medrec = App\MedicalRecord::find( $data->id_pemeriksaan_poli );

							if( !$medrec ) continue;

							$poli_registration = App\PoliRegistration::find( $medrec->id_pendaftaran_poli );
							$participant = App\Participant::find( $data->id_peserta );
				?>
				<tr class="item" id="item-{{ $data->id_surat_rujukan }}">
					<td class="column-no">{{ $i }}</td>
					<td class="column-medical-record"><a href="{{ url( 'medical-record') }}/{{ $data->id_pemeriksaan_poli }}">{{ $poli_registration->no_daftar }}</a></td>
					<td class="column-patient-id">{{ $participant->kode_peserta }}</td>
					<td class="column-patient-nik">{{ $participant->nik_peserta }}</td>
					<td class="column-name">{{ $participant->nama_peserta }}</td>
					<td class="column-sex">{{ get_participant_sex( $data->id_peserta ) }}</td>
					<td class="column-age">{{ get_participant_age( $data->id_peserta ) }}</td>
					<td class="column-action">
						<a href="#" title="{{ ( $data->status ) ? 'Edit' : 'View' }}" class="edit" data-id="{{ $data->id_surat_rujukan }}" data-code="{{ $data->id_surat_rujukan }}" data-status="{{ $data->status }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
						@if( !$data->status )<a href="{{ url( 'print/referenceletter' ) . '?id=' . $data->id_surat_rujukan }}" title="Print" class="print" data-id="{{ $data->id_surat_rujukan }}" data-code="{{ $data->id_surat_rujukan }}" data-status="{{ $data->status }}" target="_blank"><img src="{{URL::asset('assets/images/icon-print.png')}}" alt="Print" /></a>@endif
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

	<!--<div class="download-item hide">
		<a href="#" class="btn">Download</a>
	</div>-->

	<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                
                    <span id="modal-icon"></span>

                    <form id="add-item" action="" method="post" class="form-horizontal">
                    	<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
                    	<input type="hidden" name="_method" value="PUT">

                    	<h2>SURAT RUJUKAN</h2>

                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="code">No.</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="" name="no" id="no" disabled />
                    		</div>
                    	</div>
                    	
                    	<fieldset>
                    		<legend>Data Rujukan</legend>

                    		<div class="form-group">
	                    		<label class="control-label col-xs-2" for="dokter_ahli">Kepada</label>
	                    		<div class="col-xs-10">
	 			 					<input type="text" class="form-control" placeholder="" name="dokter_ahli" id="dokter_ahli" />
	                    		</div>
	                    	</div>

	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="provider">Di</label>
	                    		<div class="col-xs-10">
	 			 					<input type="text" class="form-control" placeholder="" name="provider" id="provider" />
	                    		</div>
	                    	</div>

	                    	<p>Mohon pemeriksaan / pengobatan lebih lanjut</p>

	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="nama">Nama</label>
	                    		<div class="col-xs-10">
	 			 					<input type="text" class="form-control" placeholder="" name="nama" id="nama" disabled/>
	                    		</div>
	                    	</div>

	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="sex">Jenis Kelamin</label>
	                    		<div class="col-xs-10">
	 			 					<input type="text" class="form-control" placeholder="" name="sex" id="sex" disabled/>
	                    		</div>
	                    	</div>

	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="age">Umur</label>
	                    		<div class="col-xs-10">
	 			 					<input type="text" class="form-control" placeholder="" name="age" id="age" disabled/>
	                    		</div>
	                    	</div>

	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="address">Alamat</label>
	                    		<div class="col-xs-10">
	 			 					<textarea class="form-control" placeholder="" name="address" id="address" disabled></textarea>
	                    		</div>
	                    	</div>

	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="anamnesa">Anamnesa</label>
	                    		<div class="col-xs-10">
	 			 					<input type="text" class="form-control" placeholder="" name="anamnesa" id="anamnesa" />
	                    		</div>
	                    	</div>

	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="pemeriksaan_fisik">Pemeriksaan Fisik</label>
	                    		<div class="col-xs-10">
	 			 					<input type="text" class="form-control" placeholder="" name="pemeriksaan_fisik" id="pemeriksaan_fisik" />
	                    		</div>
	                    	</div>

	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="diagnosa_dokter">Diagnosa Dokter</label>
	                    		<div class="col-xs-10">
	 			 					<input type="text" class="form-control" placeholder="" name="diagnosa_dokter" id="diagnosa_dokter" />
	                    		</div>
	                    	</div>

	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="obat_beri">Obat yang Diberikan</label>
	                    		<div class="col-xs-10">
	 			 					<input type="text" class="form-control" placeholder="" name="obat_beri" id="obat_beri" />
	                    		</div>
	                    	</div>

	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="catatan">Instruksi Khusus</label>
	                    		<div class="col-xs-10">
	 			 					<input type="text" class="form-control" placeholder="" name="catatan" id="catatan" />
	                    		</div>
	                    	</div>

	                    	<div class="form-group">
	                    		<label class="control-label col-xs-2" for="dokter_rujuk">Yang Merujuk</label>
	                    		<div class="col-xs-10">
	 			 					<input type="text" class="form-control" placeholder="" name="dokter_rujuk" id="dokter_rujuk" />
	                    		</div>
	                    	</div>
                    	</fieldset>


                    	<div class="form-group last">
                    		<div class="col-xs-2">&nbsp;</div>
                    		<div class="col-xs-10">
                    			<input type="submit" value="Simpan" id="add-submit" class="btn" />

                    			<div class="alert hide" id="form-alert"><span id="form-message"></span> <a href="#" class="close">&times;</a></div>
                    		</div>
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
	$('#collapseEight').addClass('in');
	
	$('#add-item').validate();

	$('#add-item').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
            e.preventDefault();
            return false;
        }
    });

	$('#modal-add-item').on('hidden.bs.modal', function(e){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');
		$('#add-item').find('input[type=text]').val('');
		$('#add-item').find('textarea').val('');
		$('#state').val('add');
		$('#id').val('');
	}).on('shown.bs.modal', function(e){
		$id = $('#id').val();

        $.ajax({
            url: '{{ url( 'reference-letter' ) }}/' + $id,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                
            },      
            complete: function() {
                
            },          
            success: function(json) {
            	$('#id').val(json.id_surat_rujukan);
                $('#no').val(json.no_surat_rujukan);
                $('#nama').val(json.name);
                $('#nik').val(json.nik_peserta);
                $('#sex').val(json.sex);
                $('#age').val(json.age);
                $('#address').val(json.alamat)
                $('#dokter_ahli').val(json.dokter_ahli);
                $('#provider').val(json.provider);
                $('#anamnesa').val(json.anamnesa);
                $('#pemeriksaan_fisik').val(json.pemeriksaan_fisik);
                $('#diagnosa_dokter').val(json.diagnosa_dokter);
                $('#obat_beri').val(json.obat_beri);
                $('#catatan').val(json.catatan);
                $('#dokter_rujuk').val(json.dokter_rujuk);

                $('#add-item').attr('action', '{{ url( 'reference-letter' ) }}/' + $id );
            }
        });
	});

	$('#list-items .item .column-action a.edit').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$('#state').val('edit');
		$('#id').val($id);

		$status = $(this).attr('data-status');
		if($status == '1'){
			$('#add-submit').removeClass('hide');
		}else{
			$('#add-submit').addClass('hide');
		}

		$('#modal-add-item').modal('show');

		return false;
	});

	$('#rows').change(function(){
		$rows = $(this).find('option:selected').val();
		$page = $('#page').val();
		$s = $('#s').val();
		$action = '{{ route( 'reference-letter.index' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s;

		window.location.href= $url;
	});
});
</script>
@stop