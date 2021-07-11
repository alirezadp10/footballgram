<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-survey');
    }

    public function rules()
    {
        return [
            'question' => 'required',
            'options'  => 'required',
        ];
    }

    public function validationData()
    {
        $data = $this->all();

        $data['options'] = array_map([$this, 'prepareOptions'], $data['options']);

        return $data;
    }

    public function prepareOptions($option)
    {
        return $options[] = [
            'title' => $option,
            'count' => 0,
        ];
    }
}
