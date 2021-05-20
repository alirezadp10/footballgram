<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

//--------------------------------------------------------------------------------------------------------------

Route::get('/', 'IndexController@index');

Route::post('/search', 'IndexController@search')
     ->name('search');

Route::group(['prefix' => 'league'], function () {
    Route::get('news', 'IndexController@news');
    Route::get('table', 'IndexController@table');
    Route::get('fixtures', 'IndexController@fixtures');
    Route::get('scorers', 'IndexController@scorers');
});

Route::get('surveys/show/{id}', 'IndexController@survey')
     ->name('surveys.show');
Route::post('surveys/vote', 'IndexController@surveyVote')
     ->name('surveys.vote');
Route::post('surveys/store', 'IndexController@surveysStore')
     ->name('surveys.store');

//--------------------------------------------------------------------------------------------------------------

Route::get('password/create', 'Auth\LoginController@createPassword')
     ->name('password.create')
     ->middleware('auth');

Route::post('password/store', 'Auth\LoginController@storePassword')
     ->name('password.store')
     ->middleware('auth');

//--------------------------------------------------------------------------------------------------------------

Route::group([
    'prefix'     => 'laravel-filemanager',
    'middleware' => [
        'web',
        'auth',
    ],
], function () {
    UniSharp\LaravelFilemanager\Lfm::routes();
});

//--------------------------------------------------------------------------------------------------------------

// third-party section
Route::get('login/laravel', function () {
    $query = http_build_query([
        'client_id'     => env('LARAVEL_CLIENT_ID'),
        'redirect_uri'  => env('LARAVEL_CLIENT_REDIRECT'),
        'response_type' => 'code',
        'scope'         => 'place-orders check-status',
    ]);
    return redirect('http://laravel.local/oauth/authorize?' . $query);
});

Route::get('login/laravel/callback', function (\Illuminate\Http\Request $request) {
    $http = new GuzzleHttp\Client;
    $response = $http->post('http://laravel.local/oauth/token', [
        'form_params' => [
            'grant_type'    => 'authorization_code',
            'client_id'     => env('LARAVEL_CLIENT_ID'),
            'client_secret' => env('LARAVEL_CLIENT_SECRET'),
            'redirect_uri'  => env('LARAVEL_CLIENT_REDIRECT'),
            'code'          => $request->code,
        ],
    ]);
    return json_decode((string)$response->getBody(), TRUE);
});

Route::get('login/twitter', 'Auth\LoginController@twitterRedirectToProvider');

Route::get('login/twitter/callback', 'Auth\LoginController@twitterHandleProviderCallback');


Route::get('login/instagram', 'Auth\LoginController@instagramRedirectToProvider');

Route::get('login/instagram/callback', 'Auth\LoginController@instagramHandleProviderCallback');


Route::get('login/telegram', 'Auth\LoginController@telegramRedirectToProvider');

Route::get('login/telegram/callback', 'Auth\LoginController@telegramHandleProviderCallback');


Route::get('login/google', 'Auth\LoginController@googleRedirectToProvider');

Route::get('login/google/callback', 'Auth\LoginController@googleHandleProviderCallback');

//--------------------------------------------------------------------------------------------------------------

Route::get('chief-choice', 'ChiefChoiceController@index')
     ->name('chiefChoice.index');
Route::post('chief-choice/store', 'ChiefChoiceController@store')
     ->name('chiefChoice.store');

//--------------------------------------------------------------------------------------------------------------

Route::post('broadcast-schedule/store', 'BroadcastScheduleController@store')
     ->name('broadcastSchedule.store');

Route::put('broadcast-schedule/update/{id}', 'BroadcastScheduleController@update')
     ->name('broadcastSchedule.update');

Route::delete('broadcast-schedule/destroy/{id}', 'BroadcastScheduleController@destroy')
     ->name('broadcastSchedule.destroy');

//--------------------------------------------------------------------------------------------------------------

Route::group([
    'prefix'    => 'posts',
    'namespace' => 'Post',
], function () {

    Route::post('news/slider/store', 'NewsController@storeSlide')
         ->name('news.slide.store');
    Route::get('news/slider/show', 'NewsController@showSlide')
         ->name('news.slide.show');
    Route::delete('news/slider/destroy', 'NewsController@destroySlide')
         ->name('news.slide.destroy');

    Route::post('news/like', 'NewsController@like');

    Route::post('news/dislike', 'NewsController@dislike');

    Route::get('news/preview/{slug}', 'NewsController@preview')
         ->name('news.preview');

    Route::post('news/draft', 'NewsController@draft')
         ->name('news.draft');

    Route::post('news/release', 'NewsController@release')
         ->name('news.release');

    Route::get('news/last', 'NewsController@lastNews')
         ->name('lastNews');

    Route::resource('news', 'NewsController');

    //--------------------------------------------------------------------------------------------------------------

    Route::post('user-contents/like', 'UserContentController@like');

    Route::post('user-contents/dislike', 'UserContentController@dislike');

    Route::get('user-contents/preview/{slug}', 'UserContentController@preview')
         ->name('user-contents.preview');

    Route::post('user-contents/draft', 'UserContentController@draft')
         ->name('user-contents.draft');

    Route::post('user-contents/release', 'UserContentController@release')
         ->name('user-contents.release');

    Route::get('user-contents/instagram', 'UserContentController@instagram')
         ->name('user-contents.instagram');

    Route::resource('user-contents', 'UserContentController');

    //--------------------------------------------------------------------------------------------------------------

    Route::post('tweets/like', 'TweetController@like');

    Route::post('tweets/dislike', 'TweetController@dislike');

    Route::resource('tweets', 'TweetController');

    //--------------------------------------------------------------------------------------------------------------

    Route::post('comments/like', 'CommentController@like');

    Route::post('comments/dislike', 'CommentController@dislike');

    Route::resource('comments', 'CommentController');

    Route::get('tags/get/user-contents', 'TagController@getUserContents');

    Route::get('tags/get/news', 'TagController@getNews');

    Route::get('tags/get/comments', 'TagController@getComments');

    Route::resource('tags', 'TagController');
});

//--------------------------------------------------------------------------------------------------------------

Route::group(['prefix' => 'users'], function () {
    Route::get('get/time-line', 'UserController@getTimeLine');
    Route::get('get/user-contents', 'UserController@getUserContents');
    Route::get('get/news', 'UserController@getNews');
    Route::get('get/comments', 'UserController@getComments');
    Route::post('follow', 'UserController@follow')
         ->name('users.follow');
    Route::post('update/password', 'UserController@updatePassword')
         ->name('users.updatePassword');
    Route::post('update/profile', 'UserController@updateProfile')
         ->name('users.updateProfile');
    Route::get('configuration', 'UserController@configuration')
         ->name('users.configuration');
    Route::get('{username}/followers', 'UserController@getFollowers')
         ->name('users.get-followers');
    Route::get('{username}/followings', 'UserController@getFollowings')
         ->name('users.get-followings');
    Route::get('get-notifications', 'UserController@getNotifications')
         ->name('users.get-notifications');
    Route::get('all-notifications', 'UserController@allNotifications')
         ->name('users.all-notifications');
});

Route::get('users/{id}/{name}', 'UserController@show')
     ->name('users.show');

Route::resource('users', 'UserController');


//--------------------------------------------------------------------------------------------------------------

Route::get('livescore', function () {
    return view('live-score');
});

//--------------------------------------------------------------------------------------------------------------

Route::get('90',function () {
    $response = json_decode(file_get_contents('http://fantasy.90tv.ir/players'),TRUE);
    return view('90',compact('response'));
});
Route::get('test',function () {
    //    $obj = new LeagueService();
    //    return
    //        $x = $obj->scoreboardPlayOffs('ss_1_GQ9XZuqt_SGR9PImD_draw_');
    //live score football-data
    $uri                        = 'http://api.football-data.org/v2/competitions/PL/matches/?matchday=11';
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: 731246bf26934308a8e235c78166c129';
    $stream_context             = stream_context_create($reqPrefs);
    $response                   = file_get_contents($uri,FALSE,$stream_context);
    $matches                    = json_decode($response);
    dd($matches);
});
Route::get('test2',function () {
    //live score ghatre
    return view('test');
});
