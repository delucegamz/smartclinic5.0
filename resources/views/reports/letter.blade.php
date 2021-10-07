
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Laporan Surat-Surat
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
<?php $pc = $participant; ?>
<div class="content-top-action clearfix" style="width:100%">
	<form method="get" action="{{ url( 'report/letter' ) }}" id="form">
		<div class="row-select-wrapper">
			<span>Peserta</span>
			<input type="text" name="participant" id="participant" placeholder="Nama Peserta" value="{{ $participant }}" />
            <input type="hidden" name="participant_id" id="participant_id" value="{{ $participant_id }}" />
		</div>

		<div class="row-select-wrapper">
			<span>Tampilkan</span>
			<select id="view" name="view">
				<option value="">- Pilih Surat -</option>
				<option value="day-off"{{ selected( 'day-off', $view, true ) }}>Surat Cuti</option>
				<option value="reference"{{ selected( 'reference', $view, true ) }}>Surat Referensi</option>
				<option value="sick"{{ selected( 'sick', $view, true ) }}>Surat Sakit</option>
			</select>
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
	<div class="table-responsive" style="width:100%">
		<div class="table-wrapper no-margin full-width" id="list-patient-history">
			@if( $view == 'day-off' )
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
								$poli_registration = App\PoliRegistration::find( $medrec->id_pendaftaran_poli );
								$participant = App\Participant::find( $data->id_peserta );
					?>
					<tr class="item" id="item-{{ $data->id_surat_cuti }}">
						<td class="column-no">{{ $i }}</td>
						<td class="column-medical-record"><a href="{{ url( 'medical-record') }}/{{ $data->id_pemeriksaan_poli }}">{{ $poli_registration->no_daftar }}</a></td>
						<td class="column-patient-id">{{ $participant->kode_peserta }}</td>
						<td class="column-patient-nik">{{ $participant->nik_peserta }}</td>
						<td class="column-name">{{ $participant->nama_peserta }}</td>
						<td class="column-sex">{{ get_participant_sex( $data->id_peserta ) }}</td>
						<td class="column-age">{{ get_participant_age( $data->id_peserta ) }}</td>
						<td class="column-action">
							<a href="#" title="Edit" class="edit" data-id="{{ $data->id_surat_cuti }}" data-code="{{ $data->id_surat_cuti }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
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
			<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
		        <div class="modal-dialog">
		            <div class="modal-content">
		                <div class="modal-body">
		                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
		                
		                    <span id="modal-icon"></span>

		                    <form id="add-item" action="" method="post" class="form-horizontal">
		                    	<input type="hidden" name="_method" value="PUT">

		                    	<h2>SURAT CUTI</h2>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-2" for="code">No.</label>
		                    		<div class="col-xs-10">
		 			 					<input type="text" class="form-control" placeholder="" name="no" id="no" disabled />
		                    		</div>
		                    	</div>
		                    	
		                    	<fieldset>
		                    		<legend>Data Cuti</legend>

		                    		<p>Yang berada di bawah ini menerangkan bahwa:</p>

		                    		<div class="form-group">
			                    		<label class="control-label col-xs-2" for="name">Nama</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control" placeholder="" name="name" id="name" disabled/>
			                    		</div>
			                    	</div>

			                    	<div class="form-group">
			                    		<label class="control-label col-xs-2" for="nik">NIK</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control" placeholder="" name="nik" id="nik" disabled/>
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
			                    		<label class="control-label col-xs-6" for="lama">Perlu mengambil cuti selama</label>
			                    		<div class="col-xs-3">
			 			 					<input type="text" class="form-control required" placeholder="" name="lama" id="lama" />
			                    		</div>
			                    	</div>

			                    	<div class="form-group">
			                    		<label class="control-label col-xs-2" for="dari_tanggal">Dari Tanggal</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control required" placeholder="" name="dari_tanggal" id="dari_tanggal" />
			                    		</div>
			                    	</div>

			                    	<div class="form-group">
			                    		<label class="control-label col-xs-2" for="sampai_tanggal">Sampai Tanggal</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control required" placeholder="" name="sampai_tanggal" id="sampai_tanggal" />
			                    		</div>
			                    	</div>

			                    	<div class="form-group">
			                    		<label class="control-label col-xs-2" for="jenis_cuti">Jenis Cuti</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control required" placeholder="" name="jenis_cuti" id="jenis_cuti" />
			                    		</div>
			                    	</div>

			                    	<div class="form-group">
			                    		<label class="control-label col-xs-2" for="dokter_jaga">Yang Memeriksa</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control required" placeholder="" name="dokter_jaga" id="dokter_jaga" />
			                    		</div>
			                    	</div>
		                    	</fieldset>


		                    	<div class="form-group last hide">
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
			@elseif( $view == 'reference' )
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
							<a href="#" title="Edit" class="edit" data-id="{{ $data->id_surat_rujukan }}" data-code="{{ $data->id_surat_rujukan }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
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
			<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
		        <div class="modal-dialog">
		            <div class="modal-content">
		                <div class="modal-body">
		                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
		                
		                    <span id="modal-icon"></span>

		                    <form id="add-item" action="" method="post" class="form-horizontal">
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


		                    	<div class="form-group last hide">
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
			@elseif( $view == 'sick' )
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
								$poli_registration = App\PoliRegistration::find( $medrec->id_pendaftaran_poli );
								$participant = App\Participant::find( $data->id_peserta );
					?>
					<tr class="item" id="item-{{ $data->id_surat_sakit }}">
						<td class="column-no">{{ $i }}</td>
						<td class="column-medical-record"><a href="{{ url( 'medical-record') }}/{{ $data->id_pemeriksaan_poli }}">{{ $poli_registration->no_daftar }}</a></td>
						<td class="column-patient-id">{{ $participant->kode_peserta }}</td>
						<td class="column-patient-nik">{{ $participant->nik_peserta }}</td>
						<td class="column-name">{{ $participant->nama_peserta }}</td>
						<td class="column-sex">{{ get_participant_sex( $data->id_peserta ) }}</td>
						<td class="column-age">{{ get_participant_age( $data->id_peserta ) }}</td>
						<td class="column-action">
							<a href="#" title="Edit" class="edit" data-id="{{ $data->id_surat_sakit }}" data-code="{{ $data->id_surat_sakit }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
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
			<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
		        <div class="modal-dialog">
		            <div class="modal-content">
		                <div class="modal-body">
		                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
		                
		                    <span id="modal-icon"></span>

		                    <form id="add-item" action="" method="post" class="form-horizontal">
		                    	<input type="hidden" name="_method" value="PUT">

		                    	<h2>SURAT KETERANGAN SAKIT</h2>

		                    	<div class="form-group">
		                    		<label class="control-label col-xs-2" for="code">No.</label>
		                    		<div class="col-xs-10">
		 			 					<input type="text" class="form-control" placeholder="" name="no" id="no" disabled />
		                    		</div>
		                    	</div>
		                    	
		                    	<fieldset>
		                    		<legend>Data Keterangan Sakit</legend>

		                    		<p>Yang berada di bawah ini menerangkan bahwa:</p>

		                    		<div class="form-group">
			                    		<label class="control-label col-xs-2" for="name">Nama</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control" placeholder="" name="name" id="name" disabled/>
			                    		</div>
			                    	</div>

			                    	<div class="form-group">
			                    		<label class="control-label col-xs-2" for="nik">NIK</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control" placeholder="" name="nik" id="nik" disabled/>
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
			                    		<label class="control-label col-xs-6" for="lama">Perlu beristirahat karena sakit selama</label>
			                    		<div class="col-xs-3">
			 			 					<input type="text" class="form-control required" placeholder="" name="lama" id="lama" />
			                    		</div>
			                    	</div>

			                    	<div class="form-group">
			                    		<label class="control-label col-xs-2" for="dari_tanggal">Dari Tanggal</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control required" placeholder="" name="dari_tanggal" id="dari_tanggal" />
			                    		</div>
			                    	</div>

			                    	<div class="form-group">
			                    		<label class="control-label col-xs-2" for="sampai_tanggal">Sampai Tanggal</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control required" placeholder="" name="sampai_tanggal" id="sampai_tanggal" />
			                    		</div>
			                    	</div>

			                    	<div class="form-group">
			                    		<label class="control-label col-xs-2" for="diagnosis">Diagnosa</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control required" placeholder="" name="diagnosis" id="diagnosis" disabled />
			                    		</div>
			                    	</div>

			                    	<div class="form-group">
			                    		<label class="control-label col-xs-2" for="dokter_jaga">Yang Memeriksa</label>
			                    		<div class="col-xs-10">
			 			 					<input type="text" class="form-control required" placeholder="" name="dokter_jaga" id="dokter_jaga" />
			                    		</div>
			                    	</div>
		                    	</fieldset>


		                    	<div class="form-group last hide">
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
			@else
			<div class="alert alert-warning" style="margin-bottom: 0px;">Harap pilih surat yang akan ditampilkan.</div>
			@endif
		</div>
	</div>

	@if ( $rows != 'all' && $view != '' )
	<div class="navigation clearfix">
	@if ( $datas->lastPage() > 1 )
        <ul class="pagination left clearfix">
        	@if ( $datas->currentPage() != 1 )
			<li class="pagination-item pagination-prev{{ ( $datas->currentPage() == 1 ) ? ' disabled' : '' }}">
		     	<a href="{{ $datas->url( $datas->currentPage() - 1 ) }}&rows={{ $rows }}&participant_id={{ $participant_id }}&view={{ $view }}" aria-label="Previous">
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
                    <a href="{{ $datas->url( $i ) }}&rows={{ $rows }}&participant_id={{ $participant_id }}&view={{ $view }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        </ul>
        <ul class="pagination right clearfix">
        	 @if ( $datas->currentPage() != $datas->lastPage() )
			<li class="pagination-item pagination-next{{ ( $datas->currentPage() == $datas->lastPage() ) ? ' disabled' : '' }}">
		      	<a href="{{ $datas->url( $datas->currentPage() + 1 ) }}&rows={{ $rows }}&participant_id={{ $participant_id }}&view={{ $view }}" aria-label="Next">
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
		<a href="{{ url( 'print/letter' ) }}?participant_id={{ $participant_id }}&view={{ $view }}" class="btn print" target="_blank">Print</a>
		<a href="{{ url( 'export/letter' ) }}?participant_id={{ $participant_id }}&view={{ $view }}" class="btn">Download</a>
	</div>
	@endif

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

	$('#list-items .item .column-action a.edit').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$('#state').val('edit');
		$('#id').val($id);

		$('#modal-add-item').modal('show');

		return false;
	});

	@if( $view == 'day-off' )
	$('#modal-add-item').on('hidden.bs.modal', function(e){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');
		$('#add-item').find('input[type=text]').val('');
		$('#state').val('add');
		$('#id').val('');
	}).on('shown.bs.modal', function(e){
		$id = $('#id').val();

        $.ajax({
            url: '{{ url( 'day-off-letter' ) }}/' + $id,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                
            },      
            complete: function() {
                
            },          
            success: function(json) {
            	$('#id').val(json.id_surat_cuti);
                $('#no').val(json.no_surat_cuti);
                $('#name').val(json.name);
                $('#nik').val(json.nik_peserta);
                $('#sex').val(json.sex);
                $('#age').val(json.age);
                $('#address').val(json.alamat)
                $('#lama').val(json.lama);
                $('#dari_tanggal').val(json.dari_tanggal);
                $('#sampai_tanggal').val(json.sampai_tanggal);
                $('#jenis_cuti').val(json.jenis_cuti);
                $('#dokter_jaga').val(json.dokter_jaga);

                $('#add-item').find('input[type=text]').attr('disabled', true);

                $('#add-item').attr('action', '{{ url( 'day-off-letter' ) }}/' + $id );
            }
        });

        

        $('#dari_tanggal, #sampai_tanggal').datepicker({
        	dateFormat : 'yy-mm-dd'
        });
	});;
	@elseif( $view == 'sick' )
	$('#modal-add-item').on('hidden.bs.modal', function(e){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');
		$('#add-item').find('input[type=text]').val('');
		$('#state').val('add');
		$('#id').val('');
	}).on('shown.bs.modal', function(e){
		$id = $('#id').val();

        $.ajax({
            url: '{{ url( 'sick-letter' ) }}/' + $id,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                
            },      
            complete: function() {
                
            },          
            success: function(json) {
            	$('#id').val(json.id_surat_sakit);
                $('#no').val(json.no_surat_sakit);
                $('#name').val(json.name);
                $('#nik').val(json.nik_peserta);
                $('#sex').val(json.sex);
                $('#age').val(json.age);
                $('#address').val(json.alamat)
                $('#lama').val(json.lama);
                $('#dari_tanggal').val(json.dari_tanggal);
                $('#sampai_tanggal').val(json.sampai_tanggal);
                $('#diagnosis').val(json.diagnosa);
                $('#dokter_jaga').val(json.dokter_jaga);

                $('#add-item').find('input[type=text]').attr('disabled', true);

                $('#add-item').attr('action', '{{ url( 'sick-letter' ) }}/' + $id );
            }
        });
    });
	@elseif( $view == 'reference' )
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

                $('#add-item').find('input[type=text]').attr('disabled', true);

                $('#add-item').attr('action', '{{ url( 'reference-letter' ) }}/' + $id );
            }
        });
	});
	@endif
});
</script>
@stop