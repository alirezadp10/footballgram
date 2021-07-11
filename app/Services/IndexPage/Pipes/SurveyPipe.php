<?php

namespace App\Services\IndexPage\Pipes;

use App\Models\Survey;
use Closure;

class SurveyPipe
{
    public function handle($data, Closure $next)
    {
        $survey = Survey::latest()->first();

        $data['survey'] = $survey;

        $data['surveySelectedOption'] = $survey?->votes()->where('user_id', auth()->id())->first()?->option;

        return $next($data);
    }
}
