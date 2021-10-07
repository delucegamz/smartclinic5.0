<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Medicine;
use App\MedicineAllergic;
use DB;
use Response;
use Auth;

class MedicineAllergicController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $input = $request->all();

        try {
            $medicineallergic = new MedicineAllergic();

            $medicineallergic->no_peserta_alergi = MedicineAllergic::generate_id();
            $medicineallergic->id_peserta = $input['id_peserta'];
            $medicineallergic->nama_peserta = get_participant_name( $input['id_peserta'] );
            $medicineallergic->nama_departemen = get_participant_department( $input['id_peserta'] );
            $medicineallergic->nama_factory = get_participant_factory( $input['id_peserta'] );
            $medicineallergic->nama_client = get_participant_client( $input['id_peserta'] );
            $medicineallergic->idobat = $input['medicine_id'];
            $medicineallergic->id_pengguna = $idpengguna;
            $medicineallergic->user_update = $idpengguna;

            $insert = $medicineallergic->save();

            $medicine = Medicine::find( $input['medicine_id'] );

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Peserta alergi berhasil ditambahkan.',
                    'kode_obat' => $medicine->kode_obat,
                    'nama_obat' => $medicine->nama_obat,
                    'id_obat' => $medicine->id_obat,
                    'id_golongan_obat' => get_medicine_group_name( $medicine->id_golongan_obat ), 
					'id_peserta_alergi' => $medicineallergic->id_peserta_alergi,
					'list_alergi' => get_participant_medicine_allergic( $medicineallergic->id_peserta )
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan peserta alergi.'
                );
            }

        } catch (\Exception $e) {
            $response = array(
                'success' => 'false',
                'message' => $e->getMessage()
            );
        }

        return Response::json( $response );
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
            'message' => 'Obat berhasil di hapus dari list alergi obat.'
        );
        
        return Response::json( $response );
    }
}
