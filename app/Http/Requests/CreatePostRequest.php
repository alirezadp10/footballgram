<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->type == 'NEWS') {
            return auth()->user()->can('create-news');
        }

        if ($this->type == 'USER_CONTENT') {
            return auth()->user()->can('create-user-content');
        }

        return FALSE;
    }

    public function rules(): array
    {
        return [
            'main_title'      => 'required',
            'secondary_title' => 'required',
            'context'         => 'required',
            'type'            => 'required|in:NEWS,USER_CONTENT',
            'image'           => 'required|file|mimes:jpg',
        ];
    }
}
