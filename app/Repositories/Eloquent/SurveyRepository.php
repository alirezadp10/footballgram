<?php

namespace App\Repositories\Eloquent;

use App\Models\Survey;
use App\Repositories\Contracts\SurveyRepository as SurveyContract;

class SurveyRepository extends BaseRepository implements SurveyContract
{
    public function __construct(Survey $survey)
    {
        $this->model = $survey;
    }
}
