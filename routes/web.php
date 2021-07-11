<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

require __DIR__.'/lfm.php';

//--------------------------------------------------------------------------------------------------------------

Route::get('/', 'IndexController@index')->name('index');
Route::get('competition/news', 'IndexController@news')->name('index.competition-news');
Route::get('competition/standing', 'IndexController@standing')->name('index.competition-standing');
Route::get('competition/fixtures', 'IndexController@fixtures')->name('index.competition-fixtures');
Route::get('competition/scorers', 'IndexController@scorers')->name('index.competition-scorers');

//--------------------------------------------------------------------------------------------------------------

Route::get('surveys/{survey}', 'SurveyController@show')->name('surveys.show');
Route::post('surveys', 'SurveyController@store')->middleware('auth')->name('surveys.store');
Route::post('surveys/{survey}/vote', 'SurveyController@vote')->middleware('auth')->name('surveys.vote');

//--------------------------------------------------------------------------------------------------------------

Route::get('chief-choices', 'ChiefChoiceController@index')->middleware('auth')->name('chief-choices.index');
Route::post('chief-choices', 'ChiefChoiceController@store')->middleware('auth')->name('chief-choices.store');

//--------------------------------------------------------------------------------------------------------------

Route::post('broadcast-schedule', 'BroadcastScheduleController@store')->middleware('auth')->name('broadcast-schedules.store');
Route::put('broadcast-schedule/{id}', 'BroadcastScheduleController@update')->middleware('auth')->name('broadcast-schedules.update');
Route::delete('broadcast-schedule/{id}', 'BroadcastScheduleController@destroy')->middleware('auth')->name('broadcast-schedules.destroy');

//--------------------------------------------------------------------------------------------------------------

Route::post('posts/comment', 'PostController@comment')->middleware('auth')->name('posts.comment');
Route::post('posts/like/{slug}', 'PostController@like')->middleware('auth')->name('posts.like');
Route::post('posts/dislike/{slug}', 'PostController@dislike')->middleware('auth')->name('posts.dislike');
Route::put('posts/draft/{slug}', 'PostController@draft')->middleware('auth')->name('posts.draft');
Route::put('posts/release/{slug}', 'PostController@release')->middleware('auth')->name('posts.release');
Route::get('posts/last', 'PostController@lastNews')->name('posts.last');
Route::get('posts/{slug}', 'PostController@show')->name('posts.show');
Route::get('posts/create', 'PostController@create')->middleware('auth')->name('posts.create');
Route::post('posts', 'PostController@store')->middleware('auth')->name('posts.store');
Route::get('posts/edit/{slug}', 'PostController@edit')->middleware('auth')->name('posts.edit');
Route::patch('posts/{slug}', 'PostController@update')->middleware('auth')->name('posts.update');
Route::delete('posts/{slug}', 'PostController@destroy')->middleware('auth')->name('posts.destroy');

//--------------------------------------------------------------------------------------------------------------

Route::post('tweets/like', 'TweetController@like');
Route::post('tweets/dislike', 'TweetController@dislike');
Route::get('tweets', 'TweetController@create')->name('tweets.create');
Route::post('tweets', 'TweetController@store')->name('tweets.store');
Route::get('tweets/{tweet}', 'TweetController@show')->name('tweets.show');

//--------------------------------------------------------------------------------------------------------------

Route::post('slider', 'SlideController@store')->middleware('auth')->name('slide.store');
Route::get('slider/{slider}', 'SlideController@show')->middleware('auth')->name('slide.show');
Route::delete('slider/{slider}', 'SlideController@destroy')->middleware('auth')->name('slide.destroy');

//--------------------------------------------------------------------------------------------------------------

Route::get('tags/user-contents', 'TagController@userContents')->name('tags.user-contents');
Route::get('tags/news', 'TagController@news')->name('tags.news');
Route::get('tags/comments', 'TagController@comments')->name('tags.comments');
Route::get('tags/{tag}', 'TagController@show')->name('tags.show');

//--------------------------------------------------------------------------------------------------------------

Route::post('comment/{comment}/like', 'CommentController@like')->middleware(['auth', 'throttle:20,1'])->name('comment.dislike');
Route::post('comment/{comment}/dislike', 'CommentController@dislike')->middleware(['auth', 'throttle:20,1'])->name('comment.like');

//--------------------------------------------------------------------------------------------------------------

Route::get('users/posts/timeline', 'UserController@postsTimeline')->name('users.posts-timeline');
Route::get('users/{username}/user-contents', 'UserController@userContents')->name('users.user-contents');
Route::get('users/{username}/news', 'UserController@news')->name('users.news');
Route::get('users/{username}/comments', 'UserController@comments')->name('users.comments');
Route::get('users/notifications', 'UserController@notifications')->middleware('auth')->name('users.notifications');
Route::get('users/followers/{username}', 'UserController@followers')->name('users.followers');
Route::get('users/followings/{username}', 'UserController@followings')->name('users.followings');
Route::get('users/configuration', 'UserController@configuration')->middleware('auth')->name('users.configuration');
Route::post('users/follow/{username}', 'UserController@follow')->middleware('auth')->name('users.follow');
Route::patch('users/{username}', 'UserController@update')->middleware('auth')->name('users.update');
Route::get('users/{username}', 'UserController@show')->name('users.show');
Route::get('users', 'UserController@home')->middleware('auth')->name('users.home');
