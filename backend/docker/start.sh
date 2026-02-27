#!/bin/sh

# 启动PHP-FPM
php-fpm -D

# 等待PHP-FPM启动
sleep 2

# 启动Nginx（前台运行）
nginx -g 'daemon off;'
