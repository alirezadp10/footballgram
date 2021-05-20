<?php

namespace App\Console\Commands;

use App\Services\LeagueService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class afcAsianCup extends Command
{
    private $season;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:afc-asian-cup  {season=null}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('afc-asian-cup');

        $leagueService = new LeagueService();

        $this->season = ($this->argument('season') === 'null') ? '2019' : $this->argument('season');

        $path = DB::table('scoreboard')
                  ->where('tournament', 'AFC_ASIAN_CUP')
                  ->where('season', '=', $this->season)
                  ->get([
                      'season',
                      'table',
                      'play_offs',
                      'scorers',
                  ])
                  ->first();

        $this->info("{$this->season}-standing");
        $standing = $leagueService->scoreboard($path->table, 'tables', TRUE);
        $this->standingDatabase('afc_asian_cup_table', $standing, $this->season);

        $this->info("{$this->season}-scorers");
        $scorers = $leagueService->scoreboard($path->scorers, 'scorers', TRUE);
        $this->scorersDatabase('afc_asian_cup_scorers', $scorers, $this->season);
    }

    private function standingDatabase($table, $standing, $season)
    {
        foreach ($standing as $team) {
            DB::table($table)
              ->updateOrInsert([
                  'name'   => $team['name'],
                  'season' => $season,
              ], array_merge($team, [
                  'season'     => $season,
                  'updated_at' => Carbon::now(),
              ]));
        }
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
