<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChiefChoiceRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-chief-choice');
    }

    public function rules()
    {
        return [
            'post_id'     => 'required',
            'order'       => 'nullable',
            'delete_item' => 'sometimes|exists:chief_choices,id',
        ];
    }
}
