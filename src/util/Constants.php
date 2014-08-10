<?php
namespace Easylib\util;
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 31/07/14
 * Time: 10:02
 */

class Constants{
    public static $SCRAPER = "scraper";
    public static $TMDB = "tmdb";
    public static $TMDB_KEY = "tmdb_key";
    public static $FORMATS = "formats";
    public static $CONFIG_FILENAME = "config.ini";
    public static $LIBRARY = "library";
    public static $HELP_TEXT =
"Usage: easylib help => Print this help text
       easylib scan {path} => Scans the filesystem for new media
       easylib config {property} => Gets or sets properties from the config file

";


}