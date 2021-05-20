<?php

namespace App\Console\Commands;

use App\Constants\LeaguesConstants;
use App\Services\LeagueService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\jDateTime;

class calcio extends Command
{
    private $season;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:calcio  {season=null}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('calcio');

        $leagueService = new LeagueService();

        $this->season = '2018-2019';

        $this->info("{$this->season}-standing");
        $leagueService->table(
            LeaguesConstants::CALCIO,
            $this->season,
            LeaguesConstants::$Source[LeaguesConstants::VARZESH3]
        );
        $standing = $leagueService::$table;
        $this->standingDatabase('calcio_table', $standing, $this->season);

        $this->info("{$this->season}-scorers");
        $path = DB::table('scoreboard')
                  ->where('tournament', 'CALCIO')
                  ->pluck('scorers')
                  ->first();
        $scorers = $leagueService->scoreboard($path,'scorers');
        $this->scorersDatabase('calcio_scorers', $scorers, $this->season);

        $this->info("{$this->season}-fixtures");
        $this->renderUrl(496, 'calcio');

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

    private function renderUrl($leagueId, $table)
    {
        for ($i = 1; $i <= 38; $i++) {
            $this->comment("period:" . $i);
            $url = "https://api.varzesh3.com/v2.0/leaguestat/widget/5/{$leagueId}/{$i}";
            $data = json_decode(file_get_contents($url), TRUE);
            if (!is_null($data)) {
                if ($table == 'khaligefars' || $table == 'azadegan'){
                    $season = substr($data['Widget']['MatchGroupSlug'], -5, 5);
                    $season = explode('-', $season);
                    $x = jDateTime::toGregorianDate(13 . $season[0], 01, 01)->format('Y');
                    $y = jDateTime::toGregorianDate(13 . $season[1], 01, 01)->format('Y');
                    $season = $x . '-' . $y;
                }
                else{
                    $season = substr($data['Widget']['MatchGroupSlug'], -9, 9);
                }
                if (!is_null($data['Fixtures'])) {
                    $this->fixturesDatabase("{$table}_fixtures", $data['Fixtures'], $season);
                }
            }
        }
    }

    private function fixturesDatabase($table, $fixtures, $season)
    {
        foreach ($fixtures as $fixture) {
            DB::table($table)
              ->updateOrInsert([
                  'source_id'   => $fixture['Id'],
                  'source_type' => 'VARZESH3',
              ], [
                  'source_id'   => $fixture['Id'],
                  'source_type' => 'VARZESH3',
                  'Host'        => $fixture['Host'],
                  'host_point'  => $fixture['HostPoint'],
                  'Guest'       => $fixture['Guest'],
                  'guest_point' => $fixture['GuestPoint'],
                  'season'      => $season,
                  'period'      => $fixture['Period'],
                  'final'       => $fixture['Final'],
                  'datetime'    => jDateTime::createDatetimeFromFormat(
                      'Y/m/d H:i:s', "{$fixture['Date']} {$fixture['Time']}:00")
                                            ->format('Y-m-d H:i:s'
                                            ),
                  'updated_at'  => Carbon::now(),
              ]);
        }
    }

}
