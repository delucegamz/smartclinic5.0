@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Rekam Medis
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui-timepicker-addon.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/chosen.jquery.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@stop

@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/easy-autocomplete.min.css')}}" />
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.structure.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.theme.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui-timepicker-addon.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap-chosen.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('content')
<div id="medrec2">
    {!! Form::open(['id'=> 'medrec2form', 'url' => 'reports/medrec2', 'method' => 'GET']) !!}
    <div>
        <h2 align="center">Data Rekam Medis</h2>
        <div id="pencariandate">

            <div class="">
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="form-group">
                            <label for="filter_by">Filter By</label>
                            <select name="filter_by" class="form-control input-sm" id="filter_by" required>
                                <option value="">Pilih Filter</option>
                                <option value="poli" {{ ($filter_by=='poli' ) ? 'selected' : '' }}>Poli</option>
                                <option value="diagnosa" {{ $filter_by=='diagnosa' ? 'selected' : '' }}>
                                    Diagnosa
                                </option>
                                <option value="tanggal" {{ $filter_by=='tanggal' ? 'selected' : '' }}>Tanggal</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3 filter poli-filter"
                        style="display : {{$filter_by == 'poli' ? 'block' : 'none'}};">
                        <div class="form-group">
                            <label for="nama_poli">Poli</label>
                            <select name="nama_poli" class="form-control input-sm" id="nama_poli">
                                <option value="">Pilih Poli</option>
                                @foreach ($polies as $poli)
                                <option value="{{$poli->nama_poli}}" {{ $poli->nama_poli == $nama_poli ? 'selected' :
                                    ''}}>
                                    {{$poli->nama_poli}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3 filter diagnosa-filter"
                        style="display: {{$filter_by == 'diagnosa' ? 'block' : 'none'}};">
                        <div class="form-group">
                            <label for="diagnoses">Diagnosis</label>
                            <select name="kode_diagnosa" class="form-control input-sm select2" id="diagnoses">
                                <option value="">Pilih Diagnosa</option>
                                @foreach ($diagnoses as $diagnosa)
                                <option value="{{$diagnosa->kode_diagnosa}}" {{ $kode_diagnosa==$diagnosa->kode_diagnosa
                                    ? 'selected' : ''}}>
                                    {{ $diagnosa->kode_diagnosa }} - {{$diagnosa->nama_diagnosa}}
                                </option>
                                @endforeach
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

                    <div class="col-sm-1">
                        <label for="" style="visibility: hidden;">Cari</label>
                        {!! Form::button('cari', ['class' => 'btn btn-primary btn-block',
                        'style' => 'width: 150px','type' => 'submit']) !!}
                    </div>
                </div>
            </div>

        </div>

        <hr style="border: 1px solid">

        <div id="pencarian"
            style="margin-top: 20px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <div class="input-group" style="width:300px">
                <input id="nik_peserta" type="text" class="form-control" placeholder="masukan nik peserta"
                    name="nik_peserta" value="{{$nik_peserta}}">
                <span class="input-group-btn">
                    {!! Form::button('cari', ['class' => 'btn btn-default',
                    'style' => 'width: 150px','type' => 'submit']) !!}
                </span>
            </div>
            <button class="btn btn-default" type="button" id="excel-export">Expor Excel</button>
            {{-- <div class="pull-right">
            </div> --}}
        </div>

        <input type="hidden" name="per_page" id="per_page_hidden" value="{{$per_page}}">
    </div>
    {!! Form::close() !!}

    <div>
        <div class="form-group">
            <select class="form-control" style="max-width: 64px;" name="per_page" id="per_page">
                <option value="10" {{$per_page==10 ? 'selected' : '' }}>10</option>
                <option value="25" {{$per_page==25 ? 'selected' : '' }}>25</option>
                <option value="50" {{$per_page==50 ? 'selected' : '' }}>50</option>
            </select>
        </div>

        <div>
            <p>Total: {{$medrec_list->total()}}</p>
        </div>
    </div>
    @if (!empty($medrec_list))
    <table class="table">
        <thead>
            <tr>
                <th>NIK Peserta</th>
                <th>Nama Peserta</th>
                <th>Factory</th>
                <th>Poli</th>
                <th>Created_at</th>
                <th>Kode Diagnosa</th>
                <th>Nama Diagnosa</th>
                <th>View Data</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($medrec_list as $medrecdata): ?>
            <tr>
                <td>{{ $medrecdata->nik_peserta }}</td>
                <td>{{ $medrecdata->nama_peserta }}</td>
                <td>{{ $medrecdata->nama_factory }}</td>
                <td>{{ $medrecdata->nama_poli }}</td>
                <td>{{ $medrecdata->created_at }}</td>
                <td>{{ $medrecdata->iddiagnosa }}</td>
                <td>{{ $medrecdata->nama_diagnosa}}</td>
                <td>
                    <a id="detail" class="btn btn-default fa fa-eye" data-toggle='modal' data-target="#modal-detail"
                        data-nik="{{ $medrecdata->nik_peserta}}" data-nama="{{ $medrecdata->nama_peserta}}"
                        data-factory="{{ $medrecdata->nama_factory}}"
                        data-departemen="{{ $medrecdata->nama_departemen}}"
                        data-diagnosa="{{ $medrecdata->kode_diagnosa}}"
                        data-namadiagnosa="{{ $medrecdata->nama_diagnosa}}"
                        data-keluhan="{{ $medrecdata->keluhan}}">detail</a>

                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    @else
    <p>Tidak ada data pasien</p>
    @endif
    {{ $medrec_list->appends([
    'start_date' => $start_date, 'end_date' => $end_date, 'nik_peserta' => $nik_peserta,
    'filter_by' => $filter_by,
    'nama_poli' => $nama_poli,
    'kode_diagnosa' => $kode_diagnosa,
    'per_page' => $per_page,
    ])->links() }}
</div>

<!-- menampilkan modal detail saat klik tombol detail -->
<div class="modal fade" id="modal-detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times</span>
                </button>
                <h4 class="modal-title">Data Detail Rekam Medis </h4>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-bordered no-margin">
                    <tbody>
                        <tr>
                            <th style="">NIK Pasien</th>
                            <td><span id="nik_peserta"></span></td>
                        </tr>
                        <tr>
                            <th style="">Data Keluhan</th>
                            <td><span id="keluhan"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- variable ajax untuk isi modal tombol detail -->
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click', '#detail', function() {
          var niks = $(this).data('nik');
          var keluhans = $(this).data('keluhan');
          $('#nik_peserta').text(niks);
          $('#keluhan').text(keluhans);  
        });

        $('#filter_by').on('change', function () {
            const filterBy = $(this).val();
            
            $('.filter').hide();
            $('#nama_poli').val("");
            $('#diagnoses').val("");

            // tanggal
            if (filterBy == 'tanggal') {
                $('.date-filter').prop('required', true)
            } else {
                $('.date-filter').prop('required', false)
            }

            // diagnoses
            if (filterBy == 'diagnosa') {
                $('#diagnoses').prop('required', true);
            }
            else {
                $('#diagnoses').prop('required', false);

            }
            // poli
            if (filterBy == 'poli') {
                $('#nama_poli').prop('required', true);
            }
            else {
                $('#nama_poli').prop('required', false);

            }

            $('.'+filterBy+'-filter').show();

            if (filterBy == 'diagnosa') {
                $('.select2').select2();
            }
        });

        $('.select2').select2();

        function getParams() {
            let filter = $('#filter_by').val();
            let poli = $('#nama_poli').val();
            let diagnose = $('#diagnoses').val();
            let start_date = $('#start_date').val();
            let end_date = $('#end_date').val();
            let nikPeserta = $('#nik_peserta').val();
            let per_page = $('#per_page_hidden').val();

           return `filter_by=${filter}&nama_poli=${poli}&kode_diagnosa=${diagnose}&nik_peserta=${nikPeserta}&start_date=${start_date}&end_date=${end_date}&per_page=${per_page}`
        }

        $('#excel-export').on('click', function () {
            window.open('{{route('medrec.export')}}?'+getParams(), '_blank');
        });

        $('#per_page').on('change', function () {
            $('#per_page_hidden').val($(this).val());
            $('#medrec2form').submit();
        });
    });
</script>
@endsection