<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Anc extends Model
{
    protected $table = 't_pemeriksaan_anc';
    protected $primaryKey = 'id_pemeriksaan_anc';
	protected $fillable = array( 
        'no_pemeriksaan_anc', 
        'id_pemeriksaan_poli', 
        'id_peserta_hamil', 
        'id_peserta', 
        'tgl_pemeriksaan_anc', 
        'kunjungan_ke', 
        'status_tt', 
        'berat_badan', 
        'td_atas', 
        'td_bawah', 
        'nilai_gizi', 
        'denyut_janin', 
        'presentasi', 
        'injeksi_tt', 
        'tablet_fe', 
        'pemeriksaan_hb',
        'pemeriksaan_urin',
        'tfu',
        'djj_plus',
        'tm',
        'keterangan_kehamilan',
        'kesimpulan',
        'id_pengguna',
        'user_update'
    );

	public static function generate_id(){
        $date = date( 'ym' );

        $ids = DB::table( 't_pemeriksaan_anc' )
                ->select( 'no_pemeriksaan_anc' )
                ->orderBy( 'no_pemeriksaan_anc', 'desc' )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_pemeriksaan_anc ) ) ? $ids->no_pemeriksaan_anc : "ANC000000000";
        $latest_id = str_replace( "ANC", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "ANC" . str_pad( $latest_id, 9, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
