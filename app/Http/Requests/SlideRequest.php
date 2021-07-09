<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SlideRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('manage-slide-post');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slug'       => 'required',
            'order'      => 'nullable',
            'first_tag'  => 'nullable',
            'second_tag' => 'nullable',
            'third_tag'  => 'nullable',
            'forth_tag'  => 'nullable',
        ];
    }
}
