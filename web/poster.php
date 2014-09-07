<?php
include_once(__DIR__ . '/../vendor/autoload.php');

/**
 * generates a poster returns when generation is done
 *
 * Created by PhpStorm.
 * User: robin
 * Date: 25/08/14
 * Time: 17:14
 */
if(array_key_exists('i',$_GET)){
    $config = \Easylib\util\Config::get();

    $ffmpeg = $config["ffmpeg"];
    $posterfile = "temp/poster.png";

    $input = $_GET['i'];

    $poster_time = '00:01:00';
    $width = 320;
    $height = 240;

    $param = array();
    $param[] = '-y';
    $param[] = '-ss ' . $poster_time;
    $param[] = "-i '$input'";
    $param[] = "-vf scale=$width:$height";
    $param[] = '-r 1';
    $param[] = '-t 1';
    $param[] = '-f image2';
    $param[] = "$posterfile";

    $params = implode(" ",$param);

    exec("$ffmpeg $params");

    echo $posterfile;

}