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

function play(source, path, times){
    times++;
    var video = document.getElementsByTagName('video')[0];
    /*if(video != undefined && video.readyState != "HAVE_NOTHING"){
        movie_time = video.currentTime;
        console.log(movie_time);
        video.src = source;
        return;
    }*/
    console.log("Playing: " + source);
    var movie = {"source": source};
    var template = $('#movie-player-template').html();
    var html = Mustache.to_html(template, movie);
    $("div#content").html(html);

    video = document.getElementsByTagName('video')[0];

    video.addEventListener('waiting', function() {
        console.log('waiting');
    }, false);

    video.addEventListener('loadedmetadata', function () {

        video.currentTime = movie_time;
    });


    window.setTimeout(function(){
        if(video.error){
            if(times >= 7){
                console.log("error 7 times, restarting stream");
                start_stream(path);
            }else{
                console.log("error, reloading " + times);
                play(source, path, times);
            }
        }
        video.addEventListener("error",function () {
            console.log('error');
            window.setTimeout(function(){
                play(source, path, times)
            }, 1000);
        });
    }, 1000);



}

function movie_to_html(movie){
    var template = $('#movie-card-template').html();
    var html = Mustache.to_html(template, movie);
    return html;
}

function start_stream(path){
    var feed = "feed.ffm";
    var stream = "test.mkv"
    var stream_url = "http://" + window.location.hostname + ":8090/" + stream;
    var ajax_url = "stream.php?i=" + path + "&f=" + feed;

    console.log("starting stream: " + ajax_url);

    $.ajax({
        url: ajax_url
    }).done(function( data ) {
        //this is when the file is done i think
    });

    play(stream_url, path, 0);

    return stream_url;
}

function stop_stream(){
    $.ajax({
        url: "stream.php"
    }).done(function( data ) {
        //this is when the file is done i think
    });
}