
@extends('layouts.app')

@section('page_title')
Smart Clinic System - Laporan Stock Obat
@stop

@section('content')
<div class="content-title"><h1>Laporan Stock Obat</h1></div>

<div class="content-top-action clearfix" style="width:100%">
	<div class="row-select-wrapper">
		<span>Row</span>
		<select id="rows" name="rows">
			<option value="10"{{ selected( 10, $rows, true ) }}>10</option>
			<option value="20"{{ selected( 20, $rows, true ) }}>20</option>
			<option value="50"{{ selected( 50, $rows, true ) }}>50</option>
			<option value="all"{{ selected( 'all', $rows, true ) }}>All</option>
		</select>
	</div>
	<div class="row-select-wrapper">
		<span>Golongan Obat</span>
		<select id="medicinegroup" name="medicinegroup">
			<option value="">- Pilih Golongan Obat -</option>
			@foreach( $medicinegroups as $g )
			<option value="{{ $g->id_golongan_obat }}"{{ selected( $g->id_golongan_obat, $medicinegroup, true ) }}>{{ $g->nama_golongan_obat }}</option>
			@endforeach
		</select>
		
	</div>
</div>

<div class="entry-content">
	<div class="table-wrapper" style="width:100%">
		<table class="table table-bordered table-striped list-table" id="list-items">
			<thead>
				<tr>
					<th class="column-no-title">No.</th>
					<th class="column-code-title">Kode Obat</th>
					<th class="column-name-title">Nama Obat</th>
					<th class="column-medicine-group-title">Golongan Obat</th>
					<th class="column-stock-title">Stock Obat</th>
				<tr>
			<thead>
			<tbody>
				<?php
					if( count( $datas ) ){ 
						
						foreach ( $datas as $medicine ) {
				?>
				<tr class="item" id="item-{{ $medicine->id_obat }}">
					<td class="column-no">{{ $i }}</td>
					<td class="column-code">{{ $medicine->kode_obat }}</td>
					<td class="column-name">{{ $medicine->nama_obat }}</td>
					<td class="column-medicine-group">{{ get_medicine_group_name( $medicine->id_golongan_obat ) }}</td>
					<td class="column-stock">{{ $medicine->stock_obat }}</td>
				<tr>
				<?php
							$i++;
						}
					}else{
				?>
				<tr class="no-data">
					<td colspan="5">Tidak ada data ditemukan.</td>
				</tr>
				<?php		
					}
				?>
			</tbody>
		</table>
	</div>

	@if ( $rows != 'all' )
	<div class="navigation clearfix">
	@if ( $datas->lastPage() > 1 )
        <ul class="pagination left clearfix">
        	@if ( $datas->currentPage() != 1 )
			<li class="pagination-item pagination-prev{{ ( $datas->currentPage() == 1 ) ? ' disabled' : '' }}">
		     	<a href="{{ $datas->url( $datas->currentPage() - 1 ) }}&rows={{ $rows }}&medicinegroup={{ $medicinegroup }}" aria-label="Previous">
		        	<span aria-hidden="true"><i class="fa fa-chevron-left"></i> Prev Page</span>
		      	</a>
		    </li>
		    @endif
		</ul>
		
		<ul class="pagination center clearfix">
        @for ($i = 1; $i <= $datas->lastPage(); $i++)
            <?php
            $half_total_links = floor( 7 / 2 );
            $from = $datas->currentPage() - $half_total_links;
            $to = $datas->currentPage() + $half_total_links;
            if ( $datas->currentPage() < $half_total_links ) {
               $to += $half_total_links - $datas->currentPage();
            }
            if ( $datas->lastPage() - $datas->currentPage() < $half_total_links ) {
                $from -= $half_total_links - ( $datas->lastPage() - $datas->currentPage() ) - 1;
            }
            ?>
            @if ( $from < $i && $i < $to )
                <li class="pagination-item{{ ( $datas->currentPage() == $i ) ? ' active' : '' }}">
                    <a href="{{ $datas->url( $i ) }}&rows={{ $rows }}&medicinegroup={{ $medicinegroup }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        </ul>
        <ul class="pagination right clearfix">
        	 @if ( $datas->currentPage() != $datas->lastPage() )
			<li class="pagination-item pagination-next{{ ( $datas->currentPage() == $datas->lastPage() ) ? ' disabled' : '' }}">
		      	<a href="{{ $datas->url( $datas->currentPage() + 1 ) }}&rows={{ $rows }}&medicinegroup={{ $medicinegroup }}" aria-label="Next">
		        	<span aria-hidden="true">Next Page <i class="fa fa-chevron-right"></i></span>
		      	</a>
		    </li>
		    @endif
		</ul>
	@endif
	</div>
	@endif

	<div class="download-item" style="width:100%">
		<a href="{{ url( '/print/medicinestock' ) }}?medicinegroup={{ $medicinegroup }}" class="btn">Print</a>
		<a href="{{ url( '/export/medicinestock' ) }}?medicinegroup={{ $medicinegroup }}" class="btn">Download</a>
	</div>
</div><!-- /.entry-content  -->

<script type="text/javascript">
$(document).ready(function(){
	$('#collapseSix').addClass('in');
	
	$('#rows').change(function(){
		$rows = $(this).find('option:selected').val();
		$medicinegroup = $('#medicinegroup').find('option:selected').val();
		$action = '{{ url( 'report/medicinestock' ) }}';

		$url = $action + '?rows=' + $rows + '&medicinegroup=' + $medicinegroup;

		window.location.href= $url;
	});

	$('#medicinegroup').change(function(){
		$medicinegroup = $(this).find('option:selected').val();
		$rows = $('#rows').find('option:selected').val();
		$action = '{{ url( 'report/medicinestock' ) }}';

		$url = $action + '?rows=' + $rows + '&medicinegroup=' + $medicinegroup;

		window.location.href= $url;
	});
});
</script>
@stop