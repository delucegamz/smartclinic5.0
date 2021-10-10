
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Rekam Medis
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
<div id="medrec2">
    <div>
        <h2>Data Rekam Medis</h2>
        <div class="pull-right">
            <strong>Jumlah Pasien : {{$jumlah_pasien}} </strong>
        </div>
    
    </div>

    @if (!empty($medrec_list))
  
    
    <table class="table">
        <thead>
            <tr>
                <th>NIK Peserta</th>
                <th>Nama Peserta</th>
                <th>Factory</th>
                <th>Departemen</th>
                <th>Kode Diagnosa</th>
                <th>Nama Diagnosa</th>
                <th>View Data</th>
            </tr>
        </thead>
    
        <tbody>
        <?php foreach ($medrec_list as $medrecdata): ?>
            <tr>
                <td>{{ $medrecdata->nik_peserta}}</td>
                <td>{{ $medrecdata->nama_peserta}}</td>
                <td>{{ $medrecdata->nama_factory}}</td>
                <td>{{ $medrecdata->nama_departemen}}</td>
                <td>{{ $medrecdata->kode_diagnosa}}</td>
                <td>{{ $medrecdata->nama_diagnosa}}</td>
                <td>
                    <a id="detail" class="btn btn-default fa fa-eye" data-toggle='modal' 
                    data-target ="#modal-detail"
                    data-nik = "{{ $medrecdata->nik_peserta}}"
                    data-nama = "{{ $medrecdata->nama_peserta}}"
                    data-factory = "{{ $medrecdata->nama_factory}}"
                    data-departemen = "{{ $medrecdata->nama_departemen}}"
                    data-diagnosa = "{{ $medrecdata->kode_diagnosa}}"
                    data-namadiagnosa = "{{ $medrecdata->nama_diagnosa}}"
                    data-keluhan = "{{ $medrecdata->keluhan}}"
                    >detail</a>
                    
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    @else
    <p>Tidak ada data pasien</p>
    @endif 
</div>

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

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click', '#detail', function() {
          var niks = $(this).data('nik');
          var keluhans = $(this).data('keluhan');
          $('#nik_peserta').text(niks);
          $('#keluhan').text(keluhans);  
        })
    })
</script>
@stop