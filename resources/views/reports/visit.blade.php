
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Kunjungan
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui.min.js')}}"></script>
@stop

@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/easy-autocomplete.min.css')}}" />
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.structure.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.theme.min.css')}}">
@stop

@section('content')
<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
<form method="get" action="{{ url( 'report/visit' ) }}" id="filters">

    <fieldset>
        <legend>Filter</legend>

        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <select name="view" id="view" class="form-control">
                        <option value="">- Pilih Tampilan -</option>
                        <option value="participant"{{ selected( 'participant', $view, true ) }}>Per Peserta</option>
                        <option value="department"{{ selected( 'department', $view, true ) }}>Per Departemen</option>
                        <option value="factory"{{ selected( 'factory', $view, true ) }}>Per Factory</option>
                        <option value="client"{{ selected( 'client', $view, true ) }}>Per Client</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6" id="list-filters">
                <div class="form-group participant{{ ( $view == 'participant' ? '' : ' hide' ) }}">
                    <input type="text" name="participantsearch" id="participantsearch" class="form-control" placeholder="Pilih Peserta" value="{{ $participantsearch }}" />
                    <input type="hidden" name="participant" id="participant" value="{{ $participant }}" />
                </div>
                <div class="form-group department{{ ( $view == 'department' ? '' : ' hide' ) }}">
                    <select name="department" id="department" class="form-control"{{ ( $view == 'department' ? '' : ' disabled' ) }}>
                        <option value="">- Pilih Departemen -</option>
                        @foreach( $departments as $d )
                        <option value="{{ $d->id_departemen }}"{{ selected( $d->id_departemen, $department, true ) }}>{{ $d->nama_departemen }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group client{{ ( $view == 'client' ? '' : ' hide' ) }}">
                    <select name="client" id="client" class="form-control"{{ ( $view == 'client' ? '' : ' disabled' ) }}>
                        <option value="">- Pilih client -</option>
                        @foreach( $clients as $c )
                        <option value="{{ $c->id_client }}"{{ selected( $c->id_client, $client, true ) }}>{{ $c->nama_client }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group factory{{ ( $view == 'factory' ? '' : ' hide' ) }}">
                    <select name="factory" id="factory" class="form-control"{{ ( $view == 'factory' ? '' : ' disabled' ) }}>
                        <option value="">- Pilih Factory -</option>
                        @foreach( $factories as $f )
                        <option value="{{ $f->id_factory }}"{{ selected( $f->id_factory, $factory, true ) }}>{{ $f->nama_factory }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                <div class="form-group">
                    <input type="text" name="date-from" id="date-from" class="form-control" placeholder="Tanggal (From)" value="{{ $date_from }}" />
                </div>
            </div>
            <div class="col-xs-6">
                <div class="form-group">
                    <input type="text" name="date-to" id="date-to" class="form-control" placeholder="Tanggal (To)" value="{{ $date_to }}" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <input type="submit" value="SUBMIT" class="btn btn-primary form-control" />
                </div>
            </div>
        </div>
    </fieldset>

    @if( $view != '' )
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
    </div>
    @endif
</form>

@if( $view != '' )
<div class="entry-content">
	<div class="table-responsive" style="width:100%">
		<div class="table-wrapper" style="width:auto">
			<table class="table table-bordered table-striped list-table" id="list-items">
                @if( $view == 'participant' )
				<thead>
					<tr>
						<th class="column-no-title">No.</th>
						<th class="column-waktu-pendaftaran-title">Waktu Pendaftaran</th>
                        <th class="column-nama-poli-title">Nama Poli</th>
                        <th class="column-status-title">Status Pemeriksaan</th>
					<tr>
				<thead>
                @elseif( $view == 'department' ) 
                <thead>
                    <tr>
                        <th class="column-no-title">No.</th>
                        <th class="column-nama-title">Nama Peserta</th>
                        <th class="column-client-title">Client</th>
                        <th class="column-factory-title">Factory</th>
                        <th class="column-waktu-pendaftaran-title">Waktu Pendaftaran</th>
                        <th class="column-nama-poli-title">Nama Poli</th>
                        <th class="column-status-title">Status Pemeriksaan</th>
                    <tr>
                <thead>
                @elseif( $view == 'factory' ) 
                <thead>
                    <tr>
                        <th class="column-no-title">No.</th>
                        <th class="column-nama-title">Nama Peserta</th>
                        <th class="column-client-title">Client</th>
                        <th class="column-departemen-title">Departemen</th>
                        <th class="column-waktu-pendaftaran-title">Waktu Pendaftaran</th>
                        <th class="column-nama-poli-title">Nama Poli</th>
                        <th class="column-status-title">Status Pemeriksaan</th>
                    <tr>
                <thead>
                @elseif( $view == 'client' ) 
                <thead>
                    <tr>
                        <th class="column-no-title">No.</th>
                        <th class="column-nama-title">Nama Peserta</th>
                        <th class="column-factory-title">Factory</th>
                        <th class="column-departemen-title">Departemen</th>
                        <th class="column-waktu-pendaftaran-title">Waktu Pendaftaran</th>
                        <th class="column-nama-poli-title">Nama Poli</th>
                        <th class="column-status-title">Status Pemeriksaan</th>
                    <tr>
                <thead>
                @endif
				<tbody>
			    @if( count( $datas ) )
					@foreach ( $datas as $poliregistration )
                    @if( $view == 'participant' )
					<tr class="item" id="item-{{ $poliregistration->id_pendaftaran }}">
						<td class="column-no">{{ $i }}</td>
						<td class="column-waktu-pendaftaran">{{ date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
						<td class="column-nama-poli">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
                        <td class="column-status">
                        {{ ( ( get_id_pemeriksaan( $poliregistration->id_pendaftaran, $poliregistration->id_peserta ) != '-' ) ?  'Sudah Diperiksa' : 'Tidak Diperiksa' ) }}
                        </td>
					<tr>
                    @elseif( $view == 'department' )
                    <tr class="item" id="item-{{ $poliregistration->id_pendaftaran }}">
                        <td class="column-no">{{ $i }}</td>
                        <td class="column-nama">{{ get_participant_name( $poliregistration->id_peserta ) }}</td>
                        <td class="column-client">{{ get_participant_client( $poliregistration->id_peserta ) }}</td>
                        <td class="column-factory">{{ get_participant_factory( $poliregistration->id_peserta ) }}</td>
                        <td class="column-waktu-pendaftaran">{{ date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
                        <td class="column-nama-poli">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
                        <td class="column-status">
                        {{ ( ( get_id_pemeriksaan( $poliregistration->id_pendaftaran, $poliregistration->id_peserta ) != '-' ) ?  'Sudah Diperiksa' : 'Tidak Diperiksa' ) }}
                        </td>
                    <tr>
                    @elseif( $view == 'factory' )
                    <tr class="item" id="item-{{ $poliregistration->id_pendaftaran }}">
                        <td class="column-no">{{ $i }}</td>
                        <td class="column-nama">{{ get_participant_name( $poliregistration->id_peserta ) }}</td>
                        <td class="column-client">{{ get_participant_client( $poliregistration->id_peserta ) }}</td>
                        <td class="column-departemen">{{ get_participant_department( $poliregistration->id_peserta ) }}</td>
                        <td class="column-waktu-pendaftaran">{{ date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
                        <td class="column-nama-poli">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
                        <td class="column-status">
                        {{ ( ( get_id_pemeriksaan( $poliregistration->id_pendaftaran, $poliregistration->id_peserta ) != '-' ) ?  'Sudah Diperiksa' : 'Tidak Diperiksa' ) }}
                        </td>
                    <tr>
                    @elseif( $view == 'client' )
                    <tr class="item" id="item-{{ $poliregistration->id_pendaftaran }}">
                        <td class="column-no">{{ $i }}</td>
                        <td class="column-nama">{{ get_participant_name( $poliregistration->id_peserta ) }}</td>
                        <td class="column-factory">{{ get_participant_factory( $poliregistration->id_peserta ) }}</td>
                        <td class="column-departemen">{{ get_participant_department( $poliregistration->id_peserta ) }}</td>
                        <td class="column-waktu-pendaftaran">{{ date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
                        <td class="column-nama-poli">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
                        <td class="column-status">
                        {{ ( ( get_id_pemeriksaan( $poliregistration->id_pendaftaran, $poliregistration->id_peserta ) != '-' ) ?  'Sudah Diperiksa' : 'Tidak Diperiksa' ) }}
                        </td>
                    <tr>
                    @endif
					<?php  $i++; ?>
					@endforeach
				@else
    				@if( $view == 'participant' )
                    <tr class="no-data">
                        <td colspan="4">Tidak ada data ditemukan.</td>
                    </tr>
                    @else
                    <tr class="no-data">
                        <td colspan="7">Tidak ada data ditemukan.</td>
                    </tr>
                    @endif
				@endif
				</tbody>
			</table>
		</div>
	</div>

	@if ( $rows != 'all' )
	<div class="navigation clearfix">
	@if ( $datas->lastPage() > 1 )
        <ul class="pagination left clearfix">
        	@if ( $datas->currentPage() != 1 )
			<li class="pagination-item pagination-prev{{ ( $datas->currentPage() == 1 ) ? ' disabled' : '' }}">
		     	<a href="{{ $datas->url( $datas->currentPage() - 1 ) }}&rows={{ $rows }}&participant={{ $participant }}&department={{ $department }}&client={{ $client }}&factory={{ $factory }}&view={{ $view }}&date-from={{ $date_from }}&date-to={{ $date_to }}" aria-label="Previous">
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
                    <a href="{{ $datas->url( $i ) }}&rows={{ $rows }}&participant={{ $participant }}&department={{ $department }}&client={{ $client }}&factory={{ $factory }}&view={{ $view }}&date-from={{ $date_from }}&date-to={{ $date_to }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        </ul>
        <ul class="pagination right clearfix">
        	 @if ( $datas->currentPage() != $datas->lastPage() )
			<li class="pagination-item pagination-next{{ ( $datas->currentPage() == $datas->lastPage() ) ? ' disabled' : '' }}">
		      	<a href="{{ $datas->url( $datas->currentPage() + 1 ) }}&rows={{ $rows }}&participant={{ $participant }}&department={{ $department }}&client={{ $client }}&factory={{ $factory }}&view={{ $view }}&date-from={{ $date_from }}&date-to={{ $date_to }}" aria-label="Next">
		        	<span aria-hidden="true">Next Page <i class="fa fa-chevron-right"></i></span>
		      	</a>
		    </li>
		    @endif
		</ul>
	@endif
	</div>
	@endif

    @if( count( $datas ) )
	<div class="download-item" style="width:100%">
        <a href="{{ url( 'print/visit' ) }}?participant={{ $participant }}&department={{ $department }}&client={{ $client }}&factory={{ $factory }}&view={{ $view }}&date_from={{ $date_from }}&date_to={{ $date_to }}" class="btn" target="_blank">Print</a>
		<a href="{{ url( 'export/visit' ) }}?participant={{ $participant }}&department={{ $department }}&client={{ $client }}&factory={{ $factory }}&view={{ $view }}&date_from={{ $date_from }}&date_to={{ $date_to }}" class="btn">Download</a>
	</div>
    @endif

	<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
        <div class="modal-dialog" style="width:600px">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                
                    <span id="modal-icon"></span>

                    <form id="add-item" action="{{ route( 'poliregistration.store' ) }}" method="post" class="form-horizontal">
                    	
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
                    		<label class="control-label col-xs-2" for="no_antrian">No. Antrian</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-sort-numeric-asc"></i></span>
 			 						<input type="text" class="form-control" placeholder="Nomor Antrian" aria-describedby="name-icon" name="no_antrian" id="no_antrian" disabled />
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="medrec">NIK/No Kartu</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-file-text"></i></span>
 			 						<input type="text" class="form-control" placeholder="NIK/No Kartu" aria-describedby="name-icon" name="medrec" id="medrec" />
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
 			 						<input type="text" class="form-control" placeholder="Jenis Kelamin Peserta" aria-describedby="name-icon" name="sex" id="sex" disabled />
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="department">Unit Kerja</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-briefcase"></i></span>
 			 						<input type="text" class="form-control" placeholder="Unit Kerja" aria-describedby="name-icon" name="department" id="department" disabled />
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
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="poli">Poli</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-hospital-o"></i></span>
 			 						<select name="poli" id="poli" class="form-control required">
 			 							<option value="">Pilih Poli</option>
 			 							<?php foreach( $poli as $p ) : ?>
 			 							<option value="{{ $p->id_poli }}">{{ $p->nama_poli }}</option>
 			 							<?php endforeach; ?>
 			 						</select>
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="catatan">Catatan</label>
                    		<div class="col-xs-10">
                    			<div class="input-group">
  									<span class="input-group-addon"><i class="fa fa-comment"></i></span>
 			 						<textarea class="form-control" placeholder="Catatan" aria-describedby="name-icon" name="catatan" id="catatan" rows="3"></textarea>
								</div>
                                <div class="error-placement"></div>
                    		</div>
                    	</div>
                    	<input type="hidden" name="id" value="" id="id" />
                    	<input type="hidden" name="hidden-code" value="" id="hidden-code" />
                    	<input type="hidden" name="state" value="add" id="state" />
                        <input type="hidden" name="id_peserta" value="" id="id_peserta" />
                    </form>

                    <!-- <input type="text" value="" placeholder="NIK/No Kartu" name="card_no" id="card_no" class="form-control" /> -->
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div><!-- /.entry-content  -->
@endif

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

            var $value = $('#participantsearch').val();

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
                                $('#participantsearch').val(json.nama_peserta);
                                $('#participant').val(json.id_peserta);
                            }else{
                                alert('Peserta dengan nik ' + json.nik_peserta + ' sudah tidak aktif.');
                            }
                        }else{
                            var options = {
                                getValue: function(element) {
                                    return element.nama_peserta;
                                },
                                data: json.data,
                                requestDelay: 0,
                                list: {
                                    maxNumberOfElements: 15,
                                    onSelectItemEvent: function() {
                                        var selectedItemValue = $("#participantsearch").getSelectedItemData();
                                    },
                                    onClickEvent: function() {
                                        var selectedItemValue = $("#participantsearch").getSelectedItemData();
                                    },
                                    onHideListEvent: function() {
                                        
                                    },
                                    onChooseEvent: function(){
                                        var selectedItemValue = $("#participantsearch").getSelectedItemData();

                                        $('#participantsearch').val(selectedItemValue.nama_peserta);
                                        $('#participant').val(selectedItemValue.id_peserta);

                                        $('#participantsearch').unbind();
                                        $('#participantsearch').keyup(function(a){
                                            participant_suggestion(a);
                                        });
                                    }
                                }
                            };

                            $("#participantsearch").easyAutocomplete(options);

                            var b = jQuery.Event("keyup", { keyCode: 32, which: 32});
                            $("#participantsearch").focus();
                            $("#participantsearch").triggerHandler(b);
                            $("#participantsearch").trigger('change');
                        }
                    }else{
                        alert(json.message);
                    }
                }
            });
        }
    }

    $('#participantsearch').keyup(function(e){
        participant_suggestion(e);
    });

    $('#filters').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
            e.preventDefault();
            return false;
        }
    });

	$('#collapseSix').addClass('in');

    $('#date-to, #date-from').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true,
        yearRange: '-60:+0'
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

        $('#add-item').find('input[type=text]').attr('disabled', true);
        $('#add-item').find('textarea').attr('disabled', true);
        $('#add-item').find('select').attr('disabled', true);

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
            }
        });
		
	});

	$('#list-items .item .column-action a.edit').live('click', function(){
		$id = $(this).attr('data-id');

		$('#state').val('edit');
		$('#id').val($id);

		$('#modal-add-item').modal('show');

		return false;
	});

    $('#view').change(function(){
        var $val = $(this).find('option:selected').val();

        $('#list-filters .form-group').addClass('hide');
        $('#list-filters .form-group select').attr('disabled', true);
        $('#list-filters .form-group input').attr('disabled', true);

        if($val != ''){
            $('#list-filters .form-group.' + $val).removeClass('hide');
            $('#list-filters .form-group.' + $val).find('select').removeAttr('disabled');
            $('#list-filters .form-group.' + $val).find('input').removeAttr('disabled');
        }

        // if($val == 'participant'){
        //     $('#participant').chosen();
        // }else{
        //     $("#participant").chosen("destroy");
        //     $('#participant').attr('disabled', true);
        // }

        return false;
    });
});
</script>
@stop