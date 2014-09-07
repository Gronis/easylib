<?php
/**
 * Used to get
 *
 * Created by PhpStorm.
 * User: robin
 * Date: 23/08/14
 * Time: 17:25
 */
include_once(__DIR__ . '/../vendor/autoload.php');
use Easylib\Application;

$app = new Application();

//if the user searches for something:
if(array_key_exists('search',$_GET)){
    echo json_encode($app->search($_GET['search']), JSON_UNESCAPED_SLASHES);
}

