<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'explain' => 'required|max:255',
            'condition' => ['required'],
            'category' => ['required'],
            // 'item_image' => 'required|mimes:jpeg,png' ,
            ];
    }
        public function messages()
    {
        return [
            'name.required' => '商品名を入力してください。',
            'name.max' => '名前を255文字以下で入力してください。',
            'price.required' => '金額を入力してください。',
            'price.numeric' => '数値で入力してください。',
            'explain.required' => '商品説明を入力してください。',
            'explain.max' => '商品説明を２５５文字以内で入力してください。',
            'condition.required' => '商品状態を選択してください。',
            'category.required' => 'カテゴリーを選択してください。',
            'item_image.required' => '商品画像ファイルをアップロードしてください。',
            // 'item_image.mimes' => '商品画像ファイルは.jpegまたは.png形式でアップロードしてください!!!',
        ];
    }

}
