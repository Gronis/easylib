/**
 * Created by robin on 20/08/14.
 */
var last_search;

$( document ).ready(function() {
    console.log( "ready!" );

    //init autohide navbar
    init_autohide_navbar();

    //init masonry
    init_masonry();
});

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
    console.log(search);

    var this_search = new Date().getTime();
    last_search = this_search;

    //ajax request to for the search:
    $.ajax({
        url: "get.php?search=" + search/*,
        beforeSend: function( xhr ) {
            xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
        }*/}).done(function( data ) {
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

function play_movie(file_path){
    var movie = {"file_path": file_path};
    var template = $('#movie-player-template').html();
    var html = Mustache.to_html(template, movie);
    $("div#content").html(html);
}

function movie_to_html(movie){
    var template = $('#movie-card-template').html();
    var html = Mustache.to_html(template, movie);
    return html;
}