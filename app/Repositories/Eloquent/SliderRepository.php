<?php

namespace App\Repositories\Eloquent;

use App\Models\Slider;
use App\Repositories\Contracts\SliderRepository as SliderContract;

class SliderRepository extends BaseRepository implements SliderContract
{
    public function __construct(Slider $slider)
    {
        $this->model = $slider;
    }
}
