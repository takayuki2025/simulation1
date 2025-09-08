<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
/**
     * Run the database seeds.
     *
     * @return void
     */
        public function run()
        {
                $param = [
                        'name' => 'テスト用のユーザ１',
                        'email' => 'valid.email@example.com',
                        'password' => 'testtest1',
                        'post_number' => '232-1332',
                        'address' => '東京都港区芝公園4-2-8',
                        'building' => 'コーポA',
                        //   'first_time_access' => 1,
                        'user_image' => '',
                        'address_country' => 'JP'
                ];
                DB::table('users')->insert($param);
                $param = [
                        'name' => 'テスト用のユーザ2',
                        'email' => 'test@22',
                        'password' => 'testtest2',
                        'post_number' => '232-1355',
                        'address' => '千葉',
                        'building' => 'ハイツB',
                        //   'first_time_access' => 1,
                        'user_image' => '',
                        'address_country' => 'JP'
                ];
                DB::table('users')->insert($param);
                $param = [
                        'name' => 'テスト用のユーザ3',
                        'email' => 'test@33',
                        'password' => 'testtest3',
                        'post_number' => '232-1377',
                        'address' => '静岡',
                        'building' => 'エトワール',
                         //   'first_time_access' => 1,
                        'user_image' => '',
                        'address_country' => 'JP'
                ];
                DB::table('users')->insert($param);
                $param = [
                        'name' => 'テスト用のユーザ4',
                        'email' => 'test@44',
                        'password' => 'testtest4',
                        'post_number' => '232-1399',
                        'address' => '長野',
                        'building' => 'エスポワール',
                        //   'first_time_access' => 1,
                        'user_image' => '',
                        'address_country' => 'JP'
                ];
                DB::table('users')->insert($param);

        }
}
