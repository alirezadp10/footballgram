<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class BroadcastScheduleRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-broadcast-schedule');
    }

    public function rules()
    {
        return [
            'host'                 => 'required',
            'guest'                => 'required',
            'datetime'             => 'required',
            'broadcast_channel_id' => 'required|exists:broadcast_channels,id',
        ];
    }

    public function passedValidation()
    {
        $data = $this->all();

        $data['datetime'] = Carbon::createFromTimestamp(substr($this->datetime, 0, 10))->format('Y-m-d H:i:s');

        $this->replace($data);
    }
}
