easylib
============

A simple server app that fetches media from a filesystem and creates a database that connects the media information
with the file on the filesystem. a web interface can be configured to display the contents of the library.

In order to stream the media, ffmpeg is used to transcode if nessesary. The media is transcoded to h264 and mp3
which means it only plays in chrome web browser for now. For support for other web browsers, transcoding to
webm/ogg is nessesary. This is not implemented for now, however (you are free to do so!)

Requirements:
------------

* PHP 5.3+
* Curl for php
* ffmpeg

Setup:
-----

In the future, some kind of install script will probably handle the setup, but for now it's not automatic

### Core ###

1. Change directory to project with "cd" and Run "curl -sS https://getcomposer.org/installer | php" to install composer
(see https://getcomposer.org/doc/00-intro.md#using-composer for details)
2. Install dependencies with "php composer.phar install"
3. Run "chmod +x easylib" in order to run the CLI

### Web ###

Install or compile apache or nginx (or any other web hosting application) and configure the web root to point at
easylib/web
See http://wiki.nginx.org/Main or http://httpd.apache.org/mod_smtpd/install.html for instructions

### Ffmpeg ###

You can compile ffmpeg from source or install a binary.
Make sure ffmpeg is compiled with support for mp3 and x264
See http://ffmpeg.org/ for instructions

ffmpeg streams to http port 8090 for now. That port must be open in the firewall in order to make streaming work

### Global CLI access: ###

Add a shortcut to easylib in your /usr/bin folder with "ln" so you can access it from anywhere

Cli basic usage:
-----------

run "easylib scan {path/to/scan}" to scan for media (providing path is recommended)
e.g. "easylib scan /home/user/movies"
run "easylib help" to get help
