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
        $params = [
            [
                'user_id' => 1,
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'explain' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => '良好',
                'category' => 'メンズ',
                'item_image' => 'storage/item_images/Armani+Mens+Clock.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'explain' => '高速で信頼性の高いハードディスク',
                'condition' => '目立った傷や汚れなし',
                'category' => '家電',
                'item_image' => 'storage/item_images/HDD+Hard+Disk.jpg',
            ],
            [
                'user_id' => 1,
                'name' => '玉ねぎ３束',
                'price' => 300,
                'brand' => 'なし',
                'explain' => '新鮮な玉ねぎ3束のセット',
                'condition' => 'やや傷や汚れあり',
                'category' => 'キッチン',
                'item_image' => 'storage/item_images/iLoveIMG+d.jpg',
            ],
            [
                'user_id' => 1,
                'name' => '革靴',
                'price' => 4000,
                'brand' => '',
                'explain' => 'クラシックなデザインの革靴',
                'condition' => '状態が悪い',
                'category' => 'メンズ',
                'item_image' => 'storage/item_images/Leather+Shoes+Product+Photo.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => '',
                'explain' => '高性能なノートパソコン',
                'condition' => '良好',
                'category' => '家電',
                'item_image' => 'storage/item_images/Living+Room+Laptop.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'マイク',
                'price' => 8000,
                'brand' => 'なし',
                'explain' => '高音質のレコーディング用マイク',
                'condition' => '目立った傷や汚れなし',
                'category' => '家電',
                'item_image' => 'storage/item_images/Music+Mic+4632231.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => '',
                'explain' => 'おしゃれなショルダーバッグ',
                'condition' => 'やや傷や汚れあり',
                'category' => 'レディース',
                'item_image' => 'storage/item_images/Purse+fashion+pocket.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'ダンブラー',
                'price' => 500,
                'brand' => 'なし',
                'explain' => '使いやすいダンブラー',
                'condition' => '状態が悪い',
                'category' => 'キッチン',
                'item_image' => 'storage/item_images/Tumbler+souvenir.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'slarbacks',
                'explain' => '手動のコーヒーミル',
                'condition' => '良好',
                'category' => 'キッチン',
                'item_image' => 'storage/item_images/Waitress+with+Coffee+Grinder.jpg',
            ],
            [
                'user_id' => 1,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => '',
                'explain' => '便利なメイクアップセット',
                'condition' => '目立った傷や汚れなし',
                'category' => 'レディース',
                'item_image' => 'storage/item_images/外出メイクアップセット.jpg',
            ],
        ];

        DB::table('items')->insert($params);
    }
}