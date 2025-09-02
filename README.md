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
<br>

<br>

# 修正履歴<br>
- 

# ER図<br>
<img width="1920" height="1080" alt="Image" src="https://github.com/user-attachments/assets/973adda2-8fa4-4c8b-83a0-4db581afef88" />

# 使用技術<br>
  - PHP 8.1.33
  - Laravel 8.83.8
  - MySql 8.3
<br>

# URL<br>
  - フリマアプリトップページ： http://localhost/
  - ユーザー登録： http://localhost/register/
  - phpMyAdmin:http://localhost:8080/
