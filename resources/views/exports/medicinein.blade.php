<html>
<table border="1">
	<tr>
		<td rowspan="2">No.</td>
		<td rowspan="2">No Faktur</td>
		<td rowspan="2">Nama Supplier</td>
		<td rowspan="2">Tanggal Faktur</td>
		<td rowspan="2">Tanggal Proses</td>
		<td rowspan="2">Jumlah Pembelian</td>
		<td rowspan="2">Total Harga</td>
		<td colspan="4">Detail</td>
	</tr>
	<tr>
		<td>Kode Obat</td>
		<td>Nama Obat</td>
		<td>Jumlah</td>
		<td>Harga Satuan</td>
	</tr>
	<?php
		$i = 1;
		if( count( $datas ) ){ 
			foreach ( $datas as $data ) {	
				$details = App\MedicineInDetail::where( 'id_obat_masuk', '=', $data->id_pembelian_obat )->get();

				$items = array();
				foreach( $details as $item ){
                    $medicine = App\Medicine::find( $item->id_obat );

                    $items[] = array(
                    	'kode_obat' => $medicine->kode_obat,
                    	'nama_obat' => $medicine->nama_obat,
                    	'jumlah_obat' => $item->jumlah_obat,
                    	'satuan' => $medicine->satuan
                    );
                }

                $count = count( $items );
	
				$j = 1;
				foreach( $items as $item ){
					if( $j == 1 ){
	?>
	<tr>
		<td rowspan="{{ $count }}">{{ $i }}</td>
		<td rowspan="{{ $count }}">{{ $data->no_pembelian_obat }}</td>
		<td rowspan="{{ $count }}">{{ $data->idsupplier ? $data->idsupplier : '-' }}</td>
		<td rowspan="{{ $count }}">{{ $data->tanggal_obat_masuk ? $data->tanggal_obat_masuk : '-' }}</td>
		<td rowspan="{{ $count }}">{{ $data->tanggal_proses ? $data->tanggal_proses : '-' }}</td>
		<td rowspan="{{ $count }}">{{ $data->jumlah_pembelian ? $data->jumlah_pembelian : 0 }}</td>
		<td rowspan="{{ $count }}">Rp. {{ $data->total_harga ? number_format( $data->total_harga, 0, ',', '.' ) : '-' }}</td>
		<td>{{ $item['kode_obat'] }}</td>
		<td>{{ $item['nama_obat'] }}</td>
		<td>{{ $item['jumlah_obat'] }}</td>
		<td>{{ $item['satuan'] }}</td>
	</tr>
	<?php
					}else{
				?>
	<tr>
		<td>{{ $item['kode_obat'] }}</td>
		<td>{{ $item['nama_obat'] }}</td>
		<td>{{ $item['jumlah_obat'] }}</td>
		<td>{{ $item['satuan'] }}</td>
	</tr>			
				<?php
					}

					$j++;
				}

				$i++;
			}
		}else{
	?>
	<tr>
		<td colspan="11">Tidak ada data ditemukan.</td>
	</tr>
	<?php		
		}
	?>
</table>
</html>