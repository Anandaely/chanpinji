<?php
use core\src\Helper\ProductHelper;
use core\src\Repository\AvosProductRepository;
use core\src\Repository\ParseProductRepository;

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

$product_helper = new ProductHelper();
$avos_repository = new AvosProductRepository();
$parse_repository = new ParseProductRepository();
$product = new Product($product_helper, $avos_repository, $parse_repository);
Artisan::add(new SpiderCommand($product));