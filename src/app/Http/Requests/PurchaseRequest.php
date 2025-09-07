<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Item;

class PurchaseRequest extends FormRequest
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
            'address' => 'required',
            'payment' => 'required',
            'item_id' => [
                'required',
                'exists:items,id', // itemsテーブルに存在するitem_idであること
                function ($attribute, $value, $fail) {
                    // Itemモデルを使って、対象の商品のremainカラムを取得
                    $item = Item::find($value);

                    // 商品が存在し、かつremainが0以下の場合
                    if ($item && $item->remain <= 0) {
                        $fail('この商品は売り切れです。購入できません。');
                    }
                },
            ],
        ];
    }

        public function messages()
    {
        return [
            'address.required' => '配送先住所を入力してください。',
            'payment.required' => '支払い方法を選択してください。',
        ];
    }
}
