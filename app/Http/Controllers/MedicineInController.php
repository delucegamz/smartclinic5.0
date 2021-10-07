<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\MedicineInDetail;
use App\MedicineIn;
use App\Medicine;
use DB;
use Response;
use Auth;
use URL;


class MedicineInController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware( 'auth' );
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( !current_user_can( 'data_obat_masuk' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $s = ( isset( $_GET['s'] ) && $_GET['s'] != '' ) ? filter_var( $_GET['s'], FILTER_SANITIZE_STRING ) : '';

        if( $rows == 'all' ){
    	    $datas = MedicineIn::orderBy( 'id_pembelian_obat', 'desc' )->get();
        }else{
            $datas = MedicineIn::orderBy( 'id_pembelian_obat', 'desc' )->paginate( $rows );
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'medicinein', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $medicinein = MedicineIn::find( $id );
        $medicinein = $medicinein ? $medicinein : new MedicineIn();

        $medicineindetail = MedicineInDetail::where( 'id_obat_masuk', '=', $medicinein->id_pembelian_obat )->get();
        
        $html = '';
        if( $medicineindetail ){
            $i = 1; 
            foreach( $medicineindetail as $mid ){
                $medicine = Medicine::find( $mid->id_obat );

                $html .= '<tr class="item" id="item-' . $mid->id_obat . '">
                            <td class="column-code">' . $medicine->kode_obat . '</td>
                            <td class="column-name">' . $medicine->nama_obat . '</td>
                            <td class="column-amount">' . $mid->jumlah_obat . '</td>
                            <td class="column-price">' . $medicine->satuan . '</td>
                            <td class="column-total">' . ( $mid->jumlah_obat * $medicine->satuan ) . '</td>
                            <td class="column-action">
                                <a href="#" title="Delete" class="delete" data-id="' . $mid->id_obat . '"><img src="'. URL::asset( 'assets/images/icon-delete.png' ) . '" alt="Delete" /></a>
                                <input type="hidden" name="medicine_id[]" value="' . $mid->id_obat . '" class="medicine_id" />
                                <input type="hidden" name="medicine_state[]" value="nothing" class="medicine_state" />
                                <input type="hidden" name="medicine_amount[]" value="' . $mid->jumlah_obat . '" class="medicine_amount" />
                            </td>
                        <tr>';

                $i++;
            }
        }

        $response = array(
            'html' => $html,
            'id_pembelian_obat' => $medicinein->id_pembelian_obat,
            'no_pembelian_obat' => $medicinein->no_pembelian_obat,
            'idsupplier' => $medicinein->idsupplier,
            'tanggal_obat_masuk' => $medicinein->tanggal_obat_masuk,
            'catatan_pembelian_obat' => $medicinein->catatan_pembelian_obat,
            'status_pembelian_obat' => $medicinein->status_pembelian_obat,
            'total_harga' => $medicinein->total_harga,
            'jumlah_pembelian' => $medicinein->jumlah_pembelian,
            'tanggal_proses' => $medicinein->tanggal_proses,
            'success' => 'true'
        );

        return Response::json( $response );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $input = $request->all();

        $user = Auth::user();

        $id_pengguna = $user['original']['idpengguna'];

        try {
            if( isset( $input['id'] ) && !empty( $input['id'] ) ){
                $medicinein = MedicineIn::find( $input['id'] );

                if( !$medicinein ){
                    $medicinein = new MedicineIn();
                    $state = 'add';
                }
            }else{
                $medicinein = new MedicineIn();
                $state = 'add';
            }

            $medicinein->idsupplier = $input['supplier'];
            $medicinein->tanggal_obat_masuk = $input['tanggal_obat_masuk'];
            $medicinein->catatan_pembelian_obat = '';
            $medicinein->total_harga = $input['total_harga'];
            $medicinein->jumlah_pembelian = $input['jumlah_pembelian'];
            $medicinein->user_update = $id_pengguna;

            if( $input['state'] == 'add' ){
                $medicinein->no_pembelian_obat = MedicineIn::generate_id();
                $medicinein->id_pengguna = $id_pengguna;
                $medicinein->status_pembelian_obat = 1;
            }

            $medicinein->save();
            
            if( isset( $input['medicine_id'] ) && is_array( $input['medicine_id'] ) ){
                foreach( $input['medicine_id'] as $key => $value ){
                    if( $input['medicine_state'][$key] == 'add' ){
                        $medicineindetail = new MedicineInDetail();
                        $medicineindetail->no_detail_obat_masuk = MedicineInDetail::generate_id( $medicinein->no_pembelian_obat );
                        $medicineindetail->id_obat_masuk = $medicinein->id_pembelian_obat;
                        $medicineindetail->id_obat = $value;
                        $medicineindetail->jumlah_obat = $input['medicine_amount'][$key];
                        $medicineindetail->id_pengguna = $id_pengguna;

                        $medicine = Medicine::find( $value );
                        $stock_obat = $medicine->stock_obat;
                        $stock_obat += $input['medicine_amount'][$key];
                        $medicine->stock_obat = $stock_obat;
                        $medicine->save();

                        $medicineindetail->save();
                    }elseif( $input['medicine_state'][$key] == 'delete' ){
                        $medicineindetail = MedicineInDetail::where( 'id_obat', '=', $value )->where( 'id_obat_masuk', '=', $medicinein->id_pembelian_obat )->first();

                        if( $medicineindetail ){
                            $medicine = Medicine::find( $value );
                            $stock_obat = $medicine->stock_obat;
                            $stock_obat -= $medicineindetail->jumlah_obat;
                            $medicine->stock_obat = $stock_obat;
                            $medicine->save();

                            $medicineindetail->delete();
                        }
                    }
                }

                $medicinein->tanggal_proses = date( 'Y-m-d' );
                $medicinein->status_pembelian_obat = 0;
                $medicinein->save();
            }

            return redirect()->route( 'medicine-in.index' ); 
        } catch (\Exception $e) {
            return redirect()->route( 'medicine-in.index' ); 
        }
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id )
    {
        $response = array(
            'success' => 'false',
            'message' => 'Terjadi kesalahan ketika menghapus list pembelian obat.'
        );
        
        return Response::json( $response );
    }

    public function latest_id(){
        $latest_id = MedicineIn::generate_id();

        $response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }
}
