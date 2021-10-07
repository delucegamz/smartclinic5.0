
@extends('layouts.app')

@section('page_title')
Smart Clinic System - DAFTAR KUNJUNGAN PASIEN KECELAKAAN KERJA
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
<div id="participant">
    <div class="content-title"><h1>DAFTAR KUNJUNGAN PASIEN KECELAKAAN KERJA</h1></div>

    <div class="narrow">
        <form enctype="multipart/form-data" id="import-livestock-form" method="get" class="wp-upload-form" action="{{ url( 'report/top10disease' ) }}">
            <div class="row">
                <div class="col-xs-5">
                    <div class="form-group" id="elm-date-from">
                        <label for="date-from">Date From</label>
                        <input type="text" name="date-from" id="date-from" class="form-control disabled-this" placeholder="Ketikkan tanggal awal" value="{{ $date_from }}" />
                    </div>
                    <div class="form-group" id="elm-date-to">
                        <label for="date-to">Date To</label>
                        <input type="text" name="date-to" id="date-to" class="form-control disabled-this" placeholder="Ketikkan tanggal akhir" value="{{ $date_to }}" />
                    </div>
                    
                    <div class="form-group">
                        <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Generate Report" />
                    </div>
                </div>
            </div>
            
            @php
                $date_from =  ( !empty( $date_from ) ) ? $date_from : '';
                $date_to = ( !empty( $date_to ) ) ? $date_to : '';

                $date_from_formatted = ( !empty( $date_from ) ) ? date( 'd M Y', strtotime( $date_from ) ) : '';
                $date_to_formatted = ( !empty( $date_to ) ) ? date( 'd M Y', strtotime( $date_to ) ) : date( 'd M Y' );
            @endphp
            <div id="response-update">
                <div id="print-header" class="text-center">
                    <h2>{{ get_company_name() }}</h2>
                    <h5>{{ get_company_address() }}</h5>
                </div>

                @php
                $current_user = Auth::user();
                $idpengguna = $current_user['original']['idpengguna'];
                $user = App\User::find( $idpengguna );
                $staff = App\Staff::where( 'id_karyawan', '=', $user->id_karyawan )->first();
                @endphp

                <h2 style="text-align: center; margin: 0 0 10px;">DAFTAR KUNJUNGAN PASIEN KECELAKAAN KERJA</h2>
                <h3 style="text-align: center; margin: 0 0 30px;">PERIODE: {{ $date_from_formatted }} - {{ $date_to_formatted }}</h3>
                <h5 style="text-align: center; margin: 0 0 10px;">DATE PRINTED : {{ date( 'd-m-Y' ) }}</h5>
                <h5 style="text-align: center; margin: 0 0 30px;">PRINTED BY : {{ $staff->nama_karyawan }}</h5>

                <div style="margin:0 auto; padding: 50px 0px;">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:50px;">No. </th>
                                <th style="">KAT</th>
                                <th style="">Nama</th>
                                <th style="">NIK</th>
                                <th style="">Tanggal Lahir</th>
                                <th style="">L/P</th>
                                <th style="">Factory</th>
                                <th style="">Departemen</th>
                                <th style="">Tanggal Kejadian</th>
                                <th style="">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $i = 1; 
                            @endphp
                            @foreach( $datas as $data )
                            @php
                                $participant = App\Participant::find( $data->id_peserta );
                                if( $data->uraian == 22 )
                                    $accident = App\Accident::where( 'id_pemeriksaan_poli', $data->id_pemeriksaan_poli )->first();
                                elseif( $data->uraian == 44 )
                                    $accident = null;

                                $sickletter = App\SickLetter::where( 'id_pemeriksaan_poli', '=', $data->id_pemeriksaan_poli )->first();
                                $referenceletter = App\ReferenceLetter::where( 'id_pemeriksaan_poli', '=', $data->id_pemeriksaan_poli )->first();
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ ( $data->uraian == 22  ? 'KK' : 'Kontrol' ) }}</td>
                                <td>{{ $participant->nama_peserta }}</td>
                                <td>{{ $participant->nik_peserta }}</td>
                                <td>{{ date( 'd-m-Y', strtotime( $participant->tanggal_lahir ) ) }}</td>
                                <td>{{ ucwords( $participant->jenis_kelamin ) }}</td>
                                <td>{{ $data->nama_factory }}</td>
                                <td>{{ $data->nama_departemen }}</td>
                                <td>{{ ( $accident ) ? $accident->tanggal_kejadian : '' }}</td>
                                <td>
                                @php
                                    if( $sickletter ){
                                        echo 'SKS';
                                    }else{
                                        if( $referenceletter ){
                                            echo 'RUJUKAN';
                                        }else{
                                            echo '-';
                                        }
                                    }
                                @endphp
                                </td>
                            </tr>
                            @php 
                                $i++; 
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="form-group">
                    @php
                    $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $print_url = str_replace( url( 'report/accident' ), url( 'print/accident' ), $actual_link );
                    @endphp
                    <a href="{{ $print_url }}" class="btn btn-primary" target="_blank">Print</a>
                </div>
            </div>
        </form>
        <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
    </div>
</div>
<script type="text/javascript" src="{{ URL::asset( 'assets/js/jquery.form.min.js' ) }}"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#collapseSix').addClass('in');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    $('#date-from, #date-to').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true
    });
});
</script>
@stop