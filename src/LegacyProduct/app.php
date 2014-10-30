<?php

require_once '../../vendor/autoload.php';

date_default_timezone_set('Europe/Luxembourg');
ini_set('display_errors', 1);
error_reporting(E_ALL);

use Kata\LegacyProduct\Product;
use Kata\LegacyProduct\ProductDao;

/**
 * my app
 */

define('PRODUCTION_DATABASE_FILE', './product.db');


try {
	$pdo = new \SQLite3(PRODUCTION_DATABASE_FILE);

	$productDao = new ProductDao($pdo);
	// $productDao->createTable();

    //- add my product
    $productChichken       = new Product();
    $productChichken->ean  = '0001';
    $productChichken->name = 'Chicken';

    $resultChicken = $productDao->create($productChichken);
    var_export($resultChicken);

    //- add my product - will delete
    $productTurkey       = new Product();
    $productTurkey->ean  = '0002';
    $productTurkey->name = 'Turkey';

    $resultTurkey = $productDao->create($productTurkey);
    var_export($resultTurkey);

//    $productToUpdate = ProductDao::getByEan('878789');
//    $productToUpdate->name = 'Updated product turkey';
//    $productToUpdate->ean = '9999';
//    $result = ProductDao::modify($productToUpdate);
//    var_export($result);
//
//    $result = ProductDao::getByEan('9999');
//    var_export($result);
//
//    $result = ProductDao::getById(9);
//    var_export($result);
//
//    $result = ProductDao::getById(1);
//    var_export($result);
//
//    $productToDelete = ProductDao::getByEan('878789');
//    $result = ProductDao::delete($productToDelete);
//    var_export($result);
//
//    $result = ProductDao::getByEan('878789');
//    var_export($result);


}
catch (\Exception $e) {
    echo "Exception: " . $e->getMessage()."\n";
}




