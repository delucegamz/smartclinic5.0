@extends('layouts.app')

@section('page_title')
Smart Clinic System - Rekap Data Kunjungan
@stop

@section('scripts')
<script type="text/javascript" src="{{URL::asset('assets/js/jquery-ui.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('plugins/bootstrap-select/js/bootstrap-select.min.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('plugins/multi-select/js/jquery.quicksearch.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('plugins/multi-select/js/jquery.multi-select.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/jquery.easy-autocomplete.min.js')}}"></script>
@stop

@section('styles')
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.structure.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/jquery-ui.theme.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('plugins/bootstrap-select/css/bootstrap-select.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('plugins/multi-select/css/multi-select.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/easy-autocomplete.min.css')}}" />
@stop

@section('content')
<div id="participant">
	<div class="content-title"><h1>Rekap Data Kunjungan</h1></div>

	<div class="narrow">
		<h4>Pilih jenis laporan yang akan dibuat</h4>

		<form enctype="multipart/form-data" id="import-livestock-form" method="get" class="wp-upload-form" action="{{ url( 'report/recap' ) }}">
			<div class="row">
				<div class="col-xs-5">
					<div class="form-group">
						<label for="type">Jenis Laporan</label>
						<select name="type" id="type" class="form-control required show-tick dont-disabled">
							<option value="">- Pilih Jenis Laporan -</option>
							<option value="poli"{{ selected( 'poli', $type ) }}>Rekap Kunjungan Berdasarkan Poli</option>
							<option value="factory"{{ selected( 'factory', $type ) }}>Rekap Kunjungan Berdasarkan Factory</option>
							<option value="department"{{ selected( 'department', $type ) }}>Rekap Kunjungan Berdasarkan Departemen</option>
							<option value="service"{{ selected( 'service', $type ) }}>Rekap Kunjungan Berdasarkan Jenis Pelayanan Klinik</option>
							<option value="diagnosis"{{ selected( 'diagnosis', $type ) }}>Rekap Kunjungan Berdasarkan Diagnosa</option>
							<option value="doctor"{{ selected( 'doctor', $type ) }}>Rekap Kunjungan Berdasarkan Dokter Pemeriksa</option>
						</select>
					</div>
					<div class="form-group" id="elm-doctor">
						<label for="doctor">Dokter</label>
						<select name="doctor[]" id="doctor" class="form-control show-tick" multiple data-live-search="true" title="- Pilih Dokter -">
							<option value="all">Semua</option>
							@foreach( $doctors as $doctor )
							<option value="{{ $doctor->nama_karyawan }}">{{ check_job_title( $doctor->id_jabatan ) }} {{ $doctor->nama_karyawan }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group" id="elm-service">
						<label for="service">Jenis Pelayanan</label>
						<select name="service[]" id="service" class="form-control show-tick" multiple title="- Pilih Jenis Pelayanan -">
							<option value="all"{{ ( in_array( 'all', $res_services ) )  ? ' selected' : '' }}>Semua</option>
							<option value="11"{{ ( ( in_array( '11', $res_services ) ) && ( !in_array( 'all', $res_services ) ) ) ? ' selected' : '' }}>Pelayanan Umum</option>
							<option value="observation"{{ ( ( in_array( 'observation', $res_services ) ) && ( !in_array( 'all', $res_services ) ) ) ? ' selected' : '' }}>Observasi</option>
							<option value="44"{{ ( ( in_array( '44', $res_services ) ) && ( !in_array( 'all', $res_services ) ) ) ? ' selected' : '' }}>Kontrol Kecelakaan Kerja</option>
							<option value="55"{{ ( ( in_array( '55', $res_services ) ) && ( !in_array( 'all', $res_services ) ) ) ? ' selected' : '' }}>Kontrol Pasca Rawat Inap</option>
							<option value="33"{{ ( ( in_array( '33', $res_services ) ) && ( !in_array( 'all', $res_services ) ) ) ? ' selected' : '' }}>Kecelakaan Lalu Lintas</option>
							<option value="22"{{ ( ( in_array( '22', $res_services ) ) && ( !in_array( 'all', $res_services ) ) ) ? ' selected' : '' }}>Kecelakaan Kerja</option>
							<option value="66"{{ ( ( in_array( '66', $res_services ) ) && ( !in_array( 'all', $res_services ) ) ) ? ' selected' : '' }}>ANC</option>
						</select>
					</div>
					<div class="form-group" id="elm-diagnosis">
						<label for="factory">Diagnosa</label>
						<input type="text" name="diagnosis" id="diagnosis" class="form-control disabled-this" placeholder="Ketikkan kode atau nama diagnosa" value="Semua" />
						<input type="hidden" name="diagnosis-id" id="diagnosis-id" value="all" disabled />
					</div>
					<div class="form-group" id="elm-poli">
						<label for="factory">Poli</label>
						<select name="poli[]" id="poli" class="form-control show-tick" multiple data-live-search="true" title="- Pilih Poli -">
							<option value="all"{{ ( ( is_array( $res_poli ) ) && ( count( $res_poli ) ) && ( in_array( 'all', $res_poli ) ) ) ? ' selected' : '' }}>Semua</option>
							@foreach( $poli as $p )
							<option value="{{ $p->id_poli }}"{{ ( ( in_array( $p->id_poli, $res_poli ) ) && ( !in_array( 'all', $res_poli ) ) ) ? ' selected' : '' }}>{{ $p->nama_poli }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group" id="elm-factory">
						<label for="factory">Factory</label>
						<select name="factory[]" id="factory" class="form-control show-tick" multiple title="- Pilih Factory -">
							<option value="all"{{ ( ( is_array( $res_factories ) ) && ( count( $res_factories ) ) && ( in_array( 'all', $res_factories ) ) ) ? ' selected' : '' }}>Semua</option>
							@foreach( $factories as $f )
							<option value="{{ $f->id_factory }}"{{ ( ( in_array( $f->id_factory, $res_factories ) ) && ( !in_array( 'all', $res_factories ) ) ) ? ' selected' : '' }}>{{ $f->nama_factory }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group" id="elm-department">
						<label for="department">Departemen</label>
						<select name="department[]" id="department" class="form-control show-tick" multiple title="- Pilih Departemen -">
							<option value="all"{{ ( ( in_array( 'all', $res_departments ) ) ) ? ' selected' : '' }}>Semua</option>
							@foreach( $departments as $key => $values )
							<optgroup label="{{ get_factory_name( $key ) }}" data-factory="{{ $key }}"{{ ( ( !in_array( $key, $res_factories ) ) && ( !in_array( 'all', $res_factories ) ) ) ? ' disabled' : '' }}>
								@foreach( $values as $department )
								<option value="{{ $department->id_departemen }}"{{ ( ( in_array( $department->id_departemen, $res_departments ) ) && ( !in_array( 'all', $res_departments ) ) ) ? ' selected' : '' }}>{{ $department->nama_departemen }}</option>
								@endforeach
							</optgroup>
							@endforeach
						</select>
					</div>
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
			
			@if( $is_results )
			@php
				$title = '';
				switch( $type ){
					case 'poli':
						$title = 'Berdasarkan Poli';
						break;
					case 'factory':
						$title = 'Per Factory';
						break;
					case 'department':
						$title = 'Per Departemen';
						break;
					case 'service':
						$title = 'Berdasarkan Jenis Pelayanan Klinik';
						break;
					case 'diagnosis':
						$title = 'Per Diagnosa';
						break;
					case 'doctor':
						$title = 'Per Dokter Pemeriksa';
						break;
				}

				$date_from =  ( !empty( $date_from ) ) ? $date_from . ' 00:00:00' : '';
				$date_to = ( !empty( $date_to ) ) ? $date_to . ' 23:59:59' : '';

				$date_from_formatted = ( !empty( $date_from ) ) ? date( 'd M Y', strtotime( $date_from ) ) : '';
				$date_to_formatted = ( !empty( $date_to ) ) ? date( 'd M Y', strtotime( $date_to ) ) : date( 'd M Y' );
			@endphp
			<div id="response-update">
				<div id="print-header" class="text-center">
					<h2>{{ get_company_name() }}</h2>
					<h5>{{ get_company_address() }}</h5>
				</div>

				<!--<div class="form-group">
					<div class="row">
						<div class="col-xs-4"><strong>Laporan Kunjungan Pasien Per </strong></div>
						<div class="col-xs-4"><strong>: {{ $title }}</strong></div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-4">Periode </div>
						<div class="col-xs-4">: {{ $date_from_formatted }} s/d {{ $date_to_formatted }}</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-xs-4">Tanggal Tarik Data </div>
						<div class="col-xs-4">: {{ date( 'd M Y' ) }}</div>
					</div>
				</div>-->

				@php
				$current_user = Auth::user();
			    $idpengguna = $current_user['original']['idpengguna'];
			    $user = App\User::find( $idpengguna );
				$staff = App\Staff::where( 'id_karyawan', '=', $user->id_karyawan )->first();
				@endphp

				<h2 style="text-align: center; margin: 0 0 10px;">LAPORAN KUNJUNGAN PASIEN {{ strtoupper( $title ) }}</h2>
				<h3 style="text-align: center; margin: 0 0 30px;">PERIODE: {{ $date_from_formatted }} - {{ $date_to_formatted }}</h3>
				<h5 style="text-align: center; margin: 0 0 10px;">DATE PRINTED : {{ date( 'd-m-Y' ) }}</h5>
				<h5 style="text-align: center; margin: 0 0 30px;">PRINTED BY : {{ $staff->nama_karyawan }}</h5>

				<div style="max-width:500px;margin:0 auto; padding: 50px 0px;">
					@if( $type == 'poli' )
					<table class="table table-bordered">
						<thead>
							<tr>
								<th style="width:50px;">No. </th>
								<th>Nama Poli</th>
								<th>Jumlah</th>
							</tr>
						</thead>
						<tbody>
							@php
								$polys = App\Poli::where( function( $q ) use ( $res_poli ){ 
									if( !in_array( 'all', $res_poli ) ) $q->whereIn( 'id_poli', $res_poli );
								})->get();

								$i = 1; $count_total = 0;
							@endphp
							@foreach( $polys as $poli )
							@php
								$poliregistration_ids = App\PoliRegistration::where( 'id_poli', '=', $poli->id_poli )
								->where( function( $q ) use ( $date_from ){
									if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
								})->where( function( $q ) use ( $date_to ){
									if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
								})->get( ['id_pendaftaran'] )->toArray();

								$poliregistrations = array();
					            foreach( $poliregistration_ids as $pid ){
					            	$poliregistrations[] = $pid;
					            }

					            $count = App\MedicalRecord::where( function( $q ) use ( $poliregistrations ){
				                    $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				                } )->where( function( $q ) use ( $res_factories, $res_departments ){
				                    if( !in_array( 'all', $res_factories ) ){ // Pengecekan ketika yang dipilih bukan keseluruhan factory / factory tertentu
				                       	$department_ids = App\Department::whereIn( 'nama_factory', $res_factories )->whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

									    $departments = array();
									    foreach( $department_ids as $dept_id ){
									        $departments[] = $dept_id;
									    }

									    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
									    $participants = array();
									    foreach( $participant_ids as $pid ){
									        $participants[] = $pid;
									    }

									    $q->whereIn( 'id_peserta', $participants );
				                    }else{
				                    	if( count( $res_departments ) && is_array( $res_departments ) ){
				                    		if( !in_array( 'all', $res_departments ) ){
				                    			$department_ids = App\Department::whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

											    $departments = array();
											    foreach( $department_ids as $dept_id ){
											        $departments[] = $dept_id;
											    }

											    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
											    $participants = array();
											    foreach( $participant_ids as $pid ){
											        $participants[] = $pid;
											    }

											    $q->whereIn( 'id_peserta', $participants );
				                    		}
				                    	}
				                	}
				                } )->count();

				                $count_total += $count;
							@endphp
							<tr>
								<td>{{ $i }}</td>
								<td>{{ $poli->nama_poli }}</td>
								<td>{{ $count }}</td>
							</tr>
							@php $i++; @endphp
							@endforeach
							
							<tr>
								<td colspan="2">Total</td>
								<td>{{ $count_total }}</td>
							</tr>
						</tbody>
					</table>
					@endif

					@if( $type == 'factory' )
					<table class="table table-bordered">
						<thead>
							<tr>
								<th style="width:50px;">No. </th>
								<th>Nama Factory</th>
								<th>Jumlah</th>
							</tr>
						</thead>
						<tbody>
							@php
							if( in_array( 'all', $res_factories ) )
								$factories = App\Factory::all();
							else
								$factories = App\Factory::whereIn( 'id_factory', $res_factories )->get();

							$poliregistration_ids = App\PoliRegistration::where( function( $q ) use ( $date_from ){
								if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
							})->where( function( $q ) use ( $date_to ){
								if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
							})->get( ['id_pendaftaran'] )->toArray();

							$poliregistrations = array();
				            foreach( $poliregistration_ids as $pid ){
				            	$poliregistrations[] = $pid;
				            }	

							$i = 1; $count_total = 0;
							@endphp
							@foreach( $factories as $factory )
							@php
					            $count = App\MedicalRecord::where( function( $q ) use ( $factory, $res_departments ){
			                        if( is_array( $res_departments ) && count( $res_departments ) && !in_array( 'all', $res_departments ) ){ // Pengecekan ketika yang dipilih ada departemen tertentu dalam factory yang terpilih
			                            $department_ids = App\Department::where( 'nama_factory', '=', $factory->id_factory )->whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();
			                        }else{ // Pengecekan ketika harus menampilkan semua departemen dari factory yang dipilih
			                            $department_ids = App\Department::where( 'nama_factory', '=', $factory->id_factory )->get( ['id_departemen'] )->toArray();
			                        }

			                        $departments = array();
		                            foreach( $department_ids as $dept_id ){
		                                $departments[] = $dept_id;
		                            }

		                            $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
		                            $participants = array();
		                            foreach( $participant_ids as $pid ){
		                                $participants[] = $pid;
		                            }

		                            $q->whereIn( 'id_peserta', $participants );
				                })->where( function( $q ) use ( $poliregistrations ){
				                	if( count( $poliregistrations ) ) $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				            	})->count();

				                $count_total += $count;
							@endphp
							<tr>
								<td>{{ $i }}</td>
								<td>{{ $factory->nama_factory }}</td>
								<td>{{ $count }}</td>
							</tr>
							@php $i++; @endphp
							@endforeach
							
							<tr>
								<td colspan="2">Total</td>
								<td>{{ $count_total }}</td>
							</tr>
						</tbody>
					</table>
					@endif

					@if( $type == 'department' )
					<table class="table table-bordered">
						<thead>
							<tr>
								<th style="width:50px;">No. </th>
								<th>Nama Departemen</th>
								<th>Jumlah</th>
							</tr>
						</thead>
						<tbody>
							@php
							if( in_array( 'all', $res_departments ) )
								$departments = App\Department::all();
							else
								$departments = App\Department::whereIn( 'id_departemen', $res_departments )->get();

							$poliregistration_ids = App\PoliRegistration::where( function( $q ) use ( $date_from ){
								if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
							})->where( function( $q ) use ( $date_to ){
								if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
							})->get( ['id_pendaftaran'] )->toArray();

							$poliregistrations = array();
				            foreach( $poliregistration_ids as $pid ){
				            	$poliregistrations[] = $pid;
				            }	

							$i = 1; $count_total = 0;
							@endphp
							@foreach( $departments as $department )
							@php
					            $count = App\MedicalRecord::where( function( $q ) use ( $department ){
		                            $participant_ids = App\Participant::where( 'id_departemen', '=', $department->id_departemen )->get( ['id_peserta'] )->toArray();
		                            $participants = array();
		                            foreach( $participant_ids as $pid ){
		                                $participants[] = $pid;
		                            }

		                            $q->whereIn( 'id_peserta', $participants );
				                })->where( function( $q ) use ( $poliregistrations ){
				                	if( count( $poliregistrations ) ) $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				            	})->count();

				                $count_total += $count;
							@endphp
							<tr>
								<td>{{ $i }}</td>
								<td>{{ $department->nama_departemen }}</td>
								<td>{{ $count }}</td>
							</tr>
							@php $i++; @endphp
							@endforeach
							
							<tr>
								<td colspan="2">Total</td>
								<td>{{ $count_total }}</td>
							</tr>
						</tbody>
					</table>
					@endif

					@if( $type == 'service' )
					<table class="table table-bordered">
						<thead>
							<tr>
								<th style="width:50px;">No. </th>
								<th>Nama Factory</th>
								<th>Jumlah</th>
							</tr>
						</thead>
						<tbody>
							@php $count_total = 0; $i = 1; @endphp 
							@if( in_array( '11', $res_services ) || in_array( 'all', $res_services ) )
							<tr>
								<td>{{ $i }}</td>
								<td>Umum</td>
								<td>
								@php
								$poliregistration_ids = App\PoliRegistration::where( function( $q ) use ( $date_from ){
									if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
								})->where( function( $q ) use ( $date_to ){
									if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
								})->get( ['id_pendaftaran'] )->toArray();

								$poliregistrations = array();
					            foreach( $poliregistration_ids as $pid ){
					            	$poliregistrations[] = $pid;
					            }

					            $count = App\MedicalRecord::where( function( $q ) use ( $res_factories, $res_departments ){
			                        if( !in_array( 'all', $res_factories ) ){ // Pengecekan ketika yang dipilih bukan keseluruhan factory / factory tertentu
				                       	$department_ids = App\Department::whereIn( 'nama_factory', $res_factories )->whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

									    $departments = array();
									    foreach( $department_ids as $dept_id ){
									        $departments[] = $dept_id;
									    }

									    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
									    $participants = array();
									    foreach( $participant_ids as $pid ){
									        $participants[] = $pid;
									    }

									    $q->whereIn( 'id_peserta', $participants );
				                    }else{
				                    	if( count( $res_departments ) && is_array( $res_departments ) ){
				                    		if( !in_array( 'all', $res_departments ) ){
				                    			$department_ids = App\Department::whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

											    $departments = array();
											    foreach( $department_ids as $dept_id ){
											        $departments[] = $dept_id;
											    }

											    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
											    $participants = array();
											    foreach( $participant_ids as $pid ){
											        $participants[] = $pid;
											    }

											    $q->whereIn( 'id_peserta', $participants );
				                    		}
				                    	}
				                	}
				                })->where( function( $q ) use ( $poliregistrations ){
				                	if( count( $poliregistrations ) ) $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				            	})->where( 'uraian', '=', 11 )->count();

				            	$count_total += $count;

				            	echo $count;
								@endphp
								</td>
							</tr>
							@php $i++; @endphp
							@endif

							@if( in_array( 'observation', $res_services ) || in_array( 'all', $res_services ) )
							<tr>
								<td>{{ $i }}</td>
								<td>Observasi</td>
								<td>
								@php
								$poliregistration_ids = App\PoliRegistration::where( function( $q ) use ( $date_from ){
									if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
								})->where( function( $q ) use ( $date_to ){
									if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
								})->get( ['id_pendaftaran'] )->toArray();

								$poliregistrations = array();
					            foreach( $poliregistration_ids as $pid ){
					            	$poliregistrations[] = $pid;
					            }

					            $count = App\MedicalRecord::where( function( $q ) use ( $res_factories, $res_departments ){
			                       	if( !in_array( 'all', $res_factories ) ){ // Pengecekan ketika yang dipilih bukan keseluruhan factory / factory tertentu
				                       	$department_ids = App\Department::whereIn( 'nama_factory', $res_factories )->whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

									    $departments = array();
									    foreach( $department_ids as $dept_id ){
									        $departments[] = $dept_id;
									    }

									    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
									    $participants = array();
									    foreach( $participant_ids as $pid ){
									        $participants[] = $pid;
									    }

									    $q->whereIn( 'id_peserta', $participants );
				                    }else{
				                    	if( count( $res_departments ) && is_array( $res_departments ) ){
				                    		if( !in_array( 'all', $res_departments ) ){
				                    			$department_ids = App\Department::whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

											    $departments = array();
											    foreach( $department_ids as $dept_id ){
											        $departments[] = $dept_id;
											    }

											    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
											    $participants = array();
											    foreach( $participant_ids as $pid ){
											        $participants[] = $pid;
											    }

											    $q->whereIn( 'id_peserta', $participants );
				                    		}
				                    	}
				                	}
				                })->where( function( $q ) use ( $poliregistrations ){
				                	if( count( $poliregistrations ) ) $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				            	})->where( function( $q ) {
				            		$medrec_ids = DB::table( 't_observasi' )
						                ->select( DB::raw( 'DISTINCT( id_pemeriksaan_poli )' ) )
						                ->get();

				                    $medrecs = array();
				                    foreach( $medrec_ids as $id ){
				                    	$medrecs[] = $id->id_pemeriksaan_poli;
				                	}

				                	$q->whereIn( 'id_pemeriksaan_poli', $medrecs );

				            	})->count();

				            	$count_total += $count;

				            	echo $count;
								@endphp
								</td>
							</tr>
							@php $i++; @endphp
							@endif

							@if( in_array( '44', $res_services ) || in_array( 'all', $res_services ) )
							<tr>
								<td>{{ $i }}</td>
								<td>Kontrol Kecelakaan Kerja</td>
								<td>
								@php
								$poliregistration_ids = App\PoliRegistration::where( function( $q ) use ( $date_from ){
									if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
								})->where( function( $q ) use ( $date_to ){
									if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
								})->get( ['id_pendaftaran'] )->toArray();

								$poliregistrations = array();
					            foreach( $poliregistration_ids as $pid ){
					            	$poliregistrations[] = $pid;
					            }

					            $count = App\MedicalRecord::where( function( $q ) use ( $res_factories, $res_departments ){
			                        if( !in_array( 'all', $res_factories ) ){ // Pengecekan ketika yang dipilih bukan keseluruhan factory / factory tertentu
				                       	$department_ids = App\Department::whereIn( 'nama_factory', $res_factories )->whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

									    $departments = array();
									    foreach( $department_ids as $dept_id ){
									        $departments[] = $dept_id;
									    }

									    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
									    $participants = array();
									    foreach( $participant_ids as $pid ){
									        $participants[] = $pid;
									    }

									    $q->whereIn( 'id_peserta', $participants );
				                    }else{
				                    	if( count( $res_departments ) && is_array( $res_departments ) ){
				                    		if( !in_array( 'all', $res_departments ) ){
				                    			$department_ids = App\Department::whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

											    $departments = array();
											    foreach( $department_ids as $dept_id ){
											        $departments[] = $dept_id;
											    }

											    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
											    $participants = array();
											    foreach( $participant_ids as $pid ){
											        $participants[] = $pid;
											    }

											    $q->whereIn( 'id_peserta', $participants );
				                    		}
				                    	}
				                	}
				                })->where( function( $q ) use ( $poliregistrations ){
				                	if( count( $poliregistrations ) ) $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				            	})->where( 'uraian', '=', 44 )->count();

				            	$count_total += $count;

				            	echo $count;
								@endphp
								</td>
							</tr>
							@php $i++; @endphp
							@endif

							@if( in_array( '55', $res_services ) || in_array( 'all', $res_services ) )
							<tr>
								<td>{{ $i }}</td>
								<td>Kontrol Pasca Rawat Inap</td>
								<td>
								@php
								$poliregistration_ids = App\PoliRegistration::where( function( $q ) use ( $date_from ){
									if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
								})->where( function( $q ) use ( $date_to ){
									if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
								})->get( ['id_pendaftaran'] )->toArray();

								$poliregistrations = array();
					            foreach( $poliregistration_ids as $pid ){
					            	$poliregistrations[] = $pid;
					            }

					            $count = App\MedicalRecord::where( function( $q ) use ( $res_factories, $res_departments ){
			                        if( !in_array( 'all', $res_factories ) ){ // Pengecekan ketika yang dipilih bukan keseluruhan factory / factory tertentu
				                       	$department_ids = App\Department::whereIn( 'nama_factory', $res_factories )->whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

									    $departments = array();
									    foreach( $department_ids as $dept_id ){
									        $departments[] = $dept_id;
									    }

									    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
									    $participants = array();
									    foreach( $participant_ids as $pid ){
									        $participants[] = $pid;
									    }

									    $q->whereIn( 'id_peserta', $participants );
				                    }else{
				                    	if( count( $res_departments ) && is_array( $res_departments ) ){
				                    		if( !in_array( 'all', $res_departments ) ){
				                    			$department_ids = App\Department::whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

											    $departments = array();
											    foreach( $department_ids as $dept_id ){
											        $departments[] = $dept_id;
											    }

											    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
											    $participants = array();
											    foreach( $participant_ids as $pid ){
											        $participants[] = $pid;
											    }

											    $q->whereIn( 'id_peserta', $participants );
				                    		}
				                    	}
				                	}
				                })->where( function( $q ) use ( $poliregistrations ){
				                	if( count( $poliregistrations ) ) $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				            	})->where( 'uraian', '=', 55 )->count();

				            	$count_total += $count;

				            	echo $count;
								@endphp
								</td>
							</tr>
							@php $i++; @endphp
							@endif

							@if( in_array( '33', $res_services ) || in_array( 'all', $res_services ) )
							<tr>
								<td>{{ $i }}</td>
								<td>Kecelakaan Lalu Lintas</td>
								<td>
								@php
								$poliregistration_ids = App\PoliRegistration::where( function( $q ) use ( $date_from ){
									if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
								})->where( function( $q ) use ( $date_to ){
									if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
								})->get( ['id_pendaftaran'] )->toArray();

								$poliregistrations = array();
					            foreach( $poliregistration_ids as $pid ){
					            	$poliregistrations[] = $pid;
					            }

					            $count = App\MedicalRecord::where( function( $q ) use ( $res_factories, $res_departments ){
			                       	if( !in_array( 'all', $res_factories ) ){ // Pengecekan ketika yang dipilih bukan keseluruhan factory / factory tertentu
				                       	$department_ids = App\Department::whereIn( 'nama_factory', $res_factories )->whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

									    $departments = array();
									    foreach( $department_ids as $dept_id ){
									        $departments[] = $dept_id;
									    }

									    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
									    $participants = array();
									    foreach( $participant_ids as $pid ){
									        $participants[] = $pid;
									    }

									    $q->whereIn( 'id_peserta', $participants );
				                    }else{
				                    	if( count( $res_departments ) && is_array( $res_departments ) ){
				                    		if( !in_array( 'all', $res_departments ) ){
				                    			$department_ids = App\Department::whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

											    $departments = array();
											    foreach( $department_ids as $dept_id ){
											        $departments[] = $dept_id;
											    }

											    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
											    $participants = array();
											    foreach( $participant_ids as $pid ){
											        $participants[] = $pid;
											    }

											    $q->whereIn( 'id_peserta', $participants );
				                    		}
				                    	}
				                	}
				                })->where( function( $q ) use ( $poliregistrations ){
				                	if( count( $poliregistrations ) ) $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				            	})->where( 'uraian', '=', 33 )->count();

				            	$count_total += $count;

				            	echo $count;
								@endphp
								</td>
							</tr>
							@php $i++; @endphp
							@endif

							@if( in_array( '22', $res_services ) || in_array( 'all', $res_services ) )
							<tr>
								<td>{{ $i }}</td>
								<td>Kecelakaan Kerja</td>
								<td>
								@php
								$poliregistration_ids = App\PoliRegistration::where( function( $q ) use ( $date_from ){
									if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
								})->where( function( $q ) use ( $date_to ){
									if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
								})->get( ['id_pendaftaran'] )->toArray();

								$poliregistrations = array();
					            foreach( $poliregistration_ids as $pid ){
					            	$poliregistrations[] = $pid;
					            }

					            $count = App\MedicalRecord::where( function( $q ) use ( $res_factories, $res_departments ){
			                       	if( !in_array( 'all', $res_factories ) ){ // Pengecekan ketika yang dipilih bukan keseluruhan factory / factory tertentu
				                       	$department_ids = App\Department::whereIn( 'nama_factory', $res_factories )->whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

									    $departments = array();
									    foreach( $department_ids as $dept_id ){
									        $departments[] = $dept_id;
									    }

									    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
									    $participants = array();
									    foreach( $participant_ids as $pid ){
									        $participants[] = $pid;
									    }

									    $q->whereIn( 'id_peserta', $participants );
				                    }else{
				                    	if( count( $res_departments ) && is_array( $res_departments ) ){
				                    		if( !in_array( 'all', $res_departments ) ){
				                    			$department_ids = App\Department::whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

											    $departments = array();
											    foreach( $department_ids as $dept_id ){
											        $departments[] = $dept_id;
											    }

											    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
											    $participants = array();
											    foreach( $participant_ids as $pid ){
											        $participants[] = $pid;
											    }

											    $q->whereIn( 'id_peserta', $participants );
				                    		}
				                    	}
				                	}
				                })->where( function( $q ) use ( $poliregistrations ){
				                	if( count( $poliregistrations ) ) $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				            	})->where( 'uraian', '=', 22 )->count();

				            	$count_total += $count;

				            	echo $count;
								@endphp
								</td>
							</tr>
							@php $i++; @endphp
							@endif

							@if( in_array( '66', $res_services ) || in_array( 'all', $res_services ) )
							<tr>
								<td>{{ $i }}</td>
								<td>ANC</td>
								<td>
								@php
								$poliregistration_ids = App\PoliRegistration::where( function( $q ) use ( $date_from ){
									if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
								})->where( function( $q ) use ( $date_to ){
									if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
								})->get( ['id_pendaftaran'] )->toArray();

								$poliregistrations = array();
					            foreach( $poliregistration_ids as $pid ){
					            	$poliregistrations[] = $pid;
					            }

					            $count = App\MedicalRecord::where( function( $q ) use ( $res_factories, $res_departments ){
			                       	if( !in_array( 'all', $res_factories ) ){ // Pengecekan ketika yang dipilih bukan keseluruhan factory / factory tertentu
				                       	$department_ids = App\Department::whereIn( 'nama_factory', $res_factories )->whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

									    $departments = array();
									    foreach( $department_ids as $dept_id ){
									        $departments[] = $dept_id;
									    }

									    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
									    $participants = array();
									    foreach( $participant_ids as $pid ){
									        $participants[] = $pid;
									    }

									    $q->whereIn( 'id_peserta', $participants );
				                    }else{
				                    	if( count( $res_departments ) && is_array( $res_departments ) ){
				                    		if( !in_array( 'all', $res_departments ) ){
				                    			$department_ids = App\Department::whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

											    $departments = array();
											    foreach( $department_ids as $dept_id ){
											        $departments[] = $dept_id;
											    }

											    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
											    $participants = array();
											    foreach( $participant_ids as $pid ){
											        $participants[] = $pid;
											    }

											    $q->whereIn( 'id_peserta', $participants );
				                    		}
				                    	}
				                	}
				                })->where( function( $q ) use ( $poliregistrations ){
				                	if( count( $poliregistrations ) ) $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				            	})->where( 'uraian', '=', 66 )->count();

				            	$count_total += $count;

				            	echo $count;
								@endphp
								</td>
							</tr>
							@php $i++; @endphp
							@endif
							
							<tr>
								<td colspan="2">Total</td>
								<td>{{ $count_total }}</td>
							</tr>
						</tbody>
					</table>
					@endif

					@if( $type == 'diagnosis' )
					<table class="table table-bordered">
						<thead>
							<tr>
								<th style="width:50px;">No. </th>
								<th>Nama Diagnosa</th>
								<th>Jumlah</th>
							</tr>
						</thead>
						<tbody>
							@php
								$icdx_codes = DB::table( 't_pemeriksaan_poli' )
						                ->select( DB::raw( 'DISTINCT( iddiagnosa )' ) )
						                ->get();

						        $icdxs_ids = array();
						        foreach( $icdx_codes as $code ){
						        	$icdxs_ids[] = $code->iddiagnosa;
						    	}

						    	$icdxs = App\Diagnosis::whereIn( 'kode_diagnosa', $icdxs_ids )->get();

								$i = 1; $count_total = 0;
							@endphp
							@foreach( $icdxs as $icdx )
							@php
								$poliregistration_ids = App\PoliRegistration::where( function( $q ) use ( $res_poli ){
									if( !in_array( 'all', $res_poli ) ) $q->whereIn( 'id_poli', $res_poli );
								})->where( function( $q ) use ( $date_from ){
									if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
								})->where( function( $q ) use ( $date_to ){
									if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
								})->get( ['id_pendaftaran'] )->toArray();

								$poliregistrations = array();
					            foreach( $poliregistration_ids as $pid ){
					            	$poliregistrations[] = $pid;
					            }

					            $count = App\MedicalRecord::where( function( $q ) use ( $poliregistrations ){
				                    $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				                } )->where( function( $q ) use ( $res_factories, $res_departments ){
				                    if( !in_array( 'all', $res_factories ) ){ // Pengecekan ketika yang dipilih bukan keseluruhan factory / factory tertentu
				                       	$department_ids = App\Department::whereIn( 'nama_factory', $res_factories )->whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

									    $departments = array();
									    foreach( $department_ids as $dept_id ){
									        $departments[] = $dept_id;
									    }

									    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
									    $participants = array();
									    foreach( $participant_ids as $pid ){
									        $participants[] = $pid;
									    }

									    $q->whereIn( 'id_peserta', $participants );
				                    }else{
				                    	if( count( $res_departments ) && is_array( $res_departments ) ){
				                    		if( !in_array( 'all', $res_departments ) ){
				                    			$department_ids = App\Department::whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

											    $departments = array();
											    foreach( $department_ids as $dept_id ){
											        $departments[] = $dept_id;
											    }

											    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
											    $participants = array();
											    foreach( $participant_ids as $pid ){
											        $participants[] = $pid;
											    }

											    $q->whereIn( 'id_peserta', $participants );
				                    		}
				                    	}
				                	}
				                } )->where( 'iddiagnosa', '=', $icdx->kode_diagnosa )->count();

				                $count_total += $count;
							@endphp
							<tr>
								<td>{{ $i }}</td>
								<td>{{ $icdx->kode_diagnosa }} &ndash; {{ $icdx->nama_diagnosa }}</td>
								<td>{{ $count }}</td>
							</tr>
							@php $i++; @endphp
							@endforeach
							
							<tr>
								<td colspan="2">Total</td>
								<td>{{ $count_total }}</td>
							</tr>
						</tbody>
					</table>
					@endif

					@if( $type == 'doctor' )
					<table class="table table-bordered">
						<thead>
							<tr>
								<th style="width:50px;">No. </th>
								<th>Nama Dokter/Bidan</th>
								<th>Jumlah</th>
							</tr>
						</thead>
						<tbody>
							@php
								$i = 1; $count_total = 0;
							@endphp
							@foreach( $doctors as $doctor )
							@php
								$poliregistration_ids = App\PoliRegistration::where( function( $q ) use ( $res_poli ){
									if( !in_array( 'all', $res_poli ) ) $q->whereIn( 'id_poli', $res_poli );
								})->where( function( $q ) use ( $date_from ){
									if( $date_from ) $q->where( 'tgl_daftar', '>=', $date_from );
								})->where( function( $q ) use ( $date_to ){
									if( $date_to ) $q->where( 'tgl_daftar', '<=', $date_to );
								})->get( ['id_pendaftaran'] )->toArray();

								$poliregistrations = array();
					            foreach( $poliregistration_ids as $pid ){
					            	$poliregistrations[] = $pid;
					            }

					            $count = App\MedicalRecord::where( function( $q ) use ( $poliregistrations ){
				                    $q->whereIn( 'id_pendaftaran_poli', $poliregistrations );
				                } )->where( function( $q ) use ( $res_factories, $res_departments ){
				                    if( !in_array( 'all', $res_factories ) ){ // Pengecekan ketika yang dipilih bukan keseluruhan factory / factory tertentu
				                       	$department_ids = App\Department::whereIn( 'nama_factory', $res_factories )->whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

									    $departments = array();
									    foreach( $department_ids as $dept_id ){
									        $departments[] = $dept_id;
									    }

									    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
									    $participants = array();
									    foreach( $participant_ids as $pid ){
									        $participants[] = $pid;
									    }

									    $q->whereIn( 'id_peserta', $participants );
				                    }else{
				                    	if( count( $res_departments ) && is_array( $res_departments ) ){
				                    		if( !in_array( 'all', $res_departments ) ){
				                    			$department_ids = App\Department::whereIn( 'id_departemen', $res_departments )->get( ['id_departemen'] )->toArray();

											    $departments = array();
											    foreach( $department_ids as $dept_id ){
											        $departments[] = $dept_id;
											    }

											    $participant_ids = App\Participant::whereIn( 'id_departemen', $departments )->get( ['id_peserta'] )->toArray();
											    $participants = array();
											    foreach( $participant_ids as $pid ){
											        $participants[] = $pid;
											    }

											    $q->whereIn( 'id_peserta', $participants );
				                    		}
				                    	}
				                	}
				                } )->where( 'dokter_rawat', 'like', $doctor->nama_karyawan )->count();

				                $count_total += $count;
							@endphp
							<tr>
								<td>{{ $i }}</td>
								<td>{{ check_job_title( $doctor->id_jabatan ) }} {{ $doctor->nama_karyawan }}</td>
								<td>{{ $count }}</td>
							</tr>
							@php $i++; @endphp
							@endforeach
							
							<tr>
								<td colspan="2">Total</td>
								<td>{{ $count_total }}</td>
							</tr>
						</tbody>
					</table>
					@endif
				</div>

				<div class="form-group">
					@php
					$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					$print_url = str_replace( url( 'report/recap' ), url( 'print/recap' ), $actual_link );
					@endphp
					<a href="{{ $print_url }}" class="btn btn-primary" target="_blank">Print</a>
				</div>
			</div>
			@endif
		</form>
		<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
	</div>
</div>
<script type="text/javascript" src="{{ URL::asset( 'assets/js/jquery.form.min.js' ) }}"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#collapseSix').addClass('in');

	$('select').not('.dont-selectpicker').selectpicker();

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

	var options = {
        url: function(phrase) {
            return '{{ route( 'diagnosis.index' ) }}/search';
        },
        getValue: function(element) {
            return element.display_name;
        },
        ajaxSettings: {
            dataType: "json",
            method: "POST",
            data: { val : '' }
        },
        preparePostData: function(data) {
            data.val = $("#diagnosis").val();
            return data;
        },
        requestDelay: 200,
        list: {
            maxNumberOfElements: 10,
            onSelectItemEvent: function() {
                var selectedItemValue = $("#diagnosis").getSelectedItemData();
            },
            onClickEvent: function() {
                var selectedItemValue = $("#diagnosis").getSelectedItemData();
            },
            onHideListEvent: function() {
                
            },
            onChooseEvent: function(){
                var selectedItemValue = $("#diagnosis").getSelectedItemData();

                $('#diagnosis-id').val(selectedItemValue.kode_diagnosa);
                $('#diagnosis').val(selectedItemValue.nama_diagnosa);
            }
        }
    };

    $("#diagnosis").easyAutocomplete(options);

	$('#type').change(function(){
		$('select.form-control').not('.dont-disabled').removeAttr('disabled').find('option').removeAttr('selected');
		$('select.form-control').not('.dont-disabled').find('option[value="all"]').attr('selected',true);
		$('#department').find('optgroup').removeAttr('disabled');
		$('select.form-control').not('.dont-disabled').attr('disabled',true);
		$('input[type="text"].form-control.disabled-this').removeAttr('disabled').val('');
		$('#diagnosis').val('Semua');
		$('#diagnosis-id').val('all').attr('disabled', true);
		$('input[type="text"].form-control.disabled-this').attr('disabled',true);

		$val = $(this).val();

		if($val == 'poli'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#poli').removeAttr('disabled');
			$('#factory').removeAttr('disabled');
			$('#department').removeAttr('disabled');
		}else if($val == 'factory'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#factory').removeAttr('disabled');
			$('#department').removeAttr('disabled');
		}else if($val == 'department'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#department').removeAttr('disabled');
		}else if($val == 'service'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#service').removeAttr('disabled');
			$('#factory').removeAttr('disabled');
			$('#department').removeAttr('disabled');
		}else if($val == 'diagnosis'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#poli').removeAttr('disabled');
			$('#diagnosis').removeAttr('disabled');
			$('#factory').removeAttr('disabled');
			$('#department').removeAttr('disabled');
		}else if($val == 'doctor'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#poli').removeAttr('disabled');
			$('#doctor').removeAttr('disabled');
			$('#factory').removeAttr('disabled');
			$('#department').removeAttr('disabled');
		}else{

		}

		$('.form-control').each(function(){
			$(this).data('old-data', $(this).val());
		});

		$('select.form-control').not('.dont-disabled').selectpicker('refresh');
	});

	$('#date-from, #date-to').datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true
    });

    var init = function(){
    	//$('select.form-control').not('.dont-disabled').removeAttr('disabled').find('option').removeAttr('selected');
		//$('select.form-control').not('.dont-disabled').find('option[value="all"]').attr('selected',true);
		$('select.form-control').not('.dont-disabled').attr('disabled',true);
		$('input[type="text"].form-control.disabled-this').attr('disabled',true);

		$val = '{{ $type }}';

		if($val == 'poli'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#poli').removeAttr('disabled');
			$('#factory').removeAttr('disabled');
			// $('#department').removeAttr('disabled').find('optgroup').attr('disabled', true);
			// $('#department').attr('disabled', true);
			$('#department').removeAttr('disabled');
		}else if($val == 'factory'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#factory').removeAttr('disabled');
			$('#department').removeAttr('disabled');
		}else if($val == 'department'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#department').removeAttr('disabled');
		}else if($val == 'service'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#service').removeAttr('disabled');
			$('#factory').removeAttr('disabled');
			$('#department').removeAttr('disabled');
		}else if($val == 'diagnosis'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#poli').removeAttr('disabled');
			$('#diagnosis').removeAttr('disabled');
			$('#factory').removeAttr('disabled');
			$('#department').removeAttr('disabled');
		}else if($val == 'doctor'){
			$('#date-from').removeAttr('disabled');
			$('#date-to').removeAttr('disabled');
			$('#poli').removeAttr('disabled');
			$('#doctor').removeAttr('disabled');
			$('#factory').removeAttr('disabled');
			$('#department').removeAttr('disabled');
		}else{

		}

		$('.form-control').each(function(){
			$(this).data('old-data', $(this).val());
		});

		$('select.form-control').not('.dont-disabled').selectpicker('refresh');
    }

    init();

    $('#factory').change(function(){
    	$vals = $(this).val(); 
    	$vals = ( $vals == null ) ? [] : $vals;
    	$old_data = $(this).data('old-data');
    	$old_data = ( $old_data == null ) ? [] : $old_data;

    	$('#department').removeAttr('disabled').find('optgroup').attr('disabled', true);

		$check_all =  $vals.indexOf('all');
		$check_all_old = $old_data.indexOf('all');

		if($check_all > -1 && $check_all_old < 0){
			$('#department').find('optgroup').removeAttr('disabled', true);
			$(this).find('option[value!="all"]').removeAttr('selected');	
			$(this).selectpicker('refresh');
		}else{
			$vals.forEach(generate_department);	
			$(this).find('option[value="all"]').removeAttr('selected');			
			$(this).selectpicker('refresh');
		}
    	
    	$('#department').selectpicker('refresh');

    	$new_vals = $(this).val(); 
    	
		$(this).data('old-data', $new_vals);
    });

    var generate_department = function($fact_id){
    	$('#department').find('optgroup[data-factory="' + $fact_id + '"]').removeAttr('disabled');
    }

    $('select.form-control').not('.dont-disabled').not('#factory').change(function(){
    	$vals = $(this).val(); 
    	$vals = ( $vals == null ) ? [] : $vals;
    	$old_data = $(this).data('old-data');
    	$old_data = ( $old_data == null ) ? [] : $old_data;

		$check_all = $vals.indexOf('all');
		$check_all_old = $old_data.indexOf('all');

		if($check_all > -1 && $check_all_old < 0){
			$(this).find('option[value!="all"]').removeAttr('selected');	
			
			$(this).selectpicker('refresh');
		}else{
			$(this).find('option[value="all"]').removeAttr('selected');	
			
			$(this).selectpicker('refresh');
		}

		$new_vals = $(this).val(); 
    	
		$(this).data('old-data', $new_vals);
    });

    var check_all = function(value){
		if(value == 'all'){
			return true;
		}
	}
});
</script>
@stop