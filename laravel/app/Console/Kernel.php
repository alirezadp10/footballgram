<?php

namespace App\Console;

use App\Console\Commands\afcAsianCup;
use App\Console\Commands\afcChampionsLeague;
use App\Console\Commands\azadegan;
use App\Console\Commands\bundesliga;
use App\Console\Commands\calcio;
use App\Console\Commands\coppaItalia;
use App\Console\Commands\deletePreviewPosts;
use App\Console\Commands\dfbPokal;
use App\Console\Commands\eredivisie;
use App\Console\Commands\europeLeague;
use App\Console\Commands\europeNationsLeague;
use App\Console\Commands\faCup;
use App\Console\Commands\footballi;
use App\Console\Commands\khaligefars;
use App\Console\Commands\laliga;
use App\Console\Commands\loshampione;
use App\Console\Commands\premierleague;
use App\Console\Commands\starsleague;
use App\Console\Commands\tarafdari;
use App\Console\Commands\uefaChampionsLeague;
use App\Console\Commands\uefaEuro;
use App\Console\Commands\updateFixturesLeagues;
use App\Console\Commands\updateScorersLeagues;
use App\Console\Commands\updateTableLeagues;
use App\Console\Commands\worldCup;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        deletePreviewPosts::class,
        afcAsianCup::class,
        afcChampionsLeague::class,
        azadegan::class,
        bundesliga::class,
        calcio::class,
        coppaItalia::class,
        dfbPokal::class,
        eredivisie::class,
        europeLeague::class,
        europeNationsLeague::class,
        faCup::class,
        khaligefars::class,
        laliga::class,
        loshampione::class,
        premierleague::class,
        starsleague::class,
        uefaChampionsLeague::class,
        uefaEuro::class,
        worldCup::class,
        footballi::class,
        tarafdari::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('command:deletePreviewPosts')
                 ->hourly();

        $schedule->command('command:afc-asian-cup')
                 ->hourly();

        $schedule->command('command:afc-champions-league')
                 ->hourly();

        $schedule->command('command:azadegan')
                 ->hourly();

        $schedule->command('command:bundesliga')
                 ->hourly();

        $schedule->command('command:calcio')
                 ->hourly();

        $schedule->command('command:coppa-italia')
                 ->hourly();

        $schedule->command('command:dfb-pokal')
                 ->hourly();

        $schedule->command('command:eredivisie')
                 ->hourly();

        $schedule->command('command:europe-league')
                 ->hourly();

        $schedule->command('command:europe-nations-league')
                 ->hourly();

        $schedule->command('command:fa-cup')
                 ->hourly();

        $schedule->command('command:khaligefars')
                 ->hourly();

        $schedule->command('command:laliga')
                 ->hourly();

        $schedule->command('command:premierleague')
                 ->hourly();

        $schedule->command('command:starsleague')
                 ->hourly();

        $schedule->command('command:uefa-champions-league')
                 ->hourly();

        $schedule->command('command:uefa-euro')
                 ->hourly();

        $schedule->command('command:world-cup')
                 ->hourly();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
