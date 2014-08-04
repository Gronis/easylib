<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 30/07/14
 * Time: 16:49
 *
 * Here is where all the api keys are stored
 */

function getConfig(){
    /*** turn on errors ***/
    error_reporting(E_ALL);

    /*** parse the ini file ***/
    return parse_ini_file(Constants::$CONFIG_FILENAME, 1);
}

function setConfig($array)
{
    $lines = preg_split('/\n/',file_get_contents(Constants::$CONFIG_FILENAME));
    //replace all changed configs.
    foreach(array_keys($array) as $key){
        foreach(array_keys($lines) as $lineNr){
            if(preg_match('/' . $key . '.+/',$lines[$lineNr])){
                $lines[$lineNr] = $key . " = " . $array[$key];
                break;
            }
        }
    }
    //save line as config and separate each line with EOL sign
    file_put_contents(Constants::$CONFIG_FILENAME,implode(PHP_EOL,$lines));
}
?>
