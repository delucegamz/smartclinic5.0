<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class DayOffLetter extends Model
{
    protected $table = 't_surat_cuti';
    protected $primaryKey = 'id_surat_cuti';
	protected $fillable = array( 
		'no_surat_cuti', 
		'id_pemeriksaan_poli', 
		'id_peserta', 
		'umur_peserta', 
		'jenis_cuti', 
		'dari_tanggal',
		'sampai_tanggal', 
		'lama', 
		'dokter_jaga', 
		'tgl_surat_cuti', 
		'id_pengguna',
		'user_update',
		'status'
	);

	public static function generate_id(){
        $ids = DB::table( 't_surat_cuti' )
                ->select( 'no_surat_cuti' )
                ->orderBy( 'no_surat_cuti', 'desc' )
                ->where( 'no_surat_cuti', 'LIKE', "%SC%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_surat_cuti ) ) ? $ids->no_surat_cuti : "SC0000000";
        $latest_id = str_replace( "SC", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "SC" . str_pad( $latest_id, 7, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
