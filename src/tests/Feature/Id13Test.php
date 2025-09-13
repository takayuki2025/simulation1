<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;

class Id13Test extends TestCase
{
    // refresh database trait to migrate the database for each test
    use RefreshDatabase;

    /**
     * プロフィール画像が保存されたときにユーザー画像が表示されることのテスト
     *
     * @return void
     */
    public function test_profile_image_is_displayed_when_user_image_is_saved()
    {
        // Public diskのStorageを偽装（テスト用の仮想的なストレージを作成）
        Storage::fake('public');

        // テスト用のユーザーを作成
        $user = User::factory()->create();

        // actingAs() ヘルパーを使用して、ユーザーとして認証
        $this->actingAs($user);

        // **ここが重要な変更点です**
        // UploadedFile::fake() を使用してダミーファイルを生成。
        // これにより、仮想ストレージへのファイルの配置が自動的に行われます。
        $file = UploadedFile::fake()->image('dummy.png');

        // POSTリクエストを正しいURLとパラメータ名で送信
        $response = $this->post('/upload2', ['user_image' => $file]);

        // リダイレクトのステータスコード302を確認
        $response->assertRedirect(route('profile_edit'));

        // データベースを最新の状態に更新
        $user->refresh();

        // データベースに保存されたパスが期待通りであることを確認
        $this->assertStringContainsString('storage/user_images/' . $file->hashName(), $user->user_image);

        // ファイルが正しく仮想ストレージに保存されていることを確認
        // Storage::fake() の場合、hashName() で生成されたファイル名で保存されるため
        Storage::disk('public')->assertExists('public/user_images/' . $file->hashName());
    }
}