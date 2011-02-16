<?php
/*
Plugin Name: Sierotki
Plugin URI: http://iworks.pl/
Description: Wtyczka zamienia leżące za sierotkami spacje w jedną twardą.
Author: Marcin Pietrzak
Version: 1.0.1
Author URI: http://iworks.pl/
*/

add_filter('the_content',function($c){return preg_replace('/ +([aiouwz]|że|za|na|od|nad|pod|to) +/'," $1&nbsp;",$c);});
?>
