<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\AmbulanceOut;
use App\AmbulanceIn;
use DB;
use Response;
use Auth;


class AmbulanceController extends Controller
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
        if( !current_user_can( 'ambulance' ) ) die( 'Anda tidak diperbolehkan melihat halaman ini!' );

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
        $view = ( isset( $_GET['view'] ) && $_GET['view'] != '' ) ? filter_var( $_GET['view'], FILTER_SANITIZE_STRING ) : 'out';

        if( $rows == 'all' ){
            if( empty( $s ) ){
                if( $view == 'out' ){
                    $results = AmbulanceIn::where( 'tanggal_masuk', '=', NULL )->get();

                    $ins = array();
                    foreach( $results as $res ){
                        $ins[] = $res->id_ambulance_out;
                    }

                    $datas = AmbulanceOut::whereIn( 'id_ambulance_out', $ins )->get();
                }else{
    	            $datas = AmbulanceIn::where( 'tanggal_masuk', '!=', NULL )->get();
                }
            }else{
                if( $view == 'out' ){
                    $results = AmbulanceIn::where( 'tanggal_masuk', '=', NULL )->get();

                    $ins = array();
                    foreach( $results as $res ){
                        $ins[] = $res->id_ambulance_out;
                    }

                    $datas = AmbulanceOut::where( 'nama_peserta', 'LIKE', "%$s%" )
                                         ->whereIn( 'id_ambulance_out', $ins )
                                         ->get();
                }else{
                    $results = AmbulanceOut::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

                    $outs = array();
                    foreach( $results as $res ){
                        $outs[] = $res->id_ambulance_out;
                    }

                    $datas = AmbulanceIn::whereIn( 'id_ambulance_out', $outs )->where( 'tanggal_masuk', '!=', NULL )->get();
                }
            }    
        }else{
            if( empty( $s ) ){
                if( $view == 'out' ){
                    $results = AmbulanceIn::where( 'tanggal_masuk', '=', NULL )->get();

                    $ins = array();
                    foreach( $results as $res ){
                        $ins[] = $res->id_ambulance_out;
                    }

                    $datas = AmbulanceOut::whereIn( 'id_ambulance_out', $ins )->paginate( $rows );
                }else{
                    $datas = AmbulanceIn::where( 'tanggal_masuk', '!=', NULL )->paginate( $rows );
                }
            }else{
                if( $view == 'out' ){
                    $results = AmbulanceIn::where( 'tanggal_masuk', '=', NULL )->get();

                    $ins = array();
                    foreach( $results as $res ){
                        $ins[] = $res->id_ambulance_out;
                    }

                    $datas = AmbulanceOut::where( 'nama_peserta', 'LIKE', "%$s%" )->whereIn( 'id_ambulance_out', $ins )->paginate( $rows );
                }else{
                    $results = AmbulanceOut::where( 'nama_peserta', 'LIKE', "%$s%" )->get();

                    $outs = array();
                    foreach( $results as $res ){
                        $outs[] = $res->id_ambulance_out;
                    }

                    $datas = AmbulanceIn::whereIn( 'id_ambulance_out', $outs )->where( 'tanggal_masuk', '!=', NULL )->paginate( $rows );
                }
                    
            }
        }

        $page = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

        if( $rows != 'all' )
            $i = ( $page * $rows ) - ( $rows - 1 );
        else
            $i = 1;

        $ambulances = array();
        if( $view == 'out' ){
            foreach( $datas as $data ){
                $ambulances[] = array(
                    'no_ambulance_out' => $data->no_ambulance_out,
                    'no_ambulance_in' => '-',
                    'id_peserta' => $data->id_peserta,
                    'tanggal' => date( 'd-m-Y', strtotime( $data->tanggal_keluar ) ),
                    'jam_datang' => date( 'H:i:s', strtotime( $data->tanggal_keluar ) ),
                    'jam_pulang' => '-',
                    'lokasi_penjemputan' => $data->lokasi_jemput,
                    'lokasi_pengiriman' => $data->lokasi_kirim,
                    'km_out' => $data->km_out,
                    'km_in' => '-',
                    'driver' => '-',
                    'catatan' => '-',
                    'id_ambulance_out' => $data->id_ambulance_out
                );
            }
        }else{
            foreach( $datas as $data ){
                $out = AmbulanceOut::where( 'id_ambulance_out', '=', $data->id_ambulance_out )->first();

                $ambulances[] = array(
                    'no_ambulance_out' => $out->no_ambulance_out,
                    'no_ambulance_in' => $data->no_ambulance_in,
                    'id_peserta' => $out->id_peserta,
                    'tanggal' => date( 'd-m-Y', strtotime( $out->tanggal_keluar ) ),
                    'jam_datang' => date( 'H:i:s', strtotime( $out->tanggal_keluar ) ),
                    'jam_pulang' => date( 'H:i:s', strtotime( $out->tanggal_masuk ) ),
                    'lokasi_penjemputan' => $out->lokasi_jemput,
                    'lokasi_pengiriman' => $out->lokasi_kirim,
                    'km_out' => $out->km_out,
                    'km_in' => $data->km_in,
                    'driver' => $data->driver,
                    'catatan' => $data->catatan,
                    'id_ambulance_out' => $out->id_ambulance_out
                );
            }
        }

    	return view( 'ambulance', [ 'datas' => $datas, 'ambulances' => $ambulances, 'rows' => $rows, 's' => $s, 'page' => $page, 'i' => $i, 'view' => $view ]);
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
        $user = Auth::user();

        $idpengguna = $user['original']['idpengguna'];

        $input = $request->all();

        try {
            $ambulanceout = new AmbulanceOut();

            $ambulanceout->no_ambulance_out = AmbulanceOut::generate_id();
            $ambulanceout->id_peserta = $input['id_peserta'];
            $ambulanceout->nama_peserta = get_participant_name( $input['id_peserta'] );
            $ambulanceout->nama_factory = get_participant_factory( $input['id_peserta'] );
            $ambulanceout->nama_departemen = get_participant_department( $input['id_peserta'] );
            $ambulanceout->nama_client = get_participant_client( $input['id_peserta'] );
            $ambulanceout->lokasi_jemput = $input['lokasi_jemput'];
            $ambulanceout->lokasi_kirim = $input['lokasi_kirim'];
            $ambulanceout->tanggal_keluar = $input['tanggal_keluar'];
            $ambulanceout->km_out = $input['km_out'];
            $ambulanceout->catatan = $input['catatan'];
            $ambulanceout->id_pengguna = $idpengguna;

            $insert = $ambulanceout->save();

            if( $insert ){
                $ambulancein = new AmbulanceIn();

                $ambulancein->no_ambulance_in = AmbulanceIn::generate_id();
                $ambulancein->id_ambulance_out = $ambulanceout->id_ambulance_out;
                $ambulancein->id_pengguna = $idpengguna;

                $ambulancein->save();

                $response = array(
                    'success' => 'true',
                    'message' => 'Ambulance out berhasil ditambahkan.',
                    'ambulance_out' => $ambulanceout->no_ambulance_out,
                    'ambulance_in' => '-',
                    'id_peserta' => $ambulanceout->id_peserta,
                    'tanggal' => date( 'd-m-Y', strtotime( $ambulanceout->tanggal_keluar ) ),
                    'tanggal_masuk' => '-',
                    'lokasi_penjemputan' => $ambulanceout->lokasi_jemput,
                    'lokasi_pengiriman' => $ambulanceout->lokasi_kirim,
                    'km_out' => $ambulanceout->km_out,
                    'km_in' => '-',
                    'driver' => '-',
                    'catatan' => $ambulanceout->catatan,
                    'catatan_2' => '-',
                    'id_ambulance_out' => $ambulanceout->id_ambulance_out,
                    'kode_peserta' => get_participant_code( $ambulanceout->id_peserta ),
                    'nik_peserta' => get_participant_nik( $ambulanceout->id_peserta ),
                    'nama_peserta' => get_participant_name( $ambulanceout->id_peserta ),
                    'jam_datang' => date( 'H:i:s', strtotime( $ambulanceout->tanggal_keluar ) ),
                    'jam_pulang' => '-',
                );
            }else{
                $response = array(
                    'success' => 'false',
                    'message' => 'Terjadi kesalahan ketika menambahkan ambulance out.'
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( $id )
    {
        $ambulanceout = AmbulanceOut::find( $id );
        $ambulancein = AmbulanceIn::where( 'id_ambulance_out', '=', $id )->first();

        $response = array(
            'success' => 'true',
            'no_ambulance_out' => $ambulanceout->no_ambulance_out,
            'no_ambulance_in' => $ambulancein->no_ambulance_in,
            'id_peserta' => $ambulanceout->id_peserta,
            'nik_peserta' => get_participant_nik( $ambulanceout->id_peserta ),
            'nama_peserta' => get_participant_name( $ambulanceout->id_peserta ),
            'nama_departemen' => get_participant_department( $ambulanceout->id_peserta ), 
            'nama_factory' => get_participant_factory( $ambulanceout->id_peserta ),
            'nama_client' => get_participant_client( $ambulanceout->id_peserta ),
            'tanggal_keluar' => $ambulanceout->tanggal_keluar,
            'tanggal_masuk' => $ambulancein->tanggal_masuk,
            'lokasi_jemput' => $ambulanceout->lokasi_jemput,
            'lokasi_kirim' => $ambulanceout->lokasi_kirim,
            'km_out' => $ambulanceout->km_out,
            'km_in' => $ambulancein->km_in,
            'driver' => $ambulancein->driver,
            'catatan' => $ambulancein->catatan,
            'id_ambulance_out' => $ambulanceout->id_ambulance_out
        );

        return Response::json( $response );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id )
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
    public function update( Request $request, $id )
    {
        $input = $request->all();

        $ambulanceout = AmbulanceOut::find( $id );

        $ambulanceout->id_peserta = $input['id_peserta'];
        $ambulanceout->nama_peserta = get_participant_name( $input['id_peserta'] );
        $ambulanceout->nama_factory = get_participant_factory( $input['id_peserta'] );
        $ambulanceout->nama_departemen = get_participant_department( $input['id_peserta'] );
        $ambulanceout->nama_client = get_participant_client( $input['id_peserta'] );
        $ambulanceout->lokasi_jemput = $input['lokasi_jemput'];
        $ambulanceout->lokasi_kirim = $input['lokasi_kirim'];
        $ambulanceout->tanggal_keluar = $input['tanggal_keluar'];
        $ambulanceout->km_out = $input['km_out'];
        $ambulanceout->catatan = $input['catatan'];
    
        $update_out = $ambulanceout->save();

        $ambulancein = AmbulanceIn::where( 'id_ambulance_out', '=', $id )->first();
        $ambulancein->km_in = $input['km_in'];
        $ambulancein->driver = $input['driver'];
        $ambulancein->tanggal_masuk = $input['tanggal_masuk'];
        $ambulancein->catatan = $input['catatan_2'];

        $update_in = $ambulancein->save();

        if( $update_out !== false && $update_in !== false ){
            $response = array(
                'success' => 'true',
                'message' => 'Penggunaan ambulance berhasil diperbarui.',
                'ambulance_out' => $ambulanceout->no_ambulance_out,
                'ambulance_in' => $ambulancein->no_ambulance_in,
                'id_peserta' => $ambulanceout->id_peserta,
                'tanggal' => date( 'd-m-Y', strtotime( $ambulanceout->tanggal_keluar ) ),
                'tanggal_masuk' => $ambulancein->no_ambulance_in,
                'lokasi_penjemputan' => $ambulanceout->lokasi_jemput,
                'lokasi_pengiriman' => $ambulanceout->lokasi_kirim,
                'km_out' => $ambulanceout->km_out,
                'km_in' => $ambulancein->km_in,
                'driver' => $ambulancein->driver,
                'catatan' => $ambulanceout->catatan,
                'catatan_2' => $ambulancein->catatan,
                'id_ambulance_out' => $ambulanceout->id_ambulance_out,
                'kode_peserta' => get_participant_code( $ambulanceout->id_peserta ),
                'nik_peserta' => get_participant_nik( $ambulanceout->id_peserta ),
                'nama_peserta' => get_participant_name( $ambulanceout->id_peserta ),
                'jam_datang' => date( 'H:i:s', strtotime( $ambulanceout->tanggal_keluar ) ),
                'jam_pulang' => date( 'H:i:s', strtotime( $ambulancein->tanggal_masuk ) ),
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika memperbarui penggunaan ambulance.'
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
        $ambulanceout = AmbulanceOut::find( $id );
        $ambulancein = AmbulanceIn::where( 'id_ambulance_out', '=', $id )->first();

        $delete_ambulanceout = $ambulanceout->delete();
        $delete_ambulancein = $ambulancein->delete();

        if( $delete_ambulanceout && $delete_ambulancein ){
            $response = array(
                'success' => 'true',
                'message' => 'Factory berhasil dihapus.',
            );
        }else{
            $response = array(
                'success' => 'false',
                'message' => 'Terjadi kesalahan ketika menghapus factory.'
            );
        }

        return Response::json( $response );
    }

    public function latest_id(){
    	$no_ambulance_out = AmbulanceOut::generate_id();
        $no_ambulance_in = AmbulanceIn::generate_id();

    	$response = array(
            'success' => 'true',
            'no_ambulance_out' => $no_ambulance_out,
            'no_ambulance_in' => $no_ambulance_in,
        );

        return Response::json( $response );
    }
}
