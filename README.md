##### DB定義書作成ツール

### docker 環境構築（ローカル環境の構築）
```
# ビルド

make up-build

# library インストール （composerを利用）

make ssh
cd /var/www/html
composer install
composer dumpautoload
```
- WEB APP ： http://localhost/
- phpmyadmin ： http://localhost:4040/
