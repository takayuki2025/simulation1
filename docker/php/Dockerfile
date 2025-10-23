FROM php:8.1-fpm

# 必要なシステムパッケージとPHP拡張機能をインストール
RUN apt update \
  # 必要なライブラリと開発ツールをインストール
  && apt install -y git default-mysql-client zlib1g-dev libzip-dev libpng-dev libjpeg-dev libwebp-dev unzip \
  # 依存関係のないパッケージのキャッシュをクリア
  && rm -rf /var/lib/apt/lists/* \
  
  # PHP拡張機能の設定とインストールを一つのコマンドで実行
  && docker-php-ext-configure gd --with-jpeg --with-webp \
  && docker-php-ext-install -j$(nproc) gd pdo_mysql zip bcmath # ★ 全てをこの行に集約

# Composerのインストール (公式推奨の最新版)
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
# ... (以下、MailHogとphp.iniのコピーはそのまま)

# MailHog用sendmail互換ツール
RUN curl -sL https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 -o /usr/local/bin/mhsendmail \
  && chmod +x /usr/local/bin/mhsendmail

RUN apt-get update && apt-get install -y wget && \
    wget -q https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh -O /usr/local/bin/wait-for-it.sh && \
    chmod +x /usr/local/bin/wait-for-it.sh && \
    rm -rf /var/lib/apt/lists/*

# php.iniのコピー（ボリュームマウントされている場合は不要な場合あり）
COPY php.ini /usr/local/etc/php/

# ワークディレクトリの設定
WORKDIR /var/www