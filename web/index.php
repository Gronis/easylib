<?php
    include_once(__DIR__ . '/../vendor/autoload.php');
    use Easylib\Application;

    $app = new Application();
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>easylib</title>

    <!-- font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,100,400,700,300italic" rel="stylesheet" type="text/css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/styles.css">

    <!-- Masonry, tile cards-->
    <script src="//masonry.desandro.com/masonry.pkgd.min.js"></script>

    <!-- Autohide navbar -->
    <script src="js/jquery.bootstrap-autohidingnavbar.js"></script>



</head>
    <body>

        <div class="navbar navbar-fixed-top">
            <div class="center" id="search">
                <div class="input-group">
                    <input name="search" class="form-control" type="text" autocomplete="off" autofocus="" placeholder="Search" />
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </span>
                </div>
            </div>
        </div>


        <div id="content" >
            <?php
            foreach($app->search(array("."))->movies as $movie){
                echo "<div class='card'>";
                echo "<img class='card-poster' src=$movie->poster_medium_url />";
                echo "<div class=card-info>";
                    echo "<div class='card-title'>";
                        //movie name
                        echo "<h4><a href=http://www.youtube.com/watch?v=$movie->trailer>$movie->title</a></h4>";
                        //release year
                        echo "<h5> (" . substr($movie->release_date, 0, 4) . ")</h5>";
                    echo "</div>";
                    echo "<span class='badge'>";
                        echo "<span class='glyphicon glyphicon-star'></span>";
                        echo "<h5> $movie->rating </h5>";
                    echo "</span>";
                    echo " <span class='badge'><h5>$movie->runtime min</h5></span>";
                    print_r(implode(" ", array_values($movie->tags)));

                    echo "<p>" . $movie->overview . "</p>";
                echo "</div>"; //card-info
                echo "<div class='horisontal-line'></div>";
                echo "</div>"; //card
            }
            ?>


        </div>

        <div class="footer">
            <p class="center">
                <a href="http://github.com/Gronis/easylib">easylib</a>
            </p>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
        <!-- Autohide navbar-->
        <script src="//cdn.virtuosoft.eu/virtuosoft.eu/resources/jquery.bootstrap-autohidingnavbar.min.js"></script>
        <script>$("div.navbar-fixed-top").autoHidingNavbar();</script>

        <script>
            var container = document.querySelector('#content');
            var masonry = new Masonry(container, {
                columnWidth: 10,
                itemSelector: '.card',
                isFitWidth: false
            });


            /*window.addEventListener('resize', function(event){
                if(document.body.clientWidth < 820){
                    mansory.isFitWidth = false;
                }else{
                    mansory.isFitWidth = true;
                }
            });*/

        </script>

    </body>
</html>