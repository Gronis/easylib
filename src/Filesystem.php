<?php
/**
 * Created by PhpStorm.
 * User: robin
 * Date: 05/08/14
 * Time: 21:38
 */

namespace Easylib;


class Filesystem {
    /**
     * Searches for files with a specific formats.
     * @param string $dir The directory to search in, standard is current dir (not recommended)
     * @param string $formats What formats that are accepted. Standard is everything
     * @param bool $recursive Whenever this search is recursive of not. Beware to use recursive at the root
     * in the file hierarchy.
     * @return array An array which contains an array of strings where all the files are located.
     */
    public static function search($dir = '.', $formats = null, $recursive = true){
        if($formats == null){
            $formats = Config::get()[Constants::$FORMATS];
        }
        $dir = rtrim($dir, '/');
        return Filesystem::search_req($dir,$formats,$recursive);
    }

    public static function search_req($dir, $formats, $recursive = true){
        // remove up dirs
        $scanned_dir = array_diff(scandir($dir), array('..', '.'));
        $result = array();

        //recursive search
        if($recursive){
            foreach($scanned_dir as $file){
                if(is_dir($dir . '/' . $file)){
                    $result = array_merge($result, Filesystem::search_req($dir . '/' . $file,$formats,$recursive));
                }else{
                    $result[] = $dir . '/' . $file;
                }
            }
        }

        // filter fileformats
        $result = preg_grep($formats, $result);
        return $result;
    }

} 