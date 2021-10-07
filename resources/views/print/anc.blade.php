<html>
<head>
<title>Data Pemeriksaan ANC</title>
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

@if( $participant )
<?php $participant = App\Participant::where( 'id_peserta', '=', $participant )->first(); ?>
<div class="patient-detail">
	<h4 class="patient-name">Data Pemeriksaan ANC {{ $participant->nama_peserta }}</h4>
    @if( $date_from && $date_to )
    <h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
    @endif
	<span class="patient-label">NIK</span><span class="colon">:</span><span class="patient-value">{{ $participant->nik_peserta }}</span><br />
	<span class="patient-label">Departemen</span><span class="colon">:</span><span class="patient-value">{{ get_department_name( $participant->id_departemen ) }}</span><br />
	<span class="patient-label">Factory</span><span class="colon">:</span><span class="patient-value">{{ get_participant_factory( $participant->id_peserta ) }}</span><br />
	<span class="patient-label">Client</span><span class="colon">:</span><span class="patient-value">{{ get_participant_client( $participant->id_peserta ) }}</span><br />
</div><br />
@else
<h4>Data Rekam Medis</h4>
@if( $date_from && $date_to )
<h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
@endif
@endif

<table class="table table-bordered table-striped list-table">
	<thead>
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">Tgl</th>
			<th rowspan="2">Nama</th>
			<th rowspan="2">Umur</th>
            <th rowspan="2">NIK</th>
            <th rowspan="2">Dept</th>
			<th rowspan="2">HPHT</th>
			<th rowspan="2">HTP</th>
			<th rowspan="2">Alamat</th>
			<th colspan="3">Hasil Pemeriksaan</th>
            <th rowspan="2">Diagnosa</th>
            <th rowspan="2">TM</th>
            <th rowspan="2">Therapy</th>
            <th rowspan="2">JML</th>
            <th rowspan="2">Ket</th>
            <th rowspan="2">Bidan</th>
		</tr>
        <tr>
            <th>BB(Kg)</th>
            <th>TD</th>
            <th>TFU</th>
        </tr>
	<thead>
	<tbody>
	@if( count( $datas ) )
		@php $i = 1; @endphp
		@foreach( $datas as $o )
        @php 
            $pregnant = App\PregnantParticipant::find( $o->id_peserta_hamil ); 
            $medrec = App\MedicalRecord::find( $o->id_pemeriksaan_poli );
            $diagnosa = '';
            $diagnosa .= 'G' . ( $pregnant->gravida ? $pregnant->gravida : 0 );
            $diagnosa .= 'P' . ( $pregnant->partus ? $pregnant->partus : 0 );
            $diagnosa .= 'A' . ( $pregnant->abortus ? $pregnant->abortus : 0 );

            /*$tptime = strtotime( $pregnant->tp . ' 00:00:00'  ); $weeks = '';
            if( $tptime > time() ){
                $weeks = datediffInWeeks( $pregnant->tanggal_hpht, date( 'Y-m-d' ) );
            }else{
                $weeks = datediffInWeeks( $pregnant->tanggal_hpht, $pregnant->tp );
            }*/

            $weeks = $o->keterangan_kehamilan;

            if( $weeks != '' ){
                $diagnosa .= ' ' . $weeks . ' MGG';
            }

            $tm = $o->tm ? $o->tm : '-';
            /*if( $weeks <= 12 ){
                $tm = 'I';
            }elseif( $weeks <= 13 && $weeks <= 24 ){
                $tm = 'II';
            }elseif( $weeks >= 25 ){
                $tm = 'III';
            }*/

            $ket = '';
            if( is_dayoff_letter( $o->id_pemeriksaan_poli ) ) $ket .= 'Cuti;';
            if( !empty( $o->pemeriksaan_hb ) ) $ket .= 'Hb' . $o->pemeriksaan_hb . 'gr;';
            if( !empty( $o->pemeriksaan_urin ) ) $ket .= 'Urin' . $o->pemeriksaan_urin . '%;';

            $tfu = '';
            if( $o->tfu ) $tfu .= $o->tfu . ',';
            if( $o->presentasi ) $tfu .= $o->presentasi . ',';
            if( $o->djj_plus ) $tfu .= 'DJJ+';

            $therapy = ''; $jml = '';
            if( is_doctor_recipe( $o->id_pemeriksaan_poli ) ){
                $dr = App\DoctorRecipe::where( 'id_pemeriksaan_poli', $o->id_pemeriksaan_poli )->first();
                if( $dr ){
                    $mo = App\MedicineOut::where( 'id_resep', $dr->id_resep )->first();
                    if( $mo ){
                        $mods = App\MedicineOutDetail::where( 'id_pengeluaran_obat', $mo->id_pengeluaran_obat )->get();

                        if( $mods && count( $mods ) ){
                            $modi = 1;
                            foreach( $mods as $mod ){
                                if( $modi != count( $mods ) ){ 
                                    $therapy .= get_medicine_name( $mod->id_obat ) . ',';
                                    $jml .= $mod->jumlah_obat . ',';
                                }else{
                                    $therapy .= get_medicine_name( $mod->id_obat );
                                    $jml .= $mod->jumlah_obat;
                                } 

                                $modi++;
                            }
                        }      
                    }
                }
            }else{
                $therapy = 'Lanjut';
                $jml = '-';
            }

        @endphp
		<tr class="item" id="item-{{ $o->id_pemeriksaan_anc }}">
			<td>{{ $i }}</td>
            <td>{{ date( 'd/m/Y', strtotime( $o->created_at ) ) }}</td>
            <td>{{ get_participant_name( $o->id_peserta ) }}</td>
            <td>{{ get_participant_age( $o->id_peserta ) }}</td>
            <td>{{ get_participant_nik( $o->id_peserta ) }}</td>
            <td>{{ get_participant_department( $o->id_peserta ) }}</td>
            <td>{{ date( 'd/m/Y', strtotime( $pregnant->tanggal_hpht ) ) }}</td>
            <td>{{ date( 'd/m/Y', strtotime( $pregnant->tp ) ) }}</td>
            <td>{{ get_participant_address( $o->id_peserta ) }}</td>
            <td>{{ $o->berat_badan }}</td>
            <td>{{ $o->td_bawah }} / {{ $o->td_atas }}</td>
            <td>{{ $tfu }}</td>
            <td>{{ $diagnosa }}</td>
            <td>{{ $tm }}</td>
            <td>{{ $therapy }}</td>
            <td>{{ $jml }}</td>
            <td>{{ $ket }}</td>
            <td>Bd. {{ $medrec->dokter_rawat }}</td>
		<tr>
			@php $i++ @endphp
		@endforeach
	@else
		<tr class="no-data">
			<td colspan="18">Tidak ada data ditemukan.</td>
		</tr>
	@endif
	</tbody>
</table>
</body>
</html>