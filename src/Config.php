<?php
namespace Easylib;
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 30/07/14
 * Time: 16:49
 *
 * Here is where all the api keys are stored
 */

class Config{
    public static function get(){
        /*** turn on errors ***/
        error_reporting(E_ALL);

        /*** parse the ini file ***/
        return parse_ini_file(__DIR__ . "/../" . Constants::$CONFIG_FILENAME, 1);
    }

    public static function set($array)
    {
        //get lines from file (split on break line)
        $lines = preg_split('/\n/',file_get_contents(Constants::$CONFIG_FILENAME));
        //replace all changed configs.
        foreach(array_keys($array) as $key){
            foreach(array_keys($lines) as $lineNr){
               if(preg_match('/.*'. $key . '.+/',$lines[$lineNr]) &&
                 !preg_match('/.*;.+/i',$lines[$lineNr])){
                    $lines[$lineNr] = $key . " = " . $array[$key];
                    break;
                }
            }
        }
        //save line as config and separate each line with EOL sign
        file_put_contents(__DIR__ . "/../" . Constants::$CONFIG_FILENAME, implode(PHP_EOL,$lines));
    }
}

?>
