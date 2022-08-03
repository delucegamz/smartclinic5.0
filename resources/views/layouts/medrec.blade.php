@extends( 'layouts.app' )

@section( 'page_title' )
Smart Clinic System - Medical Record
@stop

@section( 'styles' )
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap-toggle.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.structure.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.theme.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui-timepicker-addon.css')}}">
@stop

@section( 'scripts' )
<script type="text/javascript" src="{{URL::asset('assets/js/bootstrap-toggle.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui-timepicker-addon.js')}}"></script>
@stop

@section( 'content' )

@if( isset( $_GET['submitted'] ) && $_GET['submitted'] == 'true' )
<div class="alert {{ ( $_GET['success'] ) ? 'alert-success' : 'alert-danger' }}">
    @if( $_GET['success'] )
    Rekam medis berhasil diperbarui.
    @else
    Telah terjadi kesalahan ketika memperbarui data rekam medis.
    @endif
    <a href="#" class="close" data-dismiss="alert">&times;</a>
</div>
@endif

<div class="content-title"><img src="{{URL::asset('assets/images/title-medical-record.png')}}" alt="Medical Record" /></div>

@php 
$uraian = $poli_check->uraian; 
$igd = (int) get_setting( 'igd' );
$poli_umum = (int) get_setting( 'poli_umum' );
$poli_kebidanan = (int) get_setting( 'poli_kebidanan' );
@endphp

<div class="entry-content">
	<div id="medrec-wrapper">
		<div id="patient-detail">
			<div class="patient-detail-wrapper clearfix">
				<div class="patient-photo-wrapper">
					<div class="patient-photo">
						<img src="{{URL::asset('assets/images/patient.png')}}" />
					</div>
					<div class="patient-visit">
						<span class="patient-visit-text">Kunjungan Karyawan</span>
						<div class="progress">
						  	<div class="progress-bar" role="progressbar" aria-valuenow="{{ get_visit( $participant->id_peserta ) }}" style="min-width: 2em;">
						    {{ get_visit( $participant->id_peserta ) }}
						  	</div>
						</div>
					</div>
				</div>
				<div class="patient-detail">
					<h4 class="patient-name">{{ $participant->nama_peserta }}</h4>
					<span class="patient-label">No. Daftar</span><span class="colon">:</span><span class="patient-value">{{ $poliregistration->no_daftar }}</span><br />
					<span class="patient-label">NIK</span><span class="colon">:</span><span class="patient-value">{{ $participant->nik_peserta }}</span><br />
					<span class="patient-label">Departemen</span><span class="colon">:</span><span class="patient-value">{{ get_department_name( $participant->id_departemen ) }}</span><br />
					<span class="patient-label">Factory</span><span class="colon">:</span><span class="patient-value">{{ get_participant_factory( $participant->id_peserta ) }}</span><br />
					<span class="patient-label">Client</span><span class="colon">:</span><span class="patient-value">{{ get_participant_client( $participant->id_peserta ) }}</span><br />
				</div>
			</div>
		</div>

		<div id="patient-desc">
			<div class="patient-desc-wrapper">
				<h3 class="patient-desc-title">Keterangan Peserta</h3>

				<div class="patient-desc">
					<div class="patient-row clearfix">
						<span class="patient-label">Umur Peserta</span>
						<span class="patient-value">{{ get_participant_age( $participant->id_peserta ) }}</span>
					</div>
					<div class="patient-row clearfix">
						<span class="patient-label">Alergi Obat</span>
						<span class="patient-value" id="list-allergic">{{ get_participant_medicine_allergic( $participant->id_peserta ) }}</span>
					</div>
					<div class="patient-row clearfix">
						<span class="patient-label">Catatan Pemeriksaan Sebelumnya</span>
						<span class="patient-value">{{ get_participant_last_checkup_note( $poliregistration->id_pendaftaran, $participant->id_peserta ) }}</span>
					</div>
				</div>

				<div class="action-type">
					<div class="action-type-wrapper">
						<h6>Jenis Tindakan Pemeriksaan</h6>

						<div class="checbox-wrapper">
							<label>
								<i class="fa <?php echo $uraian == 11 ? 'fa-dot-circle-o' : 'fa-circle-thin'; ?>" data-action="11"></i> Umum
							</label>

							
							<label>
								<i class="fa <?php echo $uraian == 44 ? 'fa-dot-circle-o' : 'fa-circle-thin'; ?>" data-action="44"></i> Kontrol Kecelakaan Kerja
							</label>

							<label>
								<i class="fa <?php echo $uraian == 99 ? 'fa-dot-circle-o' : 'fa-circle-thin'; ?>" data-action="99"></i> Kontrol Kecelakaan Lalu Lintas
							</label>
							
					
							<label>
								<i class="fa <?php echo $uraian == 55 ? 'fa-dot-circle-o' : 'fa-circle-thin'; ?>" data-action="55"></i> Kontrol Pasca Rawat Inap
							</label>
							

							<label>
								<i class="fa <?php echo $uraian == 33 ? 'fa-dot-circle-o' : 'fa-circle-thin'; ?>" data-action="33"></i> Kecelakaan Lalu Lintas
							</label>

							<label>
								<i class="fa <?php echo $uraian == 22 ? 'fa-dot-circle-o' : 'fa-circle-thin'; ?>" data-action="22"></i> Kecelakaan Kerja
							</label>
<!-- 							
 -->
							@if( $poliregistration->id_poli == $poli_kebidanan )
							<label>
								<i class="fa <?php echo $uraian == 66 ? 'fa-dot-circle-o' : 'fa-circle-thin'; ?>" data-action="66"></i> ANC
							</label>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>

		<form id="medrec-form" action="{{ route( 'medical-record.update', [ 'id' => $poliregistration->id_pendaftaran ] ) }}" method="post">
			<?php $token = csrf_token(); ?>
			<input name="_token" id="_token" type="hidden" value="{{ $token }}" />
			<input type="hidden" name="_method" value="PUT">


			<div id="action-tab">

			  	<!-- Nav tabs -->
			  	<ul class="nav nav-tabs" role="tablist">
			    	<li role="presentation" class="active" id="doctor-diagnostic-tab"><a href="#doctor-diagnostic-elm" aria-controls="doctor-diagnostic-elm" role="tab" data-toggle="tab" id="home-tab"><span class="icon"></span>Diagnosa Dokter</a></li>
			    	<li role="presentation" id="other-action"><a href="#other-action-elm" aria-controls="other-action-elm" role="tab" data-toggle="tab"><span class="icon"></span>Tindakan Lainnya</a></li>
			    	<li role="presentation" id="medicine-allergic"><a href="#medicine-allergic-elm" aria-controls="medicine-allergic-elm" role="tab" data-toggle="tab"><span class="icon"></span>Alergi Obat</a></li>
			    	@if( $poliregistration->id_poli == 2 )
			    	<li role="presentation" id="anc"{{ ( $uraian == 66 ) ? " style=display:block;" : " style=display:none;" }}><a href="#anc-elm" aria-controls="anc-elm" role="tab" data-toggle="tab"><span class="icon"></span>Pemeriksaan ANC</a></li>
			    	@endif
			    	<li role="presentation" id="patient-history"><a href="#patient-history-elm" aria-controls="patient-history-elm" role="tab" data-toggle="tab"><span class="icon"></span>Riwayat Peserta</a></li>
			    	@if( $poliregistration->id_poli == 1 )
			    	<li role="presentation" id="accident-history"><a href="#accident-history-elm" aria-controls="accident-history-elm" role="tab" data-toggle="tab"><span class="icon"></span>Riwayat Kecelakaan</a></li>
			    	@endif
			  	</ul>

			  	<!-- Tab panes -->
			  	<div class="tab-content">
			    	<div role="tabpanel" class="tab-pane active" id="doctor-diagnostic-elm">
			    		<div id="client-form" class="form-horizontal">
							<div class="form-group">
								<label class="control-label col-xs-2" for="doctor">Dokter Rawat</label>
								<div class="col-xs-4">
									<div class="input-group">
					  					<span class="input-group-addon"><i class="fa fa-user-md"></i></span>
										<select name="doctor" id="doctor" class="form-control required" data-placeholder="Pilih Dokter">
											<option value="">Pilih Dokter</option>
											@foreach( $doctors as $doctor )
											@php
											$is_bd = check_job_title( $doctor->id_jabatan ) == 'Bd.' ? 1 : 0;
											@endphp
											@if( $doctor->nama_karyawan == $poli_check->dokter_rawat )
											<option value="{{ $doctor->nama_karyawan }}" selected data-bd="{{ $is_bd }}">{{ check_job_title( $doctor->id_jabatan ) . ' ' . $doctor->nama_karyawan }}</option>
											@else
											<option value="{{ $doctor->nama_karyawan }}" data-bd="{{ $is_bd }}">{{ check_job_title( $doctor->id_jabatan ) . ' ' . $doctor->nama_karyawan }}</option>
											@endif
											@endforeach
										</select>
										<!-- <input type="text" class="form-control required" id="doctor" name="doctor" placeholder="Nama Dokter" value="{{ $poli_check->dokter_rawat }}" /> -->
									</div>
									<div class="error-placement"></div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-2" for="patient-brief">Keluhan Pasien</label>
								<div class="col-xs-5">
									<div class="input-group">
					  					<span class="input-group-addon"><i class="fa fa-comment"></i></span>
										<textarea name="patient-brief" id="patient-brief" class="form-control" placeholder="Keluhan Pasien" rows="3">{{ $poli_check->keluhan }}</textarea>
									</div>
									<div class="error-placement"></div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-2" for="doctor-diagnostic">Diagnosa Dokter</label>
								<div class="col-xs-5">
									<div class="input-group">
					  					<span class="input-group-addon"><i class="fa fa-stethoscope"></i></span>
										<input type="text" name="doctor-diagnostic" id="doctor-diagnostic" class="form-control" placeholder="Diagnosa Dokter" value="{{ $poli_check->diagnosa_dokter }}" />
									</div>
									<div class="error-placement"></div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-2" for="icdx">ICD (X)</label>
								<div class="col-xs-4">
									<div class="input-group">
					  					<span class="input-group-addon"><i class="fa fa-heartbeat"></i></span>
										<input type="text" name="icdx" id="icdx" class="form-control required" placeholder="Code" value="{{ $poli_check->iddiagnosa }}" />
										<span class="input-group-addon"><span class="input-group-btn"><button class="btn"><i class="fa fa-search"></i></button></span></span>
									</div>
									<div class="error-placement"></div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-2" for="icdx">&nbsp;</label>
								<div class="col-xs-4">
									<input type="text" name="icdx-text" id="icdx-text" class="form-control required" placeholder="" value="{{ $diagnosis->nama_diagnosa }}" />
									<input type="hidden" name="icdx-id" id="icdx-id" value="{{ $diagnosis->id_diagnosa }}" />
									<div class="error-placement"></div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-2" for="doctor-note">Catatan Dokter</label>
								<div class="col-xs-5">
									<div class="input-group">
					  					<span class="input-group-addon"><i class="fa fa-medkit"></i></span>
										<textarea name="doctor-note" id="doctor-note" class="form-control" placeholder="Catatan Dokter">{{ $poli_check->catatan_pemeriksaan }}</textarea>
									</div>
									<div class="error-placement"></div>
								</div>
							</div>
							
							<div class="form-group">
								<label class="control-label col-xs-2">&nbsp;</label>
								<div class="col-xs-8">
									<button type="submit" class="btn form-control btn-save" id="btn-save">Simpan</button>  
								</div>
							</div>
							<div class="form-group last">
								<label class="control-label col-xs-2">&nbsp;</label>
								<div class="col-xs-8">
									<a href="{{ route( 'medical-record.index' ) }}" class="btn form-control btn-cancel" id="btn-cancel">Batal</a>  
								</div>
							</div>
							<input type="hidden" name="id-pemeriksaan" value="{{ $poli_check->id_pemeriksaan_poli }}" id="id-pemeriksaan" />
							<input type="hidden" name="id-pendaftaran" value="{{ $poli_check->id_pendaftaran_poli }}" id="id-pendaftaran" />
							<input type="hidden" name="id-peserta" value="{{ $poli_check->id_peserta }}" id="id-peserta" />
					    	<input type="hidden" name="action-type" id="action-type" value="{{ $uraian }}" />
						</div>
			    	</div>
			    	<div role="tabpanel" class="tab-pane" id="other-action-elm">
			    		<div id="other-action-form">
		    				<div class="form-group">
		    					<h5>Keterangan Penyakit Lain</h5>

		    					<div class="checkbox">
		    						<label>
		    							Suspek Tuberkulosis <input type="checkbox" data-toggle="toggle" id="tbc-suspect" name="tbc-suspect" value="{{ $poli_check->tb }}"<?php if( $poli_check->tb ) echo " checked" ?> />
		    						</label>
		    					</div>
		    					<div class="checkbox">
		    						<label>
		    							Penyakit Akibat Hubungan Kerja <input type="checkbox" data-toggle="toggle" id="work-dismissed" name="work-dismissed" value="{{ $poli_check->pahk }}"<?php if( $poli_check->pahk ) echo " checked" ?> />
		    						</label>
		    					</div>
		    				</div>
		    				<div class="form-group">
		    					<h5>Tindakan yang dibutuhkan Lainnya</h5>

		    					<div class="checkbox">
		    						<label>
		    							<?php 
		    								$is_observation = $observation ? 1 : 0;
		    							?>
	    							Melakukan Tindakan Medis <input type="checkbox" data-toggle="toggle" id="need-observation" name="need-observation" value="{{ $is_observation }}"<?php if( $is_observation ) echo " checked disabled" ?> />

		    						</label>
		    					</div>
		    				</div>
		    				<div class="form-group">
		    					<h5>Surat dan Resep</h5>

		    					<div class="checkbox">
		    						<label>
		    							<?php 
		    								$is_sick_letter = $sickletter ? 1 : 0;
		    							?>

		    							Surat Keterangan Sakit <input type="checkbox" data-toggle="toggle" id="need-sick-letter" name="need-sick-letter" value="{{ $is_sick_letter }}"<?php if( $is_sick_letter ) echo " checked disabled" ?> />
		    						</label>
		    					</div>
		    					<div class="checkbox">
		    						<label>
		    							<?php 
		    								$is_reference_letter = $referenceletter ? 1 : 0;
		    							?>

		    							Surat Rujukan <input type="checkbox" data-toggle="toggle" id="need-reference-letter" name="need-reference-letter" value="{{ $is_reference_letter }}"<?php if( $is_reference_letter ) echo " checked disabled" ?> />
		    						</label>
		    					</div>
		    					<div class="checkbox">
		    						<label>
		    							<?php 
		    								$is_day_off_letter = $dayoffletter ? 1 : 0;
		    							?>

		    							Surat Cuti <input type="checkbox" data-toggle="toggle" id="need-day-off-letter" name="need-day-off-letter" value="{{ $is_day_off_letter }}"<?php if( $is_day_off_letter ) echo " checked disabled" ?> />
		    						</label>
		    					</div>
		    					<div class="checkbox">
		    						<label>
		    							<?php 
		    								$is_doctor_recipe = $doctorrecipe ? 1 : 0;
		    							?>
		    							Resep Obat <input type="checkbox" data-toggle="toggle" id="need-doctor-recipe" name="need-doctor-recipe" value="{{ $is_doctor_recipe }}"<?php if( $is_doctor_recipe ) echo " checked disabled" ?> />
		    						</label>
		    					</div>
		    				</div>
			    		</div>

			    		<div class="form-horizontal">
			    			<div class="form-group">
								<label class="control-label col-xs-2">&nbsp;</label>
								<div class="col-xs-8">
									<input type="submit" name="submit" value="Simpan" class="btn form-control btn-save btn-action" />   
								</div>
							</div>
							<div class="form-group last">
								<label class="control-label col-xs-2">&nbsp;</label>
								<div class="col-xs-8">
									<a href="{{ route( 'medical-record.index' ) }}" class="btn form-control btn-cancel btn-action">Batal</a>  
								</div>
							</div>
			    		</div>
			    	</div>
			    	<div role="tabpanel" class="tab-pane" id="medicine-allergic-elm">
			    		<div id="medicine-allergic" class="form-inline">
			    			<div class="form-group">
			    				<label for="medicine-code">Kode Obat</label>
			    				<div class="input-group">
									<input type="text" name="medicine-code" id="medicine-code" class="form-control" placeholder="" />
									<span class="input-group-addon"><span class="input-group-btn"><button class="btn"><i class="fa fa-search"></i></button></span></span>
								</div>
			    			</div>
			    			<div class="form-group">
								<input type="button" class="btn btn-save-medicine" id="btn-save-medicine" value="Masukan Obat" />
								<input type="hidden" name="medicine-id" id="medicine-id" value="" />
			    			</div>
			    		</div>

			    		<div id="medicine-allergic-list">

				    		<div class="table-wrapper no-margin full-width" id="list-allergic-medicine">
				    			<div class="alert hide" id="form-alert"><span id="form-message"></span> <a href="#" class="close">&times;</a></div>

								<table class="table table-bordered table-striped list-table" id="list-medicines">
									<thead>
										<tr>
											<th class="column-no-title">No.</th>
											<th class="column-group-title">Golongan Obat</th>
											<th class="column-code-title">Kode Obat</th>
											<th class="column-name-title">Nama Obat</th>
											<th class="column-action-title">Action</th>
										<tr>
									<thead>
									<tbody>
									@if( count( $medicineallergics ) )
										<?php $i = 1; ?> 
										@foreach( $medicineallergics as $ma )
										<?php $medicine = App\Medicine::find( $ma->idobat ); ?>
										<tr class="item" id="item-{{ $ma->idobat }}">
											<td class="column-no">{{ $i }}</td>
											<td class="column-group">{{ get_medicine_group_name( $medicine->id_golongan_obat ) }}</td>
											<td class="column-code">{{ $medicine->kode_obat }}</td>
											<td class="column-name">{{ $medicine->nama_obat }}</td>
											<td class="column-action">
												<a href="#" title="Delete" class="delete" data-id="{{ $ma->idobat }}"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>
												<input type="hidden" name="medicine_id[]" value="{{ $ma->idobat }}" class="medicine_id" />
												<input type="hidden" name="medicine_state[]" value="nothing" class="medicine_state" />
											</td>
										</tr>
										<?php $i++; ?>
										@endforeach
									@else
										<tr class="no-data">
											<td colspan="5">Tidak ada data ditemukan.</td>
										</tr>
									@endif
										
									</tbody>
								</table>
							</div>

							<div class="form-horizontal" style="padding-top:30px;">
				    			<div class="form-group">
									<label class="control-label col-xs-2">&nbsp;</label>
									<div class="col-xs-8">
										<input type="submit" name="submit" value="Simpan" class="btn form-control btn-save btn-action" />   
									</div>
								</div>
								<div class="form-group last">
									<label class="control-label col-xs-2">&nbsp;</label>
									<div class="col-xs-8">
										<a href="{{ route( 'medical-record.index' ) }}" class="btn form-control btn-cancel btn-action">Batal</a>  
									</div>
								</div>
				    		</div>
						</div>
			    	</div>
			    	@if( $poliregistration->id_poli == 2 )
			    	@php
			    		if( !isset( $anc->id_pemeriksaan_anc ) && !$anc->id_pemeriksaan_anc ){
			    			$anc = $last_anc;
			    		}
			    	@endphp
			    	<div role="tabpanel" class="tab-pane" id="anc-elm">
			    		<div id="anc-wrapper" class="form-horizontal">
			    			<div class="anc-description">
			    				<div class="anc-number">Nomor Pemeriksaan ANC: {{ get_anc_number( $poli_check->id_pemeriksaan_poli, $poliregistration->id_peserta ) }}</div>
			    				<div class="anc-visit">ANC Ke: {{ get_anc_visit( $poli_check->id_pemeriksaan_poli, $poliregistration->id_peserta ) }}</div>
			    			</div>

			    			<div class="anc-imunitation">
			    				<div class="form-group">
			    					<label class="col-sm-2 control-label" for="status_tt">Status Imunisasi TT:</label>
			    					<div class="col-sm-2">
			    						<select class="form-control" name="status_tt" id="status_tt">
			    							<option value="">- Pilih Opsi -</option>
			    							<option value="TT1"{{ selected( 'TT1', $anc->status_tt, true ) }}>TT1</option>
			    							<option value="TT2"{{ selected( 'TT2', $anc->status_tt, true ) }}>TT2</option>
			    							<option value="TT3"{{ selected( 'TT3', $anc->status_tt, true ) }}>TT3</option>
			    						</select>
			    					</div>
			    					<div class="col-sm-6">&nbsp;</div>
			    					<div class="col-sm-2">
			    						<a href="#" class="btn btn-primary reset-anc" style="width:100%;">Kehamilan Baru</a>
			    					</div>
			    				</div>
			    			</div>

			    			<div class="panel-group" id="anc-accordion" role="tablist" aria-multiselectable="true">
							    <div class="panel panel-default">
							        <div class="panel-heading" role="tab" id="anc-heading-one">
							            <h4 class="panel-title">
									        <a role="button" data-toggle="collapse" data-parent="#anc-accordion" href="#anc-one" aria-expanded="true" aria-controls="anc-one">
									        	ANC 1*(satu)
									        </a>
									    </h4>
							        </div>
							        <div id="anc-one" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="anc-heading-one">
							            <div class="panel-body">
							            	<div class="clearfix anc-status-blood">
							            		<div class="anc-status pull-left">
								            		Status Pemeriksaan : 
								            		<span>
								            		@if( get_anc_visit( $poli_check->id_pemeriksaan_poli, $poliregistration->id_peserta ) > 1 )
								            			<strike>Baru</strike> / <strong>Lama</strong>
								            		@else
								            			<strong>Baru</strong> / <strike>Lama</strike>
								            		@endif
								            		</span>
								            	</div>
								            	<div class="blood-type pull-right">
								            		<label class="control-label col-sm-8" for="golongan_darah">Golongan Darah:</label>
							    					<div class="col-sm-4">
							    						<select class="form-control" name="golongan_darah" id="golongan_darah">
							    							<option value="">- Pilih -</option>
							    							<option value="A"{{ selected( 'A', $pregnant->golongan_darah, true ) }}>A</option>
							    							<option value="B"{{ selected( 'B', $pregnant->golongan_darah, true ) }}>B</option>
							    							<option value="AB"{{ selected( 'AB', $pregnant->golongan_darah, true ) }}>AB</option>
							    							<option value="O"{{ selected( 'O', $pregnant->golongan_darah, true ) }}>O</option>
							    						</select>
							    					</div>
								            	</div>
							            	</div>
							            	
							            	<div class="row">
							            		<div class="col-sm-5">
							            			<div class="anc-item">
							            				<h4 class="anc-heading">Riwayat Obstetrik</h4>
							            				<div class="anc-item-wrapper">
								            				<div class="row">
								            					<div class="col-sm-6">
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="gravida">Gravida</label>
								            							<div class="col-sm-6">
								            								<input type="text" name="gravida" id="gravida" class="form-control" value="{{ $pregnant->gravida }}" />
								            							</div>
								            						</div>
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="partus">Partus</label>
								            							<div class="col-sm-6">
								            								<input type="text" name="partus" id="partus" class="form-control" value="{{ $pregnant->partus }}" />
								            							</div>
								            						</div>
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="hidup">Trimester</label>
								            							<div class="col-sm-6">
								            								<input type="text" name="tm" id="tm" class="form-control" value="{{ $anc->tm }}" />
								            							</div>
								            						</div>
								            					</div>
								            					<div class="col-sm-6">
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="abortus">Abortus</label>
								            							<div class="col-sm-6">
								            								<input type="text" name="abortus" id="abortus" class="form-control" value="{{ $pregnant->abortus }}" />
								            							</div>
								            						</div>
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="hidup">Hidup</label>
								            							<div class="col-sm-6">
								            								<input type="text" name="hidup" id="hidup" class="form-control" value="{{ $pregnant->hidup }}" />
								            							</div>
								            						</div>
								            						
								            					</div>
								            				</div>
								            				<div class="form-group">
								            					<label class="control-label col-sm-3" for="keterangan_kehamilan">Keterangan Kehamilan</label>
						            							<div class="col-sm-9">
						            								<input type="text" name="keterangan_kehamilan" id="keterangan_kehamilan" class="form-control" value="{{ $anc->keterangan_kehamilan }}" />
						            							</div>
								            				</div>
							            				</div>
													</div>
							            
					            					<div class="form-group">
						            					<label class="control-label col-sm-12" for="riwayat_komplikasi">Riwayat Komplikasi Kebidanan</label>
						            					<div class="col-sm-12">
						            						<textarea id="riwayat_komplikasi" name="riwayat_komplikasi" class="form-control">{{ $pregnant->riwayat_komplikasi }}</textarea>
						            					</div>
						            				</div>
							            		</div>

							            		<div class="col-sm-7">
							            			<div class="anc-item">
							            				<h4 class="anc-heading">Pemeriksaan Bidan</h4>
							            				<div class="anc-item-wrapper">
								            				<div class="row">
								            					<div class="col-sm-8">
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="tanggal_hpht">Tanggal HPHT</label>
								            							<div class="col-sm-6">
								            								<input type="text" name="tanggal_hpht" id="tanggal_hpht" class="form-control" value="{{ $pregnant->tanggal_hpht }}" />
								            							</div>
								            						</div>
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="tp">Taksiran Persalinan</label>
								            							<div class="col-sm-6">
								            								<input type="text" name="tp" id="tp" class="form-control" value="{{ $pregnant->tp }}" disabled/>
								            							</div>
								            						</div>
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="tanggal_cuti">Tanggal Mulai Cuti</label>
								            							<div class="col-sm-6">
								            								<input type="text" name="tanggal_cuti" id="tanggal_cuti" class="form-control" value="{{ $pregnant->tanggal_cuti }}" disabled/>
								            							</div>
								            						</div>
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="tanggal_akhir_cuti">Tanggal Akhir Cuti</label>
								            							<div class="col-sm-6">
								            								@php
								            								$start_date = '';
								            								if( $pregnant->tp != '' ){
								            									$tp = strtotime( $pregnant->tp );
								            									$start = $tp + ( 3600 * 24 * 45 );
                																$start_date = date( 'Y-m-d', $start );
								            								}
								            								@endphp
								            								<input type="text" name="tanggal_akhir_cuti" id="tanggal_akhir_cuti" class="form-control" disabled value="{{ $start_date }}" />
								            							</div>
								            						</div>
								            					</div>
								            					<div class="col-sm-4">
								            						<div class="form-group">
								            							<label class="control-label col-sm-12" for="bb_normal">Berat badan sebelum hamil (kg)</label>
								            							<div class="col-sm-8 col-sm-offset-2">
								            								<input type="text" name="bb_normal" id="bb_normal" class="form-control" value="{{ $pregnant->bb_normal }}" />
								            							</div>
								            						</div>
								            						<div class="form-group">
								            							<label class="control-label col-sm-12" for="tinggi_badan">Tinggi badan (cm)</label>
								            							<div class="col-sm-8 col-sm-offset-2">
								            								<input type="text" name="tinggi_badan" id="tinggi_badan" class="form-control" value="{{ $pregnant->tinggi_badan }}" />
								            							</div>
								            						</div>
								            					</div>
								            				</div>
							            				</div>
							            			</div>

							            			<div class="row">
							            				<div class="col-sm-2 service-wrapper-label">Pelayanan</div>
							            				<div class="col-sm-10">
							            					<div class="service-wrapper">
							            						<div class="row">
								            						<div class="col-sm-6">
								            							<div class="form-group">
									            							<label class="control-label col-sm-6" for="injeksi_tt">Injeksi TT</label>
									            							<div class="col-sm-6">
									            								<select name="injeksi_tt" id="injeksi_tt" class="form-control">
									            									<option value="">- Pilih Opsi -</option>
													    							<option value="TT1"{{ selected( 'TT1', $anc->injeksi_tt, true ) }}>TT1</option>
													    							<option value="TT2"{{ selected( 'TT2', $anc->injeksi_tt, true ) }}>TT2</option>
													    							<option value="TT3"{{ selected( 'TT3', $anc->injeksi_tt, true ) }}>TT3</option>
									            								</select>
									            							</div>
									            						</div>
								            						</div>
								            						<div class="col-sm-6">
								            							<div class="form-group">
									            							<label class="control-label col-sm-9" for="tablet_fe">Pemberian Tablet FE</label>
									            							<div class="col-sm-3">
									            								<input type="checkbox" name="tablet_fe" id="tablet_fe" value="1"{{ ( $anc->tablet_fe ? " checked='checked'" : "" ) }} />
									            							</div>
									            						</div>
								            						</div>
								            					</div>
							            					</div>
							            				</div>
							            			</div>
							            		</div>
							            	</div>
							            </div>
							        </div>
							    </div>
							    <div class="panel panel-default">
							        <div class="panel-heading" role="tab" id="anc-heading-two">
							            <h4 class="panel-title">
							        		<a class="collapsed" role="button" data-toggle="collapse" data-parent="#anc-accordion" href="#anc-two" aria-expanded="false" aria-controls="anc-two">
							          			ANC 2*(dua)
							        		</a>
							      		</h4>
							        </div>
							        <div id="anc-two" class="panel-collapse collapse" role="tabpanel" aria-labelledby="anc-heading-two">
							            <div class="panel-body">
							                <div class="row">
							            		<div class="col-sm-8">
							            			<div class="anc-item">
							            				<h4 class="anc-heading">Hasil Pemeriksaan</h4>

							            				<div class="anc-item-wrapper">
							            					<div class="row">
								            					<div class="col-sm-7">
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="berat_badan">Berat Badan (kg)</label>
								            							<div class="col-sm-6">
								            								<input type="text" name="berat_badan" id="berat_badan" class="form-control" value="{{ $anc->berat_badan }}" />
								            							</div>
								            						</div>
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="td_bawah">Tekanan Darah (mmHg)</label>
								            							<div class="col-sm-3">
								            								<input type="text" name="td_bawah" id="td_bawah" class="form-control" value="{{ $anc->td_bawah }}" />
								            							</div>
								            							<div class="col-sm-3">
								            								<input type="text" name="td_atas" id="td_atas" class="form-control" value="{{ $anc->td_atas }}" />
								            							</div>
								            						</div>
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="nilai_gizi">Nilai Status Gizi (M/N)</label>
								            							<div class="col-sm-6">
								            								<input type="text" name="nilai_gizi" id="nilai_gizi" class="form-control" value="{{ $anc->nilai_gizi }}" />
								            							</div>
								            						</div>
								            						<div class="form-group">
								            							<label class="control-label col-sm-6" for="denyut_janin">Denyut Jantung Janin (DJJ)(x/menit)</label>
								            							<div class="col-sm-6">
								            								<input type="text" name="denyut_janin" id="denyut_janin" class="form-control" value="{{ $anc->denyut_janin }}" />
								            							</div>
								            						</div>
								            					</div>
								            					<div class="col-sm-5">
								            						<fieldset>
								            							<legend>Presentasi</legend>

								            							<div class="form-group">
									            							<label class="control-label col-sm-6" for="tfu">TFU</label>
									            							<div class="col-sm-6">
									            								<input type="text" name="tfu" id="tfu" class="form-control" value="{{ $anc->tfu }}" />
									            							</div>
									            						</div>

									            						<hr />

								            							<div class="radio">
																			<label for="ballotement">
									            								<input type="radio" name="presentasi" id="ballotement" value="Ballotement"{{ ( $anc->presentasi == 'Ballotement' ? " checked='checked'" : "" ) }} />
									            								Ballotement
									            							</label>
									            						</div>
									            						<div class="radio">
																			<label for="kepala">
									            								<input type="radio" name="presentasi" id="kepala" value="Kepala"{{ ( $anc->presentasi == 'Kepala' ? " checked='checked'" : "" ) }} />
									            								Kepala
									            							</label>
									            						</div>
									            						<div class="radio">
																			<label for="bokong">
									            								<input type="radio" name="presentasi" id="bokong" value="Bokong / Sungsang"{{ ( $anc->presentasi == 'Bokong / Sungsang' ? " checked='checked'" : "" ) }} />
									            								Bokong / Sungsang
									            							</label>
									            						</div>
									            						<div class="radio">
																			<label for="lintang">
									            								<input type="radio" name="presentasi" id="lintang" value="Letak Lintang"{{ ( $anc->presentasi == 'Letak Lintang' ? " checked='checked'" : "" ) }} />
									            								Letak Lintang
									            							</label>
									            						</div>

									            						<hr />

									            						<div class="checkbox">
																			<label for="djj_plus">
									            								<input type="checkbox" name="djj_plus" id="djj_plus" value="1"{{ ( $anc->djj_plus ? " checked='checked'" : "" ) }} />
									            								DJJ+
									            							</label>
									            						</div>
								            						</fieldset>
								            						
								            					</div>
								            				</div>
							            				</div>
							            				
													</div>

													<div class="form-group">
														<label class="control-label col-sm-12" for="kesimpulan">Kesimpulan</label>
				            							<div class="col-sm-12">
				            								<textarea name="kesimpulan" id="kesimpulan" class="form-control">{{ $anc->kesimpulan }}</textarea>
				            							</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="anc-item">
														<h4 class="anc-heading">Pemeriksaan Laboratorium</h4>

														<div class="anc-item-wrapper">
															<div class="checkbox">
																<label for="pemeriksaan_hb">
																	<input type="checkbox" name="pemeriksaan_hb" id="pemeriksaan_hb"{{ ( $anc->pemeriksaan_hb  ? " checked='checked'" : "" ) }} />
																	Pemeriksaan Hb (gr%)
																</label>
															</div>
															<div class="row">
																<div class="col-sm-10 col-sm-offset-2">
																	<div class="form-group">
																		<label class="control-label col-sm-3" for="pemeriksaan_hb_hasil">Hasil</label>
																		<div class="col-sm-9">
																			<input type="text" name="pemeriksaan_hb_hasil" id="pemeriksaan_hb_hasil" class="form-control" value="{{ $anc->pemeriksaan_hb }}" />
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-sm-12" for="pemeriksaan_hb_kesimpulan">Kesimpulan</label>
																		<div class="col-sm-12">
																			<textarea type="text" name="pemeriksaan_hb_kesimpulan" id="pemeriksaan_hb_kesimpulan" class="form-control" disabled></textarea>
																		</div>
																	</div>
																</div>
															</div>

															<div class="checkbox">
																<label for="pemeriksaan_urin">
																	<input type="checkbox" name="pemeriksaan_urin" id="pemeriksaan_urin"{{ ( $anc->pemeriksaan_urin  ? " checked='checked'" : "" ) }} />
																	Protein Urin (%)
																</label>
															</div>
															<div class="row">
																<div class="col-sm-10 col-sm-offset-2">
																	<div class="form-group">
																		<label class="control-label col-sm-3" for="pemeriksaan_urin_hasil">Hasil</label>
																		<div class="col-sm-9">
																			<input type="text" name="pemeriksaan_urin_hasil" id="pemeriksaan_urin_hasil" class="form-control" value="{{ $anc->pemeriksaan_urin }}" />
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-sm-12" for="pemeriksaan_urin_kesimpulan">Kesimpulan</label>
																		<div class="col-sm-12">
																			<textarea type="text" name="pemeriksaan_urin_kesimpulan" id="pemeriksaan_urin_kesimpulan" class="form-control" disabled></textarea>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														
													</div>
												</div>
											</div>
							            </div>
							        </div>
							    </div>
							</div>
			    		</div>
			    		<div class="form-horizontal" style="padding-top:30px;">
			    			<div class="form-group">
								<label class="control-label col-xs-2">&nbsp;</label>
								<div class="col-xs-8">
									<input type="submit" name="submit" value="Simpan" class="btn form-control btn-save btn-action" />   
								</div>
							</div>
							<div class="form-group last">
								<label class="control-label col-xs-2">&nbsp;</label>
								<div class="col-xs-8">
									<a href="{{ route( 'medical-record.index' ) }}" class="btn form-control btn-cancel btn-action">Batal</a>  
								</div>
							</div>
			    		</div>
			    	</div>
			    	<input type="hidden" name="pregnant_id" value="{{ $pregnant->id_peserta_hamil }}" />
			    	<input type="hidden" name="anc_id" value="{{ $anc->id_pemeriksaan_anc }}" />
			    	@endif
			    	<div role="tabpanel" class="tab-pane" id="patient-history-elm">
			    		<h3>Riwayat Pemeriksaan</h3>
			    		<div class="table-wrapper no-margin full-width" id="list-patient-history">
							<table class="table table-bordered table-striped list-table">
								<thead>
									<tr>
										<th class="column-no-title">No.</th>
										<th class="column-register-date-title">Tanggal Daftar</th>
										<th class="column-complaint-title">Keluhan</th>
										<th class="column-diagnosys-title">Diagnosis</th>
										<th class="column-doctor-title">Dokter</th>
										<th class="column-action-title">Action</th>
									<tr>
								<thead>
								<tbody>
								@if( count( $others ) ) 
									@php $i = 1; @endphp
									@foreach( $others as $o )
									@php 
										$p = App\PoliRegistration::find( $o->id_pendaftaran_poli );

										if( !$p ) continue;
										if( $p->id_poli == 2 ) continue;
									@endphp
									<tr class="item" id="item-{{ $o->id_pemeriksaan_poli }}">
										<td class="column-no">{{ $i }}</td>
										<td class="column-register-date">{{ date( 'd-m-Y H:i:s', strtotime( $p->tgl_daftar ) ) }}</td>
										<td class="column-complaint">{{ $o->keluhan ? $o->keluhan : '-' }}</td>
										<td class="column-diagnosys">{{ $o->diagnosa_dokter ? $o->diagnosa_dokter : '-' }}</td>
										<td class="column-doctor">{{ $o->dokter_rawat ? $o->dokter_rawat : '-' }}</td>
										<td class="column-action">
											
												<a href="{{ route( 'medical-record.show', [ 'id' => $o->id_pendaftaran_poli ] ) }}" title="View" class="edit" data-id=""><img src="{{URL::asset('assets/images/icon-view.png')}}" alt="View" /></a>
											
												<!-- <a href="{{ route( 'medical-record.index' ) }}/print_medrec/{{ $o->id_pemeriksaan_poli }}" title="Print" class="delete" data-id=""><img src="{{URL::asset('assets/images/icon-print.png')}}" alt="Print" /></a> -->
											
										</td>
									<tr>
										@php $i++ @endphp
									@endforeach
								@else
									<tr class="no-data">
										<td colspan="6">Tidak ada data ditemukan.</td>
									</tr>
								@endif
								</tbody>
							</table>
						</div>

						@if( $poliregistration->id_poli == 2 && $uraian == 66 )
						<h3>Riwayat Pemeriksaan ANC</h3>
						<div class="table-wrapper no-margin full-width" id="list-patient-history">
							<table class="table table-bordered table-striped list-table">
								<thead>
									<tr>
										<th class="column-no-title">No.</th>
										<th class="column-register-date-title">Tanggal Daftar</th>
										<th class="column-complaint-title">Keluhan</th>
										<th class="column-diagnosys-title">Diagnosis</th>
										<th class="column-doctor-title">Dokter</th>
										<th class="column-action-title">Action</th>
									<tr>
								<thead>
								<tbody>
								@if( count( $anc_items ) )
									@php $i = 1; @endphp
									@foreach( $anc_items as $o )
									@php 
										$p = App\PoliRegistration::find( $o->id_pendaftaran_poli ); 
									@endphp
									<tr class="item" id="item-{{ $o->id_pemeriksaan_poli }}">
										<td class="column-no">{{ $i }}</td>
										<td class="column-register-date">{{ date( 'd-m-Y H:i:s', strtotime( $p->tgl_daftar ) ) }}</td>
										<td class="column-complaint">{{ $o->keluhan ? $o->keluhan : '-' }}</td>
										<td class="column-diagnosys">{{ $o->diagnosa_dokter ? $o->diagnosa_dokter : '-' }}</td>
										<td class="column-doctor">Bd. {{ $o->dokter_rawat ? $o->dokter_rawat : '-' }}</td>
										<td class="column-action">
											
												<a href="{{ route( 'medical-record.show', [ 'id' => $o->id_pendaftaran_poli ] ) }}" title="View" class="edit" data-id=""><img src="{{URL::asset('assets/images/icon-view.png')}}" alt="View" /></a>
											
												<!-- <a href="{{ route( 'medical-record.index' ) }}/print_medrec/{{ $o->id_pemeriksaan_poli }}" title="Print" class="delete" data-id=""><img src="{{URL::asset('assets/images/icon-print.png')}}" alt="Print" /></a> -->
											
										</td>
									<tr>
										@php $i++ @endphp
									@endforeach
								@else
									<tr class="no-data">
										<td colspan="6">Tidak ada data ditemukan.</td>
									</tr>
								@endif
								</tbody>
							</table>
						</div>

						@endif
			    	</div>
			    	@if( $poliregistration->id_poli == 1 )
			    	<div role="tabpanel" class="tab-pane" id="accident-history-elm">
			    		<div id="accident-form" class="form-horizontal">
			    			<div class="accident-history-panel" id="accident-note">
				    			<fieldset>
				    				<legend><span>Keterangan Kecelakaan</span></legend>

				    				<div class="form-group">
				    					<label class="control-label col-xs-4" for="accident-result">Akibat Kecelakaan / Bagian Tubuh yang Sakit / Luka</label>
				    					<div class="col-xs-8">
				    						<input type="text" name="accident-result" id="accident-result" class="form-control required" value="{{ $accident->akibat_kecelakaan }}" />
				    						<div class="error-placement"></div>
				    					</div>
				    				</div>
				    				<div class="form-group">
				    					<label class="control-label col-xs-4" for="action-given">Tindakan / Perawatan yang Diberikan</label>
				    					<div class="col-xs-8">
				    						<input type="text" name="action-given" id="action-given" class="form-control required" value="{{ $accident->tindakan }}" />
				    						<div class="error-placement"></div>
				    					</div>
				    				</div>
				    				<div class="form-group">
				    					<label class="control-label col-xs-4" for="accident-cause">Mesin / Peralatan / Benda yang Menyebabkan Kecelakaan</label>
				    					<div class="col-xs-8">
				    						<input type="text" name="accident-cause" id="accident-cause" class="form-control required" value="{{ $accident->penyebab_kecelakaan }}" />
				    						<div class="error-placement"></div>
				    					</div>
				    				</div>
				    				<div class="form-group">
				    					<label class="control-label col-xs-4" for="doctor-recommendation">Rekomendasi Dokter / Paramedis</label>
				    					<div class="col-xs-8">
				    						<input type="text" name="doctor-recommendation" id="doctor-recommendation" class="form-control required" value="{{ $accident->rekomendasi }}" />
				    						<div class="error-placement"></div>
				    					</div>
				    				</div>
				    				<div class="form-group">
				    					<label class="control-label col-xs-4" for="accident-explanation">Penjelasan Terjadinya Kecelakaan</label>
				    					<div class="col-xs-8">
				    						<input type="text" name="accident-explanation" id="accident-explanation" class="form-control required" value="{{ $accident->keterangan_kecelakaan }}" />
				    						<div class="error-placement"></div>
				    					</div>
				    				</div>
				    			</fieldset>
				    		</div>

				    		<div class="row">
				    			<div class="col-sm-5">
				    				<div class="accident-history-panel" id="accident-status">
				    					<fieldset>
				    						<legend><span>Status Kecelakaan</span></legend>

				    						<div class="radio-button">
				    							<label data-status="fac">
				    								<i class="fa {{ ( $accident->jenis_kecelakaan == 'fac' ) ? 'fa-dot-circle-o' : 'fa-circle-thin' }}"></i> 
				    								<strong><em>Kasus P3K (FAC)</em></strong><br />
				    								Dapat ditangani di tempat kejadian oleh petugas P3K / Pekerja yang terlatih
				    							</label>
				    						</div>

				    						<div class="radio-button">
				    							<label data-status="mtc">
				    								<i class="fa {{ ( $accident->jenis_kecelakaan == 'mtc' ) ? 'fa-dot-circle-o' : 'fa-circle-thin' }}"></i>
				    								<strong><em>Kasus Tindakan Medis (MTC)</em></strong><br />
				    								Harus ditangani oleh dokter / paramedis, pekerja dapat kembali kerja
				    							</label>
				    						</div>

				    						<div class="radio-button">
				    							<label data-status="rwc">
				    								<i class="fa {{ ( $accident->jenis_kecelakaan == 'rwc' ) ? 'fa-dot-circle-o' : 'fa-circle-thin' }}"></i>
				    								<strong><em>Kasus Hari Kerja Terbatas (RWC)</em></strong><br />
				    								Setelah kecelakaan, pekerja tidak dapat bekerja di bagian semula, hanya dapat menangani tugas yang lebih ringan
				    							</label>
				    						</div>

				    						<div class="radio-button">
				    							<label data-status="lwc">
				    								<i class="fa {{ ( $accident->jenis_kecelakaan == 'lwc' ) ? 'fa-dot-circle-o' : 'fa-circle-thin' }}"></i> 
				    								<strong><em>Kasus Hari Kerja Hilang (LWC)</em></strong><br />
				    								Tidak dapat kembali kerja dan memerlukan istirahat lebih dari 2 hari
				    							</label>
				    						</div>

				    						<div class="radio-button">
				    							<label data-status="death">
				    								<i class="fa {{ ( $accident->jenis_kecelakaan == 'death' ) ? 'fa-dot-circle-o' : 'fa-circle-thin' }}"></i> 
				    								<strong><em>Kematian</em></strong>
				    							</label>
				    						</div>

				    						<input type="hidden" name="jenis-kecelakaan" id="jenis-kecelakaan" value="{{ $accident->jenis_kecelakaan }}" class="" />
				    						<div class="error-placement"></div>
				    					</fieldset>
				    				</div>
				    			</div>

				    			<div class="col-sm-7">
				    				<div class="accident-history-panel" id="accident-time">
					    				<fieldset>
					    					<legend><span>Waktu dan Tempat Kejadian</span></legend>

					    					<div class="row">
					    						<div class="col-xs-6">
					    							<div class="form-group">
					    								<label class="control-label col-xs-12" for="day-accident">Kejadian</label>
					    								<div class="col-xs-12">
					    									<div class="input-group">
											  					<span class="input-group-addon"><i class="fa fa-sun-o"></i></span>
																<input type="text" name="day-accident" id="day-accident" class="form-control required" placeholder="Hari Kejadian" value="{{ $accident->hari_kejadian }}" />
															</div>
															<div class="error-placement"></div>
					    								</div>
					    							</div>
					    						</div>
					    						<div class="col-xs-6">
					    							<div class="form-group">
					    								<label class="control-label col-xs-12" for="datetime-accident">Tanggal & Jam</label>
					    								<div class="col-xs-12">
					    									<div class="input-group">
											  					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input type="text" name="datetime-accident" id="datetime-accident" class="form-control required" placeholder="Tanggal & Jam Kejadian" value="{{ $accident->tanggal_kejadian }}" />
															</div>
															<div class="error-placement"></div>
					    								</div>
					    							</div>
					    						</div>
					    					</div>

					    					<div class="row">
					    						<div class="col-xs-12">
					    							<div class="form-group">
					    								<label class="control-label col-xs-12" for="witness">Nama Saksi-Saksi</label>
					    								<div class="col-xs-12">
					    									<div class="input-group">
											  					<span class="input-group-addon"><i class="fa fa-user"></i></span>
																<input type="text" name="witness" id="witness" class="form-control" placeholder="Nama Saksi-Saksi" value="{{ $accident->saksi }}" />
															</div>
					    								</div>
					    							</div>
					    						</div>
					    					</div>

					    					<div class="row">
					    						<div class="col-sm-6">
					    							<div class="accident-user-wrapper">
					    								<h6>Atasan Langsung</h6>

					    								<div class="form-group">
					    									<label class="control-label col-xs-12" for="supervisor-nik">NIK</label>
					    									<div class="col-xs-12">
					    										<input type="text" name="supervisor-nik" id="supervisor-nik" class="form-control" value="{{ $accident->atasan_langsung }}" />
					    									</div>
					    								</div>
					    								<div class="form-group">
					    									<label class="control-label col-xs-12" for="supervisor-phone">No. Telp</label>
					    									<div class="col-xs-12">
					    										<input type="text" name="supervisor-phone" id="supervisor-phone" class="form-control" value="{{ $accident->telepon }}" />
					    									</div>
					    								</div>
					    							</div>
					    						</div>

					    						<div class="col-sm-6">
					    							<div class="accident-user-wrapper">
					    								<h6>Dilaporkan Oleh</h6>

					    								<div class="form-group">
					    									<label class="control-label col-xs-12" for="informant-name">Nama</label>
					    									<div class="col-xs-12">
					    										<input type="text" name="informant-name" id="informant-name" class="form-control required" value="{{ $accident->nama_penanggung_jawab }}" />
					    										<div class="error-placement"></div>
					    									</div>
					    								</div>
					    								<div class="form-group">
					    									<label class="control-label col-xs-12" for="informant-job-title">Jabatan</label>
					    									<div class="col-xs-12">
					    										<input type="text" name="informant-job-title" id="informant-job-title" class="form-control required" value="{{ $accident->jabatan }}" />
					    										<div class="error-placement"></div>
					    									</div>
					    								</div>
					    							</div>
					    						</div>

					    					</div>

					    				</fieldset>
					    			</div>
				    			</div>
				    		</div>

			    			<div class="form-group" style="padding-top:50px;">
								<label class="control-label col-xs-2">&nbsp;</label>
								<div class="col-xs-8">
									<input type="submit" name="submit" value="Simpan" class="btn form-control btn-save btn-action" />   
								</div>
							</div>
							<div class="form-group last">
								<label class="control-label col-xs-2">&nbsp;</label>
								<div class="col-xs-8">
									<a href="{{ route( 'medical-record.index' ) }}" class="btn form-control btn-cancel btn-action">Batal</a>  
								</div>
							</div>
							<input type="hidden" name="is-accident" value="1" />
			    		</div>
			    	</div>
			    	@endif
			  	</div>

			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('#_token').val()
        }
    });

    $(function(){
    	$type = $('#action-type').val();

    	if($type == 66){
    		$('#doctor').find('option[data-bd="0"]').hide();
    	}
    });

    $('#collapseFive').addClass('in');

    $('.action-type-wrapper label').click(function(){
		var $this = $(this),
			$fa = $(this).find('i.fa'),
			$val = $fa.attr('data-action');

		if($fa.hasClass('fa-circle-thin')){
			$('#action-type').val($val);
			$('.action-type-wrapper label i.fa').removeClass('fa-dot-circle-o').addClass('fa-circle-thin');
			$fa.removeClass('fa-circle-thin').addClass('fa-dot-circle-o');
		}

		if($val == 66){
			$('#anc').show();
			$('#doctor').find('option[data-bd="0"]').hide();
		}else{
			$('#doctor').find('option[data-bd="0"]').show();
			$('#anc').hide();
			$("#home-tab").click();
		}

		return false;
	});

	$('.btn-cancel').click(function(){
		var x = confirm( "Anda yakin ingin membatalkan? Semua isian yang sudah dilakukan tidak akan disimpan" );

		return x;
	});

	$('#tanggal_hpht').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true,
        onSelect : function(date,elm){
        	var jsdate = new Date(date);
        	var month = jsdate.getMonth();
        	var htpdate;

        	if(month <=2){
	        	@php
					$hpl_formula_3m_down = get_setting( 'hpl_formula_3m_down' );
					$hpl_formula_3m_down = $hpl_formula_3m_down ? $hpl_formula_3m_down : '+7,+9,+0';

					$formula_parts = explode( ',', $hpl_formula_3m_down );
					$day = $formula_parts[0];
					$month = $formula_parts[1];
					$year = $formula_parts[2];
				@endphp
				htpdate = new Date(jsdate.getFullYear() {{ $year }}, jsdate.getMonth() {{ $month }}, jsdate.getDate() {{ $day }});
        	}else if(month >=3){
        		@php
					$hpl_formula_4m_up = get_setting( 'hpl_formula_4m_up' );
					$hpl_formula_4m_up = $hpl_formula_4m_up ? $hpl_formula_4m_up : '+7,-3,+1';

					$formula_parts = explode( ',', $hpl_formula_4m_up );
					$day = $formula_parts[0];
					$month = $formula_parts[1];
					$year = $formula_parts[2];
				@endphp
				htpdate = new Date(jsdate.getFullYear() {{ $year }}, jsdate.getMonth() {{ $month }}, jsdate.getDate() {{ $day }});
        	}
   	
        	var dayoffdate = new Date(htpdate.getFullYear(), htpdate.getMonth(), htpdate.getDate() - 45);
        	var enddayoffdate = new Date(htpdate.getFullYear(), htpdate.getMonth(), htpdate.getDate() + 45);

        	$htp = htpdate.getFullYear() + '-' + ('0' + ( htpdate.getMonth() + 1 )).slice(-2) + '-' + ('0' + htpdate.getDate()).slice(-2);
        	$dayoff = dayoffdate.getFullYear() + '-' + ('0' + ( dayoffdate.getMonth() + 1 )).slice(-2) + '-' + ('0' + dayoffdate.getDate()).slice(-2);
        	$startday = enddayoffdate.getFullYear() + '-' + ('0' + ( enddayoffdate.getMonth() + 1 )).slice(-2) + '-' + ('0' + enddayoffdate.getDate()).slice(-2);

        	$('#tp').val($htp);
        	$('#tanggal_cuti').val($dayoff);
        	$('#tanggal_akhir_cuti').val($startday);
        }
    });

    $('.reset-anc').click(function(){
    	$('#status_tt').val('');
    	$('#tm').val('');
    	$('#keterangan_kehamilan').val('');
    	$('#tanggal_hpht').val('');
    	$('#tp').val('');
    	$('#tanggal_cuti').val('');
    	$('#tanggal_akhir_cuti').val('');
    	$('#bb_normal').val('');
    	$('#tinggi_badan').val('');
    	$('#injeksi_tt').val('');
    	$('#tablet_fe').removeAttr('checked');
    	$('#riwayat_komplikasi').val('');
    	$("#berat_badan").val('');
    	$("#td_bawah").val('');
    	$("#td_atas").val('');
    	$('#nilai_gizi').val('');
    	$("#denyut_janin").val('');
    	$('#tfu').val('');
    	$('input[name="presentasi"]').removeAttr('checked');
    	$('#djj_plus').removeAttr('checked');
    	$('#kesimpulan').val('');
    	$('#pemeriksaan_hb').removeAttr('checked');
    	$('#pemeriksaan_hb_hasil').val('');
    	$('#pemeriksaan_urin').removeAttr('checked');
    	$('#pemeriksaan_urin_hasil').val('');

    	return false;
    });
});
</script>
@yield( 'diagnosis.scripts' )
@yield( 'action.scripts' )
@yield( 'allergic.scripts' )
@yield( 'history.scripts' )
@if( $poliregistration->id_poli == 1)
	@yield( 'accident.scripts' )
@endif	
@stop