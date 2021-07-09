<?php

namespace App\Repositories\Eloquent;

use App\Models\ChiefChoice;
use App\Repositories\Contracts\ChiefChoiceRepository as ChiefChoiceContract;

class ChiefChoiceRepository extends BaseRepository implements ChiefChoiceContract
{
    public function __construct(ChiefChoice $chiefChoice)
    {
        $this->model = $chiefChoice;
    }

    public function news()
    {
        return ChiefChoice::with('post')->latest('order')->get()->map(function ($chiefChoice) {
            return [
                'id'    => $chiefChoice->post->id,
                'slug'  => $chiefChoice->post->slug,
                'title' => $chiefChoice->post->title,
                'image' => $chiefChoice->post->image,
            ];
        });
    }
}
