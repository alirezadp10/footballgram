<?php

namespace App\Console\Commands;

use App\Constants\LeaguesConstants;
use App\Helpers\MainHelper;
use Illuminate\Console\Command;

class tarafdari extends Command
{
    private $mainHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:tarafdari';

    public function __construct(MainHelper $mainHelper)
    {
        parent::__construct();
        $this->mainHelper = $mainHelper;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('tarafdari');
        $fa = json_decode(file_get_contents(resource_path('lang/fa.json')), TRUE);
        $keys = array_keys($fa);
        $content = json_decode(file_get_contents('http://sdm.tarafdari.com/v1/matches?token=sdm-android&start=0&end=1539300600%20HTTP/1.1'), TRUE);
        foreach ($content['data']['teams'] as $team) {
            $this->info($team['name_en']);
            foreach ($content['data']['competitions'] as $competition) {
                if ($competition['cid'] === $team['cid']){
                    $club = json_decode(
                        file_get_contents(
                            "http://sdm.tarafdari.com/v1/team/squads?token=sdm-web&team_id={$team['tid']}&season_id={$competition['last_season']}"
                        ),
                        TRUE
                    );
                    foreach ($club['data']['players'] as $player) {
                        $full_name_fa = "{$player['firstname_fa']} {$player['lastname_fa']}";
                        $full_name_en = $this->mainHelper->convertAccentsAndSpecialToNormal("{$player['firstname_en']} {$player['lastname_en']}");
                        if ($full_name_fa == " "){
                            continue ;
                        }
                        foreach ($keys as $key) {
                            if ($full_name_en === $key) {
                                continue 2;
                            }
                        }
                        if(substr_count($full_name_en," ") == 1){
                            $name = strtoupper(substr($full_name_en, 0,1));
                            $family = substr($full_name_en, strpos($full_name_en," ") + 1);
                            $fa["{$family} {$name}."] = $full_name_fa;
                            file_put_contents(resource_path('lang/fa.json'), json_encode($fa, JSON_UNESCAPED_UNICODE));
                            continue;
                        }
                        $fa[$full_name_en] = $full_name_fa;
                        file_put_contents(resource_path('lang/fa.json'), json_encode($fa, JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            if ($team['name_fa'] == ""){
                continue ;
            }
            $name = $this->mainHelper->convertAccentsAndSpecialToNormal($team['name_en']);
            foreach ($keys as $key) {
                if ($name === $key) {
                    continue 2;
                }
            }
            $fa[$name] = $team['name_fa'];
            if ($fa[$name] == "" || $fa[$name] == " "){
                continue ;
            }
            file_put_contents(resource_path('lang/fa.json'), json_encode($fa, JSON_UNESCAPED_UNICODE));
        }
    }
}
