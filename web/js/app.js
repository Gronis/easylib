/**
 * Created by robin on 20/08/14.
 */
var last_search;
var movie_time = 0;
var video_path = "";
var video_poster= "";
var video_loading = false;

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

        video.addEventListener("loadedmetadata", function(){
           video.play();
        }, false);

        video.addEventListener('waiting', function() {
            console.log('waiting');
        }, false);

        video.addEventListener('loadedmetadata', function () {

            video.currentTime = movie_time;
        });

        video.addEventListener("error",function () {
            if(!video.paused){
                console.log("error, restarting stream "+ video.src);
                start_stream(video_path, video_poster);
            }
            video_loading = false;

            window.setTimeout(function(){
                if(!video.src != null && video.src.match("/null/i") != null){

                }
            }, 1000);
        }, false);

    }else{
        video.poster = video_poster;
    }

}

function play(source){
    var video = document.getElementsByTagName('video')[0];
    if(video != undefined){
        video.src = source + "?buffer=5";
        video.play();
        video.addEventListener('click',function(){
            video.play();
        },false);

        console.log("Playing: " + video.src);
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
    pause();
    var feed = "feed.ffm";
    var stream = "test.mkv"
    var stream_url = "http://" + window.location.hostname + ":8090/" + stream;
    var stream_ajax_url = "stream.php?i=" + path + "&f=" + feed;

    if(video_loading == false){
        video_loading = true;
        video_path = path;
        video_poster = poster;
        console.log("starting stream: " + stream_ajax_url);
        $.ajax({
            url: stream_ajax_url,
            success : function (data){
                console.log("stream started on server side");
                video_loading = false;
                play(stream_url);
            },
            error: function (data){
                video_loading = false;
            }
        }).done(function( data ) {

        });
        create_videoplayer(stream_url);
    }
    return stream_url;

}

function stop_stream(){
    var video = document.getElementsByTagName('video')[0];
    if(video != undefined){
        video.pause();
        //video.src = null;
    }
    console.log("Killing ffmpeg on server...");
    $.ajax({
        url: "stream.php"
    }).done(function( data ) {
        //this is when the file is done i think
    });
}