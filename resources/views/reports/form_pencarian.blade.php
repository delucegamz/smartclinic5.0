
<div id="pencarian" style="margin-top: 40px; " >
	{!! Form::open(['url' => 'reports/medrec2/cari', 'method' => 'GET']) !!}
	 <div class="input-group" style="width:300px">
		{!! Form::text('kata_kunci', (!empty($kata_kunci)) ? 
		$kata_kunci : null, ['class' => 'form-control col-sm-3', 'placeholder' => 'masukan nik peserta', 'required' => '']) !!}
		<span class="input-group-btn">
		{!! Form::button('cari', ['class' => 'btn btn-default', 
		'style' => 'width: 150px','type' => 'submit']) !!}
		</span>
	</div>
	{!! Form::close() !!}
</div>