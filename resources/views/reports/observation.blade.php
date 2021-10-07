
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Observasi
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui-timepicker-addon.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/chosen.jquery.js')}}"></script>
@stop

@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/easy-autocomplete.min.css')}}" />
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.structure.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.theme.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui-timepicker-addon.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap-chosen.css')}}">
@stop

@section('content')
<div class="content-top-action clearfix" style="width:100%">
	<form method="get" action="{{ url( 'report/observation' ) }}" id="form">
		<div class="row-select-wrapper" id="participant-wrapper">
			<span>Peserta</span>
            <input type="text" name="participant" id="participant" placeholder="Nama Peserta" value="{{ $participant }}" />
            <input type="hidden" name="participant_id" id="participant_id" value="{{ $participant_id }}" />
        </div>

        <div class="row-select-wrapper date-from">
            <span>From</span>
            <input type="text" name="date-from" id="date-from" placeholder="Dari Tanggal" value="{{ $date_from }}" />
        </div>
        <div class="row-select-wrapper date-to">
            <span>To</span>
            <input type="text" name="date-to" id="date-to" placeholder="Hingga Tanggal" value="{{ $date_to }}" />
        </div>

		<div class="row-select-wrapper">
			<span>Row</span>
			<select id="rows" name="rows">
				<option value="10"{{ selected( 10, $rows, true ) }}>10</option>
				<option value="20"{{ selected( 20, $rows, true ) }}>20</option>
				<option value="50"{{ selected( 50, $rows, true ) }}>50</option>
				<option value="all"{{ selected( 'all', $rows, true ) }}>All</option>
			</select>
		</div>

		<div class="row-select-wrapper">
			<button class="btn" type="submit">GO</button>
		</div>
	</form>
</div>

<div class="entry-content">
	<div class="" style="width:100%">
		<div class="table-wrapper no-margin full-width table-responsive" id="list-patient-history">
			<table class="table table-bordered table-striped list-table">
				<thead>
					<tr>
						<th rowspan="2">No.</th>
						<th rowspan="2">Kode</th>
						<th rowspan="2">Nama</th>
						<th rowspan="2">Departemen</th>
                        <th rowspan="2">Factory</th>
                        <th rowspan="2">Client</th>
						<th rowspan="2">Umur</th>
						<th rowspan="2">Tanggal Mulai</th>
						<th rowspan="2">Tanggal Selesai</th>
                        <th colspan="8">Hasil Pemeriksaan</th>
						<th rowspan="2">Diagnosa Akhir</th>
						<th rowspan="2">Kesimpulan</th>
						<th rowspan="2">Keterangan dan Tindak Lanjut</th>
                        <th rowspan="2">Surat Sakit</th>
                        <th rowspan="2">Surat Rujukan</th>
                        <th rowspan="2">Surat Cuti</th>
                        <th rowspan="2">Resep Dokter</th>
                        <th rowspan="2">Action</th>
					</tr>
                    <tr>
                        <th>Keadaan Umum</th>
                        <th>Eye Opening</th>
                        <th>Respon Verbal</th>
                        <th>Respon Motorik</th>
                        <th>Tensi Darah</th>
                        <th>Suhu</th>
                        <th>Denyut Nadi</th>
                        <th>Nafas</th>
                    </tr>
				<thead>
				<tbody>
				@if( count( $datas ) )
					@php $i = 1; @endphp
					@foreach( $datas as $o )
                        @php $od = App\ObservationDetail::where( 'no_observasi', '=', $o->id_observasi )->first(); @endphp
					<tr class="item" id="item-{{ $o->id_observasi }}">
						<td>{{ $i }}</td>
						<td>{{ $o->no_observasi }}</td>
						<td>{{ get_participant_name( $o->id_peserta ) }}</td>
						<td>{{ get_participant_department( $o->id_peserta ) }}</td>
                        <td>{{ get_participant_factory( $o->id_peserta ) }}</td>
                        <td>{{ get_participant_client( $o->id_peserta ) }}</td>
                        <td>{{ get_participant_age( $o->id_peserta ) }}</td>
						<td>{{ date( 'd/m/Y H:i:s', strtotime( $o->tanggal_mulai ) ) }}</td>
						<td>{{ date( 'd/m/Y H:i:s', strtotime( $o->tanggal_selesai ) ) }}</td>
                        <td>{{ $od->keadaan_umum }}</td>
                        <td>{{ get_eye_opening( $od->k_mata ) }}</td>
                        <td>{{ get_verbal_response( $od->k_bicara ) }}</td>
                        <td>{{ get_motoric_response( $od->k_motorik ) }}</td>
                        <td>{{ $od->td_bawah }} / {{ $od->td_atas }}</td>
                        <td>{{ $od->suhu }}</td>
                        <td>{{ $od->nadi }}</td>
                        <td>{{ $od->jalan_nafas }}</td>
						<td>{{ $o->diagnosa_akhir }}</td>
						<td>{{ $o->kesimpulan_observasi }}</td>
                        <td>{{ $o->hasil_observasi }}</td>
                        <td><span class="fa {{ ( is_sick_letter( $o->id_pemeriksaan_poli ) ? 'fa-check' : 'fa-times' ) }}"></span></td>
                        <td><span class="fa {{ ( is_reference_letter( $o->id_pemeriksaan_poli ) ? 'fa-check' : 'fa-times' ) }}"></span></td>
                        <td><span class="fa {{ ( is_dayoff_letter( $o->id_pemeriksaan_poli ) ? 'fa-check' : 'fa-times' ) }}"></span></td>
                        <td><span class="fa {{ ( is_doctor_recipe( $o->id_pemeriksaan_poli ) ? 'fa-check' : 'fa-times' ) }}"></span></td>
						<td>
							<a href="{{ url( 'observation' ) . '/' . $o->id_observasi }}" title="View" class="view"><img src="{{URL::asset('assets/images/icon-view.png')}}" alt="View" /></a>
						</td>
					<tr>
						@php $i++ @endphp
					@endforeach
				@else
					<tr class="no-data">
						<td colspan="24">Tidak ada data ditemukan.</td>
					</tr>
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
		     	<a href="{{ $datas->url( $datas->currentPage() - 1 ) }}&rows={{ $rows }}&participant={{ $participant_id }}&date-from={{ $date_from }}&date-to={{ $date_to }}" aria-label="Previous">
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
                    <a href="{{ $datas->url( $i ) }}&rows={{ $rows }}&participant={{ $participant_id }}&date-from={{ $date_from }}&date-to={{ $date_to }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        </ul>
        <ul class="pagination right clearfix">
        	 @if ( $datas->currentPage() != $datas->lastPage() )
			<li class="pagination-item pagination-next{{ ( $datas->currentPage() == $datas->lastPage() ) ? ' disabled' : '' }}">
		      	<a href="{{ $datas->url( $datas->currentPage() + 1 ) }}&rows={{ $rows }}&participant={{ $participant_id }}&date-from={{ $date_from }}&date-to={{ $date_to }}" aria-label="Next">
		        	<span aria-hidden="true">Next Page <i class="fa fa-chevron-right"></i></span>
		      	</a>
		    </li>
		    @endif
		</ul>
	@endif
	</div>
	@endif

	<div class="download-item" style="width:100%">
		<a href="{{ url( 'print/observation' ) }}?participant={{ $participant_id }}&date_from={{ $date_from }}&date_to={{ $date_to }}" class="btn print" target="_url">Print</a>
		<a href="{{ url( 'export/observation' ) }}?participant={{ $participant_id }}&date_from={{ $date_from }}&date_to={{ $date_to }}" class="btn">Download</a>
	</div>

	<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
</div><!-- /.entry-content  -->

<script type="text/javascript">
$(document).ready(function(){
	$('#collapseSix').addClass('in');
	
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $('#date-to, #date-from').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true,
        yearRange: '-60:+0'
    });

    var participant_suggestion = function(e){
        var code = (e.keyCode ? e.keyCode : e.which);

        if(code == 13) {
            e.preventDefault();

            var $value = $('#participant').val();

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
                                $('#participant').val(json.nama_peserta);
                                $('#participant_id').val(json.id_peserta);
                            }else{
                                alert('Peserta dengan nik ' + json.nik_peserta + ' sudah tidak aktif.');
                            }
                        }else{
                            var options = {
                                getValue: function(element) {
                                    return element.display_name;
                                },
                                data: json.data,
                                requestDelay: 0,
                                list: {
                                    maxNumberOfElements: 15,
                                    onSelectItemEvent: function() {
                                        var selectedItemValue = $("#participant").getSelectedItemData();
                                    },
                                    onClickEvent: function() {
                                        var selectedItemValue = $("#participant").getSelectedItemData();
                                    },
                                    onHideListEvent: function() {
                                        
                                    },
                                    onChooseEvent: function(){
                                        var selectedItemValue = $("#participant").getSelectedItemData();

                                        $('#participant').val(selectedItemValue.nama_peserta);
                                        $('#participant_id').val(selectedItemValue.id_peserta);
                                       
                                        $('#participant').unbind();
                                        $('#participant').keyup(function(a){
                                            participant_suggestion(a);
                                        });
                                    }
                                }
                            };

                            $("#participant").easyAutocomplete(options);

                            var b = jQuery.Event("keyup", { keyCode: 32, which: 32});
                            $("#participant").focus();
                            $("#participant").triggerHandler(b);
                            $("#participant").trigger('change');
                        }
                    }else{
                        alert('Data tidak ditemukan.');
                    }
                }
            });
        }
    }

    $('#form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
            e.preventDefault();
            return false;
        }
    });

    $('#participant').keyup(function(e){
        participant_suggestion(e);
    });
});
</script>
@stop