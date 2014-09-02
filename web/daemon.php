<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 28/08/14
 * Time: 21:48
 */

run();

function run(){

    $last_check = false;



    while(true){
        sleep(10);

        $html = get_html("http://localhost:8090/stat.html");
        $bitrate = get_bitrate($html);

        if($bitrate == 0 || is_null($html) && $last_check){
            kill("ffserver");
            break;
        }
        $last_check = $bitrate == 0 || is_null($html);
    }
}

function get_html($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

function is_streaming($html, $stream){
    return preg_match("/($stream)/i",$html);
}

function get_bitrate($html){
    $matches = array();
    preg_match("/(Bandwidth in use:).*\//i",$html,$matches);
    preg_match("/[0-9]+/i",$matches[0],$matches);
    return $matches[0];
}

function kill($service){
    shell_exec("pkill $service");
}