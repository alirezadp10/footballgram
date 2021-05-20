<?php

namespace App\Console\Commands;

use App\Services\LeagueService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class coppaItalia extends Command
{
    private $season;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:coppa-italia  {season=null}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('coppa-italia');

        $leagueService = new LeagueService();

        $this->season = '2018-2019';

        $this->info("{$this->season}-scorers");
        $path = DB::table('scoreboard')
                  ->where('tournament', 'COPPA_ITALIA')
                  ->pluck('scorers')
                  ->first();
        $scorers = $leagueService->scoreboard($path, 'scorers');
        $this->scorersDatabase('coppa_italia_scorers', $scorers, $this->season);
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
