<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 08/08/14
 * Time: 19:18
 */

namespace Easylib\model;


use Easylib\util\Config;
use Easylib\util\Constants;

class Library {

    function __construct(){
        $this->movies = array();
        $this->persons = array();
        $this->series = array();
    }

    public $movies;
    public $persons;
    public $series;

    /**
     * Searches for media. if any media file contains anything that matches the
     * search string, the media will be returned in the result.
     * @param $match_values - A regular expression (regex) that the result should match e.g '/search/i'
     * @return Library - A new library with only the search result
     */
    public function search($match_values, $reject_values = '/^.]/', $match_keys = '/./',
            $reject_keys = '/^(poster)|(trailer)|(file_path)|(overview)|(crew)|(cast)/i'){
        $lib = new Library();

        foreach($this->movies as $id => $movie){
            $relevance = $this->search_inner($match_values, $reject_values, $match_keys, $reject_keys, $id, $movie);
            if(!is_null($relevance)){
                $movie->{'relevance'} = $relevance;
                $lib->movies[$movie->id] = $movie;
            }
        }
        return $lib;
    }

    /**
     * Inner recursive function for search
     * @param $match_values regex for values to match.
     * @param $reject_values regex for values to reject.
     * @param $match_keys regex for keys to match.
     * @param $reject_keys regex for keys to reject.
     * @param $key key of the current
     * @param $value
     * @return null
     */
    private function search_inner($match_values, $reject_values, $match_keys, $reject_keys, $key, $value){
        //if is array or object, continue to search
        if(is_array($value) || is_object($value)){
            //continue only if key matches conditions
            if(!preg_match($reject_keys, $key) && preg_match($match_keys, $key)){
                foreach($value as $inner_key => $inner_value){
                    $relevance = $this->search_inner($match_values, $reject_values, $match_keys, $reject_keys,
                        $inner_key, $inner_value);
                    if(!is_null($relevance)){
                        return $relevance;
                    }
                }
            }
        } else{
            if( preg_match($match_values, $value,$relevance) && !preg_match($reject_values, $value) ){
                return $this->search_relevance($relevance[0],$value);
            }

        }
        return null;
    }

    /**
     * Returns a number how well the match matches with value. Lower is better. Perfect match is 0.
     * @param $match a string with the text to match with value.
     * @param $value a string with the tet to match.
     */
    private function search_relevance($match, $value){
        return abs(strlen($match) - strlen($value));
    }

    public function save($path = null){
        if($path == null){
            $path = __DIR__ . "/../../" . Config::get()[Constants::$LIBRARY];
        }
        $json = json_encode($this);
        file_put_contents($path,$json);
    }

    public static function load($path = null){
        if($path == null){
            $path = __DIR__ . "/../../" . Config::get()[Constants::$LIBRARY];
        }

        $lib = new Library();

        if(file_exists($path)){
            $json = file_get_contents($path);
            $data = json_decode($json);

            //copy instance variables
            foreach ($data as $key => $value) {
                $lib->{$key} = $value;
            }
        }
        return $lib;
    }

} 