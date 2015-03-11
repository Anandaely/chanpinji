## 环境要求

PHP 5.4.X以上

数据存储使用了leancloud或者parse.com

## 安装

1.composer update

2.给app/storage权限，chmod -R 777 app/storage

3.app/config/avos.php配置相应选项，app/config/parse.php配置相应选项

4.app/config/database.php配置使用哪个云数据平台，

'cloud' => 'parse',是parse.com

'cloud' => 'avos',是leancloud

5.抓取数据，在命令行下执行php artisan fetch:data all，一次性抓取mindstore，next，producthunt的数据。

抓取单一数据分别是php artisan fetch:data mindstore，php artisan fetch:data next，php artisan fetch:data producthunt