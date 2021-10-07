<html>
<head>
<title>Data Kunjungan</title>
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

@if( $view == 'participant' )
<div class="patient-detail">
    <?php $participant = App\Participant::where( 'id_peserta', '=', $participant )->first(); ?>
	<h4 class="patient-name">Data Kunjungan {{ $participant->nama_peserta }}</h4>
    @if( $date_from && $date_to )
    <h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
    @endif
	<span class="patient-label">NIK</span><span class="colon">:</span><span class="patient-value">{{ $participant->nik_peserta }}</span><br />
	<span class="patient-label">Departemen</span><span class="colon">:</span><span class="patient-value">{{ get_department_name( $participant->id_departemen ) }}</span><br />
	<span class="patient-label">Factory</span><span class="colon">:</span><span class="patient-value">{{ get_participant_factory( $participant->id_peserta ) }}</span><br />
	<span class="patient-label">Client</span><span class="colon">:</span><span class="patient-value">{{ get_participant_client( $participant->id_peserta ) }}</span><br />
</div><br />
@elseif( $view == 'department' ) 
<?php $department = App\Department::where( 'id_departemen', '=', $department )->first(); ?>
<div class="patient-detail">
	<h4 class="patient-name">Data Kunjungan Departemen {{ $department->nama_departemen }}</h4>
	<h5>Kode Departemen : {{ $department->kode_departemen }}</h5>
    @if( $date_from && $date_to )
    <h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
    @endif
</div><br />
@elseif( $view == 'factory' )
<?php $factory = App\Factory::where( 'id_factory', '=', $factory )->first(); ?>
<div class="patient-detail">
	<h4 class="patient-name">Data Kunjungan Factory {{ $factory->nama_factory }}</h4>
	<h5>Kode Factory : {{ $factory->kode_factory }}</h5>
    @if( $date_from && $date_to )
    <h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
    @endif
</div><br />
@elseif( $view == 'client' ) 
<?php $client = App\Client::where( 'id_client', '=', $client )->first(); ?>
<div class="patient-detail">
	<h4 class="patient-name">Data Kunjungan Client {{ $client->nama_client }}</h4>
	<h5>Kode Client : {{ $client->kode_client }}</h5>
    @if( $date_from && $date_to )
    <h5>Periode {{ date( 'd/m/Y', strtotime( $date_from ) ) }} - {{ date( 'd/m/Y', strtotime( $date_to ) ) }}</h5>
    @endif
</div><br />
@endif


<table class="table table-bordered table-striped list-table" id="list-items">
    @if( $view == 'participant' )
	<thead>
		<tr>
			<th class="column-no-title">No.</th>
			<th class="column-waktu-pendaftaran-title">Waktu Pendaftaran</th>
            <th class="column-nama-poli-title">Nama Poli</th>
            <th class="column-status-title">Status Pemeriksaan</th>
		<tr>
	<thead>
    @elseif( $view == 'department' ) 
    <thead>
        <tr>
            <th class="column-no-title">No.</th>
            <th class="column-nama-title">Nama Peserta</th>
            <th class="column-client-title">Client</th>
            <th class="column-factory-title">Factory</th>
            <th class="column-waktu-pendaftaran-title">Waktu Pendaftaran</th>
            <th class="column-nama-poli-title">Nama Poli</th>
            <th class="column-status-title">Status Pemeriksaan</th>
        <tr>
    <thead>
    @elseif( $view == 'factory' ) 
    <thead>
        <tr>
            <th class="column-no-title">No.</th>
            <th class="column-nama-title">Nama Peserta</th>
            <th class="column-client-title">Client</th>
            <th class="column-departemen-title">Departemen</th>
            <th class="column-waktu-pendaftaran-title">Waktu Pendaftaran</th>
            <th class="column-nama-poli-title">Nama Poli</th>
            <th class="column-status-title">Status Pemeriksaan</th>
        <tr>
    <thead>
    @elseif( $view == 'client' ) 
    <thead>
        <tr>
            <th class="column-no-title">No.</th>
            <th class="column-nama-title">Nama Peserta</th>
            <th class="column-factory-title">Factory</th>
            <th class="column-departemen-title">Departemen</th>
            <th class="column-waktu-pendaftaran-title">Waktu Pendaftaran</th>
            <th class="column-nama-poli-title">Nama Poli</th>
            <th class="column-status-title">Status Pemeriksaan</th>
        <tr>
    <thead>
    @endif
	<tbody>
    @if( count( $datas ) )
    	<?php $i = 1; $count = count( $datas ); ?>
		@foreach ( $datas as $poliregistration )
        @if( $view == 'participant' )
		<tr class="item" id="item-{{ $poliregistration->id_pendaftaran }}">
			<td class="column-no">{{ $i }}</td>
			<td class="column-waktu-pendaftaran">{{ date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
			<td class="column-nama-poli">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
            <td class="column-status">
            <?php
                $medrec = App\MedicalRecord::where( 'id_pendaftaran_poli', '=', $poliregistration->id_pendaftaran )->first();

                if( $poliregistration->status == 1 ){
                    echo 'Belum Diperiksa';
                }else{
                    if( $medrec->status == 1 ){
                        echo 'Sudah Diperiksa';
                    }else{
                        echo 'Tidak Diperiksa';
                    }
                }
            ?>
            </td>
		<tr>
        @elseif( $view == 'department' )
        <?php $participant = App\Participant::find( $poliregistration->id_peserta ); ?>
        <tr class="item" id="item-{{ $poliregistration->id_pendaftaran }}">
            <td class="column-no">{{ $i }}</td>
            <td class="column-nama">{{ $participant->nama_peserta }}</td>
            <td class="column-client">{{ get_participant_client( $poliregistration->id_peserta, $participant ) }}</td>
            <td class="column-factory">{{ get_participant_factory( $poliregistration->id_peserta, $participant ) }}</td>
            <td class="column-waktu-pendaftaran">{{ date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
            <td class="column-nama-poli">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
            <td class="column-status">
            <?php
                $medrec = App\MedicalRecord::where( 'id_pendaftaran_poli', '=', $poliregistration->id_pendaftaran )->first();

                if( $poliregistration->status == 1 ){
                    echo 'Belum Diperiksa';
                }else{
                    if( $medrec->status == 1 ){
                        echo 'Sudah Diperiksa';
                    }else{
                        echo 'Tidak Diperiksa';
                    }
                }
            ?>
            </td>
        <tr>
        @elseif( $view == 'factory' )
        <?php $participant = App\Participant::find( $poliregistration->id_peserta ); ?>
        <tr class="item" id="item-{{ $poliregistration->id_pendaftaran }}">
            <td class="column-no">{{ $i }}</td>
            <td class="column-nama">{{ $participant->nama_peserta }}</td>
            <td class="column-client">{{ get_participant_client( $poliregistration->id_peserta, $participant ) }}</td>
            <td class="column-departemen">{{ get_participant_department_alt( $poliregistration->id_peserta, $participant ) }}</td>
            <td class="column-waktu-pendaftaran">{{ date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
            <td class="column-nama-poli">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
            <td class="column-status">
            <?php
                $medrec = App\MedicalRecord::where( 'id_pendaftaran_poli', '=', $poliregistration->id_pendaftaran )->first();

                if( $poliregistration->status == 1 ){
                    echo 'Belum Diperiksa';
                }else{
                    if( $medrec->status == 1 ){
                        echo 'Sudah Diperiksa';
                    }else{
                        echo 'Tidak Diperiksa';
                    }
                }
            ?>
            </td>
        <tr>
        @elseif( $view == 'client' )
        <?php $participant = App\Participant::find( $poliregistration->id_peserta ); ?>
        <tr class="item" id="item-{{ $poliregistration->id_pendaftaran }}">
            <td class="column-no">{{ $i }}</td>
            <td class="column-nama">{{ $participant->nama_peserta }}</td>
            <td class="column-factory">{{ get_participant_factory( $poliregistration->id_peserta, $participant ) }}</td>
            <td class="column-departemen">{{ get_participant_department_alt( $poliregistration->id_peserta, $participant ) }}</td>
            <td class="column-waktu-pendaftaran">{{ date( 'd-m-Y H:i:s', strtotime( $poliregistration->tgl_daftar ) ) }}</td>
            <td class="column-nama-poli">{{ get_poli_name( $poliregistration->id_poli ) }}</td>
            <td class="column-status">
            <?php
                $medrec = App\MedicalRecord::where( 'id_pendaftaran_poli', '=', $poliregistration->id_pendaftaran )->first();

                if( $poliregistration->status == 1 ){
                    echo 'Belum Diperiksa';
                }else{
                    if( $medrec->status == 1 ){
                        echo 'Sudah Diperiksa';
                    }else{
                        echo 'Tidak Diperiksa';
                    }
                }
            ?>
            </td>
        <tr>
        @endif
		<?php  $i++; ?>
		@endforeach
	@else
		@if( $view == 'participant' )
		<tr class="no-data">
			<td colspan="4">Tidak ada data ditemukan.</td>
		</tr>
		@else
		<tr class="no-data">
			<td colspan="7">Tidak ada data ditemukan.</td>
		</tr>
		@endif
	@endif

    @if( $view == 'participant' )
    <tr class="no-data">
        <td colspan="3" align="center" class="text-center"><strong>Total</strong></td>
        <td colspan="1" align="center" class="text-center"><strong>{{ $count }}</strong></td>
    </tr>
    @else
    <tr class="no-data">
        <td colspan="6" align="center" class="text-center"><strong>Total</strong></td>
        <td colspan="1" align="center" class="text-center"><strong>{{ $count }}</strong></td>
    </tr>
    @endif
	</tbody>
</table>
</script>
</body>
</html>