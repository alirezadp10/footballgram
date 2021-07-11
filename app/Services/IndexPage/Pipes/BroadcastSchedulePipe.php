<?php

namespace App\Services\IndexPage\Pipes;

use Closure;
use Facades\App\Repositories\Contracts\BroadcastScheduleRepository;

class BroadcastSchedulePipe
{
    public function handle($data, Closure $next)
    {
        $data['broadcastSchedule'] = BroadcastScheduleRepository::all();

        return $next($data);
    }
}
