<?php
namespace Easylib;
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 30/07/14
 * Time: 17:59
 */

use Easylib\model\Library;
use Easylib\scrapers\Scraper;
use Easylib\util\Config;
use Easylib\util\Constants;
use Easylib\util\Filesystem;

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

        $lib = new Library();
        $scraper = Scraper::get();

        foreach($files as $file){
            $movie = $scraper->search_movie($file);
            if($movie != null){
                print_r($movie);
                $lib->movies[$movie->id] = $movie;
            }
        }
        $lib->save();

    }

    public function search($param){
        if(count($param) < 1) return;
        //implode arguments with regex or sign
        $match_values = '/' .implode('|',$param) . '/i';
        $lib = Library::load();
        return $lib->search($match_values);
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
