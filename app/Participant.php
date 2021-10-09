<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;

class Participant extends Model
{
    protected $table = 'm_peserta';
    protected $primaryKey = 'id_peserta';
	protected $fillable = array( 
        'kode_peserta', 
        'no_medrec', 
        'nik_peserta', 
        'nama_peserta', 
        'id_departemen', 
        'jenis_kelamin', 
        'tempat_lahir', 
        'tanggal_lahir', 
        'alamat', 
        'kota', 
        'provinsi', 
        'kodepos', 
        'tanggal_aktif', 
        'tanggal_nonaktif', 
        'status_aktif', 
        'status_kawin', 
        'jumlah_anak', 
        'id_pengguna' 
    );

    public function polimedrec(){
        return $this->belongsTo(polimedrec::class);
    }


   	public static function generate_id(){
        $ids = DB::table( 'm_peserta' )
                ->select( 'kode_peserta' )
                ->orderBy( 'kode_peserta', 'desc' )
                ->first();

        $latest_id = ( $ids && !empty( $ids->kode_peserta ) ) ? $ids->kode_peserta : "11000100000000";
        $latest_id++;

        return $latest_id;
    }

    public static function generate_medrec(){
        $date = date( 'ym' );

        $ids = DB::table( 'm_peserta' )
                ->select( 'no_medrec' )
                ->orderBy( 'no_medrec', 'desc' )
                ->where( 'no_medrec', 'LIKE', "$date%" )
                ->first();

        $latest_id = ( $ids && !empty( $ids->no_medrec ) ) ? $ids->no_medrec : $date . "00000000";
        $latest_id++;

        return $latest_id;
    }

    public static function get_nik( $id = 0 ){
        $participant = Participant::find( $id );

        return ( $participant && $participant->nik_peserta ) ? $participant->nik_peserta : '';
    }

    public static function get_code( $id = 0 ){
        $participant = Participant::find( $id );

        return ( $participant && $participant->kode_peserta ) ? $participant->kode_peserta : '';
    }

    public static function get_name( $id = 0 ){
        $participant = Participant::find( $id );

        return ( $participant && $participant->nama_peserta ) ? $participant->nama_peserta : '';
    }
    
    public static function get_age( $id = 0 ){
        $participant = Participant::find( $id );

        return ( $participant && $participant->tanggal_lahir ) ? get_age_by_mysql_date( $participant->tanggal_lahir ) : '';
    }
    
    public static function get_sex( $id = 0 ){
        $participant = Participant::find( $id );

        return ( $participant && $participant->jenis_kelamin ) ? ucwords( $participant->jenis_kelamin ) : '';
    }

    public static function get_address( $id = 0 ){
        $participant = Participant::find( $id );

        return ( $participant && $participant->alamat ) ? $participant->alamat : '';
    }
    
    public static function get_department( $id = 0 ){
        $participant = Participant::find( $id );

        return ( $participant && $participant->id_departemen ) ? get_department_name( $participant->id_departemen ) : '';
    }
    
    public static function get_factory( $id = 0 ){
        $participant = Participant::find( $id );

        if( $participant ){
            $department = Department::find( $participant->id_departemen );

            return ( $participant && $participant->id_departemen ) ? get_factory_name( $department->nama_factory ) : '';
        }else{
            return '';
        }
    }
    
    public static function get_client( $id = 0 ){
        $participant = Participant::find( $id );

        if( $participant ){
            $department = Department::find( $participant->id_departemen );

            return ( $participant && $participant->id_departemen ) ? get_client_name( $department->nama_client ) : '';
        }else{
            return '';
        }
    }

    public static function get_participant( $id = 0 ){
        $participants = Cache::rememberForever( 'participants', function() {
            $datas = DB::table( 'm_peserta' )->get();

            $participants = array();

            foreach ( $datas as $data ) {
                $participants[$data->id_peserta] = $data;
            }

            return $participants;
        });

        return isset( $participants[$id] ) && $participants[$id] ? $participants[$id] : null;
    }
    
}
