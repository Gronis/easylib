<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>easylib</title>

    <!-- font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,100,400,700,300italic" type="text/css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/styles.css">

</head>
    <body>

        <div class="navbar navbar-fixed-top">
            <div class="input-group center" id = "search">
                <input id="search-input" class="form-control" type="text" autocomplete="off" autofocus=""
                       placeholder="Search" oninput="search(this.value)" onkeydown="if(event.keyCode == 13) this.oninput()"/>
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button"
                            onclick="search(document.getElementById('search-input').value)">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
        </div>


        <div id="content" >

        </div>

        <div class="footer">
            <p class="center">
                <a href="http://github.com/Gronis/easylib">easylib</a>
            </p>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="js/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="js/bootstrap.min.js"></script>
        <!-- Autohide navbar-->
        <script src="js/bootstrap-autohidingnavbar.min.js"></script>
        <!-- Mustache, template system-->
        <script src="js/mustache.js"></script>
        <!-- Masonry, tile cards-->
        <script src="js/masonry.min.js"></script>
        <!-- my own javascript-->
        <script src="js/app.js"></script>

        <!-- Templates-->
        <script id="movie-template" type="text/template">
            <div class='card'>
                <img class='card-poster' src={{poster_medium_url}} />
                <div class=card-info>
                    <div class='card-title'>
                        <!--movie name-->
                        <h4><a href='http://www.youtube.com/watch?v={{trailer}}'>{{title}}</a></h4>
                        <!--release year-->
                        <h5>{{release_date}}</h5>
                    </div>
                    <span class='badge'>
                        <span class='glyphicon glyphicon-star'></span>
                        <h5> {{rating}} </h5>
                    </span>
                    <span class='badge'><h5>{{runtime}} min</h5></span>

                    <p>{{overview}}</p>
                    </div> <!--card-info-->
                <div class='horisontal-line'></div>
            </div>
        </script>

    </body>
</html>