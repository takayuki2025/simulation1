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
- バリデーション未実装なので実装する。<br>
- 商品購入後の価格の項目をsold表示にする。itemsテーブルのrimeainを使用。<br>
- 一つのカラムに複数選択可能時の、保存、表示の実装の仕方を考える。<br>
- 検索を絞ったままマイリストに移動できるように実装する<br>
- Laravelの画像編集ライブラリー”Intervention Image”の使用を検討しています。<br>
- 汎用性の高い決済機能、stripeを使用しての開発を考える。<br>
- mailhogを使用してメール認証サービスを実装する<br><br>
課題<br>
- get引数、routeヘルパーを活用して効率を高めて品質を高める。<br>
- javascriptを活用したのですが、今はできるだけ学習方針のHTML、CSS、Git,MySql,PHP,laravelでできることを深めるためそちらを活用することを考える。<br>
- PHPUnitを使用してテストを作成するつもりなのですが外部キーを設定した時のテストの仕方を考えています。<br>


<br>

# 修正履歴（模擬案件の時だけ掲載）<br>
- 

# ER図<br>
<img width="1920" height="1080" alt="Image" src="https://github.com/user-attachments/assets/0403421b-5f2b-4821-9187-6e1b8c62befc" />

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
