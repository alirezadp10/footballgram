<?php

namespace App\Http\Controllers;

use App\BroadcastSchedule;
use App\Constants\TagConstants;
use App\Helpers\MainHelper;
use App\News;
use App\Survey;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Facades\jDate;

class IndexController extends Controller
{
    private $mainHelper;

    public function __construct(MainHelper $mainHelper)
    {
        $this->mainHelper = $mainHelper;
    }

    public function index()
    {
        $trends = Tag::trends(Carbon::now()->subYear(2), 10);
        $response['trends'] = [];
        foreach ($trends as $trend) {
            $response['trends'][] = [
                'name'  => "#{$trend->name}",
                'count' => $trend->count,
                'url'   => route('tags.show', [$trend->name]),
            ];
        }

        $slider = News::slider();
        $response['slider'] = [];
        foreach ($slider as $news) {
            $response['slider'][] = [
                'mainTitle'      => $news->main_title,
                'secondaryTitle' => $news->secondary_title,
                'concatTitle'    => $news->main_title . ' ؛ ' . $news->secondary_title,
                'firstTag'       => !$news->first_tag ? NULL : "#{$news->first_tag}",
                'firstTagURL'    => route('tags.show', [$news->first_tag]),
                'secondTag'      => !$news->second_tag ? NULL : "#{$news->second_tag}",
                'secondTagURL'   => route('tags.show', [$news->second_tag]),
                'thirdTag'       => !$news->third_tag ? NULL : "#{$news->third_tag}",
                'thirdTagURL'    => route('tags.show', [$news->third_tag]),
                'forthTag'       => !$news->forth_tag ? NULL : "#{$news->forth_tag}",
                'forthTagURL'    => route('tags.show', [$news->forth_tag]),
                'thumbnailImage' => Storage::url($news->sm),
                'mainImage'      => Storage::url($news->lg),
                'newsURL'        => route('news.show', [$news->slug]),
            ];
        }

        $lastNews = News::lastNews();
        $response['lastNews'] = [];
        foreach ($lastNews as $news) {
            $separator = $news->secondaryTitle ? '؛' : '';
            $response['lastNews'][] = [
                'title' => "{$news->mainTitle} {$separator} {$news->secondaryTitle}",
                'time'  => Carbon::parse($news->created_at)
                                 ->format('H:i'),
                'url'   => route('news.show', [$news->slug]),
            ];
        }

        $hotNews = News::hotNews();
        $response['hotNews'] = [];
        foreach ($hotNews as $news) {
            $separator = $news->secondaryTitle ? '؛' : '';
            $response['hotNews'][] = [
                'title' => "{$news->mainTitle} {$separator} {$news->secondaryTitle}",
                'time'  => Carbon::parse($news->created_at)
                                 ->format('H:i'),
                'url'   => route('news.show', [$news->slug]),
            ];
        }

        $leagueNews = Tag::getNewsRelatedToTagWithoutImage('خلیج_فارس', 15);
        $response['leagueNews'] = [];
        foreach ($leagueNews as $news) {
            $separator = $news->secondaryTitle ? '؛' : '';
            $response['leagueNews'][] = [
                'title' => "{$news->mainTitle} {$separator} {$news->secondaryTitle}",
                'time'  => Carbon::parse($news->created_at)
                                 ->format('H:i'),
                'url'   => route('news.show', [$news->slug]),
            ];
        }

        $response['leagueNewsClubs'] = TagConstants::KHALIGEFARS['clubs'];

        $users = User::getUsersHaveMustFollower(5);
        $response['usersHaveMustFollower'] = [];
        foreach ($users as $user) {
            $response['usersHaveMustFollower'][] = [
                'name'            => $user->name,
                'countFollowers'  => $user->count_followers,
                'countFollowings' => $user->count_followings,
                'countPosts'      => $user->count_posts,
                'avatar'          => $user->avatar ? Storage::url($user->avatar) : '/images/userPhoto.png',
                'url'             => route('users.show', [
                    'id'   => $user->id,
                    'name' => $user->name,
                ]),
            ];
        }

        $broadcastSchedule = BroadcastSchedule::schedule();
        $response['broadcastSchedule'] = [];
        foreach ($broadcastSchedule as $item) {
            $response['broadcastSchedule'][] = [
                'id'       => $item->id,
                'host'     => $item->host,
                'guest'    => $item->guest,
                'title'    => "{$item->host} - {$item->guest}",
                'datetime' => $item->datetime,
                'time'     => jDate::forge($item->datetime)
                                   ->format('l ، j F  - ساعت: H:i'),
                'image'    => "/images/{$item->broadcast_channel}.png",
                'alt'      => $item->broadcast_channel,
            ];
        }

        $response['survey'] = [];
        $survey = Survey::getLastSurvey();
        if (count($survey)) {
            $survey = $survey[0];
            $selection = [];
            if (Auth::check()) {
                $selection = Survey::authSelection($survey['id']);
            }
            $response['survey'] = [
                'id'       => $survey['id'],
                'question' => $survey['question'],
            ];
            $options = json_decode($survey['options'], TRUE);
            foreach ($options as $key => $value) {
                $response['survey']['options'][] = [
                    'title'    => $value['title'],
                    'count'    => $value['count'],
                    'selected' => in_array($key, $selection) ? TRUE : FALSE,
                ];
            }
        }

        $response['chiefChoices'] = [];
        $chief_choices = News::chiefChoice();
        if (count($chief_choices)) {
            foreach ($chief_choices as $chief_choice) {
                $separator = $chief_choice->secondary_title ? '؛' : '';
                $response['chiefChoices'][] = [
                    'title'   => "{$chief_choice->main_title} {$separator} {$chief_choice->secondary_title}",
                    'image'   => Storage::url($chief_choice->image),
                    'newsURL' => route('news.show', [$chief_choice->slug]),
                ];
            }
        }

        $response['manageBroadcastSchedule'] = FALSE;

        $response['manageSurvey'] = FALSE;

        if (Gate::allows('manage-broadcast-schedule')) {
            $response['manageBroadcastSchedule'] = TRUE;
        }

        if (Gate::allows('manage-survey')) {
            $response['manageSurvey'] = TRUE;
        }

        return view('welcome.welcome', compact('response'));
    }

    private function season()
    {
        return Carbon::now()->month > 8
            ? Carbon::now()->year . '-' . Carbon::now()
                                                ->addYear()->year
            : Carbon::now()->year . '-' . Carbon::now()
                                                ->subYear()->year;
    }

    public function news(Request $request)
    {
        $tag = $request->tag;

        $response['categories'] = [];
        if ($request->league == 'khaligefars') {
            $response['categories'] = TagConstants::KHALIGEFARS['clubs'];
            if ($request->tag == 'all') {
                $tag = TagConstants::KHALIGEFARS['tag'];
            }
        }
        if ($request->league == 'premier_league') {
            $response['categories'] = TagConstants::PREMIERLEAGUE['clubs'];
            if ($request->tag == 'all') {
                $tag = TagConstants::PREMIERLEAGUE['tag'];
            }
        }
        if ($request->league == 'calcio') {
            $response['categories'] = TagConstants::CALCIO['clubs'];
            if ($request->tag == 'all') {
                $tag = TagConstants::CALCIO['tag'];
            }
        }
        if ($request->league == 'bundesliga') {
            $response['categories'] = TagConstants::BUNDESLIGA['clubs'];
            if ($request->tag == 'all') {
                $tag = TagConstants::BUNDESLIGA['tag'];
            }
        }
        if ($request->league == 'laliga') {
            $response['categories'] = TagConstants::LALIGA['clubs'];
            if ($request->tag == 'all') {
                $tag = TagConstants::LALIGA['tag'];
            }
        }

        $offset = isset($request->offset) ? $request->offset : 0;

        $news = Tag::getNewsRelatedToTagWithoutImage($tag, 15, $offset);

        $response['news'] = [];
        foreach ($news as $item) {
            $separator = $item->secondaryTitle ? '؛' : '';
            $response['news'][] = [
                'title' => "{$item->mainTitle} {$separator} {$item->secondaryTitle}",
                'time'  => Carbon::parse($item->created_at)
                                 ->format('H:i'),
                'url'   => route('news.show', [$item->slug]),
            ];
        }

        return response()->json($response);
    }

    public function table(Request $request)
    {
        $this->validate($request, [
            'league' => 'required|in:khaligefars,premier_league,calcio,bundesliga,laliga,uefa_champions_league,europe_league,afc_champions_league,europe_nations_league,uefa_euro,afc_asian_cup,loshampione,eredivisie,azadegan,world_cup,stars_league',
            'season' => 'required',
        ]);

        $response = [];

        if (in_array($request->league, [
            'uefa_champions_league',
            'europe_league',
            'afc_champions_league',
            'europe_nations_league',
            'uefa_euro',
            'afc_asian_cup',
            'world_cup',
        ])) {
            $table = DB::table("{$request->league}_table")
                       ->orderBy('group', 'ASC')
                       ->orderBy('position', 'ASC')
                       ->where('season', '=', $request->season)
                       ->get()
                       ->toArray();

            foreach ($table as $club) {
                $response[] = [
                    'name'             => __($club->name),
                    'drawn'            => $club->drawn,
                    'goals_against'    => $club->goals_against,
                    'goals_difference' => $club->goals_difference,
                    'goals_for'        => $club->goals_for,
                    'group'            => $club->group,
                    'lost'             => $club->lost,
                    'played'           => $club->played,
                    'points'           => $club->points,
                    'position'         => $club->position,
                    'season'           => $club->season,
                    'won'              => $club->won,
                ];
            }

        } else {
            $table = DB::table("{$request->league}_table")
                       ->orderBy('position', 'ASC')
                       ->where('season', '=', $request->season)
                       ->get()
                       ->toArray();

            foreach ($table as $club) {
                $response[] = [
                    'name'             => __($club->name),
                    'drawn'            => $club->drawn,
                    'goals_against'    => $club->goals_against,
                    'goals_difference' => $club->goals_difference,
                    'goals_for'        => $club->goals_for,
                    'lost'             => $club->lost,
                    'played'           => $club->played,
                    'points'           => $club->points,
                    'position'         => $club->position,
                    'season'           => $club->season,
                    'won'              => $club->won,
                ];
            }

        }

        return response()->json($response);
    }

    public function scorers(Request $request)
    {
        $this->validate($request, [
            'league' => 'required|in:khaligefars,premier_league,calcio,bundesliga,laliga,uefa_champions_league,europe_league,afc_champions_league,europe_nations_league,uefa_euro,afc_asian_cup,loshampione,eredivisie,azadegan,world_cup,stars_league',
            'season' => 'required',
        ]);

        $scorers = DB::table("{$request->league}_scorers")
                     ->where('season', '=', $request->season)
                     ->orderBy('count_scores', 'DESC')
                     ->get();

        $response = [];

        $fa = json_decode(file_get_contents(resource_path('lang/fa.json')), TRUE);

        $keys = array_keys($fa);

        foreach ($scorers as $scorer) {

            $name = $scorer->name;
            foreach ($keys as $key) {
                //            if (preg_match("/{$substr_name}.*/i", $key)) {
                //                $name = $key;
                //            }
                if (strtolower(trim($name)) == strtolower(trim($key))) {
                    $name = $key;
                }
            }

            $response[] = [
                'id'            => $scorer->id,
                'season'        => $scorer->season,
                'en_club'       => $scorer->club,
                'fa_club'       => __($scorer->club),
                'en_name'       => $scorer->name,
                'fa_name'       => __($name),
                'count_scores'  => $scorer->count_scores,
                'count_assists' => $scorer->count_assists,
                'updated_at'    => $scorer->updated_at,
            ];
        }

        return response()->json($response);
    }

    public function fixtures(Request $request)
    {
        $this->validate($request, [
            'league' => 'required|in:khaligefars,premier_league,calcio,bundesliga,laliga,uefa_champions_league,europe_league,afc_champions_league,europe_nations_league,uefa_euro,afc_asian_cup,loshampione,eredivisie,azadegan,world_cup,stars_league',
        ]);

        $response = [];

        $fixtures = DB::table("{$request->league}_fixtures")
                      ->orderBy('datetime', 'ASC');

        if (isset($request->season)) {
            $fixtures->where('season', '=', $request->season);
        } else {
            $fixtures->where('season', '=', $this->season());
        }

        if (isset($request->filter)) {
            if ($request->filter['type'] == 'period') {
                $response["selectedWeek"] = $request->filter['value'];
                $fixtures->where('period', '=', $request->filter['value']);
            }

            if ($request->filter['type'] == 'club') {
                $response["selectedClub"] = $request->filter['value'];
                $fixtures->where('host', '=', $request->filter['value']);
                $fixtures->orWhere('guest', '=', $request->filter['value']);
            }
        }

        if (!isset($request->filter)) {
            $temp = DB::table("{$request->league}_fixtures")
                      ->orderBy('datetime', 'ASC')
                      ->where('datetime', '>', Carbon::now())
                      ->get()
                      ->toArray();

            $response['currentWeek'] = 1;

            if (count($temp)) {
                $response['currentWeek'] = $temp[0]->period;
            }

            $fixtures->where('period', '=', $response['currentWeek']);
        }

        $fixtures = $fixtures->get();

        foreach ($fixtures as $fixture) {
            $response['fixtures'][] = [
                'host'       => $fixture->host,
                'hostPoint'  => $fixture->final ? $fixture->host_point : '-',
                'guest'      => $fixture->guest,
                'guestPoint' => $fixture->final ? $fixture->guest_point : '-',
                'datetime'   => $this->mainHelper->digitToFarsi(jDate::forge($fixture->datetime)
                                                                     ->format('l ، j F o - ساعت: H:i')),
            ];
        }

        /////////////////////////////////////////////////////////

        $clubs = DB::table("{$request->league}_table");

        if (isset($request->season)) {
            $clubs->where('season', '=', $request->season);
        } else {
            $clubs->where('season', '=', $this->season());
        }

        $clubs = $clubs->select('name')
                       ->get();

        foreach ($clubs as $club) {
            $response['clubs'][] = $club->name;
        }

        /////////////////////////////////////////////////////////

        $countWeeks = DB::table("{$request->league}_fixtures")
                        ->orderBy('datetime', 'ASC');

        if (isset($request->season)) {
            $countWeeks->where('season', '=', $request->season);
        } else {
            $countWeeks->where('season', '=', $this->season());
        }

        $result = $countWeeks->get()
                             ->toArray();

        $response['countWeeks'] = 0;

        if (count($result)) {
            $response['countWeeks'] = array_last($result)->period;
        }

        return response()->json($response);
    }

    public function survey($id)
    {
        $survey = Survey::get($id);

        if (is_null($survey)) {
            return response()->json(['message' => 'موردی یافت نشد',], 404);
        }

        $selection = [];
        if (Auth::check()) {
            $selection = Survey::authSelection($id);
        }

        $response = [
            'id'       => $survey->id,
            'question' => $survey->question,
        ];

        $options = json_decode($survey->options, TRUE);

        foreach ($options as $key => $value) {
            $response['options'][] = [
                'title'    => $value['title'],
                'count'    => $value['count'],
                'selected' => in_array($key, $selection) ? TRUE : FALSE,
            ];
        }

        return response()->json($response);
    }

    public function surveyVote()
    {
        if (Auth::guest()) {
            return response()->json(['message' => 'برای ثبت رای ابتدا ثبت نام کنید'], 401);
        }

        $x = DB::table('surveys_pivot_users')
               ->where('user_id', Auth::id())
               ->where('survey_id', request('survey_id'))
               ->get()
               ->first();

        if (!is_null($x)) {
            if ($x->option_selected == request('option_selected')) {
                return response()->json(['message' => 'success'], 200);
            }

            Auth::user()
                ->survey()
                ->detach(request('survey_id'), [
                    'option_selected' => $x->option_selected,
                ]);

            $this->countSurvey(request('survey_id'), $x->option_selected, 'decrement');
        }

        Auth::user()
            ->survey()
            ->attach(request('survey_id'), [
                'option_selected' => request('option_selected'),
            ]);

        $this->countSurvey(request('survey_id'), request('option_selected'), 'increment');

        return response()->json(['message' => 'success'], 200);
    }

    private function countSurvey($survey_id, $option_selected, $operator)
    {

        $survey = Survey::find($survey_id);

        $options = json_decode($survey->options, TRUE);

        $options[$option_selected] = [
            'title' => $options[$option_selected]['title'],
            'count' => $operator == 'increment' ? $options[$option_selected]['count'] + 1
                : $options[$option_selected]['count'] - 1,
        ];

        Survey::find($survey_id)
              ->update([
                  'options' => json_encode($options, TRUE),
              ]);

    }

    public function surveysStore()
    {
        $validator = Validator::make(request()->all(), [
            'question' => 'required',
        ]);

        if ($validator->fails()) {
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content', $validator->errors()
                                             ->first()
            );
            session()->flash('message.time', '15');
            return back();
        }

        $options = [];

        foreach (request('options') as $option) {
            array_push($options, [
                'title' => $option,
                'count' => 0,
            ]);
        }

        array_unshift($options, "kuft");

        unset($options[0]);

        Survey::create([
            'id'       => Survey::orderBy('id', 'DESC')
                                ->take(1)
                                ->get()
                                ->first()->id + 1,
            'question' => request('question'),
            'options'  => json_encode($options, TRUE),
        ]);

        session()->flash('message.type', 'success');
        session()->flash(
            'message.content', 'با موفقیت انجام شد'
        );
        session()->flash('message.time', '10');
        return back();
    }

    public function search(Request $request)
    {
        $count = Tag::where('name', $request->name)
                    ->get(['count'])
                    ->first();
        $response['count'] = is_null($count) ? '0' : $count->count;
        $response['name'] = $request->name;
        return view('post.tag', compact('response'));
    }
}
