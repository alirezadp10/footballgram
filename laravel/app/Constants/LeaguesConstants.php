<?php
/**
 * Created by PhpStorm.
 * User: majid
 * Date: 12/20/17
 * Time: 2:18 PM
 */

namespace App\Constants;

class LeaguesConstants
{
    const WIKIPEDIA        = 'WIKIPEDIA';
    const GOAL             = 'GOAL';
    const FOOTBALLDATABASE = 'FOOTBALLDATABASE';
    const PREMIERLEAGUE    = 'PREMIERLEAGUE';
    const WORLDFOOTBALL    = 'WORLDFOOTBALL';
    const CALCIO           = 'CALCIO';
    const EREDIVISIE       = 'EREDIVISIE';
    const LOSHAMPIONE      = 'LOSHAMPIONE';
    const STARSLEAGUE      = 'STARSLEAGUE';
    const BUNDESLIGA       = 'BUNDESLIGA';
    const LALIGA           = 'LALIGA';
    const KHALIGEFARS      = 'KHALIGEFARS';
    const AZADEGAN         = 'AZADEGAN';
    const VARZESH3         = 'VARZESH3';

    public static $Source = [
        LeaguesConstants::GOAL             => "Goal",
        LeaguesConstants::WORLDFOOTBALL    => "WorldFootball",
        LeaguesConstants::FOOTBALLDATABASE => "FootballDatabase",
        LeaguesConstants::WIKIPEDIA        => "Wikipedia",
        LeaguesConstants::VARZESH3         => "Varzesh3",
    ];

    public static $Goal = [
        LeaguesConstants::PREMIERLEAGUE => "premier-league",
    ];

    public static $WorldFootball = [
        LeaguesConstants::PREMIERLEAGUE => "eng-premier-league",
        LeaguesConstants::CALCIO        => "ita-serie-a",
        LeaguesConstants::EREDIVISIE    => "ned-eredivisie",
        LeaguesConstants::LOSHAMPIONE   => "fra-ligue-1",
        LeaguesConstants::STARSLEAGUE   => "qat-qatar-stars-league",
        LeaguesConstants::BUNDESLIGA    => "bundesliga",
        LeaguesConstants::LALIGA        => "esp-primera-division",
        LeaguesConstants::KHALIGEFARS   => "irn-persian-gulf-pro-league",
    ];

    public static $Wikipedia = [
        LeaguesConstants::CALCIO => "Serie_A",
        LeaguesConstants::LALIGA => "La_Liga",

        LeaguesConstants::PREMIERLEAGUE => "eng-premier-league",
        LeaguesConstants::EREDIVISIE    => "ned-eredivisie",
        LeaguesConstants::LOSHAMPIONE   => "fra-ligue-1",
        LeaguesConstants::STARSLEAGUE   => "qat-qatar-stars-league",
        LeaguesConstants::BUNDESLIGA    => "bundesliga",
        LeaguesConstants::KHALIGEFARS   => "irn-persian-gulf-pro-league",
    ];

    public static $FootballDatabase = [
        LeaguesConstants::PREMIERLEAGUE => "england-premier-league",
        LeaguesConstants::CALCIO        => "italy-serie-a",
        LeaguesConstants::EREDIVISIE    => "netherlands-eredivisie",
        LeaguesConstants::LOSHAMPIONE   => "france-ligue-1",
        LeaguesConstants::STARSLEAGUE   => "qatar-stars-league",
        LeaguesConstants::BUNDESLIGA    => "germany-bundesliga",
        LeaguesConstants::LALIGA        => "spain-liga-bbva",
        LeaguesConstants::KHALIGEFARS   => "iran-persian-gulf-league",
    ];

    public static $Navad = [
        'BASE_URL'              => 'https://2018.90tv.ir',
        'LEAGUE_PATH'           => '/api/web/tournaments',
        'COOKIE_PATH'           => '/leagues',
        'SCORERS'               => 'top-scorers',
        'TABLE'                 => 'standing',
        'FIXTURES'              => 'stages',
        'KHALIGEFARS'           => [
            'ID' => '12925',
        ],
        'AZADEGAN'              => [
            'ID' => '13070',
        ],
        'AFC_CHAMPIONS_LEAGUE'  => [
            'ID'  => '12344',
            'GPA' => '853459',
            'GPB' => '853460',
            'GPC' => '853461',
            'GPD' => '853462',
            'GPE' => '853466',
            'GPF' => '853463',
            'GPG' => '853464',
            'GPH' => '853465',
        ],
        'LALIGA'                => [
            'ID' => '13025',
        ],
        'BUNDESLIGA'            => [
            'ID' => '12876',
        ],
        'CALCIO'                => [
            'ID' => '13037',
        ],
        'PREMIERLEAGUE'         => [
            'ID' => '12776',
        ],
        'LOSHAMPIONE'           => [
            'ID' => '12737',
        ],
        'AFC_ASIAN_CUP'         => [
            'ID'  => '12640',
            'GPA' => '855193',
            'GPB' => '855194',
            'GPC' => '855195',
            'GPD' => '855196',
            'GPE' => '855205',
            'GPF' => '855206',
        ],
        'UEFA_CHAMPIONS_LEAGUE' => [
            'ID'  => '12764',
            'GPA' => '857029',
            'GPB' => '857030',
            'GPC' => '857031',
            'GPD' => '857032',
            'GPE' => '857033',
            'GPF' => '857034',
            'GPG' => '857035',
            'GPH' => '857036',
        ],
        'EUROPE_LEAGUE'         => [
            'ID'  => '12765',
            'GPA' => '857058',
            'GPB' => '857059',
            'GPC' => '857060',
            'GPD' => '857061',
            'GPE' => '857062',
            'GPF' => '857064',
            'GPG' => '857063',
            'GPH' => '857065',
            'GPI' => '857066',
            'GPJ' => '857068',
            'GPK' => '857069',
            'GPL' => '857069',
        ],
        'STARSLEAGUE'           => [
            'ID' => '12933',
        ],
    ];

    public static $ScoreBoard = [
        'BASE_URL' => 'https://d.scoreboard.com/en/x/feed',
    ];

    public static $Footballi = [
        'BASE_URL'              => 'http://api.footballi.net',
        'LEAGUE_PATH'           => '/api/v2/competition/',
        'SCORERS'               => '/topscorers',
        'TABLE'                 => '/standings',
        'FIXTURES'              => '/matches/week',
        'KHALIGEFARS'           => [
            'ID' => '14',
        ],
        'AZADEGAN'              => [
            'ID' => '15',
        ],
        'AFC_CHAMPIONS_LEAGUE'  => [
            'ID' => '25',
        ],
        'LALIGA'                => [
            'ID' => '21',
        ],
        'BUNDESLIGA'            => [
            'ID' => '12',
        ],
        'CALCIO'                => [
            'ID' => '17',
        ],
        'PREMIERLEAGUE'         => [
            'ID' => '9',
        ],
        'LOSHAMPIONE'           => [
            'ID' => '11',
        ],
        'UEFA_CHAMPIONS_LEAGUE' => [
            'ID' => '3',
        ],
        'EUROPE_LEAGUE'         => [
            'ID' => '4',
        ],
        'EREDIVISIE'            => [
            'ID' => '18',
        ],
    ];

}
