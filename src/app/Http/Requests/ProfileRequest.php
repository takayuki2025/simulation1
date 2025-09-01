<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'post_number' => ['required'],
            'address' => ['required'],
            // 'building' => ['required']
        ];
    }


    public function messages()
    {
        return [
             'name.required' => '名前を入力してください',
             'name.string' => '名前を文字列で入力してください',
             'name.max' => '名前を255文字以下で入力してください',
             'post_number.required' => '郵便を入力してください',
             'address.required' => '住を入力してください',
            // 'address.required' => '建物をを入力してください'

         ];
     }
}
