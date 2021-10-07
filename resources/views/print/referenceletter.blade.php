@php
$id = ( isset( $_GET['id'] ) && $_GET['id'] != '' ) ? absint( $_GET['id'] ) : '';

if( !$id ) die( 'Maaf tidak dapat menemukan apa yang anda cari.' );

$rf = App\ReferenceLetter::find( $id );

if( !$rf ) die( 'Maaf tidak dapat menemukan apa yang anda cari.' );
@endphp
<html>
<head>
<title>Surat Rujukan</title>
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

	<h2>SURAT RUJUKAN</h2>

	<div class="form-group">
		<label class="control-label col-xs-4" for="code">No.</label>
		<div class="col-xs-8">
		{{ $rf->no_surat_rujukan }}	
		</div>
	</div>

	<h3 style="text-align:left">Data Rujukan</h3>

	<div class="form-group">
		<label class="control-label col-xs-4" for="dokter_ahli">Kepada</label>
		<div class="col-xs-8">
		{{ $rf->dokter_ahli }}			
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-4" for="provider">Di</label>
		<div class="col-xs-8">
		{{ $rf->provider }}				
		</div>
	</div>

	<h4 style="text-align:left">Mohon pemeriksaan / pengobatan lebih lanjut</h4>

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

	<div class="form-group">
		<label class="control-label col-xs-4" for="anamnesa">Anamnesa</label>
		<div class="col-xs-8">
		{{ $rf->anamnesa }}			
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-4" for="pemeriksaan_fisik">Pemeriksaan Fisik</label>
		<div class="col-xs-8">
		{{ $rf->pemeriksaan_fisik }}				
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-4" for="diagnosa_dokter">Diagnosa Dokter</label>
		<div class="col-xs-8">
		{{ $rf->diagnosa_dokter }}				
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-4" for="obat_beri">Obat yang Diberikan</label>
		<div class="col-xs-8">
		{{ $rf->obat_beri }}				
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-4" for="catatan">Instruksi Khusus</label>
		<div class="col-xs-8">
		{{ $rf->catatan }}			
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-xs-8">&nbsp;</label>
		<div class="col-xs-4" style="text-align:center;">
		Pemberi Rujukan			
		</div>
	</div>   

	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>

	 <div class="form-group">
		<label class="control-label col-xs-8">&nbsp;</label>
		<div class="col-xs-4" style="text-align:center;">
		{{ $rf->dokter_rujuk }}			
		</div>
	</div>   	                	
</div>

</body>
</html>