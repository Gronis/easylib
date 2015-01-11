<?php
include_once(__DIR__ . '/../vendor/autoload.php');

/**
 * starts to stream
 *
 * Created by PhpStorm.
 * User: robin
 * Date: 25/08/14
 * Time: 17:14
 */
if(array_key_exists('i',$_GET) && array_key_exists('f',$_GET) && array_key_exists('t',$_GET)){
    exec('pkill ffmpeg');

    if(!file_exists("temp")){
        mkdir("temp");
    }

    $config = \Easylib\util\Config::get();

    $ffserver = $config["ffserver"];
    $ffmpeg = $config["ffmpeg"];
    $ffprobe = $config["ffprobe"];
    $daemon = "php daemon.php";
    $logfile = "temp/logfile";
    $pidfile = "temp/pidfile";

    $input = $_GET['i'];
    $feed = $_GET['f'];
    $time = $_GET['t'] - 5 < 0? 0 : $_GET['t'] - 5;

    $probe_output = shell_exec("$ffprobe \"$input\" 2>&1");

    $is_h264 = preg_match("/h264/i", $probe_output);
    preg_match("/Duration: [\d][\d]:[\d][\d]:[\d][\d].[\d][\d]/",$probe_output, $matches)[0];
    $duration = substr($matches[0],10);
    $duration = strtotime("1970-01-01 $duration UTC");

    $protocol = "http";
    $server = "localhost";
    $port = "8090";

    $ffserver_config = "config/ffserver.conf";
    $override_ffserver = true;
    $video_time = sprintf('%02d:%02d:%02d', ($time/3600),($time/60%60), $time%60);

    $video_codec = 'libx264';
    $audio_codec = 'libmp3lame';

    if($is_h264){
        $video_codec = 'copy';
        $override_ffserver = false;
    }

    $param = array('-y','-re');
    if($override_ffserver) $param[] = '-override_ffserver';
    $param[] = '-ss ' . $video_time;
    $param[] = "-i \"$input\"";
    $param[] = "-acodec " . $audio_codec;
    $param[] = "-vcodec " . $video_codec;
    $param[] = '-preset ultrafast';
    $param[] = "-flags +global_header";
    $param[] = "$protocol://$server:$port/$feed";

    $params = implode(" ",$param);

    //delete old logfile before starting new stream
    unlink($logfile);

    exec_background("$ffserver -f $ffserver_config");
    sleep(0.1);
    exec_background("$ffmpeg $params", $logfile, $pidfile);

    //wait for 2 seconds or error to return ajax request
    while(!preg_match("/(time=00:00:02)|(Conversion failed)/i",file_get_contents($logfile)));

    $return_data['stdout'] = file_get_contents($logfile);
    $return_data['probe'] = $probe_output;
    $return_data['duration'] = $duration;

    echo(json_encode($return_data));

    exec_background($daemon);

}else{
    exec('pkill ffmpeg');
    //exec('pkill ffserver');
}

function exec_background($cmd, $logfile = "/dev/null", $pidfile = "/dev/null"){
    //exec("$cmd > /dev/null 2>/dev/null &");
    exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $logfile, $pidfile));
}

function isRunning($pid){
    try{
        $result = shell_exec(sprintf("ps %d", $pid));
        if( count(preg_split("/\n/", $result)) > 2){
            return true;
        }
    }catch(Exception $e){}

    return false;
}