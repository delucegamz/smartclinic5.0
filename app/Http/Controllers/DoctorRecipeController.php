<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\DoctorRecipe;
use App\DoctorRecipeDetail;
use App\MedicalRecord;
use App\Medicine;
use App\MedicineOut;
use App\MedicineOutDetail;
use DB;
use Response;
use Auth;
use URL;


class DoctorRecipeController extends Controller
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
        if( !current_user_can( 'resep_obat_dokter' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );
        
        if( isset( $_GET['rows'] ) && $_GET['rows'] != '' ){
            if( $_GET['rows'] == 'all' ){
                $rows = 'all';
            }else{
                $rows = absint( $_GET['rows'] );
            }
        }else{
            $rows = 10;
        }

        $filter = ( isset( $_GET['filter'] ) && $_GET['filter'] != '' ) ? $_GET['filter'] : 'today';
        $date_from = date( 'Y-m-d 00:00:00' );
        $date_to = date( 'Y-m-d 23:59:59' );

        $s = ( isset( $_GET['s'] ) && $_GET['s'] != '' ) ? filter_var( $_GET['s'], FILTER_SANITIZE_STRING ) : '';

        $details = DB::table( 't_resep_detail' )
                    ->select( DB::raw( 'DISTINCT( id_resep )' ) )
                    ->get();

        $ids = array();
        foreach( $details as $d ){
            $ids[] = $d->id_resep;
        }

        if( $rows == 'all' ){
            if( empty( $s ) ){
    	        if( $filter == 'today' ){
                    $datas = DoctorRecipe::where( 'created_at', '>=', $date_from )
                                         ->where( 'created_at', '<=', $date_to )
                                         ->whereNotIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->get();
                }elseif( $filter == 'recorded' ){
                    $datas = DoctorRecipe::whereIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->get();
                }elseif( $filter == 'not-recorded' ){
                    $datas = DoctorRecipe::where( 'created_at', '>', $date_to )
                                         ->whereNotIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->get();
                }
            }else{
                $participants = MedicalRecord::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

                $ids = array();
                foreach ( $participants as $p ) {
                    $ids[] = $p->id_pemeriksaan_poli;
                }

                if( $filter == 'today' ){
                    $datas = DoctorRecipe::whereIn( 'id_pemeriksaan_poli', function ( $query ) use ( $s ){
                                            $query->select( 'id_pemeriksaan_poli' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->from( 't_pemeriksaan_poli' );
                                         })
                                         ->where( 'created_at', '>=', $date_from )
                                         ->where( 'created_at', '<=', $date_to )
                                         ->whereNotIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->get();
                }elseif( $filter == 'recorded' ){
                    $datas = DoctorRecipe::whereIn( 'id_pemeriksaan_poli', function ( $query ) use ( $s ){
                                            $query->select( 'id_pemeriksaan_poli' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->from( 't_pemeriksaan_poli' );
                                         })
                                         ->whereIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->get();
                }elseif( $filter == 'not-recorded' ){
                    $datas = DoctorRecipe::whereIn( 'id_pemeriksaan_poli', function ( $query ) use ( $s ){
                                            $query->select( 'id_pemeriksaan_poli' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->from( 't_pemeriksaan_poli' );
                                         })
                                         ->where( 'created_at', '>', $date_to )
                                         ->whereNotIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->get();
                }
            }

        }else{
            if( empty( $s ) ){
                if( $filter == 'today' ){
                    $datas = DoctorRecipe::where( 'created_at', '>=', $date_from )
                                         ->where( 'created_at', '<=', $date_to )
                                         ->whereNotIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->paginate( $rows );
                }elseif( $filter == 'recorded' ){
                    $datas = DoctorRecipe::whereIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->paginate( $rows );
                }elseif( $filter == 'not-recorded' ){
                    $datas = DoctorRecipe::where( 'created_at', '<', $date_from )
                                         ->whereNotIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->paginate( $rows );
                }
            }else{
                if( $filter == 'today' ){
                    $datas = DoctorRecipe::whereIn( 'id_pemeriksaan_poli', function ( $query ) use ( $s ){
                                            $query->select( 'id_pemeriksaan_poli' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->from( 't_pemeriksaan_poli' );
                                         })
                                         ->where( 'created_at', '>=', $date_from )
                                         ->where( 'created_at', '<=', $date_to )
                                         ->whereNotIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->paginate( $rows );
                }elseif( $filter == 'recorded' ){
                    $datas = DoctorRecipe::whereIn( 'id_pemeriksaan_poli', function ( $query ) use ( $s ){
                                            $query->select( 'id_pemeriksaan_poli' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->from( 't_pemeriksaan_poli' );
                                         })
                                         ->whereIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->paginate( $rows );
                }elseif( $filter == 'not-recorded' ){
                    $datas = DoctorRecipe::whereIn( 'id_pemeriksaan_poli', function ( $query ) use ( $s ){
                                            $query->select( 'id_pemeriksaan_poli' )
                                                  ->where( 'nama_peserta', 'LIKE', "%$s%" )
                                                  ->from( 't_pemeriksaan_poli' );
                                         })
                                         ->where( 'created_at', '>', $date_to )
                                         ->whereNotIn( 'id_resep', function( $query ){
                                            $query->select( DB::raw( 'DISTINCT( id_resep )' ) )->from( 't_resep_detail' );
                                         })
                                         ->orderBy( 'id_resep', 'desc' )
                                         ->paginate( $rows );
                }
            }

        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

    	return view( 'doctorrecipe', [ 'datas' => $datas, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'filter' => $filter ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $recipes = DoctorRecipeDetail::where( 'id_resep', '=', $id )->get();

        $i = 1; $html = '';
        foreach( $recipes as $recipe ){
            $medicine = Medicine::find( $recipe->id_obat );

            $html .= '<tr class="item item-' . $recipe->id_obat . '">
                        <td class="column-no">' . $i . '</td>
                        <td class="column-group">' . get_medicine_group_name( $medicine->id_golongan_obat ) . '</td>
                        <td class="column-code">' . $medicine->kode_obat . '</td>
                        <td class="column-name">' . $medicine->nama_obat . '</td>
                        <td class="column-amount">' . $recipe->jumlah_obat . '</td>
                        <td class="column-action">
                            <a href="#" title="Delete" data-id="' . $recipe->id_obat . '" class="delete"><img src="' . URL::asset( 'assets/images/icon-delete.png' ) . '" alt="Delete" /></a>
                            <input type="hidden" name="medicine_id_1[]" value="' . $recipe->id_obat . '" class="medicine_id_1" />
                            <input type="hidden" name="medicine_state_1[]" value="nothing" class="medicine_state_1" />
                            <input type="hidden" name="medicine_amount_1[]" value="' . $recipe->jumlah_obat . '" class="medicine_amount_1" />
                        </td>
                    </tr>';

            $i++;
        }

        if( empty( $html ) ){
            $html = '<tr class="no-data"><td colspan="6">Tidak ada data ditemukan.</td></tr>';
        }

        $response = array(
            'html' => $html,
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

        $id = absint( $input['id'] );
  
        try {
            if( isset( $input['medicine_id_1'] ) && is_array( $input['medicine_id_1'] ) ){
                foreach( $input['medicine_id_1'] as $key => $value ){
                    if( $input['medicine_state_1'][$key] == 'add' ){
                        $amount = (int) $input['medicine_amount_1'][$key];
                        $recipedetail = new DoctorRecipeDetail();
                        $recipedetail->t_resep_detail = DoctorRecipeDetail::generate_id();
                        $recipedetail->id_resep = $id;
                        $recipedetail->id_obat = (int) $value;
                        $recipedetail->jumlah_obat = $amount;

                        $recipedetail->save();
                    }elseif( $input['medicine_state_1'][$key] == 'delete' ){
                        $recipedetail = DoctorRecipeDetail::where( 'id_obat', '=', $value )->where( 'id_resep', '=', $id )->first();

                        if( $recipedetail ){
                            $recipedetail->delete();
                        }
                    }
                }
            }

            if( isset( $input['medicine_id_2'] ) && is_array( $input['medicine_id_2'] ) ){
                $medicineout = MedicineOut::where( 'id_resep', '=', $id )->first();
                
                if( !$medicineout ){
                    $medicineout = new MedicineOut();
                    $medicineout->no_pengeluaran_obat = MedicineOut::generate_id();
                    $medicineout->id_pengguna = $id_pengguna;
                    $medicineout->id_resep = $id;
                    $medicineout->tanggal_pengeluaran_obat = date( 'Y-m-d' );
                    $medicineout->catatan_pengeluaran_obat = '';
                    $medicineout->jumlah_pengeluaran_obat = 0;
                    $medicineout->user_update = $id_pengguna;

                    $medicineout->save();
                }
                
                $jumlah_pengeluaran_obat = $medicineout->jumlah_pengeluaran_obat;
                foreach( $input['medicine_id_2'] as $key => $value ){
                    if( $input['medicine_state_2'][$key] == 'add' ){
                        $amount = (int) $input['medicine_amount_2'][$key];

                        $medicineoutdetail = new MedicineOutDetail();
                        $medicineoutdetail->no_detail_pengeluaran_obat = MedicineOutDetail::generate_id( $medicineout->no_pengeluaran_obat );
                        $medicineoutdetail->id_pengeluaran_obat = $medicineout->id_pengeluaran_obat;
                        $medicineoutdetail->id_obat = (int) $value;
                        $medicineoutdetail->jumlah_obat = $amount;
                        $medicineoutdetail->id_pengguna = $id_pengguna;

                        $medicine = Medicine::find( $value );
                        $stock_obat = $medicine->stock_obat;
                        $stock_obat -= $amount;
                        $medicine->stock_obat = $stock_obat;
                        $medicine->save();

                        $jumlah_pengeluaran_obat += $amount;

                        $medicineoutdetail->save();
                    }elseif( $input['medicine_state_2'][$key] == 'delete' ){
                        $medicineoutdetail = MedicineOutDetail::where( 'id_obat', '=', $value )->where( 'id_pengeluaran_obat', '=', $medicineout->id_pengeluaran_obat )->first();

                        if( $medicineoutdetail ){
                            $medicine = Medicine::find( $value );
                            $stock_obat = $medicine->stock_obat;
                            $stock_obat += $medicineoutdetail->jumlah_obat;
                            $medicine->stock_obat = $stock_obat;
                            $medicine->save();

                            $jumlah_pengeluaran_obat -= $medicineoutdetail->jumlah_obat;

                            $medicineoutdetail->delete();
                        }
                    }
                }

                $medicineout->jumlah_pengeluaran_obat = $jumlah_pengeluaran_obat;
                $medicineout->save();
            }
        } catch (\Exception $e) { die_dump( $e->getMessage());
            //return redirect()->route( 'doctor-recipe.index' ); 
        }

       return redirect()->route( 'doctor-recipe.index' );
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
            'success' => 'true',
            'message' => 'Resep obat berhasil dihapus.',
        );
       
        return Response::json( $response );
    }

    public function search_doctor_recipe( Request $request ){
        $input = $request->all();

        $value = $input['val'];

        $dd = DoctorRecipe::where( function($q) use ( $value ){
            $participants = MedicalRecord::where( 'nama_peserta', 'LIKE', "%$value%" )->get();

            $ids = array();
            foreach ( $participants as $p ) {
                $ids[] = $p->id_pemeriksaan_poli;
            }

            $q->where( 'no_resep', 'like', '%' . $value . '%' )
              ->orWhereIn( 'id_pemeriksaan_poli', $ids );
        })->get(); 

        $responses = array();

        if( $dd ){
            foreach( $dd as $d ){
                $medrec = MedicalRecord::find( $d->id_pemeriksaan_poli )->first();

                $responses[] = array(
                    'id_resep' => $d['id_resep'],
                    'no_resep' => $d['no_resep'],
                    'display_name' => $d['no_resep'] . ' / ' . $medrec['nama_peserta'] 
                );
            }

        }else{
            $responses = array(
                'success' => 'false',
            );
        }

        return Response::json( $responses );
    }
}
