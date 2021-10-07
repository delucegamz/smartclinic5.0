<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\MedicineOutDetail;
use App\MedicineOut;
use App\Medicine;
use App\DoctorRecipe;
use App\MedicalRecord;
use DB;
use Response;
use Auth;
use URL;


class MedicineOutController extends Controller
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
        if( !current_user_can( 'data_obat_keluar' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
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
    	    $datas = MedicineOut::orderBy( 'id_pengeluaran_obat', 'desc' )->get();
        }else{
            $datas = MedicineOut::orderBy( 'id_pengeluaran_obat', 'desc' )->paginate( $rows );
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'medicineout', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {   
        $search_by_recipe = false;
        if( isset( $_GET['search_by_recipe'] ) && $_GET['search_by_recipe'] == 1 ){
            $medicineout = MedicineOut::where( 'id_resep', '=', $id )->first();
            $search_by_recipe = true;
        }else{
            $medicineout = MedicineOut::find( $id );
        }

        $medicineout =  $medicineout ? $medicineout : new MedicineOut();

        $medicineoutdetail = MedicineOutDetail::where( 'id_pengeluaran_obat', '=', $medicineout->id_pengeluaran_obat )->get();
        
        $html = '';
        if( $medicineoutdetail ){
            $i = 1; 

            if( $search_by_recipe ){
                $i = 1;
                foreach( $medicineoutdetail as $mid ){
                    $medicine = Medicine::find( $mid->id_obat );

                    $html .= '<tr class="item item-' . $mid->id_obat . '">
                                <td class="column-no">' . $i . '</td>
                                <td class="column-group">' . get_medicine_group_name( $medicine->id_golongan_obat ) . '</td>
                                <td class="column-code">' . $medicine->kode_obat . '</td>
                                <td class="column-name">' . $medicine->nama_obat . '</td>
                                <td class="column-amount">' . $mid->jumlah_obat . '</td>
                                <td class="column-action">
                                    <a href="#" title="Delete" class="delete" data-id="' . $mid->id_obat . '"><img src="'. URL::asset( 'assets/images/icon-delete.png' ) . '" alt="Delete" /></a>
                                    <input type="hidden" name="medicine_id_2[]" value="' . $mid->id_obat . '" class="medicine_id_2" />
                                    <input type="hidden" name="medicine_state_2[]" value="nothing" class="medicine_state_2" />
                                    <input type="hidden" name="medicine_amount_2[]" value="' . $mid->jumlah_obat . '" class="medicine_amount_2" />
                                </td>
                            </tr>';

                    $i++;
                }

                if( empty( $html ) ){
                    $html = '<tr class="no-data"><td colspan="6">Tidak ada data ditemukan.</td></tr>';
                }
            }else{
                foreach( $medicineoutdetail as $mid ){
                    $medicine = Medicine::find( $mid->id_obat );

                    $html .= '<tr class="item" id="item-' . $mid->id_obat . '">
                                <td class="column-code">' . $medicine->kode_obat . '</td>
                                <td class="column-name">' . $medicine->nama_obat . '</td>
                                <td class="column-amount">' . $mid->jumlah_obat . '</td>
                                <td class="column-action">
                                    <a href="#" title="Delete" class="delete" data-id="' . $mid->id_obat . '"><img src="'. URL::asset( 'assets/images/icon-delete.png' ) . '" alt="Delete" /></a>
                                    <input type="hidden" name="medicine_id[]" value="' . $mid->id_obat . '" class="medicine_id" />
                                    <input type="hidden" name="medicine_state[]" value="nothing" class="medicine_state" />
                                    <input type="hidden" name="medicine_amount[]" value="' . $mid->jumlah_obat . '" class="medicine_amount" />
                                </td>
                            </tr>';

                    $i++;
                }
            }
        }

        $resep = '';
        if( $medicineout->id_resep ){
            $recipe = DoctorRecipe::find( $medicineout->id_resep );
            $medrec = MedicalRecord::find( $recipe->id_pemeriksaan_poli );

            $resep = $recipe->no_resep . ' / ' . $medrec->nama_peserta;
        }

        $response = array(
            'html' => $html,
            'id_pengeluaran_obat' => $medicineout->id_pengeluaran_obat,
            'no_pengeluaran_obat' => $medicineout->no_pengeluaran_obat,
            'id_resep' => $medicineout->id_resep,
            'tanggal_pengeluaran_obat' => $medicineout->tanggal_pengeluaran_obat,
            'catatan_pengeluaran_obat' => $medicineout->catatan_pengeluaran_obat,
            'jumlah_pengeluaran_obat' => $medicineout->jumlah_pengeluaran_obat,
            'success' => 'true',
            'resep' => $resep
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
                $medicineout = MedicineOut::find( $input['id'] );

                if( !$medicineout ){
                    $medicineout = new MedicineOut();
                    $state = 'add';
                }
            }else{
                $medicineout = new MedicineOut();
                $state = 'add';
            }

            $medicineout->id_resep = $input['id_resep'];
            $medicineout->tanggal_pengeluaran_obat = $input['tanggal_pengeluaran_obat'];
            $medicineout->catatan_pengeluaran_obat = $input['catatan_pengeluaran_obat'];
            $medicineout->jumlah_pengeluaran_obat = $input['jumlah_pengeluaran_obat'];
            $medicineout->user_update = $id_pengguna;

            if( $input['state'] == 'add' ){
                $medicineout->no_pengeluaran_obat = MedicineOut::generate_id();
                $medicineout->id_pengguna = $id_pengguna;
            }

            $medicineout->save();
            
            if( isset( $input['medicine_id'] ) && is_array( $input['medicine_id'] ) ){
                foreach( $input['medicine_id'] as $key => $value ){
                    if( $input['medicine_state'][$key] == 'add' ){
                        $medicineoutdetail = new MedicineOutDetail();
                        $medicineoutdetail->no_detail_pengeluaran_obat = MedicineOutDetail::generate_id( $medicineout->no_pengeluaran_obat );
                        $medicineoutdetail->id_pengeluaran_obat = $medicineout->id_pengeluaran_obat;
                        $medicineoutdetail->id_obat = $value;
                        $medicineoutdetail->jumlah_obat = $input['medicine_amount'][$key];
                        $medicineoutdetail->id_pengguna = $id_pengguna;

                        $medicine = Medicine::find( $value );
                        $stock_obat = $medicine->stock_obat;
                        $stock_obat -= $input['medicine_amount'][$key];
                        $medicine->stock_obat = $stock_obat;
                        $medicine->save();

                        $medicineoutdetail->save();
                    }elseif( $input['medicine_state'][$key] == 'delete' ){
                        $medicineoutdetail = MedicineOutDetail::where( 'id_obat', '=', $value )->where( 'id_pengeluaran_obat', '=', $medicineout->id_pengeluaran_obat )->first();

                        if( $medicineoutdetail ){
                            $medicine = Medicine::find( $value );
                            $stock_obat = $medicine->stock_obat;
                            $stock_obat += $medicineoutdetail->jumlah_obat;
                            $medicine->stock_obat = $stock_obat;
                            $medicine->save();

                            $medicineoutdetail->delete();
                        }
                    }
                }

                $medicineout->save();
            }

            return redirect()->route( 'medicine-out.index' ); 
        } catch (\Exception $e) {
            return redirect()->route( 'medicine-out.index' ); 
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
            'message' => 'Terjadi kesalahan ketika menghapus list pengeluaran obat.'
        );
        
        return Response::json( $response );
    }

    public function latest_id(){
        $latest_id = MedicineOut::generate_id();

        $response = array(
            'success' => 'true',
            'latest_id' => $latest_id,
        );

        return Response::json( $response );
    }
}
