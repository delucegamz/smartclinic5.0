
@extends( 'layouts.app' )

@section( 'page_title' )
Smart Clinic System - Konfigurasi Umum
@stop

@section( 'styles' )
<link rel="stylesheet" href="{{URL::asset('assets/css/bootstrap-chosen.css')}}">
@stop

@section( 'scripts' )
<script type="text/javascript" src="{{URL::asset('assets/js/chosen.jquery.js')}}"></script>
@stop

@section( 'content' )
<div class="content-title"><h1>Konfigurasi Umum</h1></div>

@if( Session::has( 'message' ) )
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="alert alert-success">
                {{ Session::get( 'message' ) }}
            </div>
        </div>
    </div>
</div>
@endif

@php
$polies = App\Poli::all();
@endphp
<div class="entry-content">
	<form id="client-form" class="form-horizontal" action="{{ url( 'company/save_setting' ) }}" method="post">
		<input name="_token" type="hidden" value="{{ csrf_token() }}"/>
		<div class="form-group">
			<label class="control-label col-xs-2">Formula HPL</label>
			<div class="col-xs-8">
				<input type="text" name="hpl_formula_3m_down" id="hpl_formula_3m_down" class="form-control" placeholder="Rumus penghitungan HPL untuk 3 bulan ke bawah" value="{{ get_setting( 'hpl_formula_3m_down' ) }}" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2">&nbsp;</label>
			<div class="col-xs-8">
				<input type="text" name="hpl_formula_4m_up" id="hpl_formula_4m_up" class="form-control" placeholder="Rumus penghitungan HPL untuk 4 bulan ke atas" value="{{ get_setting( 'hpl_formula_4m_up' ) }}" style="margin-bottom: 10px;" />
				<span class="desc">Contoh: +7,+9,+0</span>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2">IGD</label>
			<div class="col-xs-8">
				<select name="igd" id="igd" class="form-control">
					<option value="">- Pilih Poli -</option>
					@foreach( $polies as $poly )
					<option value="{{ $poly->id_poli }}"{{ selected( $poly->id_poli, get_setting( 'igd' ) ) }}>{{ $poly->nama_poli }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2" for="poli_umum">Poli Umum</label>
			<div class="col-xs-8">
				<select name="poli_umum" id="poli_umum" class="form-control">
					<option value="">- Pilih Poli -</option>
					@foreach( $polies as $poly )
					<option value="{{ $poly->id_poli }}"{{ selected( $poly->id_poli, get_setting( 'poli_umum' ) ) }}>{{ $poly->nama_poli }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-xs-2" for="poli_kebidanan">Poli Kebidanan</label>
			<div class="col-xs-8">
				<select name="poli_kebidanan" id="poli_kebidanan" class="form-control">
					<option value="">- Pilih Poli -</option>
					@foreach( $polies as $poly )
					<option value="{{ $poly->id_poli }}"{{ selected( $poly->id_poli, get_setting( 'poli_kebidanan' ) ) }}>{{ $poly->nama_poli }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group last">
			<label class="control-label col-xs-2">&nbsp;</label>
			<div class="col-xs-8">
				<input type="submit" name="submit" value="Simpan" class="btn form-control btn-save" id="btn-save" />  
			</div>
		</div>
		<input type="hidden" name="id" value="" id="id" />
    	<input type="hidden" name="state" value="add" id="state" />
	</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#collapseOne').addClass('in');
});
</script>
@stop