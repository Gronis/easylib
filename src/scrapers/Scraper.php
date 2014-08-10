<?php
namespace Easylib\scrapers;
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 30/07/14
 * Time: 17:54
 */

use Easylib\util\Config;
use Easylib\util\Constants;
use Easylib\model\Movie;
/**
 * Class Scraper
 *
 * A Scraper fetches media information from another source
 * this is an abstract scraper that every other scraper uses.
 *
 * @package Easylib
 */
abstract class Scraper {

    public static function get(){
        switch(Config::get()[Constants::$SCRAPER]){
            case Constants::$TMDB:
                return new TMDB_Scraper();
                break;
            default:
                return null;
        }
    }
    /**
     * Get a movie from the library backend that matches the fileName
     * @param $full_filename The filename starting from root e.g /home/user/Sentinel.mp4
     */
    public function search_movie($full_filename){
        if($this->valid($full_filename)){
            $filename = $this->file($full_filename);
            $folder = $this->folder($full_filename);
            $result = $this->search_movie_inner($full_filename,$filename);

            if(is_null($result)){
                $result =  $this->search_movie_inner($full_filename,$folder);
            }
            return $result;
        }
    }

    private function search_movie_inner($full_filename, $search){
        $title = $this->title($search);
        $year = $this->year($search);

        echo "\n" . '--------------------------------'. "\n";
        echo 'Filename: ' . $full_filename . "\n";
        echo 'Search: ' . $title  . " Year: " . $year . "\n";

        return $this->search_movie_dependent($full_filename, $title,$year);

    }

    public abstract function search_movie_dependent($full_filename, $title, $year = 'Unknown');

    /**
     * Removes path in the front if any
     * @param $filename
     * @return mixed
     */
    protected function file($filename){
        $splitted = preg_split('/\//',$filename);
        return $splitted[count($splitted) - 1];
    }

    /**
     * Removes path in the front if any
     * @param $filename
     * @return mixed
     */
    protected function folder($filename){
        $splitted = preg_split('/\//',$filename);
        return $splitted[count($splitted) - 2];
    }

    protected function valid($filename){
        return !preg_match('/.*sample.*/i',$filename);
    }

    /**
     * Returns the text to the point where linebreak or something in the regex is found.
     * @param $regex
     * @param $text
     * @return the string until first occurrence in the regex is noticed
     */
    protected function untilFirst($regex, $text){
        return preg_split('/\n/',preg_replace($regex,"\n",$text))[0];
    }

    /**
     * Filter the fileName and attempts to get the media title from it
     * @param $fileName The filename of the file to search for
     */
    protected function title($fileName){
        // As soon as any of these expressions does occur, skip the rest (Title has already been anounced in the filename)
        $title = $this->untilFirst(
            '/(HDTV|bluray|\w{2,3}rip)|(x264|SWESUB|UNRATED|XViD)|(\w{0,3}SCR)|(CD\d{1,2})|(SWE|\.avi|\.mkv|\.mp4|DTS)|([^0-9]\d{4})|(\d{3,4}p)|(AC\d)|([^\wÅÄÖåäö \'\.\-\_\&]+)/i',
            $fileName);
        //Remove unnessesary chars
        return preg_replace('/\[|\]|\.|\_|\-/', ' ', $title);
    }

    /**
     * Filter the fileName and attempts to get the media release year from it
     * @param $fileName The filename of the file to search for
     * return the year as a string if any. otherwise returns "Unknown"
     */
    protected function year($fileName){
        //if anything in the filename matches a year e.g. 2012 that is not the first thing in the string
        if(preg_match("/([^0-9]+\d{4})/i", $fileName, $matches)){
            return preg_replace('/[^0-9]/','',$matches[0]);
        }else{
            return 'Unknown';
        }
    }






}