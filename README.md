# アプリケーション名： 模擬案件初級_フリマアプリ
# 環境構築
Dockerビルド
<br>
<br>
　1\. git cloneリンク git clone git@github.com:Estra-Coachtech/laravel-docker-template.git
<br>
　2\. docker-compose.ymlファイルの、mysql:image: mysql:8.3　に変更
<br>
　3\. docker-compose up -d --build
<br>
　＊MySqlは、OSによって起動しない場合があるのでそれぞれのPCに合わせて、docker-compose.ymlファイルを編集
<br>してください。
  <br>
  <br>
laravel環境構築
<br>
<br>
　1\. docker-compose exec php bash
<br>
　2\. composer install
<br>
　3\. env.exampleファイルから.envを作成し、環境変数を変更
<br>
　4\. アプリケーションキーの作成<br>
　　php artisan key:generate
<br>
　5\. マイグレーションの実行<br>
　　php artisan migrate
<br>
　6\. シーディングの実行<br>
　　php artisan db:seed
<br>

# 伝えること<br>
-
<br>

<br>

# これからすること、課題（模擬案件の時だけ掲載）<br>
- 09/09 09/08の面談後更新
- 　完了　出品する際のアイテムイメージ、価格入力はプラスのみの、カテゴリー選択のバリデーション<br>
- 　完了　購入前の配送先ページ移動前のバリデーション<br>
- 　完了　カード支払いでの取引の時、バリデーション、購入履歴の反映、キャンセルボタンの設置<br>
- 　完了　新規登録時、ログイン時、プロフィール変更時のバリデーション（気になる点はメールアドレス入力）<br>
- 　完了　新規登録時、ログイン時、プロフィール変更時の名前２０文字以内バリデーション<br>
- 　完了　商品コメント２５５文字以内バリデーション<br>
- 　完了　sold表示機能をトップページにも反映する、購入できないようにする<br>
- 　完了　トップページのおすすめには自分の商品は表示しない<br>
- 　完了　いいね機能は自分の商品にはできないようにする<br>
- 　完了　新規ユーザ情報登録、プロフィール変更時のバリデーションがかかった時そのページにbackするようにする<br>
- 　サーチ機能実行後マイリストに移っても継続サーチできるようにする<br>
- 　アイテム・ユーザイメージ名をユニークにするためのイメージ名保存<br>
- 　完了　mailhogを使用してメール認証サービスを実装する<br><br>
-   PHPUnitを使用してテスト作成
- <br>

課題<br>
- get引数、routeヘルパーを活用して効率を高めて品質を高める。<br>
- javascriptを活用したのですが、今はできるだけ学習方針のHTML、CSS、Git,MySql,PHP,laravelでできることを深めるためそちらを活用することを考える。<br>
- PHPUnitを使用してテストを作成するつもりなのですが外部キーを設定した時のテストの仕方を考えています。<br><br>
<br>
<br>
<br>

> # 確認事項（模擬案件の時だけ掲載）<br>
- 
<br>
<br>
<br>

# 修正履歴（模擬案件の時だけ掲載）<br>




# ER図<br>
<img width="1920" height="1080" alt="Image" src="https://github.com/user-attachments/assets/3f3ae433-2aa9-48b5-b838-683a9b010a98" />

# 使用技術<br>
  - PHP 8.1.33
  - Laravel 8.83.8
  - MySql 8.3
  - nginx 1.21.1
<br>

# URL<br>
  - フリマアプリトップページ： http://localhost/
  - ユーザー登録： http://localhost/register/
  - phpMyAdmin:http://localhost:8080/
  - meilhog：http://http://localhost:8025/
