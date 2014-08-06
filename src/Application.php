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
use Easylib\Scraper;

class Application{
    public function help(){
        return Constants::$HELP_TEXT;
    }

    /**
     * Scan for media
     *
     * This method will search the filesystem for media with formats (specified in the config file)
     * and search them with the specified scraper (specified in config file).
     *
     * @param $param An array with paths where to search for media. Current path is used if no path is specified
     *
     */
    public function scan($param){

        $files = array();

        if(count($param) > 0){
            foreach($param as $path){
                $files = array_merge($files,Filesystem::search($path));
            }
        }else{
            $files = Filesystem::search();
        }

        switch(Config::get()[Constants::$SCRAPER]){
            case Constants::$TMDB:
                $scraper = new TMDB_Scraper();
                break;
            default:
                return 'Cannot read scraper from config file';
        }
        foreach($files as $file){
            $scraper->getMovies($file);
        }
    }

    /**
     * Get or sets a config parameter
     * @param $param An array parameters.
     * To set a config key, the array should have the format array[ {key=value} ]
     * e.g. "tmdb=new_api_key"
     *
     * To get a config key, the array should have the format array[ {key} ]
     *
     * e.g. "tmdb"
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
