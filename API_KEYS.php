<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 30/07/14
 * Time: 16:49
 *
 * Here is where all the api keys are stored
 */

class API_KEYS{
    public static function getTMDB(){
        //return getenv(Constants::$TMDB_KEY);//'ENTER YOUR API KEY HERE'; //Get it from https://www.themoviedb.org
        return $_ENV[Constants::$TMDB_KEY];
    }

    public static function setTMDB($key){
        //return putenv(Constants::$TMDB_KEY . "=" . $key);//'ENTER YOUR API KEY HERE'; //Get it from https://www.themoviedb.org
        return $_ENV[Constants::$TMDB_KEY] = $key;
    }
}

?>
