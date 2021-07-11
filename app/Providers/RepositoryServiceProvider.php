<?php

namespace App\Providers;

use App\Repositories\Contracts\AbilityRepository;
use App\Repositories\Contracts\BroadcastChannelRepository;
use App\Repositories\Contracts\BroadcastScheduleRepository;
use App\Repositories\Contracts\ChiefChoiceRepository;
use App\Repositories\Contracts\CommentRepository;
use App\Repositories\Contracts\CompetitionRepository;
use App\Repositories\Contracts\DislikeRepository;
use App\Repositories\Contracts\FixtureRepository;
use App\Repositories\Contracts\FollowRepository;
use App\Repositories\Contracts\LikeRepository;
use App\Repositories\Contracts\PostRepository;
use App\Repositories\Contracts\ScorerRepository;
use App\Repositories\Contracts\SliderRepository;
use App\Repositories\Contracts\StandingRepository;
use App\Repositories\Contracts\SurveyRepository;
use App\Repositories\Contracts\TagRepository;
use App\Repositories\Contracts\TweetRepository;
use App\Repositories\Contracts\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->bind(AbilityRepository::class, \App\Repositories\Eloquent\AbilityRepository::class);
        $this->app->bind(ScorerRepository::class, \App\Repositories\Eloquent\ScorerRepository::class);
        $this->app->bind(StandingRepository::class, \App\Repositories\Eloquent\StandingRepository::class);
        $this->app->bind(CompetitionRepository::class, \App\Repositories\Eloquent\CompetitionRepository::class);
        $this->app->bind(FixtureRepository::class, \App\Repositories\Eloquent\FixtureRepository::class);
        $this->app->bind(BroadcastChannelRepository::class, \App\Repositories\Eloquent\BroadcastChannelRepository::class);
        $this->app->bind(BroadcastScheduleRepository::class, \App\Repositories\Eloquent\BroadcastScheduleRepository::class);
        $this->app->bind(ChiefChoiceRepository::class, \App\Repositories\Eloquent\ChiefChoiceRepository::class);
        $this->app->bind(CommentRepository::class, \App\Repositories\Eloquent\CommentRepository::class);
        $this->app->bind(DislikeRepository::class, \App\Repositories\Eloquent\DislikeRepository::class);
        $this->app->bind(FollowRepository::class, \App\Repositories\Eloquent\FollowRepository::class);
        $this->app->bind(LikeRepository::class, \App\Repositories\Eloquent\LikeRepository::class);
        $this->app->bind(PostRepository::class, \App\Repositories\Eloquent\PostRepository::class);
        $this->app->bind(SliderRepository::class, \App\Repositories\Eloquent\SliderRepository::class);
        $this->app->bind(SurveyRepository::class, \App\Repositories\Eloquent\SurveyRepository::class);
        $this->app->bind(TagRepository::class, \App\Repositories\Eloquent\TagRepository::class);
        $this->app->bind(TweetRepository::class, \App\Repositories\Eloquent\TweetRepository::class);
        $this->app->bind(UserRepository::class, \App\Repositories\Eloquent\UserRepository::class);
    }
}
