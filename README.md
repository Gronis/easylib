easylib
============

A simple server app that fetches media from a filesystem and creates a database that connects the media information with the file on the filesystem

Requirements:
------------

* PHP 5.3+
* Curl for php
* TMDB Api-key (Get it from https://www.themoviedb.org)

Setup:
-----

1. Change directory to project with "cd" and Run "curl -sS https://getcomposer.org/installer | php" to install composer (see https://getcomposer.org/doc/00-intro.md#using-composer for details)
2. Install dependencies with "php composer.phar install"
3. Run "chmod +x easylib" in order to run the CLI
4. Set TMDB_KEY in config.ini or run ./easylib -config TMDB_KEY={YOUR TMDB API KEY HERE} to set API-key

Optional:

5. Add a shortcut to easylib in your /usr/bin folder with "ln" so you can access it from anywhere (this is optional)

Basic Usage:
-----------

run "easylib scan" {path/to/scan} to scan for media
run "easylib help" to get help
