##### DB定義書作成ツール

## docker 環境構築
```
make up-build
```
- WEB：http://localhost/
- phpmyadmin：http://localhost:4040/

## library インストール （composerを利用）
```
make ssh
cd /var/www/html
composer install
composer dumpautoload
```