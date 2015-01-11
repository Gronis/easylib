/**
 * Created by robin on 20/08/14.
 */
var last_search;
var stream_start_time = 0;
var stream_current_time = 0;
var video_start_time = 0;
var video_current_time = 0;
var video_total_duration = 0;
var video_path = "";
var video_poster= "";
var video_loading = false;

var video_current_duration = function(){
    return video_start_time + video_current_time;
}

var has_video = function(){
    video = $("video")[0];
    return video != undefined && video.src.match("test") != null;
}

$( document ).ready(function() {
    console.log( "ready!" );

    //init autohide navbar
    init_autohide_navbar();

    //init masonry
    init_masonry();
});

window.onunload = function(){
    stop_stream();
    console.log("stopped stream");
};

function init_autohide_navbar(){
    $("div.navbar-fixed-top").autoHidingNavbar();
}

function init_masonry(){
    var container = document.querySelector('#card-layout');

    var masonry = new Masonry(container, {
        columnWidth: 10,
        itemSelector: '.card',
        isFitWidth: false,
        isAnimated: false,
        transitionDuration: 0
    });
    /*window.addEventListener('resize', function(event){
     if(document.body.clientWidth < 820){
     mansory.isFitWidth = false;
     }else{
     mansory.isFitWidth = true;
     }
     });*/
}

function search(search){
    console.log(search);

    var this_search = new Date().getTime();
    last_search = this_search;

    //ajax request to for the search:
    $.ajax({
        url: "get.php?search=" + search
    }).done(function( data ) {
        if ( console && console.log ) {
            if(last_search == this_search){
                var lib = JSON.parse(data);
                var movies = [];
                var html = "";

                //get movies
                for(var movie in lib.movies){
                    movies.push([movie, lib.movies[movie]]);
                    html += movie_to_html(lib.movies[movie]);
                }
                $("div#card-layout").html(html);
                init_masonry();
            }
        }
    });
}

function create_videoplayer(source){
    video = $("video")[0];

    if(video == undefined){
        movie = {path: video_path, poster: video_poster};
        console.log("creating player: " + source);
        var template = $('#movie-player-template').html();
        var html = Mustache.to_html(template, movie);
        $("div#player-layout").html(html);

        video = $("video")[0];

        video.addEventListener('click',function(){
            if(video.errorCode != undefined){
                console.log("Cannot resume stream, restarting stream");
                start_stream(video_path,video_poster);
            }
            if(video.readyState == 4){
                video.play();
            }
        },false);

        video.addEventListener("loadedmetadata", function(){
            play();
        }, false);

        video.addEventListener('waiting', function() {
            console.log('waiting');
        }, false);

        video.addEventListener('timeupdate', function() {
            if(!video.paused){
                stream_current_time = video.currentTime;
                video_current_time = stream_current_time - stream_start_time;
                console.log('updated, current time: ' + video_current_duration());
            }
        }, false);

        video.addEventListener('loadeddata', function () {
            stream_start_time = video.currentTime;
            console.log('data loaded, starting time: ' + stream_start_time );
        });

        video.addEventListener("error",function () {
            console.log("error, networkState: " + video.networkState +  ", has video: " + has_video());

            if(has_video() && (!video.paused ||
                    video.networkState == HTMLMediaElement.NETWORK_NO_SOURCE ||
                    video.networkState == HTMLMediaElement.NETWORK_IDLE)){
                console.log("error, restarting stream "+ video.src);
                start_stream(video_path, video_poster);
            }
            window.setTimeout(function(){
                if(!video.src != null && video.src.match("/null/i") != null){

                }
            }, 1000);
        }, false);

        video.addEventListener("ended",function () {
            if(video_total_duration > video_current_duration()){
                console.log("stream died suddenly, restarting "+ video.src);
                start_stream(video_path, video_poster);
            }
        }, false);

    }else{
        video.poster = video_poster;
    }

}

function play(source){
    var video = document.getElementsByTagName('video')[0];
    if(video != undefined){
        if(!has_video() && source != undefined){
            video.src = source + "?buffer=2";
        }
        if(video.paused){
            if(video.readyState == 4){
                video.play();
                console.log("Playing: " + video.src);
            }else{
                window.setTimeout(function(){
                    console.log("Not ready to play, retrying...");
                    play();
                },200);
            }
        }
    }else{
        console.log("Can't play video: " + (video != undefined? video.readyState : "video element missing"));
    }
}

function pause(){
    var video = document.getElementsByTagName('video')[0];
    if(video != undefined){
        video.pause();
    }
}

function movie_to_html(movie){
    var template = $('#movie-card-template').html();
    var html = Mustache.to_html(template, movie);
    return html;
}

function start_stream(path, poster){
    stop_stream();
    //restart video when starting to stream new video
    if(!video_loading && path != video_path){
        video_start_time = 0;
        video_current_time = 0;
        stream_start_time = stream_current_time;
    }
    var feed = "feed.ffm";
    var stream = "test.mkv"
    var stream_url = "http://" + window.location.hostname + ":8090/" + stream;
    var stream_ajax_url = "stream.php?i=" + path + "&f=" + feed + "&t=" + video_current_duration();

    if(!video_loading){
        video_start_time += video_current_time;
        video_current_time = 0;
        video_loading = true;
        video_path = path;
        video_poster = poster;
        console.log("starting stream: " + stream_ajax_url);
        $.ajax({
            url: stream_ajax_url,
            success : function (data){
                var json_data = JSON.parse(data);
                video_total_duration = json_data.duration;
                console.log("stream started on server side, duration: " + video_total_duration);
                video_loading = false;
                play(stream_url);
            },
            error: function (data){
                video_loading = false;
                console.log("Something when wrong on server side when starting steam, restarting stream");
                start_stream(path,poster);
            }
        }).done(function( data ) {

        });
        create_videoplayer(stream_url);
    }
    pause();
    return stream_url;

}

function stop_stream(){
    var video = document.getElementsByTagName('video')[0];
    if(video != undefined){
        video.pause();
        video.src = "";
    }
    /*
    console.log("Killing ffmpeg on server...");
    $.ajax({
        url: "stream.php"
    }).done(function( data ) {
        //this is when the file is done i think
    });*/
}