<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'             => 'required',
            'email'            => 'nullable|email',
            'current_password' => 'required_if:current_password|current_password:web',
            'new_password'     => 'nullable|confirmed|min:4',
            'image'            => 'nullable|base64image',
            'username'         => [
                'nullable',
                'min:4',
                'regex:/^[A-Za-z-_0-9]+$/',
                Rule::unique('users')->ignore(User::whereUsername($this->route('username')), 'username'),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required'                     => 'وارد کردن نام الزامی است !',
            'username.min'                      => 'نام کاربری حداقل باید ۴ حرف باشد !',
            'username.unique'                   => 'این نام کاربری از قبل توسط فرد دیگری انتخاب شده است !',
            'username.regex'                    => 'نام کاربری تنها باید شامل عدد و حروف انگلیسی باشد !',
            'email.email'                       => 'آدرس ایمیل معتبر نمی باشد !',
            'avatar.mimes'                      => 'فرمت تصویر اشتباه است !',
            'avatar.image'                      => 'فرمت تصویر اشتباه است !',
            'current_password.current_password' => 'رمز عبور فعلی صحیح نیست !',
            'current_password.required'         => 'وارد کردن رمز عبور فعلی الزامی است !',
            'new_password.confirmed'            => 'لطفا در وارد کردن تکرار رمز عبور دقت فرمایید !',
            'new_password.min'                  => 'رمز عبور حداقل باید ۴ حرف باشد !',
        ];
    }

    public function validationData()
    {
        $data = $this->all();

        if (isset($data['new_password'])) {
            $data['password'] = bcrypt($data['new_password']);
        }

        return $data;
    }
}
