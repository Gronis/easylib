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

var lastScrollTop = 0;

var video_current_duration = function(){
    return video_start_time + video_current_time;
}

var has_video = function(){
    video = $("video")[0];
    return video != undefined && video.src.match("test") != null;
}

var should_video_play = function(){
    return $("div#media_player").length != 0;
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
    //$("div.navbar-fixed-top").autoHidingNavbar();
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

//scrolling
$(window).scroll(function(event){
    var scroll_from_top = $(document).scrollTop();
    if(is_minimized() && scroll_from_top < $("div#player-block").height() && $("div#player-block").is(":visible")){
        $("div#player-block").hide();
        $(document).scrollTop(0);
        console.log("top:" + scroll_from_top);
    }
    var st = $(this).scrollTop();
    if (st > lastScrollTop){
        // Down scroll
        if(scroll_from_top > video_height() * 2){
            //minimize_player();
        }
    } else {
        // Up scroll

        if(scroll_from_top < video_height() * 2){
            //maximize_player();
            console.log(scroll_from_top);
        }
    }
    lastScrollTop = st;
});

function video_duration_changed(){
    var slider_value = parseInt($("#video-duration-slider").val());
    var percent_new = slider_value * 100 / video_total_duration;
    var percent_old = video_current_duration() * 100 / video_total_duration;
    if(percent_old + 2 > percent_new &&
       percent_old - 2 < percent_new){
        $("#video-duration-slider").blur();
    }else{
        //start at time
        restart_stream(slider_value);
        console.log("Time changed: " + slider_value);
    }
}

function search(search){
    console.log(search);
    minimize_player();
    $("div#player-block").hide();
    $("div#card-layout").html("<div class='loading-circle'></div>");

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

function focus_search_input(){
    $("div.navbar").addClass("typing");
    minimize_player();
}

function drop_search_input(){
    $("div.navbar").removeClass("typing");
}

function create_videoplayer(source){
    video = $("video")[0];

    if(video == undefined){
        movie = {path: video_path, poster: video_poster};
        console.log("creating player: " + source);
        var template = $('#movie-player-template').html();
        var html = Mustache.to_html(template, movie);
        $("div#player-layout").html(html);
        show_player();
        video = $("video")[0];

        video.addEventListener('click',function(){
            maximize_player();
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
                var percent = (video_current_duration() / video_total_duration * 100) + "%";
                document.getElementById("video-duration-slider-level").style.width = percent;
                if(!$("#video-duration-slider").is(":focus")){
                    $("#video-duration-slider").val(video_current_duration());
                }
                console.log('updated, current time: ' + video_current_duration() + " percent: " + percent);
            }
        }, false);

        video.addEventListener('loadeddata', function () {
            stream_start_time = video.currentTime;
            console.log('data loaded, starting time: ' + stream_start_time );
        });

        video.addEventListener("error",function () {

            if(!should_video_play()){
                //should not handle error when media player closes
                console.log("Video player has been closed.");
                return;
            }
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

function destroy_videoplayer(){
    hide_player();
    stop_stream();
    $("div#media_player").remove();
    $("div.navbar").removeClass("watching", 1000, "easeInBack");
}

function hide_player(){
    $("div#player-layout").hide();
    $("div#player-block").hide();
}

function show_player(){
    $("div#player-layout").show();
    $("div#player-block").show();
}

function minimize_player(){
    if(should_video_play()){
        //hide block if is visible
        if($(document).scrollTop() < video_height()){
            $(document).scrollTop(0);
            $("div#player-block").hide();
        }else{
            $("div#player-block").show();
        }
        $("div.navbar").removeClass("watching", 1000, "easeInBack");
        $("div#player-layout").addClass("media_player_minimized", 1000, "easeInBack");
    }
}

function maximize_player(){
    if(should_video_play()){
        $("div.navbar").addClass("watching");
        $("div#player-block").show();
        $("div#player-layout").removeClass("media_player_minimized");
    }
}

function is_minimized(){
    return $("div#player-layout").hasClass("media_player_minimized");
}

function video_height(){
    return $("div#player-layout").height();
}

function video_width(){
    return $("div#player-layout").width();
}

function video_toggle_play_pause(){

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

function start_video(path, poster){
    start_stream(path, poster);
    var scroll = $(document).scrollTop();
    maximize_player();
    console.log(scroll);
    $(document).scrollTop(scroll);
}

function restart_stream(duration){
    if(duration != undefined){
        video_start_time = duration;
        video_current_time = 0;
    }
    start_stream(video_path, video_poster)
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
                $("#video-duration-slider").attr('max', video_total_duration);
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