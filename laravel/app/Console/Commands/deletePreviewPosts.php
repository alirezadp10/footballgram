<?php

namespace App\Console\Commands;

use App\News;
use App\UserContent;
use Carbon\Carbon;
use Illuminate\Console\Command;

class deletePreviewPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deletePreviewPosts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete posts where the status is interrupted and 1 hour has elapsed since it was created';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        News::where('status', 'PENDING')
            ->where('created_at', '<', Carbon::now()->subHour(1))
            ->each(function ($item) {
                $item->delete();
            });

        UserContent::where('status', 'PENDING')
                   ->where('created_at', '<', Carbon::now()->subHour(1))
                   ->each(function ($item) {
                       $item->delete();
                   });
    }
}
