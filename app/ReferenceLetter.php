<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class ReferenceLetter extends Model
{
    protected $table = 't_surat_rujukan';
    protected $primaryKey = 'id_surat_rujukan';
	protected $fillable = array( 
		'no_surat_rujukan', 
		'id_pemeriksaan_poli', 
		'id_peserta', 
		'provider', 
		'dokter_ahli', 
		'diagnosa_dokter',
		'anamnesa', 
		'pemeriksaan_fisik', 
		'obat_beri', 
		'umur_peserta', 
		'dokter_rujuk',
		'catatan_surat_rujukan',
		'tanggal_surat_rujukan',
		'id_pengguna',
		'user_update',
		'status'
	);

	public static function generate_id(){
        $ids = DB::table( 't_surat_rujukan' )
                ->select( 'no_surat_rujukan' )
                ->orderBy( 'no_surat_rujukan', 'desc' )
                ->where( 'no_surat_rujukan', 'LIKE', "%SRD%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_surat_rujukan ) ) ? $ids->no_surat_rujukan : "SRD0000000";
        $latest_id = str_replace( "SRD", "", $latest_id );
        $latest_id = (int) $latest_id;
        $latest_id++;

        $latest_id = "SRD" . str_pad( $latest_id, 7, "0", STR_PAD_LEFT );

        return $latest_id;
    }
}
