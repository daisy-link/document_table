FROM php:8.1-apache

# 必要なパッケージをインストール
RUN apt-get update && \
    apt-get install -y libzip-dev zlib1g-dev \
    && docker-php-ext-install mysqli pdo_mysql zip \
    && a2enmod rewrite \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get install -y zlib1g-dev libpng-dev libjpeg62-turbo-dev wget gnupg \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# PostgreSQL拡張のインストール
RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer \
    | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin \
    && chown www-data /var/www \
    && chmod g+s /var/www/html

# SQL Server拡張のインストール
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/debian/11/prod.list > /etc/apt/sources.list.d/mssql-release.list

RUN apt-get update
RUN ACCEPT_EULA=Y apt-get install -y msodbcsql18
RUN ACCEPT_EULA=Y apt-get install -y mssql-tools18
RUN echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> ~/.bashrc
RUN . ~/.bashrc
RUN apt-get install -y --allow-downgrades unixodbc-dev unixodbc odbcinst1debian2 odbcinst
RUN apt-get install -y --allow-downgrades libgssapi-krb5-2

RUN pecl install sqlsrv
RUN pecl install pdo_sqlsrv
RUN docker-php-ext-enable sqlsrv
RUN docker-php-ext-enable pdo_sqlsrv