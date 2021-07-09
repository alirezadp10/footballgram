<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePostRequest extends FormRequest
{
    public $post;

    public function authorize()
    {
        return Gate::allows('edit',$this->model());
    }

    public function rules()
    {
        return [
            'main_title'      => 'nullable',
            'secondary_title' => 'nullable',
            'context'         => 'nullable',
            'image'           => 'nullable|file|mimes:jpg',
        ];
    }

    public function model()
    {
        $this->post = Post::whereSlug($this->route('slug'))->firstOrFail();

        return $this->post;
    }
}
