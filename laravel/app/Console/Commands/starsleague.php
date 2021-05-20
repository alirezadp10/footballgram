<?php

namespace App\Console\Commands;

use App\Constants\LeaguesConstants;
use App\Services\LeagueService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class starsleague extends Command
{
    private $season;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:starsleague  {season=null}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('starsleague');

        $leagueService = new LeagueService();

        $this->season = '2018-2019';

        $this->info("{$this->season}-standing");
        $standing = $leagueService->navad(
            LeaguesConstants::$Navad['STARSLEAGUE'],
            LeaguesConstants::$Navad['TABLE']
        );
        if (count($standing)) {
            $this->standingDatabase('stars_league_table', $standing, $this->season);
        }

        $this->info("{$this->season}-scorers");
        $path = DB::table('scoreboard')
                  ->where('tournament', 'STARSLEAGUE')
                  ->pluck('scorers')
                  ->first();
        $scorers = $leagueService->scoreboard($path,'scorers');
        $this->scorersDatabase('stars_league_scorers', $scorers, $this->season);
    }

    private function standingDatabase($table, $standing, $season)
    {
        foreach ($standing[0]['standing'] as $team) {
            DB::table($table)
              ->updateOrInsert([
                  'name'   => $team['team']['name'],
                  'season' => $season,
              ], [
                  'name'             => $team['team']['name'],
                  'position'         => $team['rank'],
                  'played'           => $team['played'],
                  'won'              => $team['wins'],
                  'drawn'            => $team['draws'],
                  'lost'             => $team['defeits'],
                  'goals_for'        => $team['goalsfor'],
                  'goals_against'    => $team['goalsagainst'],
                  'goals_difference' => $team['diff'],
                  'points'           => $team['points'],
                  'season'           => $season,
                  'updated_at'       => Carbon::now(),
              ]);
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
