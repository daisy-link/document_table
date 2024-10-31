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

# 下層階層に設置する場合

```
cd /var/www/html
mv .env.example .env
```
上記の .env の ROOT_PATH にパスを指定



### 簡単な利用方法

https://github.com/daisy-link/document_table/blob/master/manual/simple.pdf
