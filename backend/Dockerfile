# 使用多平台支持的PHP基础镜像
FROM php:8.2-fpm-alpine AS builder

# 安装系统依赖和PHP扩展
RUN apk add --no-cache \
    git \
    unzip \
    curl \
    libzip-dev \
    oniguruma-dev \
    linux-headers \
    && docker-php-ext-install pdo pdo_mysql mysqli zip mbstring opcache

# 安装Redis扩展
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# 安装Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 设置工作目录
WORKDIR /var/www/html

# 复制composer文件
COPY composer.json composer.lock* ./

# 安装PHP依赖
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 复制应用代码
COPY . .

# 重新生成autoload和服务发现
RUN composer dump-autoload --optimize

# 设置目录权限
RUN mkdir -p runtime && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/runtime

# 生产镜像
FROM php:8.2-fpm-alpine

# 安装运行时依赖和PHP扩展
RUN apk add --no-cache \
    libzip-dev \
    oniguruma-dev \
    nginx \
    && docker-php-ext-install pdo pdo_mysql mysqli zip mbstring opcache

# 安装Redis扩展
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# 复制Composer（用于后续可能的依赖安装）
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 复制Nginx配置
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/http.d/default.conf

# 从构建阶段复制应用（包含vendor目录）
COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html

# 创建必要的目录
RUN mkdir -p /var/www/html/runtime /run/nginx \
    && chown -R www-data:www-data /var/www/html/runtime

# 复制启动脚本
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

WORKDIR /var/www/html

EXPOSE 80

CMD ["/start.sh"]
