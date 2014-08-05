<?php
namespace Easylib;
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 30/07/14
 * Time: 17:54
 */

use TMDB\Client;

class Database {
    private $db;
    function __construct(){
        $this->db = Client::getInstance(Config::get()[Constants::$TMDB_KEY]);
        $this->db->adult = true;  // return adult content
        $this->db->paged = false; // merges all paged results into a single result automatically
    }

    /**
     * Get a list of movies from library backend that matches the fileName
     * @param $fileName
     */
    function getMovies($fullFilename){
        $filename = $this->removeLocation($fullFilename);
        $title = $this->title($filename);
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
        return $results;
    }

    private function removeLocation($filename){
        $splitted = preg_split('/\//',$filename);
        return $splitted[count($splitted) - 1];
    }


    /**
     * Returns the text to the point where linebreak or something in the regex is found.
     * @param $regex
     * @param $text
     * @return the string until first occurrence in the regex is noticed
     */
    private function untilFirst($regex, $text){
        return preg_split('/\n/',preg_replace($regex,"\n",$text))[0];
    }

    /**
     * Filter the fileName and attempts to get the media title from it
     * @param $fileName The filename of the file to search for
     */
    function title($fileName){
        // As soon as any of these expressions does occur, skip the rest (Title has already been anounced in the filename)
        $title = $this->untilFirst(
            '/(HDTV|bluray|\w{2,3}rip)|(x264|SWESUB|UNRATED|XViD)|(\w{0,3}SCR)|(SWE|\.avi|\.mkv|\.mp4|DTS)|([^0-9]\d{4})|(\d{3,4}p)|(AC\d)|([^a-zA-Z0-9 \.\-\&]+)/i',
            $fileName);
        //Remove unnessesary chars
        return preg_replace('/\[|\]|\./', ' ', $title);
    }

    /**
     * Filter the fileName and attempts to get the media release year from it
     * @param $fileName The filename of the file to search for
     * return the year as a string if any. otherwise returns "Unknown"
     */
    function year($fileName){
        //if anything in the filename matches a year e.g. 2012 that is not the first thing in the string
        if(preg_match("/([^0-9]+\d{4})/i", $fileName, $matches)){
            return preg_replace('/[^0-9]/','',$matches[0]);
        }else{
            return 'Unknown';
        }
    }




}