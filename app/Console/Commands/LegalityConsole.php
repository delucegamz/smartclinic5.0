<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\LegalityDetail;

class LegalityConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:legality';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pengecekan otomatis legalitas yang sudah/akan expired';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $results = DB::table( 't_legalitas' )->where( 'status', '!=', 4 )->get();

        $now = date( 'Y-m-d' );
        $next3m = date( 'Y-m-d', strtotime( '+3 month', time() ) );
        $next6m = date( 'Y-m-d', strtotime( '+6 month', time() ) );

        foreach( $results as $res ){
            $legalitydetail = LegalityDetail::find( $res->id_t_legalitas );

            if( $res->exp_legalitas >= $now && $res->exp_legalitas <= $next3m ){
                $legalitydetail->exp_legalitas = 3;
            }elseif( $res->exp_legalitas >= $now && $res->exp_legalitas <= $next6m ){
                $legalitydetail->exp_legalitas = 2;
            }elseif( $res->exp_legalitas > $next6m ){
                $legalitydetail->exp_legalitas = 1;
            }

            $legalitydetail->save();
        }

        $this->info('Pengecekan legalitas berhasil dilakukan.');
    }
}
