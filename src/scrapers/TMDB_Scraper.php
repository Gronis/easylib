<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 06/08/14
 * Time: 19:44
 */

namespace Easylib\scrapers;

use Easylib\util\Config;
use Easylib\util\Constants;

use TMDB\Client;
use Easylib\model\Movie;

class TMDB_Scraper extends Scraper{

    private $db;

    function __construct(){
        $this->db = Client::getInstance(Config::get()[Constants::$TMDB_KEY]);
        $this->db->adult = true;  // return adult content
        $this->db->paged = false; // merges all paged results into a single result automatically
    }

    public function search_movie_dependent($full_filename, $title, $year = "Unknown"){

        //If year is found
        if ($year != 'Unknown'){
            $results = $this->db->search('movie', array('query'=>$title, 'year'=>$year));
        }else{ //there is no year in filename, search anyways
            $results = $this->db->search('movie', array('query'=>$title));
        }
        foreach($results as $result){
            echo 'Found: ' . $result->title . "\n";

            //create link
            $file_link = "library/" . $this->file($full_filename);
            if(!file_exists(__DIR__ ."/../../web/library")){
                mkdir(__DIR__ ."/../../web/library");
            }
            if(file_exists(__DIR__ ."/../../web/" . $file_link)){
                unlink(__DIR__ ."/../../web/" . $file_link);
            }
            print(__DIR__ ."/../../web/" . $file_link);
            symlink($full_filename, __DIR__ ."/../../web/" . $file_link);

            //fetch info form TMDB
            $info = $this->db->info('movie', $result->id);

            $genres = array();
            foreach($info->genres as $genre){
                $genres[$genre->id] = $genre->name;
            }

            $persons = $result->casts();
            $cast = array();
            foreach($persons['cast'] as $person){
                $cast[$person->id] = $person->name;
            }

            $crew = array();
            foreach($persons['crew'] as $person){
                $crew[$person->id] = $person->name;
            }

            $keywords = array();
            foreach($result->keywords()->keywords as $keyword){
                $keywords[$keyword->id] = $keyword->name;
            }

            $trailer = '';
            foreach($result->trailers()->youtube as $clip){
                if($clip->type == 'Trailer'){
                    $trailer = $clip->source;
                    break;
                }
            }



            $movie = new Movie();

            $movie->id = $info->id;
            $movie->imdb_id = $info->imdb_id;
            $movie->title = $info->title;
            $movie->overview = $info->overview;
            $movie->runtime = $info->runtime;
            $movie->genre = $genres;
            $movie->release_date = $info->release_date;
            $movie->cast = $cast;
            $movie->crew = $crew;
            $movie->tags = $keywords;
            $movie->rating = $info->vote_average;
            $movie->votes = $info->vote_count;
            $movie->trailer = $trailer;
            $movie->poster_small_url = $this->db->image_url('poster','w185',$result->poster_path);
            $movie->poster_medium_url = $this->db->image_url('poster','w342',$result->poster_path);
            $movie->poster_large_url = $this->db->image_url('poster','w500',$result->poster_path);
            $movie->file_path = $file_link;

            //print_r($movie);

            return $movie;
        }
        return null;
    }
} 