
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Data Pengguna
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
@stop

@section('styles')
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/css/easy-autocomplete.min.css')}}" />
@stop

@section('content')
<div class="content-title"><h1>Data Pengguna</h1></div>

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
		<form method="get" action="{{ route( 'user.index' ) }}">
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
					<th class="column-id-title">ID Pengguna</th>
					<th class="column-username-title">Username</th>
					<th class="column-id-staff-title">NIK Karyawan</th>
					<th class="column-nama-title">Nama</th>
					<th class="column-jabatan-title">Jabatan</th>
					<th class="column-status-title">Status</th>
					<th class="column-action-title">Action</th>
				<tr>
			<thead>
			<tbody>
				<?php
					if( count( $datas ) ){ 
						foreach ( $datas as $user ) {
							$staff = App\Staff::find( $user->id_karyawan );
				?>
				<tr class="item" id="item-{{ $user->id_pengguna }}">
					<td class="column-no">{{ $i }}</td>
					<td class="column-id">{{ $user->idpengguna }}</td>
					<td class="column-username">{{ $user->username }}</td>
					<td class="column-id-staff">{{ $staff->nik_karyawan }}</td>
					<td class="column-nama">{{ $staff->nama_karyawan }}</td>
					<td class="column-jabatan">{{ ( $staff->id_jabatan ? get_job_title_name( $staff->id_jabatan ) : '-' ) }}</td>
					<td class="column-status">{{ ( ( $user->status == 1 ) ? 'Aktif' : 'Tidak Aktif' ) }}</td>
					<td class="column-action">
						<a href="#" title="Edit" class="edit" data-id="{{ $user->idpengguna }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
						<!-- <div class="action-item first">
							<a href="#" title="Edit" class="edit" data-id="{{ $user->idpengguna }}"><img src="{{URL::asset('assets/images/icon-file.png')}}" alt="Edit" /></a>
						</div>
						<div class="action-item last">
							<a href="#" title="Delete" class="delete" data-id="{{ $user->idpengguna }}"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>
						</div> -->
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

	<div id="pagination" class="full-width">
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
	</div>

	<div class="download-item hide">
		<a href="#" class="btn">Download</a>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" id="modal-add-item">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                
                    <span id="modal-icon"></span>

                    <form id="add-item" action="{{ route( 'user.store' ) }}" method="post" class="form-horizontal">
                    	<input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}"/>
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="idpengguna">ID</label>
                    		<div class="col-xs-7">
 			 					<input type="text" class="form-control" placeholder="ID Pengguna" name="idpengguna" id="idpengguna" disabled />
								<div class="error-placement"></div>
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="nik_karyawan">NIK Karyawan</label>
                    		<div class="col-xs-7">
 			 					<input type="text" class="form-control required" placeholder="NIK Karyawan" name="nik_karyawan" id="nik_karyawan" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>
                    	
                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="nama_karyawan">Nama</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Nama Karyawan" name="nama_karyawan" id="nama_karyawan" disabled />
								<div class="error-placement"></div>
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="email">Email</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Alamat Email" name="email" id="email" disabled />
								<div class="error-placement"></div>
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="no_telp">No. Telp</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Nomor Telepon" name="no_telp" id="no_telp" disabled />
								<div class="error-placement"></div>
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="jabatan">Jabatan</label>
                    		<div class="col-xs-10">
 			 					<input type="text" class="form-control" placeholder="Jabatan" name="jabatan" id="jabatan" disabled />
								<div class="error-placement"></div>
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="username">Username</label>
                    		<div class="col-xs-7">
 			 					<input type="text" class="form-control required" placeholder="Username" name="username" id="username" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="password_1">Password</label>
                    		<div class="col-xs-7">
 			 					<input type="password" class="form-control" placeholder="Password" name="password_1" id="password_1" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>

                    	<div class="form-group">
                    		<label class="control-label col-xs-2" for="password_2">&nbsp;</label>
                    		<div class="col-xs-7">
 			 					<input type="password" class="form-control" placeholder="Konfirmasi Password" name="password_2" id="password_2" />
								<div class="error-placement"></div>
                    		</div>
                    	</div>

                    	<div class="form-group status-wrapper">
                    		<label class="control-label col-xs-2" for="status">Status</label>
                    		<div class="col-xs-10">
 			 					<select class="form-control" placeholder="Status" name="status" id="status">
 			 						<option value="1">Aktif</option>
 			 						<option value="0">Tidak Aktif</option>
 			 					</select>
								<div class="error-placement"></div>
                    		</div>
                    	</div>

                    	
                    	<fieldset id="access-rights-wrapper">
                    		<legend>Hak Akses</legend>

                    		<div id="access-rights">
								<!-- Nav tabs -->
								<ul class="nav nav-tabs" role="tablist">
								    <li role="presentation" class="active"><a href="#master-data-wrapper" aria-controls="master-data-wrapper" role="tab" data-toggle="tab">Master Data</a></li>
								    <li role="presentation"><a href="#kepesertaan-wrapper" aria-controls="kepesertaan-wrapper" role="tab" data-toggle="tab">Kepesertaan</a></li>
								    <li role="presentation"><a href="#karyawan-user-wrapper" aria-controls="karyawan-user-wrapper" role="tab" data-toggle="tab">Karyawan / User</a></li>
								    <li role="presentation"><a href="#pemeriksaan-medis-wrapper" aria-controls="pemeriksaan-medis-wrapper" role="tab" data-toggle="tab">Pemeriksaan Medis</a></li>
								    <li role="presentation"><a href="#laporan-wrapper" aria-controls="laporan-wrapper" role="tab" data-toggle="tab">Laporan</a></li>
								    <li role="presentation"><a href="#farmasi-wrapper" aria-controls="farmasi-wrapper" role="tab" data-toggle="tab">Farmasi</a></li>
								    <li role="presentation"><a href="#surat-wrapper" aria-controls="surat-surat-wrapper" role="tab" data-toggle="tab">Surat-Surat</a></li>
								</ul>

	  							<!-- Tab panes -->
	  							<div class="tab-content">
								    <div role="tabpanel" class="tab-pane active" id="master-data-wrapper">
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="master_data" /> Master Data
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="setting" /> Konfigurasi Umum
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_organisasi" /> Data Organisasi
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_legalitas" /> Data Legalitas
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_klien" /> Data Klien
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_factory" /> Data Factory
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_unit_kerja" /> Data Unit Kerja
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_diagnosa" /> Data Diagnosa
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_poli" /> Data Poli
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_tindakan" /> Data Tindakan / Pengobatan Observasi
								    		</label>
								    	</div>
								    </div>
								    <div role="tabpanel" class="tab-pane" id="kepesertaan-wrapper">
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="kepesertaan" /> Kepesertaan
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_peserta" /> Data Peserta
								    		</label>
								    	</div>
								    </div>
								    <div role="tabpanel" class="tab-pane" id="karyawan-user-wrapper">
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="karyawan_user" /> Karyawan & User
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_jabatan" /> Data Jabatan
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_karyawan" /> Data Karyawan
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_pengguna" /> Data Pengguna
								    		</label>
								    	</div>
								    </div>
								    <div role="tabpanel" class="tab-pane" id="pemeriksaan-medis-wrapper">
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="pemeriksaan_medis" /> Pemeriksaan Medis
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="pendaftaran_poli" /> Pendaftaran Poli
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="pendaftaran_igd" /> Pendaftaran IGD
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="pemeriksaan_dokter" /> Pemeriksaan Dokter
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="observasi" /> Observasi
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="ambulance" /> Ambulance
								    		</label>
								    	</div>
								    </div>
								    <div role="tabpanel" class="tab-pane" id="laporan-wrapper">
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan" /> Laporan
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_karyawan" /> Laporan Karyawan
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_peserta" /> Laporan Peserta
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_organisasi" /> Laporan Organisasi
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_kunjungan" /> Laporan Kunjungan
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_rekap_kunjungan" /> Laporan Rekap Kunjungan
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_rekam_medis" /> Laporan Rekam Medis
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_anc" /> Laporan ANC
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_observasi" /> Laporan Observasi
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_surat" /> Laporan Surat
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_pendaftaran" /> Laporan Pendaftaran
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_ambulance" /> Laporan Ambulance
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_stock_obat" /> Laporan Stok Obat
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_resep_dokter" /> Laporan Resep Dokter
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_obat_keluar" /> Laporan Obat Keluar
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_obat_masuk" /> Laporan Obat Masuk
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="laporan_top_10_penyakit" /> Laporan Top 10 Penyakit
								    		</label>
								    	</div>
								    </div>
								    <div role="tabpanel" class="tab-pane" id="farmasi-wrapper">
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="farmasi" /> Farmasi
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_golongan_obat" /> Data Golongan Obat
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_obat" /> Data Obat
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_obat_masuk" /> Data Obat Masuk
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="data_obat_keluar" /> Data Obat Keluar
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="resep_obat_dokter" /> Resep Obat Dokter
								    		</label>
								    	</div>
								    </div>
								    <div role="tabpanel" class="tab-pane" id="surat-wrapper">
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="surat" /> Surat
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="surat_rujukan" /> Surat Rujukan
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="surat_keterangan_sakit" /> Surat Keterangan Sakit
								    		</label>
								    	</div>
								    	<div class="checkbox">
								    		<label>
								    			<input type="checkbox" name="access-rights[]" value="surat_cuti" /> Surat Cuti
								    		</label>
								    	</div>
								    </div>
	 	 						</div>
                    		</div>
                    	</fieldset>
                    	
                    	
                    	<div class="form-group last">
                    		<div class="col-xs-12">
                    			<input type="submit" value="Simpan" id="add-submit" class="btn" />

                    			<div class="alert hide" id="form-alert"><span id="form-message"></span> <a href="#" class="close">&times;</a></div>
                    		</div>
                    	</div>
                    	<input type="hidden" name="id" value="" id="id" />
                    	<input type="hidden" name="id_karyawan" value="" id="id_karyawan" />
                    	<input type="hidden" name="state" value="add" id="state" />
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div><!-- /.entry-content  -->

<script type="text/javascript">
$(document).ready(function(){
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

	var staff_suggestion = function(e){
		var code = (e.keyCode ? e.keyCode : e.which);

		if(code == 13) {
		    e.preventDefault();

		    var $value = $('#nik_karyawan').val();

		    $.ajax({
		        url: '{{ url( 'staff/search_staff' ) }}',
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
		                        $('#nama_karyawan').val(json.nama_karyawan);
		                        $('#id_karyawan').val(json.id_karyawan);
		                        $('#email').val(json.email);
		                        $('#no_telp').val(json.no_telepon);
		                        $('#jabatan').val(json.jabatan);
		                        $('#nik_karyawan').val(json.nik_karyawan);
		                    }else{
		                        alert('Karyawan dengan nik ' + json.nik_karyawan + ' sudah tidak aktif.');
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
		                                var selectedItemValue = $("#nik_karyawan").getSelectedItemData();
		                            },
		                            onClickEvent: function() {
		                                var selectedItemValue = $("#nik_karyawan").getSelectedItemData();
		                            },
		                            onHideListEvent: function() {
		                                
		                            },
		                            onChooseEvent: function(){
		                                var selectedItemValue = $("#nik_karyawan").getSelectedItemData();

		                                $('#nama_karyawan').val(selectedItemValue.nama_karyawan);
		                                $('#id_karyawan').val(selectedItemValue.id_karyawan);
		                                $('#email').val(selectedItemValue.email);
		                                $('#no_telp').val(selectedItemValue.no_telepon);
		                                $('#jabatan').val(selectedItemValue.jabatan);
		                                $('#nik_karyawan').val(selectedItemValue.nik_karyawan);

		                                $('#nik_karyawan').unbind();
		                                $('#nik_karyawan').keyup(function(a){
											staff_suggestion(a);
										});
		                            }
		                        }
		                    };

		                    $("#nik_karyawan").easyAutocomplete(options);

		                    var b = jQuery.Event("keyup", { keyCode: 32, which: 32});
						    $("#nik_karyawan").focus();
						    $("#nik_karyawan").triggerHandler(b);
						    $("#nik_karyawan").trigger('change');
		                }
		            }else{
		                alert('Data tidak ditemukan.');
		            }
		        }
		    });
		}
	}

	$('#collapseFour').addClass('in');
	
	$('#add-item').validate({
		errorPlacement: function(error, element) { 
			var selector = $(element.context).attr('id');

     		error.appendTo($('#' + selector).parents('div[class*="col-xs"]').find('.error-placement'));
   		},
		submitHandler: function(form) {
	       	$state = $('#state').val();
	       	$url = $(form).attr('action');
	       	$type = 'POST';
	       	$id = $('#id').val();
	       	$rows = $('#rows').val();
	       	$formData = $(form).serialize();
	       	$formData = $formData + '&rows=' + $rows;

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
	               	
	            },      
	            complete: function() {
	            	
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$(form).find('input[type=text]').val('');
	            		$(form).find('input[type=password]').val('');
	            		$(form).find('select').find('option').removeAttr('selected').eq(0).attr('selected',true);
	            		$('#s').val('');
	            		$('input[name="access-rights[]"]').removeAttr('checked');

	            		$('#form-message').html(json.message);
	            		$('#form-alert').removeClass('alert-danger').addClass('alert-success').removeClass('hide');

	            		$('#list-items tbody').html(json.html);
	            		$('#pagination').html(json.pagination);

	            		$.ajax({
				            url: $(form).attr('action') + '/latest_id',
				            type: 'GET',
				            dataType: 'json',
				            beforeSend: function() {
				               	
				            },      
				            complete: function() {
				            	
				            },          
				            success: function(json) {
				            	$('#idpengguna').val(json.latest_id);
				            }
				        });

				        $('#state').val('add');
				        $('#id').val('');
				        $('#nik_karyawan').removeAttr('disabled');
				        $('#username').removeAttr('disabled');
	            	}else{
	            		$('#form-message').html(json.message);
	            		$('#form-alert').addClass('alert-danger').removeClass('hide');
	            	}
	            }
	        });

		    return false;
		}
	});

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
		$('#add-item').find('select').find('option').removeAttr('selected').eq(0).attr('selected',true);
		$('#nik_karyawan').removeAttr('disabled');
		$('#state').val('add');
		$('#id').val('');
		$('#id_karyawan').val('');
		$('input[name="access-rights[]"]').removeAttr('checked');
	}).on('shown.bs.modal', function(e){
		$id = $('#id').val();
		$url = $('#add-item').attr('action');

		if($id != ''){
			$.ajax({
	            url: $url + '/' + $id,
	            type: 'GET',
	            dataType: 'json',
	            beforeSend: function() {
	               	
	            },      
	            complete: function() {
	            	
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
		            	$('#nama_karyawan').val(json.staff.nama_karyawan);
	                    $('#id_karyawan').val(json.staff.id_karyawan);
	                    $('#email').val(json.staff.email);
	                    $('#no_telp').val(json.staff.no_telepon);
	                    $('#jabatan').val(json.jabatan);
	                    $('#nik_karyawan').val(json.staff.nik_karyawan).attr('disabled', true);
	                    $('#idpengguna').val(json.user.idpengguna);
	                    $('#username').val(json.user.username).attr('disabled', true);
	                    $('#status').val(json.user.status);

	                    if(json.access_rights){
		                    $('input[name="access-rights[]"]').each(function(){
		                    	$val = $(this).val();

		                    	if(json.access_rights.indexOf($val) > -1){
		                    		$(this).attr('checked', true);
		                    	}
		                    });
		                }
	                }else{
	                	alert(json.message);
	                }
	            }
	        });

			$('#password_1').rules('remove', 'required');
			$('#password_2').rules('remove', 'required equalTo');

			$('#password_2').rules('add', {
				equalTo: $('#password_1')
			});
		}else{
			$.ajax({
	            url: $url + '/latest_id',
	            type: 'GET',
	            dataType: 'json',
	            beforeSend: function() {
	               	
	            },      
	            complete: function() {
	            	
	            },          
	            success: function(json) {
	            	$('#idpengguna').val(json.latest_id);
	            }
	        });

	        $('#password_1').rules('add', {
				required: true
			});

			$('#password_2').rules('add', {
				required: true,
				equalTo: $('#password_1')
			});
		}

		$('#nik_karyawan').keyup(function(event){
			staff_suggestion(event);
		});
	});

	$('#list-items .item .column-action a.edit').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$('#state').val('edit');
		$('#id').val($id);

		$('#modal-add-item').modal('show');

		return false;
	});

	$('#form-alert .close').click(function(){
		$('#form-alert').removeClass('alert-danger').removeClass('alert-success').addClass('hide');
		$('#form-message').html('');

		return false;
	});

	$('#list-items .item .column-action a.delete').live('click', function(){
		$id = $(this).attr('data-id');
		$item = $('tr#item-' + $id);

		$confirm = confirm('Anda yakin ingin menghapus poli ini?');

		if($confirm){
			$url = $('#add-item').attr('action');
			$url = $url + '/' + $id;

			$.ajax({
	            url: $url,
	            type: 'delete',
	            data: {},
	            dataType: 'json',
	            beforeSend: function() {
	               
	            },      
	            complete: function() {
	            
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$('#s').val('');
	            		$('#rows').find('option').removeAttr('selected').eq(0).attr('selected', true);
	            		$('#list-items tbody').html(json.html);
	            		$('#pagination').html(json.pagination);
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
		$action = '{{ route( 'user.index' ) }}';

		$url = $action + '?rows=' + $rows + '&page=' + $page + '&s=' + $s;

		window.location.href= $url;
	});
});
</script>
@stop