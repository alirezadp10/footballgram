<?php

namespace App\Console\Commands;

use App\Services\LeagueService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class uefaChampionsLeague extends Command
{
    private $season;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:uefa-champions-league  {season=null}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('uefa-champions-league');

        $leagueService = new LeagueService();

        $this->season = ($this->argument('season') === 'null') ? '2018-2019' : $this->argument('season');

        $path = DB::table('scoreboard')
                  ->where('tournament', 'UEFA_CHAMPIONS_LEAGUE')
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
        $this->standingDatabase('uefa_champions_league_table', $standing, $this->season);

        $this->info("{$this->season}-scorers");
        $scorers = $leagueService->scoreboard($path->scorers, 'scorers');
        $this->scorersDatabase('uefa_champions_league_scorers', $scorers, $this->season);

        $this->info("{$this->season}-fixtures");
        $play_offs = $leagueService->scoreboardPlayOffs($path->play_offs);
        $this->playOffsDatabase('uefa_champions_league_fixtures', $play_offs, $this->season);

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

    private function playOffsDatabase($table, $play_offs, $season)
    {
        foreach ($play_offs as $fixture) {
            foreach ($fixture as $key => $value) {
                if ($value['timestamp'] !== ""){
                    DB::table($table)
                      ->updateOrInsert([
                          'datetime' => Carbon::createFromTimestamp($value['timestamp'])
                                              ->format('Y-m-d H:i:s'),
                          'host'     => $value['host'],
                          'guest'    => $value['guest'],
                      ], [
                          'match_type'  => 'PLAY_OFFS',
                          'odd_even'    => strtoupper($key),
                          'host'        => $value['host'],
                          'host_point'  => $value['hostPoint'],
                          'guest'       => $value['guest'],
                          'guest_point' => $value['guestPoint'],
                          'season'      => $season,
                          'datetime'    => Carbon::createFromTimestamp($value['timestamp'])
                                                 ->format('Y-m-d H:i:s'),
                          'updated_at'  => Carbon::now(),
                      ]);
                }
            }
        }
    }

}
