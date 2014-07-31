movieLibrary
============

A simple server app that fetches media from a filesystem and creates a database that connects the media information with the file on the filesystem

Requirements:
------------

* PHP 5.3+
* Curl
* TMDB Api-key (Get it from https://www.themoviedb.org)

Setup
-----

1. Add the API-key from TMDB in API-KEYS.php
2. Also run "chmod +x movielibrary" in order to run the CLI

You should add a shortcut to movielibrary in your bin folder so you can access it from anywhere (this is optional)

Basic Usage
-----------

run "movielibrary scan" to scan for movies
run "movielibrary help" to get help
