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
-  stripe決済のクレジットカード番号は、4242 4242 4242 4242　で有効期限日は未来の日にち、セキュリティー番号とメールアドレス(メール形式で)、名前はなんでも大丈夫です。<br>
-  stripe決済の都合上最低決済金額が50円なので少し余裕を持たせて出品商品の最低金額を100円以上にしてバリデーションを実装しました。<br>
-  COACHTECHのロゴをクリックするとトップページに、ログインユーザーが商品詳細画面で自分が出品した商品の購入手続きをクリックするとプロフィールページに、ゲストユーザーが購入手続きへ・ヘッダーのマイページ・出品・コメントを送信するをクリックするとログインページに移動するようになっています。<br>
-  PHPUnitのテストファイルはスプレットシートのテストケース一覧のID番号に沿ってFeatureディレクトリーに保存してあります。phpコンテナーで php artisan test を実行してテストをしてください。 <br>
-  Route,Controller共に基本設計書に沿ってプロジェクトのディレクトリーの中に基本並び替えしています。<br>
-  ダミーのユーザーデーターと出品商品データーをシーダーファイルで作りましたので、PHPコンテナーで　php artisan db:seed　を実行してください。<br>
   ダミーのユーザー情報です。<br>
   １：名前:'テスト用のユーザ１'、アドレス:'valid.email@example.com'、パスワード:'testtest1'、<br>
   ２：名前:'テスト用のユーザ2'、アドレス:'test@22'、パスワード:'testtest2'<br>
   ３：名前:'テスト用のユーザ3'、アドレス:'test@33'、パスワード:'testtest3'<br>
   ４：名前:'テスト用のユーザ4'、アドレス:'test@44'、パスワード:'testtest4'　　です。メール認証は登録済みでログイン後トップページに移動します。<br>
-  プロフィールのユーザー画像を登録していない時は初期画面として、default-profile２.jpgファイルの画像を使っています。<br>


<br>
<br>
# スプレットシートの基本設計書にある項目で追加した内容（模擬案件の時だけ掲載）<br>
- Route,Controller関係<br>
　　出品完了処理画面：パス・/thanks_sell　アクション・thanks_sell_create<br>
　　購入完了処理画面：パス・/thanks_buy　アクション・thanks_buy_create<br>
　　email認証前再送信ページ前までの処理:パス・email/verify　アクション名・<br>
　　追加コントローラー名：EmailVerificationController（認証メール関係のコントローラー）<br>
- View関係<br>
　　出品完了画面：thanks_sell.blade.php<br>
　　購入完了画面：thanks_buy.blade.php<br>
　　email認証前ページ：verify-email.blade.php<br>
　　カード支払い決済画面：stripe_payment.blade<br>
- バリデーション関係<br>
　　ファイル名：ProfileImageRequest.php　内容・ユーザー画像アップロード　ルール・拡張子が.jpegもしくは.png<br>


<br>
<br>

# これからすること、課題（模擬案件の時だけ掲載）<br>
- 09/15 09/15の面談後更新
- <br>

課題<br>
- get引数、routeヘルパーを活用して効率を高めて品質を高める。<br>
- javascriptを活用したのですが、今はできるだけ学習方針のHTML、CSS、Git,MySql,PHP,laravelでできることを深めるためそちらを活用することを考える。<br>
- 
<br>
<br>
<br>

># 確認事項（模擬案件の時だけ掲載）<br>
<br>

# 修正履歴（模擬案件の時だけ掲載）<br>
<br>



# ER図<br>
<img width="1920" height="1080" alt="Image" src="https://github.com/user-attachments/assets/9fede595-ecf6-4482-8894-1336d41adf97" />

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
  - meilhog： http://localhost:8025/
