<?php
namespace Easylib\model;
/**
 * Helper class to hold the Movie Info API calls
 *
 * @link http://help.themoviedb.org/kb/api/movie-info-2
 */
class Movie{

    public $id;
    public $title;
    public $overview;
    public $runtime;
    public $genre;
    public $release_date;
    public $cast;
    public $crew;
    public $rating;
    public $votes;
    public $trailer;
    public $poster_small_url;
    public $poster_medium_url;
    public $poster_large_url;
    public $file_path;

    public function toJSON(){
        return json_encode($this);
    }




    /**
     * Get Cast
     * @return mixed
     */
    //public abstract function casts();

	/**
	 * @link http://help.themoviedb.org/kb/api/movie-keywords
	 */
     //public abstract function keywords();

	/**
	 * @link http://help.themoviedb.org/kb/api/movie-trailers
	 */
     //public abstract function trailers();
	/**
	 * Get similar Movies
	 */
     //public abstract function similar_movies();

	/**
	 * Get the Posters
	 */
     //public abstract function poster();
}
