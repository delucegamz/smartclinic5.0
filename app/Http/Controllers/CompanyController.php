<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Branch;
use App\Company;
use App\CompanyStructure;
use App\Province;
use DB;
use Response;
use Auth;

class CompanyController extends Controller
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
        if( !current_user_can( 'data_organisasi' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $company = Company::find(1);
        if( !$company ){
            $company = new Company();
        }

        $provinces = Province::all();

    	return view( 'company', [ 'company' => $company, 'provinces' => $provinces ] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $input = $request->all();

        try {
            $company = Company::find(1);
            if( !$company ){
                $company = new Company();
            }

            $company->nama_organisasi = $input['name'];
            $company->alamat_organisasi = $input['address'];
            $company->provinsi_organisasi = $input['province'];
            $company->kota_organisasi = $input['city'];
            $company->kode_pos_organisasi = $input['zip_code'];
            $company->no_telepon_1 = $input['phone_1'];
            $company->no_telepon_2 = $input['phone_2'];
            $company->no_fax = $input['fax'];
            $company->email = $input['email'];
            $company->kode_karyawan = $input['kode_karyawan'];

            $insert = $company->save();

            if( $insert ){
                $response = array(
                    'success' => 'true',
                    'message' => 'Perusahaan berhasil diperbarui.',
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika memperbarui perusahaan.'
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

    public function general_setting(){
        if( !current_user_can( 'setting' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        return view( 'setting' );
    }

    public function save_setting( Request $request ){
        if( !current_user_can( 'setting' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

        $input = $request->all();

        $hpl_formula_3m_down = $input['hpl_formula_3m_down'];
        $hpl_formula_4m_up = $input['hpl_formula_4m_up'];
        $igd = $input['igd'];
        $poli_umum = $input['poli_umum'];
        $poli_kebidanan = $input['poli_kebidanan'];

        update_setting( 'hpl_formula_3m_down', $hpl_formula_3m_down );
        update_setting( 'hpl_formula_4m_up', $hpl_formula_4m_up );
        update_setting( 'igd', $igd );
        update_setting( 'poli_umum', $poli_umum );
        update_setting( 'poli_kebidanan', $poli_kebidanan );

        return redirect( 'company/setting' )->with( 'message', 'Konfigurasi aplikasi berhasil diperbarui.' );
    }
}
