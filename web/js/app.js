/**
 * Created by robin on 20/08/14.
 */
var last_search;
var movie_time = 0;

$( document ).ready(function() {
    console.log( "ready!" );

    //init autohide navbar
    init_autohide_navbar();

    //init masonry
    init_masonry();
});

window.onunload = function(){
    stop_stream();
    alert("stopped stream");
    console.log("stopped stream");
};

function init_autohide_navbar(){
    $("div.navbar-fixed-top").autoHidingNavbar();
}

function init_masonry(){
    var container = document.querySelector('#content');

    var masonry = new Masonry(container, {
        columnWidth: 10,
        itemSelector: '.card',
        isFitWidth: false,
        isAnimated: true
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
    stop_stream();
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
                $("div#content").html(html);
                init_masonry();
            }
        }
    });
}

function create_videoplayer(source, path, poster){
    video = $("video")[0];

    if(video == undefined){
        movie = {path: path, poster: poster};
        console.log("creating player: " + source);
        var template = $('#movie-player-template').html();
        var html = Mustache.to_html(template, movie);
        $("div#content").html(html);

        video = $("video")[0];

        video.addEventListener('waiting', function() {
            console.log('waiting');
        }, false);

        video.addEventListener('loadedmetadata', function () {

            video.currentTime = movie_time;
        });

        video.addEventListener("error",function () {
            window.setTimeout(function(){
                if(!video.src != null && video.src.match("/null/i") != null){
                    console.log("error, restarting stream "+ video.src);
                    start_stream(path)
                }
            }, 1000);
        });

    }

}

function play(source){
    var video = document.getElementsByTagName('video')[0];
    video.src = source + "?buffer=5";
    console.log("Playing: " + video.src);
}

function movie_to_html(movie){
    var template = $('#movie-card-template').html();
    var html = Mustache.to_html(template, movie);
    return html;
}

function start_stream(path, poster){
    var feed = "feed.ffm";
    var stream = "test.mkv"
    var stream_url = "http://" + window.location.hostname + ":8090/" + stream;
    var stream_ajax_url = "stream.php?i=" + path + "&f=" + feed;

    console.log("starting stream: " + stream_ajax_url);
    $.ajax({
        url: stream_ajax_url
    }).done(function( data ) {
        console.log("stream started on server side");
        play(stream_url);
    });

    create_videoplayer(stream_url, path, poster);

    return stream_url;
}

function stop_stream(){
    var video = document.getElementsByTagName('video')[0];
    if(video != undefined){
        video.pause();
        video.src = null;
    }

    $.ajax({
        url: "stream.php"
    }).done(function( data ) {
        //this is when the file is done i think
    });
}