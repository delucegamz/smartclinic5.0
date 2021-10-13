<div>
	
</div>


<div id="pencariandate" align="center">
	{!! Form::open(['url' => 'reports/medrec2/caridate', 'method' => 'GET']) !!}	

	<div class="container" style="margin-top: 40px; margin-left: 80px">
		<div class="row">
			<div class="container-fluid">				
				<div class="form-group row">
					<label for="date" class="col-form-label col-md-1">From</label>
					<div class="col-sm-3">
						<input type="date" class="form-control input-sm" id="fromdate" name="date" required="">
					</div>
				</div>
				<div class="form-group row">
					<label for="date" class="col-form-label col-md-1">To</label>
					<div class="col-sm-3">
						<input type="date" class="form-control input-sm" id="todate" name="todate" required="">		
					</div>
					<div class="col-sm-1">
						{!! Form::button('cari', ['class' => 'btn btn-default', 
						'style' => 'width: 150px','type' => 'submit']) !!}		
					</div>					
				</div>
			</div>	
		</div>
	</div>

	{!! Form::close() !!}
</div>