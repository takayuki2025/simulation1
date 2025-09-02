<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'name' => '腕時計',
            'price' => 15,000,
            'brand' => 'Rolax',
            'explain' => 'スタイリッシュなデザインのメンズ腕時計',
            'condition' => '良好',
            'category' => 'メンズ',
            'image_date' => '',

    ];
    DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => 'HDD',
            'price' => 5,000,
            'brand' => '西芝',
            'explain' => '高速で信頼性の高いハードディスク',
            'condition' => '目立った傷や汚れなし',
            'category' => '家電',
            'image_date' => '',

    ];
    DB::table('items')->insert($param);

            $param = [
            'user_id' => 1,
            'name' => '玉ねぎ３束',
            'price' => 300,
            'brand' => 'なし',
            'explain' => '新鮮な玉ねぎ3束のセット',
            'condition' => 'やや傷や汚れあり',
            'category' => 'キッチン',
            'image_date' => '',

    ];
    DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => '革靴',
            'price' => 4,000,
            'brand' => '',
            'explain' => 'クラシックなデザインの革靴',
            'condition' => '状態が悪い',
            'category' => 'メンズ',
            'image_date' => '',

    ];
    DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => 'ノートPC',
            'price' => 45,000,
            'brand' => '',
            'explain' => '高性能なノートパソコン',
            'condition' => '良好',
            'category' => '家電',
            'image_date' => '',

    ];
    DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => 'マイク',
            'price' => 8,000,
            'brand' => 'なし',
            'explain' => '高音質のレコーディング用マイク',
            'condition' => '目立った傷や汚れなし',
            'category' => '家電',
            'image_date' => '',

    ];
    DB::table('items')->insert($param);

            $param = [
            'user_id' => 1,
            'name' => 'ショルダーバッグ',
            'price' => 3,500,
            'brand' => '',
            'explain' => 'おしゃれなショルダーバッグ',
            'condition' => 'やや傷や汚れあり',
            'category' => 'レディース',
            'image_date' => '',

    ];
    DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => 'ダンブラー',
            'price' => 500,
            'brand' => 'なし',
            'explain' => '使いやすいダンブラー',
            'condition' => '状態が悪い',
            'category' => 'キッチン',
            'image_date' => '',

    ];
    DB::table('items')->insert($param);

            $param = [
            'user_id' => 1,
            'name' => 'コーヒーミル',
            'price' => 4,000,
            'brand' => 'slarbacks',
            'explain' => '手動のコーヒーミル',
            'condition' => '良好',
            'category' => 'キッチン',
            'image_date' => '',

    ];
    DB::table('items')->insert($param);

        $param = [
            'user_id' => 1,
            'name' => 'メイクセット',
            'price' => 2,500,
            'brand' => '',
            'explain' => '便利なメイクアップセット',
            'condition' => '目立った傷や汚れなし',
            'category' => 'レディース',
            'image_date' => '',

    ];
    DB::table('items')->insert($param);








    }
}
