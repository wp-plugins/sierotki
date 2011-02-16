<?php
/*
Plugin Name: Sierotki
Plugin URI: http://iworks.pl/
Description: Wtyczka zamienia leżące za sierotkami spacje w jedną twardą.
Author: Marcin Pietrzak
Version: 1.0
Author URI: http://iworks.pl/
*/

add_filter('the_content', function($content){return preg_replace('/ +(a|i|o|u|w|z|że|za|na|od|nad|pod) +/', " $1&nbsp;", $content);});

?>
