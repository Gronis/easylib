easylib
============

A simple server app that fetches media from a filesystem and creates a database that connects the media information with the file on the filesystem

Clone:
-----
clone with --recursive to insure that submodules are included

Requirements:
------------

* PHP 5.3+
* Curl for php
* TMDB Api-key (Get it from https://www.themoviedb.org)

Setup:
-----

1. Run "chmod +x easylib" in order to run the CLI
2. Set TMDB_KEY in config.ini or run ./easylib -config TMDB_KEY={YOUR TMDB API KEY HERE} to set API-key

Optional
3. "ln easylib /usr/bin/" To add a shortcut to easylib in your /usr/bin run folder so you can access it from anywhere (this is optional)

Basic Usage:
-----------

run "easylib -scan" to scan for media
run "easylib -help" to get help
