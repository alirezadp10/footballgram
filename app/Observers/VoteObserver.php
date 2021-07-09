<?php

namespace App\Observers;

use App\Models\Vote;

class VoteObserver
{
    public function created(Vote $vote)
    {
        $options = $vote->survey->options;

        $options[$vote->option - 1]['count'] + 1;

        $vote->survey()->update($options);
    }

    public function deleted(Vote $vote)
    {
        $options = $vote->survey->options;

        $options[$vote->option - 1]['count'] - 1;

        $vote->survey()->update($options);
    }
}
