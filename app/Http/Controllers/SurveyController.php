<?php

namespace App\Http\Controllers;

use App\Http\Requests\SurveyRequest;
use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function show(Survey $survey)
    {
        $response['survey'] = $survey;

        $response['surveySelectedOption'] = $survey?->votes()->where('user_id', auth()->id())->first()?->option;

        return response()->json($response);
    }

    public function vote(Request $request, $survey)
    {
        auth()->user()->votes()->toggle($survey->id, $this->validate($request, [
            'option' => 'required',
        ]));

        return response()->json(['message' => 'success']);
    }

    public function store(SurveyRequest $request)
    {
        Survey::create($request->validated());

        session()->flash('message.type', 'success');
        session()->flash('message.content', 'با موفقیت انجام شد.');
        session()->flash('message.time', '10');

        return back();
    }
}
