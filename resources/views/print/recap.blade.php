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
<html>
<head>
<title>Laporan Kunjungan Pasien {{ $title }}</title>
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{URL::asset('assets/css/print.css')}}">
<script type="text/javascript">
window.print();
</script>
</head>
<body>
<div id="header" class="text-center">
	<h2>{{ get_company_name() }}</h2>
	<h5>{{ get_company_address() }}</h5>
</div>
<!--<div class="form-group">
	<div class="row">
		<div class="col-xs-4"><strong>Laporan Kunjungan Pasien Berdasarkan </strong></div>
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

<h2 style="text-align: center; margin: 0 0 10px;">LAPORAN KUNJUNGAN PASIEN PER {{ strtoupper( $title ) }}</h2>
<h3 style="text-align: center; margin: 0 0 30px;">PERIODE: {{ $date_from_formatted }} - {{ $date_to_formatted }}</h3>
<h5 style="text-align: center; margin: 0 0 10px;">DATE PRINTED : {{ date( 'd-m-Y' ) }}</h5>
<h5 style="text-align: center; margin: 0 0 0px;">PRINTED BY : {{ $staff->nama_karyawan }}</h5>

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


</body>
</html>
@endif