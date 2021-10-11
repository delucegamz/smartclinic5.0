<div id="pencarian">
	{!! Form::open(['url' => 'reports/medrec2/cari', 'method' => 'GET']) !!}
	<div class="input-group" style="width:600px">
			{!! Form::text('kata_kunci', (!empty($kata_kunci)) ? 
			$kata_kunci : null, ['class' => 'form-control', 'placeholder' => 'masukan nik peserta']) !!}
	<span class="input-group-btn">
			{!! Form::button('cari', ['class' => 'btn btn-default', 
			'style' => 'width: 150px',
			
			'type' => 'submit']) !!}
			</span>							
	</div>


	{!! Form::close() !!}
</div>