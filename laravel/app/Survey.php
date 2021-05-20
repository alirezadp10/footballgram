<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Survey extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'question',
        'options',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'surveys_pivot_users');
    }

    public static function getLastSurvey()
    {
        return
            $survey = Survey::take(1)
                            ->orderBy('surveys.id', 'DESC')
                            ->get([
                                'surveys.id',
                                'surveys.question',
                                'surveys.options',
                            ])->toArray();
    }

    public static function authSelection($survey_id)
    {
        return
            DB::table('surveys_pivot_users')
              ->where('survey_id', $survey_id)
              ->where('user_id', Auth::id())
              ->pluck('option_selected')
              ->toArray();
    }

    public static function get($survey_id)
    {
        return
            $survey = Survey::where('id', $survey_id)
                            ->get([
                                'surveys.id',
                                'surveys.question',
                                'surveys.options',
                            ])
                            ->first();
    }

}
