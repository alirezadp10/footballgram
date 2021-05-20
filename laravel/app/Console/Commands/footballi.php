<?php

namespace App\Console\Commands;

use App\Constants\LeaguesConstants;
use App\Helpers\MainHelper;
use Illuminate\Console\Command;

class footballi extends Command
{
    private $mainHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:footballi';

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
        $this->info('footballi');

        $content = json_decode(file_get_contents(
            LeaguesConstants::$Footballi['BASE_URL'] .
            LeaguesConstants::$Footballi['LEAGUE_PATH'] .
            LeaguesConstants::$Footballi['PREMIERLEAGUE']['ID'] .
            LeaguesConstants::$Footballi['SCORERS']
        ), TRUE);

        $fa = json_decode(file_get_contents(resource_path('lang/fa.json')), TRUE);

        $keys = array_keys($fa);

        foreach ($content['data'] as $item) {
            foreach ($item as $value) {
                $name = $this->mainHelper->convertAccentsAndSpecialToNormal($value['player']['name_en']);
                foreach ($keys as $key) {
                    if ($name === $key) {
                        continue 2;
                    }
                }
                $fa[$name] = $value['player']['name_fa'];
                file_put_contents(resource_path('lang/fa.json'), json_encode($fa, JSON_UNESCAPED_UNICODE));
            }

            foreach ($item as $value) {
                $name = $this->mainHelper->convertAccentsAndSpecialToNormal($value['team']['name_en']);
                foreach ($keys as $key) {
                    if ($name === $key) {
                        continue 2;
                    }
                }
                $fa[$name] = $value['team']['name_fa'];
                file_put_contents(resource_path('lang/fa.json'), json_encode($fa, JSON_UNESCAPED_UNICODE));
            }

        }

    }
}
