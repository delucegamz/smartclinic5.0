@extends( 'layouts.app' )

@section( 'page_title' )
Smart Clinic System - Medical Record
@stop

@section( 'content' )
<div class="content-title"><img src="{{URL::asset('assets/images/title-medical-record.png')}}" alt="Medical Record" /></div>

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

		<div id="diagnostic-status">
			<span class="diagnostic-label">Status Diagnosa</span> <span class="diagnostic-link">{{ ( $medrec->iddiagnosa ) ? $medrec->iddiagnosa : 'QQQ' }}</span> <span class="diagnostic-link">{{ ( $medrec->iddiagnosa ) ? get_diagnosis_name( $medrec->iddiagnosa ) : 'Tidak ada diagnosa' }}</span>
		</div>

		<div id="observation-wrapper">
			<form id="observation-form" method="post" action="{{ url( '/observation/' . $observation->id_observasi ) }}" class="form-horizontal">
				<input name="_token" id="_token" type="hidden" value="{{ csrf_token() }}" />
				<input type="hidden" name="_method" value="PUT">

				<div class="observation-container container-1" style="background-image:url({{URL::asset('assets/images/gradient-01.jpg')}});">

					<h4>Pemeriksaan Observasi</h4>

					<div class="form-group">
						<label class="control-label col-xs-2" for="general-condition">Keadaan Umum</label>
						<div class="col-xs-10">
							<textarea class="form-control" name="general-condition" id="general-condition">{{ $observation_detail->keadaan_umum }}</textarea>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-8">
							<h4>Kesadaran Pasien</h4>
							<div class="form-group">
								<label class="control-label col-xs-3" for="eye-opening">Eye Opening</label>
								<div class="col-xs-9">
									<select class="form-control" name="eye-opening" id="eye-opening">
										<option value="">- Pilih Kondisi -</option>
										<option value="1"{{ selected( $observation_detail->k_mata, 1 ) }}>Tidak ada respon (Diam)</option>
										<option value="2"{{ selected( $observation_detail->k_mata, 2 ) }}>Rangsang Nyeri</option>
										<option value="3"{{ selected( $observation_detail->k_mata, 3 ) }}>Dipanggil / Perintah Verbal</option>
										<option value="4"{{ selected( $observation_detail->k_mata, 4 ) }}>Spontan</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-3" for="verbal-response">Respon Verbal</label>
								<div class="col-xs-9">
									<select class="form-control" name="verbal-response" id="verbal-response">
										<option value="">- Pilih Kondisi -</option>
										<option value="1"{{ selected( $observation_detail->k_bicara, 1 ) }}>Tidak bersuara</option>
										<option value="2"{{ selected( $observation_detail->k_bicara, 2 ) }}>Bersuara tidak berarti (Incomprehensible)</option>
										<option value="3"{{ selected( $observation_detail->k_bicara, 3 ) }}>Kata-kata kacau (inappropriate)</option>
										<option value="4"{{ selected( $observation_detail->k_bicara, 4 ) }}>Konversi / jawaban kacau</option>
										<option value="5"{{ selected( $observation_detail->k_bicara, 5 ) }}>Orientasi baik</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-3" for="motoric-response">Respon Motorik</label>
								<div class="col-xs-9">
									<select class="form-control" name="motoric-response" id="motoric-response">
										<option value="">- Pilih Kondisi -</option>
										<option value="1"{{ selected( $observation_detail->k_motorik, 1 ) }}>Tidak ada respon (Diam)</option>
										<option value="2"{{ selected( $observation_detail->k_motorik, 2 ) }}>Ekstensi (Desebrasi)</option>
										<option value="3"{{ selected( $observation_detail->k_motorik, 3 ) }}>Fleksi abnormal (Dekortikasi)</option>
										<option value="4"{{ selected( $observation_detail->k_motorik, 4 ) }}>Reaksi pada nyeri (Menarik/Berlawanan rasa nyeri)</option>
										<option value="5"{{ selected( $observation_detail->k_motorik, 5 ) }}>Lokalisasi / rangsang nyeri</option>
										<option value="5"{{ selected( $observation_detail->k_motorik, 6 ) }}>Sesuai perintah</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-xs-4" id="vital-sign">
							<h4>Tanda-tanda Vital</h4>
							<div class="form-group">
								<label class="control-label col-xs-6" for="blood-tension">Tensi Darah (mmHg)</label>
								<div class="col-xs-3">
									<input type="text" id="mm" name="mm" class="form-control" value="{{ $observation_detail->td_atas }}" />
								</div>
								<div class="col-xs-3 with-slash">
									<input type="text" id="hg" name="hg" class="form-control" value="{{ $observation_detail->td_bawah }}" />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-6" for="temperature">Suhu</label>
								<div class="col-xs-3">
									<input type="text" id="temperature" name="temperature" class="form-control" value="{{ $observation_detail->suhu }}" />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-6" for="blood-pulse">Denyut Nadi</label>
								<div class="col-xs-3">
									<input type="text" id="blood-pulse" name="blood-pulse" class="form-control" value="{{ $observation_detail->nadi }}" />
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-xs-6" for="breath">Nafas (X/Menit)</label>
								<div class="col-xs-3">
									<input type="text" id="breath" name="breath" class="form-control" value="{{ $observation_detail->jalan_nafas }}" />
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="observation-container container-2" style="background-image:url({{URL::asset('assets/images/gradient-02.jpg')}});">

					<h4>Tindakan dan Pengobatan</h4>

					<div class="row">
						<div class="col-xs-7">
							<div class="form-group">
								<label class="control-label col-xs-2" for="actions">Tindakan</label>
								<div class="col-xs-10">
									<select class="form-control" name="actions" id="actions">
										<option value="">- Pilih Tindakan -</option>
										@foreach( $observation_actions as $act )
										<option value="{{ $act->id_pemeriksaan_observasi }}" data-name="{{ $act->nama_pemeriksaan_observasi }}" data-code="{{ $act->kode_pemeriksaan_observasi }}">{{ $act->nama_pemeriksaan_observasi }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-12 align-right">
									<input type="button" value="Masukan" id="action-add" class="btn btn-action-add" />
								</div>
							</div>

							<div class="table-wrapper no-margin full-width" id="list-actions">
								<table class="table table-bordered table-striped list-table" id="list-items">
									<thead>
										<tr>
											<th class="column-no-title">No.</th>
											<th class="column-code-title" style="width:80px;">Kode Tindakan</th>
											<th class="column-name-title">Nama Tindakan</th>
											<th class="column-action-title">Action</th>
										<tr>
									<thead>
									<tbody>
										@if( count( $observation_checks ) )
										<?php $i = 1; ?>
										@foreach( $observation_checks as $checks )
										<?php $observation_check = App\ObservationAction::find( $checks->id_pemeriksaan_observasi ); ?>
										<tr class="item" id="item-{{ $checks->no_pemeriksaan_observasi }}">
											<td class="column-no">{{ $i }}</td>
											<td class="column-code">{{ $observation_check->kode_pemeriksaan_observasi }}</td>
											<td class="column-name">{{ $observation_check->nama_pemeriksaan_observasi }}</td>
											<td class="column-action">
												<a href="#" title="Delete" class="delete" data-id="{{ $checks->no_pemeriksaan_observasi }}"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>
											</td>
										<tr>
										<?php $i++; ?>
										@endforeach
										@else
										<tr class="no-data">
											<td colspan="4">Tidak ada data ditemukan.</td>
										</tr>
										@endif
										
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-xs-5">
							<div class="form-group">
								<label class="control-label col-xs-12" for="more-desc">Keterangan dan tindak lanjut</label>
								<div class="col-xs-12">
									<textarea class="form-control" name="more-desc" id="more-desc" placeholder="Catatan">{{ $observation->hasil_observasi }}</textarea>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-xs-6">
									<div class="checkbox">
										<label{{ ( $is_sick_letter ) ? ' class="disabled"' : '' }}>
											<i class="fa {{ ( $is_sick_letter ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> Surat Sakit
											<input type="hidden" name="sick-letter" id="sick-letter" value="{{ $is_sick_letter }}" />
										</label>
									</div>
									<div class="checkbox">
										<label{{ ( $is_reference_letter ) ? ' class="disabled"' : '' }}>
											<i class="fa {{ ( $is_reference_letter ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> Surat Rujukan
											<input type="hidden" name="reference-letter" id="reference-letter" value="{{ $is_reference_letter }}" />
										</label>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="checkbox">
										<label{{ ( $is_dayoff_letter ) ? ' class="disabled"' : '' }}>
											<i class="fa {{ ( $is_dayoff_letter ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> Surat Cuti
											<input type="hidden" name="dayoff-letter" id="dayoff-letter" value="{{ $is_dayoff_letter }}" />
										</label>
									</div>
									<div class="checkbox">
										<label{{ ( $is_doctor_recipe ) ? ' class="disabled"' : '' }}>
											<i class="fa {{ ( $is_doctor_recipe ) ? 'fa-check-square-o' : 'fa-square-o' }}"></i> Resep Doktor
											<input type="hidden" name="doctor-recipe" id="doctor-recipe" value="{{ $is_doctor_recipe }}" />
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="observation-container container-3" style="background-image:url({{URL::asset('assets/images/gradient-03.jpg')}});">
					<h4>Keterangan Pasca Observasi</h4>

					<div class="row">
						<div class="col-xs-6">
							<div class="form-group">
								<label class="control-label col-xs-12">Diagnosa Akhir</label>
								<div class="col-xs-12">
									<textarea class="form-control" name="final-diagnosis" id="final-diagnosis">{{ $observation->diagnosa_akhir }}</textarea>
								</div>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<label class="control-label col-xs-12">Kesimpulan</label>
								<div class="col-xs-12">
									<textarea class="form-control" name="summary" id="summary">{{ $observation->kesimpulan_observasi }}</textarea>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-xs-2">&nbsp;</label>
					<div class="col-xs-8">
						<input type="submit" name="submit" value="Simpan" class="btn form-control btn-save btn-action" />   
					</div>
				</div>
				<div class="form-group last">
					<label class="control-label col-xs-2">&nbsp;</label>
					<div class="col-xs-8">
						<a href="{{ route( 'observation.index' ) }}" class="btn form-control btn-cancel btn-action">Batal</a>  
					</div>
				</div>
			</form>
			
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#collapseFive').addClass('in');
	
	$('.checkbox label').click(function(){
		if($(this).hasClass('disabled')) return false;

		var fa = $(this).find('.fa');

		if(fa.hasClass('fa-square-o')){
			$(this).find('input[type=hidden]').val(1);
			fa.removeClass('fa-square-o').addClass('fa-check-square-o');
		}else if(fa.hasClass('fa-check-square-o')){
 			$(this).find('input[type=hidden]').val(0);
 			fa.removeClass('fa-check-square-o').addClass('fa-square-o');
		}

		return false;
	});

	$('#action-add').click(function(){
		var $actions = $('#actions');

		if($actions.val() == ''){
			alert('Harap pilih tindakan!');
			return false;
		}

		var $name = $actions.find('option:selected').attr('data-name'),
			$code = $actions.find('option:selected').attr('data-code'),
			$id = $actions.val();
			

		$url = '{{ url( 'observation' ) }}';

		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            }
        });

		$.ajax({
            url: $url,
            type: 'POST',
            data: {
            	id_pemeriksaan_observasi: $id,
            	id_observasi: '{{ $observation->id_observasi }}'
            },
            dataType: 'json',
            beforeSend: function() {
               
            },      
            complete: function() {
            
            },          
            success: function(json) {
            	if(json.success == 'true'){
            		var $count = $('#list-items tbody tr.item').length;
            		$count++;

					var $html = '<tr class="item" id="item-' + json.id + '">\
								<td class="column-no">' + $count + '</td>\
								<td class="column-code">' + $code + '</td>\
								<td class="column-name">' + $name + '</td>\
								<td class="column-action">\
									<a href="#" title="Delete" class="delete" data-id="' + json.id + '"><img src="{{URL::asset('assets/images/icon-delete.png')}}" alt="Delete" /></a>\
									<input type="hidden" name="actions[]" value="' + json.id + '" />\
								</td>\
								</tr>';

					$('#list-items tbody tr.no-data').remove();
					$('#list-items tbody').append($($html));
            	}else{
            		alert(json.message);
            	}
            }
        });


			

		return false;
	});

	$('#list-items tr.item td.column-action a.delete').live('click', function(){
		var $id = $(this).attr('data-id');

		$confirm = confirm('Anda yakin ingin menghapus tindakan ini?');

		if($confirm){
			$url = '{{ url( 'observation' ) }}/' + $id;

			$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('input[name="_token"]').val()
	            }
	        });

			$.ajax({
	            url: $url,
	            type: 'delete',
	            data: {
	            	id_pemeriksaan_observasi: $id,
	            	id_observasi: '{{ $observation->id_observasi }}'
	            },
	            dataType: 'json',
	            beforeSend: function() {
	               
	            },      
	            complete: function() {
	            
	            },          
	            success: function(json) {
	            	if(json.success == 'true'){
	            		$('#list-items tbody tr#item-' + $id).remove();

						var $count = $('#list-items tbody tr.item').length;

						if(!$count){
							var $no_data = '<tr class="no-data">\
												<td colspan="4">Tidak ada data ditemukan.</td>\
											</tr>';

							$('#list-items tbody').append($($no_data));
						}

	            		alert(json.message);
	            	}else{
	            		alert(json.message);
	            	}
	            }
	        });
		}

		return false;
	});

	$('.btn-cancel').click(function(){
		var x = confirm( "Anda yakin ingin membatalkan? Semua isian yang sudah dilakukan tidak akan disimpan" );

		return x;
	});
});
</script>
@stop