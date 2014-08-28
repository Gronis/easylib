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
if(array_key_exists('i',$_GET) && array_key_exists('f',$_GET)){
    exec('pkill ffmpeg');
    //exec('pkill ffserver');

    $config = \Easylib\util\Config::get();

    $ffserver = $config["ffserver"];
    $ffmpeg = $config["ffmpeg"];
    $ffprobe = $config["ffprobe"];
    $daemon = "php daemon.php";

    $input = $_GET['i'];
    $feed = $_GET['f'];

    $is_h264 = preg_match("/h264/i",shell_exec("$ffprobe '$input' 2>&1"));

    $protocol = "http";
    $server = "localhost";
    $port = "8090";

    $ffserver_config = "config/ffserver.conf";
    $override_ffserver = true;
    $time = '00:00:00';

    $video_codec = 'libx264';
    $audio_codec = 'libmp3lame';
    $bitrate_video = '1500k';

    if($is_h264){
        $video_codec = 'copy';
        $override_ffserver = false;
    }

    $param = array('-y','-re');
    if($override_ffserver) $param[] = '-override_ffserver';
    $param[] = '-ss ' . $time;
    $param[] = "-i '$input'";
    $param[] = "-acodec " . $audio_codec;
    $param[] = "-vcodec " . $video_codec;
    $param[] = '-b:v ' . $bitrate_video;
    $param[] = '-preset ultrafast';
    $param[] = "-movflags +faststart";
    $param[] = "-tune fastdecode";
    $param[] = "-threads 2";
    $param[] = "$protocol://$server:$port/$feed";

    $params = implode(" ",$param);
    $cmd = "$ffserver -f $ffserver_config & $ffmpeg $params & $daemon";

    echo $cmd;
    echo shell_exec($cmd);

}else{
    exec('pkill ffmpeg');
    exec('pkill ffserver');
}