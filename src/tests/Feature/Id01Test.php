<?php

// 会員登録機能のテスト

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Id01Test extends TestCase
{
    use RefreshDatabase;


    // ID1無効なデータでバリデーションが失敗するテスト
    public function test_registration_with_empty_email_fails_validation_with_specific_message()
    {

        // ID1-1　名前が入力されていない場合
        $response = $this->post('/register', [
            'name' => '', //名前を空にする
            'email' => 'valid.email@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        // バリデーションエラーによって302が返されることを確認
        $response->assertStatus(302);
        // emailフィールドに特定のメッセージがあることを確認
        $response->assertSessionHasErrors(['name' => 'お名前を入力してください。']);



        // ID1-2　メールアドレスが入力されていない場合
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => '', // メールアドレスを空にする
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        // バリデーションエラーによって302が返されることを確認
        $response->assertStatus(302);
        // emailフィールドに特定のメッセージがあることを確認
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください。']);



        // ID1-3　パスワードが入力されていない場合
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'valid.email@example.com',
            'password' => '', //パスワードをからにする
            'password_confirmation' => 'password',
        ]);
        // バリデーションエラーによって302が返されることを確認
        $response->assertStatus(302);
        // emailフィールドに特定のメッセージがあることを確認
        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください。']);



        // ID1-4　パスワードが７文字以下の場合
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'valid.email@example.com',
            'password' => 'pass',//パスワードを４文字にする
            'password_confirmation' => 'password',
        ]);
        // バリデーションエラーによって302が返されることを確認
        $response->assertStatus(302);
        // emailフィールドに特定のメッセージがあることを確認
        $response->assertSessionHasErrors(['password' => 'パスワードは８文字以上で入力してください。']);



        // ID1-5　パスワードと確認パスワードが違う場合
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'valid.email@example.com',
            'password' => 'password',
            'password_confirmation' => 'password2233',//パスワードと違う入力をする
        ]);
        // バリデーションエラーによって302が返されることを確認
        $response->assertStatus(302);
        // emailフィールドに特定のメッセージがあることを確認
        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません。']);


    }


    // ID1-６　入力正常用のアクション
    public function test_registration_with_valid_data_is_successful()
    {
                // ID1-6　全ての項目を入力して次の画面に移動する場合
        // 有効なデータで登録リクエストを送信
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'valid.email@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        // 登録後、意図したページにリダイレクトされるか確認
        $response->assertRedirect('/onetime');//応用のメール認証を実装したので、メール認証確認画面に移動するかテスト

        // セッションにエラーメッセージがないことを確認
        $response->assertSessionDoesntHaveErrors();

        // データベースからユーザーを取得
        $user = DB::table('users')->where('email', 'valid.email@example.com')->first();

        // ユーザーが存在することと、パスワードが正しくハッシュ化されていることを確認
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('password', $user->password));
    }


    public function test_non_existent_route_returns_404()
    {
        $response = $this->post('/no_route');
        $response->assertStatus(404);
    }
}