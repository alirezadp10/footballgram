<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompetitionRequest;
use App\Services\IndexPage\Pipes\BroadcastSchedulePipe;
use App\Services\IndexPage\Pipes\ChiefChoicesPipe;
use App\Services\IndexPage\Pipes\CompetitionNewsPipe;
use App\Services\IndexPage\Pipes\HotNewsPipe;
use App\Services\IndexPage\Pipes\LastNewsPipe;
use App\Services\IndexPage\Pipes\MostFollowedUsersPipe;
use App\Services\IndexPage\Pipes\SliderContentsPipe;
use App\Services\IndexPage\Pipes\SurveyPipe;
use App\Services\IndexPage\Pipes\TrendTagsPipe;
use Facades\App\Repositories\Contracts\CompetitionRepository;
use Facades\App\Repositories\Contracts\TagRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller
{
    public function index()
    {
        $response = app(Pipeline::class)->send([])->through([
            TrendTagsPipe::class,
            SliderContentsPipe::class,
            LastNewsPipe::class,
            MostFollowedUsersPipe::class,
            CompetitionNewsPipe::class,
            HotNewsPipe::class,
            ChiefChoicesPipe::class,
            SurveyPipe::class,
            BroadcastSchedulePipe::class,
        ])->thenReturn();

        $response['manageBroadcastSchedule'] = Gate::allows('manage-broadcast-schedule');

        $response['manageSurvey'] = Gate::allows('manage-survey');

        return view('index.index', compact('response'));
    }

    public function standing(CompetitionRequest $request)
    {
        $competition = CompetitionRepository::retrieveByName($request->competition);

        $response = CompetitionRepository::retrieveStanding($competition, $request->season);

        return response()->json($response);
    }

    public function scorers(CompetitionRequest $request)
    {
        $competition = CompetitionRepository::retrieveByName($request->competition);

        $response = CompetitionRepository::retrieveScorers($competition, $request->season);

        return response()->json($response);
    }

    public function fixtures(CompetitionRequest $request)
    {
        $competition = CompetitionRepository::retrieveByName($request->competition);

        $response['lastWeek'] = CompetitionRepository::lastWeek($competition, $request->season);

        if (isset($request->filter['type'])) {
            if ($request->filter['type'] == 'week') {
                $response['selectedWeek'] = $request->filter['value'];
            }

            if ($request?->filter['type'] == 'club') {
                $response['selectedClub'] = $request->filter['value'];
            }
        }

        $response['fixtures'] = CompetitionRepository::retrieveFixtures($competition, $request->season, $request->filter, $response['lastWeek']);

        $response['clubs'] = CompetitionRepository::clubs($competition, $request->season);

        return response()->json($response);
    }

    public function news(Request $request)
    {
        $response = TagRepository::relatedNews($request->tag);

        return response()->json($response);
    }
}
