@php
$id = ( isset( $_GET['id'] ) && $_GET['id'] != '' ) ? absint( $_GET['id'] ) : '';

if( !$id ) die( 'Maaf tidak dapat menemukan apa yang anda cari.' );

$rf = App\SickLetter::find( $id );

if( !$rf ) die( 'Maaf tidak dapat menemukan apa yang anda cari.' );
@endphp
<html>
<head>
<title>Surat Sakit</title>
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
<div class="form-horizontal">

	<h2>SURAT KETERANGAN SAKIT</h2>

	<div class="form-group">
		<label class="control-label col-xs-4" for="code">No.</label>
		<div class="col-xs-8">
		{{ $rf->no_surat_sakit }}	
		</div>
	</div>

	<h3 style="text-align:left">Data Keterangan Sakit</h3>

	<h4 style="text-align:left">Yang berada di bawah ini menerangkan bahwa:</h4>

	<div class="form-group">
		<label class="control-label col-xs-4" for="nama">Nama</label>
		<div class="col-xs-8">
		{{ get_participant_name( $rf->id_peserta ) }}				
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-4" for="sex">Jenis Kelamin</label>
		<div class="col-xs-8">
		{{ get_participant_sex( $rf->id_peserta ) }}			
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-4" for="age">Umur</label>
		<div class="col-xs-8">
		{{ get_participant_age( $rf->id_peserta ) }}			
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-4" for="address">Alamat</label>
		<div class="col-xs-8">
		{{ get_participant_address( $rf->id_peserta ) }}			
		</div>
	</div>

	<h4 style="text-align:left">Perlu beristirahat karena sakit selama</h4>

	<div class="form-group">
		<label class="control-label col-xs-4" for="anamnesa">Jumlah Hari</label>
		<div class="col-xs-8">
		{{ $rf->lama }}			
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-4" for="pemeriksaan_fisik">Dari Tanggal</label>
		<div class="col-xs-8">
		{{ date( 'd-m-Y', strtotime( $rf->dari_tanggal ) ) }}				
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-4" for="pemeriksaan_fisik">Sampai Tanggal</label>
		<div class="col-xs-8">
		{{ date( 'd-m-Y', strtotime( $rf->sampai_tanggal ) ) }}				
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-8">&nbsp;</label>
		<div class="col-xs-4" style="text-align:center;">
		Dokter jaga		
		</div>
	</div>   

	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>

	 <div class="form-group">
		<label class="control-label col-xs-8">&nbsp;</label>
		<div class="col-xs-4" style="text-align:center;">
		{{ $rf->dokter_jaga }}			
		</div>
	</div>   	                	
</div>

</body>
</html>