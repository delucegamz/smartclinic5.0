<html>
<head>
<title>DAFTAR KUNJUNGAN PASIEN KECELAKAAN KERJA</title>
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
<?php
	$date_from =  ( !empty( $date_from ) ) ? $date_from : '';
	$date_to = ( !empty( $date_to ) ) ? $date_to : '';

	$date_from_formatted = ( !empty( $date_from ) ) ? date( 'd M Y', strtotime( $date_from ) ) : '';
	$date_to_formatted = ( !empty( $date_to ) ) ? date( 'd M Y', strtotime( $date_to ) ) : date( 'd M Y' );

	$current_user = Auth::user();
    $idpengguna = $current_user['original']['idpengguna'];
    $user = App\User::find( $idpengguna );
	$staff = App\Staff::where( 'id_karyawan', '=', $user->id_karyawan )->first();
?>
<h2 style="text-align: center; margin: 30px 0 5px;">DAFTAR KUNJUNGAN PASIEN KECELAKAAN KERJA</h2>
<h3 style="text-align: center; margin: 0 0 30px;">PERIODE: {{ $date_from_formatted }} - {{ $date_to_formatted }}</h3>
<h5 style="text-align: center; margin: 0 0 10px;">DATE PRINTED : {{ date( 'd-m-Y' ) }}</h5>
<h5 style="text-align: center; margin: 0 0 30px;">PRINTED BY : {{ $staff->nama_karyawan }}</h5>
<table class="table table-bordered table-striped">
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
            <th style="">Tanggal Kejadian</th>
            <th style="">Tindakan</th>
        </tr>
    </thead>
	<tbody>
        @php 
            $i = 1; 
        @endphp
        @foreach( $datas as $data )
        @php
            $participant = App\Participant::find( $data->id_peserta );
            if( $data->uraian == 22 )
                $accident = App\Accident::where( 'id_pemeriksaan_poli', $data->id_pemeriksaan_poli )->first();
            elseif( $data->uraian == 44 )
                $accident = null;

            $sickletter = App\SickLetter::where( 'id_pemeriksaan_poli', '=', $data->id_pemeriksaan_poli )->first();
            $referenceletter = App\ReferenceLetter::where( 'id_pemeriksaan_poli', '=', $data->id_pemeriksaan_poli )->first();
        @endphp
        <tr>
            <td>{{ $i }}</td>
            <td>{{ ( $data->uraian == 22  ? 'KK' : 'Kontrol' ) }}</td>
            <td>{{ $participant->nama_peserta }}</td>
            <td>{{ $participant->nik_peserta }}</td>
            <td>{{ date( 'd-m-Y', strtotime( $participant->tanggal_lahir ) ) }}</td>
            <td>{{ ucwords( $participant->jenis_kelamin ) }}</td>
            <td>{{ $data->nama_factory }}</td>
            <td>{{ $data->nama_departemen }}</td>
            <td>{{ ( $accident ) ? $accident->tanggal_kejadian : '' }}</td>
            <td>
            @php
                if( $sickletter ){
                    echo 'SKS';
                }else{
                    if( $referenceletter ){
                        echo 'RUJUKAN';
                    }else{
                        echo '-';
                    }
                }
            @endphp
            </td>
        </tr>
        @php 
            $i++; 
        @endphp
        @endforeach
    </tbody>
</table>
</body>
</html>