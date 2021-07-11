<?php

namespace App\Console\Commands;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;

class deletePreviewPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete-preview-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete posts where the status is pending and 1 hour has elapsed since it was created';

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
        Post::where('status', 'PENDING')
            ->where('created_at', '<', Carbon::now()->subHour())
            ->each(function ($item) {
                $item->delete();
            });
    }
}
