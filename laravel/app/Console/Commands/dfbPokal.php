<?php

namespace App\Console\Commands;

use App\Services\LeagueService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class dfbPokal extends Command
{
    private $season;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dfb-pokal  {season=null}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('dfb-pokal');

        $leagueService = new LeagueService();

        $this->season = '2018-2019';

        $this->info("{$this->season}-scorers");
        $path = DB::table('scoreboard')
                  ->where('tournament', 'DFB_POKAL')
                  ->pluck('scorers')
                  ->first();
        $scorers = $leagueService->scoreboard($path, 'scorers');
        $this->scorersDatabase('dfb_pokal_scorers', $scorers, $this->season);
    }

    private function scorersDatabase($table, $scorers, $season)
    {
        foreach ($scorers as $scorer) {
            DB::table($table)
              ->updateOrInsert([
                  'name'   => $scorer['name'],
                  'club'   => $scorer['club'],
                  'season' => $season,
              ], [
                  'name'          => $scorer['name'],
                  'club'          => $scorer['club'],
                  'count_scores'  => $scorer['count_scores'],
                  'count_assists' => $scorer['count_assists'],
                  'season'        => $season,
                  'updated_at'    => Carbon::now(),
              ]);
        }
    }
}
