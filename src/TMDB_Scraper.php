<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 06/08/14
 * Time: 19:44
 */

namespace Easylib;

use TMDB\Client;

class TMDB_Scraper extends Scraper{

    private $db;

    function __construct(){
        $this->db = Client::getInstance(Config::get()[Constants::$TMDB_KEY]);
        $this->db->adult = true;  // return adult content
        $this->db->paged = false; // merges all paged results into a single result automatically
    }

    public function getMovies($fullFilename){
        $results = array();
        $filename = $this->shortPath($fullFilename);
        //if filename is valid
        if($this->valid($filename)){
            $title = $this->valid($filename)? $this->title($filename) : "";
            $year = $this->year($filename);
            echo '--------------------------------'. "\n";
            echo 'Filename: ' . $fullFilename . "\n";
            echo 'Search: ' . $title  . " Year: " . $year . "\n\n";

            //If year is found
            if ($year != 'Unknown'){
                $results = $this->db->search('movie', array('query'=>$title, 'year'=>$year));
            }else{ //there is no year in filename, search anyways
                $results = $this->db->search('movie', array('query'=>$title));
            }
            foreach($results as $result){
                echo 'Found: ' . $result->title . "\n";
            }
            $movie = reset($results);
        }
        return $results;
    }
} 