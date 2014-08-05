<?php
namespace Easylib;
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 30/07/14
 * Time: 17:59
 */

use Easylib\Constants;
use Easylib\Config;
use Easylib\Database;

class Application{
    public function help(){
        return Constants::$HELP_TEXT;
    }

    public function scan($param){
        $files = array();
        if(count($param) > 0){
            foreach($param as $path){
                $files = array_merge($files,Filesystem::search($path,Config::get()[Constants::$FORMATS]));
            }
        }else{
            $files = Filesystem::search('.',Config::get()[Constants::$FORMATS]);
        }

        $db = new Database();
        foreach($files as $file){
            $db->getMovies($file);
        }

        //print_r($files);

    }

    /**
     * Get or sets a config parameter
     * @param $param An array parameters.
     * To set a config key, the array should have the format array[ {key=value} ]
     * e.g. "TMDB=new_api_key"
     *
     * To get a config key, the array should have the format array[ {key} ]
     *
     * e.g. "TMDB"
     */
    public function config($param){
        $result = "";
        $config = Config::get();
        for($i = 0; $i < count($param); $i++){
            $p = $param[$i];
            //is it a get method? (doesn't contains "=" sign)
            $isGet = !preg_match('/.+\=.+/',$p);
            if($isGet){
                if(array_key_exists($p,$config)){
                    $result = $config[$p] . "\n";
                }
            }else{
                $splited = preg_split('/\=/',$p);
                $field = $splited[0];
                $value = $splited[count($splited) - 1];
                if(array_key_exists($field,$config)){
                    $config[$field] = $value;
                }
            }
        }
        Config::set($config);
        return $result;
    }



}
