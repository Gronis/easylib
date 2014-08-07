<?php
namespace Easylib\model;

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

}
