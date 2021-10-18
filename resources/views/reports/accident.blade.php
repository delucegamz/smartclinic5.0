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
    <div class="content-title">
        <h1>DAFTAR KUNJUNGAN PASIEN KECELAKAAN KERJA</h1>
    </div>

    <div class="narrow">
        <form enctype="multipart/form-data" id="import-livestock-form" method="get" class="wp-upload-form"
            action="{{ url( 'report/accident' ) }}">
            <input type="hidden" name="per_page" id="per_page_hidden" value="{{$per_page}}">
            <div class="">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="form-group">
                            <label for="filter_by">Filter By</label>
                            <select name="filter_by" class="form-control input-sm" id="filter_by">
                                <option value="">Pilih Filter</option>
                                <option value="kecelakaan">
                                    Kecelakaan Kerja
                                </option>
                                <option value="kontrol">
                                    Kontrol
                                </option>
                                <option value="nik">
                                    NIK Peserta
                                </option>
                                <option value="tanggal">
                                    Tanggal
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3 nik-filter" style="display: {{$filter_by=='nik' ? 'block' : 'none'}};">
                        <div class="form-group">
                            <label for="nik_peserta">NIK Peserta</label>
                            <input id="nik_peserta" type="text" class="form-control" placeholder="masukan nik peserta"
                                name="nik_peserta" value="{{$nik_peserta}}">
                        </div>
                    </div>

                    <div class="col-12 col-sm-3 accident-filter"
                        style="display: {{$filter_by=='kecelakaan' ? 'block' : 'none'}};">
                        <div class="form-group">
                            <label for="accident">Kecelakaan Kerja</label>
                            <select name="accident" class="form-control input-sm" id="accident">
                                <option value="">Pilih Filter</option>
                                <option value="22" {{ $accident==22 ? 'selected' : '' }}>
                                    Kecelakaan Kerja
                                </option>
                                <option value="33" {{ $accident==33 ? 'selected' : '' }}>
                                    Kecelakaan Lalu Lintas
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3 control-filter"
                        style="display: {{$filter_by=='kontrol' ? 'block' : 'none'}};">
                        <div class="form-group">
                            <label for="control">Kontrol</label>
                            <select name="control" class="form-control input-sm" id="control">
                                <option value="">Pilih Filter</option>
                                <option value="44" {{ $control==44 ? 'selected' : '' }}>
                                    Kontrol Kecelakaan Kerja
                                </option>
                                <option value="55" {{ $control==55 ? 'selected' : '' }}>
                                    Kontrol Pasca Rawat Inap
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3">
                        <div class="form-group">
                            <label for="start_date">From</label>
                            <input type="date" class="form-control input-sm date-filter" id="start_date"
                                value="{{$start_date}}" name="start_date">
                        </div>
                    </div>
                    <div class="col-12 col-sm-3">
                        <div class="form-group">
                            <label for="end_date">To</label>
                            <input type="date" class="form-control input-sm date-filter" id="end_date" name="end_date"
                                value="{{$end_date}}">
                        </div>
                    </div>

                    <div class="col-12 col-sm-3">
                        <div class="form-group">
                            <label for="" style="visibility: hidden;">Cari</label>
                            {!! Form::button('cari', ['class' => 'btn btn-primary btn-block',
                            'style' => 'width: 150px','type' => 'submit']) !!}
                        </div>
                    </div>
                </div>
            </div>

            @php
            // $date_from = ( !empty( $date_from ) ) ? $date_from : '';
            // $date_to = ( !empty( $date_to ) ) ? $date_to : '';

            $date_from_formatted = ( !empty( $start_date ) ) ? date( 'd M Y', strtotime( $start_date ) ) : '';
            $date_to_formatted = ( !empty( $end_date ) ) ? date( 'd M Y', strtotime( $end_date ) ) : date( 'd M Y' );
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
                <h3 style="text-align: center; margin: 0 0 30px;">PERIODE: {{ $date_from_formatted }} - {{
                    $date_to_formatted }}</h3>
                <h5 style="text-align: center; margin: 0 0 10px;">DATE PRINTED : {{ date( 'd-m-Y' ) }}</h5>
                <h5 style="text-align: center; margin: 0 0 30px;">PRINTED BY : {{ $staff->nama_karyawan }}</h5>

                <div style="margin:0 auto; padding: 50px 0px;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="form-group">
                                <select class="form-control" style="max-width: 64px;" name="per_page" id="per_page">
                                    <option value="10" {{$per_page==10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{$per_page==25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{$per_page==50 ? 'selected' : '' }}>50</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default" type="button" id="excel-export">Expor Excel</button>
                            </div>
                        </div>

                        <div>
                            <p>Total: {{$medicalRecords->total()}}</p>
                        </div>
                    </div>

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
                                <th style="">Tanggal Pelaporan</th>
                                <th style="">Tindakan</th>
                                <th style="">Prewiev</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 1;
                            @endphp
                            @foreach( $medicalRecords as $record )
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $record->getUraianName() }}</td>
                                <td>{{ $record->participant->nama_peserta }}</td>
                                <td>{{ $record->participant->nik_peserta }}</td>
                                <td>{{ date( 'd-m-Y', strtotime( $record->participant->tanggal_lahir ) ) }}</td>
                                <td>{{ ucwords( $record->participant->jenis_kelamin ) }}</td>
                                <td>{{ $record->nama_factory }}</td>
                                <td>{{ $record->nama_departemen }}</td>
                                <td>{{ $record->accident ? $record->accident->tanggal_lapor : '' }}</td>
                                <td>
                                    {{
                                    $record->sick_letter_count
                                    ? 'SKS'
                                    : ($record->referece_letter_count ? 'RUJUKAN' :'-')
                                    }}
                                </td>
                                <td class="column-action text-center">
                                    <a class="btn btn-default btn-xs fa fa-eye detail" data-toggle="modal"
                                        data-target="#modal-detail"
                                        data-namapeserta="{{ $record->participant->nama_peserta }}"
                                        data-nikpeserta="{{ $record->participant->nik_peserta }}"> Detail</a>
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
                <div>
                    {{$medicalRecords->appends([
                    'filter_by' => $filter_by,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'per_page' => $per_page,
                    'nik_peserta' => $nik_peserta,
                    'accident' => $accident,
                    ])->links()}}
                </div>
            </div>
        </form>
        <input name="_token" type="hidden" value="{{ csrf_token() }}" />
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
<div class="modal fade" id="modal-detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Detail </h4>
            </div>
        </div class="modal-body table-responsive">
        <table class="table table-bordered no-margin">
            <tbody>
                <tr>
                    <th style="">nama</th>
                    <td><span id="nama-peserta"></span> </td>
                </tr>
                <tr>
                    <th style="">nik</th>
                    <td><span id="nik-peserta"></span> </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(document).on('click','.detail', function(){
            var nama = $(this).data('namapeserta');
            var nik = $(this).data('nikpeserta');
            $('#nama-peserta').text(nama);
            $('#nik-peserta').text(nik);
        });

        @if ($filter_by)
            $('#filter_by').val('{{$filter_by}}')
        @endif

        $('#filter_by').on('change', function () {
            var filterBy = $(this).val();

            if (filterBy == 'kecelakaan') {
                $('#accident').val('');
                $('#accident').prop('required', true);
                $('#nik_peserta').prop('required', false);
                $('#control').prop('required', false);
                $('.accident-filter').show();
            }
            else {
                $('.accident-filter').hide();
            }

            if (filterBy == 'kontrol') {
                $('#control').val('');
                $('#control').prop('required', true);
                $('#nik_peserta').prop('required', false);
                $('#accident').prop('required', false);
                $('.control-filter').show();
            }
            else {
                $('.control-filter').hide();
            }

            if (filterBy == 'nik') {
                $('#nik_peserta').val('');
                $('#nik_peserta').prop('required', true);
                $('#accident').prop('required', false);
                $('#control').prop('required', false);
                $('.nik-filter').show();
            }
            else {
                $('.nik-filter').hide();
            }
            
            // tanggal
            if (filterBy == 'tanggal') {
                $('#accident').prop('required', false);
                $('#nik_peserta').prop('required', false);
                $('.date-filter').prop('required', true)
            } else {
                $('.date-filter').prop('required', false)
            }
        });

        function getParams() {
            let filter = $('#filter_by').val();
            let accident = $('#accident').val();
            let control = $('#control').val();
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            let nikPeserta = $('#nik_peserta').val();
            let per_page = $('#per_page_hidden').val();

           return `filter_by=${filter}&accident=${accident}&control=${control}&nik_peserta=${nikPeserta}&start_date=${start_date}&end_date=${end_date}&per_page=${per_page}`
        }

        $('#excel-export').on('click', function () {
            window.open('{{route('accident.export')}}?'+getParams(), '_blank');
        });

        $('#per_page').on('change', function () {
            $('#per_page_hidden').val($(this).val());
            $('#import-livestock-form').submit();
        });
    })
</script>


@stop