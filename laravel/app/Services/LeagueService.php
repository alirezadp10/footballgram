<?php

namespace App\Services;

use App\Constants\LeaguesConstants;

class LeagueService
{
    public static $table = [];

    private static $group;

    public function table($league, $season, $source, $type = 'CURL')
    {
        $methodName = "tablesFrom${source}";
        return $this->$methodName($league, $season, $type);
    }

    private function tablesFromFootballDatabase($league, $season, $type)
    {
        $season = explode('-', $season);
        $startSeason = $season[0];
        $endSeason = substr($season[1], -2, 2);
        $url = "http://footballdatabase.com/league-scores-tables/${league}-${startSeason}-${endSeason}";
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $html = curl_exec($c);
        curl_close($c);
        $start = strpos($html, '<tbody>') + 7;
        $finish = strpos($html, '</tbody>');
        $table = substr($html, $start, $finish - $start);
        $teams = explode('</tr>', str_replace("\r\n", '', $table));
        self::$table = [];
        foreach ($teams as $row) {
            $col = explode('<td>', $row);
            if (count($col) == 11) {
                self::$table[] = [
                    'position'         => html_entity_decode(strip_tags($col[1])),
                    'name'             => camel_case(camel_case(html_entity_decode(strip_tags($col[2])))),
                    'points'           => camel_case(html_entity_decode(strip_tags($col[3]))),
                    'goals_difference' => camel_case(html_entity_decode(strip_tags($col[4]))),
                    'played'           => camel_case(html_entity_decode(strip_tags($col[5]))),
                    'won'              => camel_case(html_entity_decode(strip_tags($col[6]))),
                    'drawn'            => camel_case(html_entity_decode(strip_tags($col[7]))),
                    'lost'             => camel_case(html_entity_decode(strip_tags($col[8]))),
                    'goals_for'        => camel_case(html_entity_decode(strip_tags($col[9]))),
                    'goals_against'    => camel_case(html_entity_decode(strip_tags($col[10]))),
                ];
            }
        }
    }

    private function tablesFromWorldFootball($league, $season, $type)
    {
        $url = "http://worldfootball.net/schedule/${league}-${season}";
        $document = new \DOMDocument();
        libxml_use_internal_errors(TRUE);
        $document->loadHTML(file_get_contents($url));
        libxml_clear_errors();
        $xpath = new \DOMXPath($document);
        $teams = $xpath->query("//table[@class='standard_tabelle']");
        $collect = explode('----', str_replace("\r\n", '', preg_replace("/\t{2,}/", "-", $teams[1]->nodeValue)));
        array_shift($collect);
        self::$table = [];
        foreach ($collect as $row) {
            $col = explode('-', $row);
            $goals = explode(':', $col[6]);
            self::$table[] = [
                'name'             => $col[0],
                'position'         => $col[9] - 1,
                'played'           => $col[2],
                'won'              => $col[3],
                'drawn'            => $col[4],
                'lost'             => $col[5],
                'goals_for'        => $goals[0],
                'goals_against'    => $goals[1],
                'goals_difference' => $col[7],
                'points'           => $col[8],
            ];
        }
    }

    private function tablesFromWikipedia($league, $season, $type)
    {
        $season = explode('-', $season);
        $startSeason = $season[0];
        $endSeason = substr($season[1], -2, 2);
        $url = "https://en.wikipedia.org/wiki/${startSeason}%E2%80%93${endSeason}_${league}";
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $html = curl_exec($c);
        curl_close($c);
        $start = strpos($html, '<table class="wikitable" style="text-align:') + 52;
        $finish = strpos($html, '</table>', $start);
        $table = substr($html, $start, $finish - $start);
        $teams = explode('</tr>', $table);
        array_shift($teams);
        array_pop($teams);
        self::$table = [];
        foreach ($teams as $row) {
            $col = explode('</td>', $row);
            if (count($col) >= 9) {
                $positionAndClub = trim(strip_tags(preg_replace("/\n/", " ", $col[0])));

                $length = strlen($positionAndClub);

                if (strpos($positionAndClub, '(') != NULL) {
                    $length = strpos($positionAndClub, '(') - strpos($positionAndClub, ' ');
                };

                self::$table[] = [
                    'position'         => substr($positionAndClub, '0', strpos($positionAndClub, ' ')),
                    'name'             => camel_case(html_entity_decode(trim(substr($positionAndClub, strpos($positionAndClub, ' '), $length)))),
                    'played'           => camel_case(html_entity_decode(strip_tags(preg_replace("/\n/", "", $col[1])))),
                    'won'              => camel_case(html_entity_decode(strip_tags(preg_replace("/\n/", "", $col[2])))),
                    'drawn'            => camel_case(html_entity_decode(strip_tags(preg_replace("/\n/", "", $col[3])))),
                    'lost'             => camel_case(html_entity_decode(strip_tags(preg_replace("/\n/", "", $col[4])))),
                    'goals_for'        => camel_case(html_entity_decode(strip_tags(preg_replace("/\n/", "", $col[5])))),
                    'goals_against'    => camel_case(html_entity_decode(strip_tags(preg_replace("/\n/", "", $col[6])))),
                    'goals_difference' => camel_case(html_entity_decode(strip_tags(preg_replace("/\n/", "", $col[7])))),
                    'points'           => camel_case(html_entity_decode(strip_tags(preg_replace("/\n/", "", $col[8])))),
                ];
            }
        }
    }

    private function tablesFromGoal($league, $season, $type)
    {
        $url = "http://www.goal.com/en/${league}/table/2kwbbcootiqqgmrzs6o5inle5";
        $document = new \DOMDocument();
        libxml_use_internal_errors(TRUE);
        $document->loadHTML(file_get_contents($url));
        $xpath = new \DOMXPath($document);
        $teams = explode('    ', $xpath->query("//div[@class='table__body']")[0]->nodeValue);
        foreach ($teams as $team) {
            $items = explode(' ', preg_replace("/ {2,}/", " ", trim($team)));
            self::$table[] = [
                'name'             => $items[1],
                'position'         => $items[0],
                'played'           => $items[2],
                'won'              => $items[3],
                'drawn'            => $items[4],
                'lost'             => $items[5],
                'goals_for'        => '',
                'goals_against'    => '',
                'goals_difference' => $items[6],
                'points'           => $items[7],
            ];
        }
    }

    private function tablesFromVarzesh3($league, $season, $type)
    {
        if ($league == LeaguesConstants::CALCIO) {
            $url = 'https://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D8%A7%DB%8C%D8%AA%D8%A7%D9%84%DB%8C%D8%A7-2018-2019-%D8%B3%D8%B1%DB%8C-%D8%A2';
        }
        if ($league == LeaguesConstants::PREMIERLEAGUE) {
            $url = 'https://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D8%A7%D9%86%DA%AF%D9%84%DB%8C%D8%B3-2018-2019-%D9%84%DB%8C%DA%AF-%D8%A8%D8%B1%D8%AA%D8%B1';
        }
        if ($league == LeaguesConstants::LALIGA) {
            $url = 'https://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D8%A7%D8%B3%D9%BE%D8%A7%D9%86%DB%8C%D8%A7-2018-2019-%D9%84%D8%A7%D9%84%DB%8C%DA%AF%D8%A7';
        }
        if ($league == LeaguesConstants::BUNDESLIGA) {
            $url = 'https://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D8%A2%D9%84%D9%85%D8%A7%D9%86-2018-2019-%D8%A8%D9%88%D9%86%D8%AF%D8%B3%D9%84%DB%8C%DA%AF%D8%A7';
        }
        if ($league == LeaguesConstants::KHALIGEFARS) {
            $url = 'https://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D9%84%DB%8C%DA%AF-%D8%A8%D8%B1%D8%AA%D8%B1-98-97';
        }
        if ($league == LeaguesConstants::AZADEGAN) {
            $url = 'https://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D9%84%D9%8A%DA%AF-%D8%A2%D8%B2%D8%A7%D8%AF%DA%AF%D8%A7%D9%86-97-98-%D9%84%DB%8C%DA%AF-%D8%A2%D8%B2%D8%A7%D8%AF%DA%AF%D8%A7%D9%86';
        }
        if ($league == LeaguesConstants::LOSHAMPIONE) {
            $url = 'https://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D9%81%D8%B1%D8%A7%D9%86%D8%B3%D9%87-2018-2019-%D9%84%D9%88%D8%B4%D8%A7%D9%85%D9%BE%DB%8C%D9%88%D9%86%D8%A7';
        }
        if ($league == LeaguesConstants::EREDIVISIE) {
            $url = 'https://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D9%84%DB%8C%DA%AF-%D8%A8%D8%B1%D8%AA%D8%B1-%D9%87%D9%84%D9%86%D8%AF-2018-2019';
        }

        $methodName = "varzesh3${type}";
        return $this->$methodName($url);
    }

    private function varzesh3DOMXPATH($url)
    {
        $document = new \DOMDocument();
        libxml_use_internal_errors(TRUE);
        $document->loadHTML(file_get_contents($url));
        libxml_clear_errors();
        $xpath = new \DOMXPath($document);
        $teams = $xpath->query("//tbody/tr");
        self::$table = [];
        foreach ($teams as $team) {

            $team = preg_replace("/ {2,}/", "|", trim(str_replace("\r\n", '', $team->nodeValue)));

            $items = explode('|', $team);

            self::$table[] = [
                'name'             => $items[1],
                'position'         => $items[0],
                'played'           => $items[2],
                'won'              => $items[3],
                'drawn'            => $items[4],
                'lost'             => $items[5],
                'goals_for'        => $items[6],
                'goals_against'    => $items[7],
                'goals_difference' => $items[8],
                'points'           => $items[9],
            ];

        }
    }

    private function varzesh3CURL($url)
    {
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $html = curl_exec($c);
        $start = strpos($html, '<tbody>') + 7;
        $finish = strpos($html, '</tbody>');
        $table = substr($html, $start, $finish - $start);
        $teams = explode('                                                                        ', strip_tags(str_replace("\r\n", '', $table)));
        self::$table = [];
        foreach ($teams as $team) {
            $row = preg_replace("/ {2,}/", "|", trim($team));
            $col = explode('|', $row);
            self::$table[] = [
                'name'             => $col[1],
                'position'         => $col[0],
                'played'           => $col[2],
                'won'              => $col[3],
                'drawn'            => $col[4],
                'lost'             => $col[5],
                'goals_for'        => $col[6],
                'goals_against'    => $col[7],
                'goals_difference' => $col[8],
                'points'           => $col[9],
            ];
        }
        if (curl_error($c)) {
            $error = curl_error($c);
            $message = "update table by curl fails because of: {$error}";
            Log::error($message);
            return abort(500, $message);
        }
        curl_close($c);
    }

    public function navad($league, $foo)
    {
        $cookie_curl = curl_init();

        curl_setopt_array($cookie_curl, [
            CURLOPT_URL            =>
                LeaguesConstants::$Navad['BASE_URL'] .
                LeaguesConstants::$Navad['COOKIE_PATH']
            ,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HEADER         => TRUE,
        ]);

        $response = curl_exec($cookie_curl);

        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $response, $matches);

        parse_str($matches[1][0], $cookie);

        curl_close($cookie_curl);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            =>
                LeaguesConstants::$Navad['BASE_URL'] .
                LeaguesConstants::$Navad['LEAGUE_PATH'] .
                DIRECTORY_SEPARATOR .
                $league['ID'] .
                DIRECTORY_SEPARATOR .
                $foo
            ,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "cookie: {$matches[1][2]}; {$matches[1][0]}; {$matches[1][1]}",
                "x-xsrf-token: {$cookie['XSRF-TOKEN']}",
            ],
        ]);

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response, TRUE);
        }
    }

    public function scoreboard($path, $type, $flag = FALSE)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => LeaguesConstants::$ScoreBoard['BASE_URL'] . DIRECTORY_SEPARATOR . $path,
//            CURLOPT_PROXY          => env('NEXTVPN_HOST') . ':' . env('NEXTVPN_PORT'),
//            CURLOPT_PROXYUSERPWD   => env('NEXTVPN_USERNAME') . ':' . env('NEXTVPN_PASSWORD'),
//            CURLOPT_PROXYTYPE      => CURLPROXY_SOCKS5,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING       => "UTF-8",
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "x-fsign: SW9D1eZo",
            ],
        ]);

        $result = curl_exec($curl);

        curl_close($curl);

        $rows = explode('<tr class="', str_replace("&nbsp;", '', $result));

        array_shift($rows);

        $response = [];

        foreach ($rows as $row) {

            if (strpos($row, 'data-type="participant_name">Ranking')) {
                break;
            }

            if (!strpos($row, 'team_name_span')) {

                if ($flag) {
                    $cols = explode('<td', substr($row, strpos($row, '<td')));
                    self::$group = substr(substr(strip_tags($cols[0]), strpos(strip_tags($cols[0]), '>')), 8, 1);
                }

                continue;
            }

            $cols = explode('<td', substr($row, strpos($row, '<td')));

            if ($type == 'scorers') {

                $name = strip_tags($cols[2]);

                $club = strip_tags($cols[3]);

                $count_scores = strip_tags($cols[4]);

                $count_assists = isset($cols[5]) ? strip_tags($cols[5]) : '';

                $response[] = [
                    'name'          => substr(substr($name, strpos($name, '>')), 1),
                    'club'          => substr(substr($club, strpos($club, '>')), 1),
                    'count_scores'  => substr(substr($count_scores, strpos($count_scores, '>')), 1),
                    'count_assists' => substr(substr($count_assists, strpos($count_assists, '>')), 1) ?: NULL,
                ];

            }

            if ($type == 'tables') {

                $position = strip_tags($cols[1]);

                $club = strip_tags($cols[2]);

                $played = strip_tags($cols[3]);

                $won = strip_tags($cols[4]);

                $drawn = strip_tags($cols[5]);

                $lost = strip_tags($cols[6]);

                $goals = strip_tags($cols[7]);

                $goals = explode(':', substr(substr($goals, strpos($goals, '>')), 1));

                $points = strip_tags($cols[8]);

                $response[] = [
                    'position'         => strtok(substr(substr($position, strpos($position, '>')), 1), '.'),
                    'name'             => substr(substr($club, strpos($club, '>')), 1),
                    'played'           => substr(substr($played, strpos($played, '>')), 1),
                    'won'              => substr(substr($won, strpos($won, '>')), 1),
                    'drawn'            => substr(substr($drawn, strpos($drawn, '>')), 1),
                    'lost'             => substr(substr($lost, strpos($lost, '>')), 1),
                    'goals_for'        => $goals[0],
                    'goals_against'    => $goals[1],
                    'goals_difference' => $goals[0] - $goals[1],
                    'points'           => substr(substr($points, strpos($points, '>')), 1),
                ];

                if ($flag) {
                    $response[] = array_merge(array_pop($response), ['group' => self::$group]);
                }

            }

        }

        return $response;
    }

    public function top90()
    {
        $curl = curl_init();

        $season = '2008-2009';

        curl_setopt_array($curl, [
            CURLOPT_URL            => "http://www.top90.ir/iran/persian-gulf-league/?tpl=season&year={$season}",
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.99 Safari/537.36",
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        $response = preg_replace('/\n|\t/', '', $response);

        preg_match('/(<table)[\s\S]*(<\/table>)/', $response, $matches);

        $rows = explode('</tr>', $matches[0]);

        array_shift($rows);

        array_pop($rows);

        $response = [];
        foreach ($rows as $row) {

            $cols = explode('</td>', strip_tags($row, '<td>'));

            array_pop($cols);

            $tr = [];

            foreach ($cols as $col) {
                $tr[] = strip_tags($col);
            }

            DB::table('khaligefars_table')
              ->updateOrInsert([
                  'name'   => $tr[1],
                  'season' => $season,
              ], [
                  'name'             => $tr[1],
                  'position'         => $tr[0],
                  'played'           => $tr[2],
                  'won'              => $tr[3],
                  'drawn'            => $tr[4],
                  'lost'             => $tr[5],
                  'goals_for'        => $tr[6],
                  'goals_against'    => $tr[7],
                  'goals_difference' => $tr[8],
                  'points'           => $tr[9],
                  'updated_at'       => Carbon::now(),
                  'season'           => $season,
              ]);

            $response[] = $tr;
        }


        dd($response);


    }

    public function scoreboardPlayOffs($path)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => LeaguesConstants::$ScoreBoard['BASE_URL'] . DIRECTORY_SEPARATOR . $path,
//            CURLOPT_PROXY          => env('NEXTVPN_HOST') . ':' . env('NEXTVPN_PORT'),
//            CURLOPT_PROXYUSERPWD   => env('NEXTVPN_USERNAME') . ':' . env('NEXTVPN_PASSWORD'),
//            CURLOPT_PROXYTYPE      => CURLPROXY_SOCKS5,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING       => "UTF-8",
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => [
                "x-fsign: SW9D1eZo",
            ],
        ]);
        $result = curl_exec($curl);
        curl_close($curl);
        $html = "
            <!doctype html>
            <html lang=\"en\">
                <head>
                    <meta charset=\"UTF - 8\">
                </head>
                <body>{$result}</body>
            </html>
        ";
        $document = new \DOMDocument();
        libxml_use_internal_errors(TRUE);
        $document->loadHTML($html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($document);
        $fixtures = $xpath->query("//div[@title='Click to view a list of matches']");
        $response = [];
        foreach ($fixtures as $fixture) {
            $odd = preg_split('/:/', ($fixture->childNodes[2]->childNodes[0]->childNodes[0]->childNodes[0]->childNodes[2]->nodeValue));
            if ($fixture->childNodes[2]->childNodes[0]->childNodes->length != 1){
                $even = preg_split('/:/', ($fixture->childNodes[2]->childNodes[0]->childNodes[1]->childNodes[0]->childNodes[2]->nodeValue));
                if (strpos('PEN', $even[1])){
                }
                $response[] = [
                    'odd'  => [
                        'timestamp'  => $fixture->childNodes[2]->childNodes[0]->childNodes[0]->childNodes[0]->childNodes[0]->nodeValue,
                        'host'       => $fixture->childNodes[0]->childNodes[0]->nodeValue,
                        'guest'      => $fixture->childNodes[1]->childNodes[0]->nodeValue,
                        'hostPoint'  => $odd[0],
                        'guestPoint' => $odd[1],
                    ],
                    'even' => [
                        'timestamp'  => $fixture->childNodes[2]->childNodes[0]->childNodes[1]->childNodes[0]->childNodes[0]->nodeValue,
                        'host'       => $fixture->childNodes[1]->childNodes[0]->nodeValue,
                        'guest'      => $fixture->childNodes[0]->childNodes[0]->nodeValue,
                        'hostPoint'  => $even[0],
                        'guestPoint' => $even[1],
                    ],
                ];
            }
            else{
                $response[] = [
                    'odd'  => [
                        'timestamp'  => $fixture->childNodes[2]->childNodes[0]->childNodes[0]->childNodes[0]->childNodes[0]->nodeValue,
                        'host'       => $fixture->childNodes[0]->childNodes[0]->nodeValue,
                        'guest'      => $fixture->childNodes[1]->childNodes[0]->nodeValue,
                        'hostPoint'  => $odd[0],
                        'guestPoint' => $odd[1],
                    ]
                ];
            }
        }
        return $response;
    }
}
