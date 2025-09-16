# アプリケーション名： 模擬案件初級_フリマアプリ
# 環境構築
Dockerビルド
<br>
<br>
　1\. git cloneリンク（ターミナルコマンド） git clone https://github.com/takayuki2025/simulation1.git  の実行
<br>
　2\. （ターミナルコマンド）cd simulation1　の実行。
<br>
　3\. （ターミナルコマンド）docker-compose up -d --build　の実行
<br>
　
  <br>
laravel環境構築
<br>
<br>
　1\. （ターミナルコマンド）docker-compose exec php bash　の実行
<br>
　2\. （PHPコンテナー）composer install　の実行
<br>
　3\. env.exampleファイルから.envを作成し、.envファイルの環境変数を変更（　cp .env.example .env　の実行後環境変数の変更）<br>

APP_KEY=　　　　　　　　　　を　APP_KEY=base64:KUoosogBL0QQaaukA2mxjGSicokkBKJ+dPItJHJ2MvQ=　に<br>

DB_HOST=127.0.0.1　　　　を　DB_HOST=mysql　に<br>

DB_DATABASE=laravel　　　を　DB_DATABASE=laravel_db　に<br>
DB_USERNAME=root     　　を　DB_USERNAME=laravel_user　に<br>
DB_PASSWORD=         　　を　DB_PASSWORD=laravel_pass　に<br>

MAIL_FROM_ADDRESS=null　を　MAIL_FROM_ADDRESS="hello@example.com"　に<br><br>

　ここからは全て追加です(env.ファイルの一番下に追加してください。)<br><br>
STRIPE_KEY="pk_test_51S4djbL5FmW737EdtTZZSncxQjYuIhaB4FxsBjg7Of1Lr7mYkT74ZU2yauWUY2t0aPPGyIIydYnx8VfxrLs755yl0028SjCRfD"
STRIPE_SECRET="sk_test_51S4djbL5FmW737EdLMGqe36QaYF9cLb3QjIXfiEn8PDJkz6tnKFaJw7x3jKX97LNVLHX7dgJOlbvZ5MJhovqi5fp00QXOUV9Td"
CASHIER_CURRENCY=ja_JP
CASHIER_CURRENCY_LOCALE=ja_JP
CASHIER_LOGGER=daily

FORTIFY_FEATURES=registration,reset-passwords,update-profile-information,update-passwords,two-factor-authentication,email-verification
<br>
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
　7\. テスト用のデーターベース作成からPHPUnitテスト実行まで。<br>
（ターミナルコマンドに戻ってから）docker-compose exec mysql bash　を実行<br>
（mysqlコンテナー）mysql -u root -p   の実行後パスワード　root　と入力して実行<br>
（mysql接続後）CREATE DATABASE coachtech1_test;　を実行 (実行後exitコマンドでターミナルまで戻る）<br>
（ターミナルコマンドで　docker-compose exec php bash を実行した後PHPコンテナーにて）php artisan test　を実行してテストをしてください。<br>

<br>

# 伝えること<br>
-  stripe決済のクレジットカード番号は、4242 4242 4242 4242　で有効期限日は未来の日にち、セキュリティー番号とメールアドレス(メール形式で)、名前はなんでも大丈夫です。<br>
-  stripe決済の都合上最低決済金額が50円なので少し余裕を持たせて出品商品の最低金額を100円以上にしてバリデーションを実装しました。<br><br>
-  COACHTECHのロゴをクリックするとトップページに、ログインユーザーが商品詳細画面で自分が出品した商品の購入手続きをクリックするとプロフィールページに、ゲストユーザーが購入手続きへ・ヘッダーのマイページ・出品・コメントを送信するをクリックするとログインページに移動するようになっています。<br><br>
-  PHPUnitのテストファイルはスプレットシートのテストケース一覧のID番号に沿ってFeatureディレクトリーに保存してあります。phpコンテナーで php artisan test を実行してテストをしてください。 <br><br>
-  Route,Controllerは基本設計書に沿ってファイルの中に基本並び替えしています。<br><br>
-  ダミーのユーザーデーターと出品商品データーをシーダーファイルで作りましたので、PHPコンテナーで　php artisan db:seed　を実行してください。<br>
   ダミーのユーザー情報です。<br>
   １：名前:'テスト用のユーザ１'、アドレス:'valid.email@example.com'、パスワード:'testtest1'、出品数：'２品'<br>
   ２：名前:'テスト用のユーザ2'、アドレス:'test@22'、パスワード:'testtest2'、出品数：'２品'<br>
   ３：名前:'テスト用のユーザ3'、アドレス:'test@33'、パスワード:'testtest3'、出品数：'３品'<br>
   ４：名前:'テスト用のユーザ4'、アドレス:'test@44'、パスワード:'testtest4'、出品数：'３品'　　です。メール認証は登録済みでログイン後トップページに移動します。<br><br>
-  プロフィールのユーザー画像を登録していない時は初期画面として、default-profile２.jpgファイルの画像を使っています。<br>


<br>
<br>

# スプレットシートの基本設計書にある項目で追加した内容（模擬案件の時だけ掲載）<br><br>
- Route,Controller関係<br>
　　出品完了処理画面：パス・/thanks_sell　アクション名・thanks_sell_create<br>
　　購入完了処理画面：パス・/thanks_buy　アクション名・thanks_buy_create<br>
　　email認証前再送信ページ前までの処理:パス・/email/verify　アクション名・notice/verify/resend<br>
　　stripe決済の処理：パス・/stripe_success　アクション名・stripeSuccess<br>
　　追加コントローラー名：EmailVerificationController（認証メール関係のコントローラー）<br><br>
- View関係<br>
　　出品完了画面：thanks_sell.blade.php<br>
　　購入完了画面：thanks_buy.blade.php<br>
　　email認証前ページ：verify-email.blade.php<br>
　　stripeカード支払い決済画面：stripe_payment.blade<br><br>
- バリデーション関係<br>
　　ファイル名：ProfileImageRequest.php　内容・ユーザー画像アップロード　ルール・拡張子が.jpegもしくは.png<br>
　　変更（RegisterRequest.phpは作成せずにlang/ja/validation.php）を修正してfortifyの機能でバリデーションしました。<br>
　　変更（LoginRequest.phpは作成せずにlang/ja/validation.php）を修正してfortifyの機能でバリデーションしました。<br>


<br>
<br>

# これからすること、課題（模擬案件の時だけ掲載）<br>
- 09/15 09/15の面談後更新
- <br>

課題<br>
- get引数、routeヘルパーを活用して効率を高めて品質を高める。<br>
- javascriptを活用したのですが、今はできるだけ学習方針のHTML、CSS、Git,MySql,PHP,laravelでできることを深めるためそちらを活用することを考える。<br>
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