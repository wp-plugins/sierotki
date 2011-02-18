<?php
/*
Plugin Name: Sierotki
Plugin URI: http://iworks.pl/
Description: Wtyczka zamienia leżące za sierotkami spacje w jedną twardą.
Author: Marcin Pietrzak
Version: trunk
Author URI: http://iworks.pl/
*/

function iworks_orphan($content)
{
    if ( $content ) {
        return preg_replace('/([ >]+)([aiouwz]|że|za|na|od|nad|pod|to) +/i', "$1$2&nbsp;", $content);
    }
    return $content;
}

function iworks_orphan_init()
{
    add_filter('comment_text', 'iworks_orphan');
    add_filter('the_content',  'iworks_orphan');
    add_filter('the_excerpt',  'iworks_orphan');
}

add_action('init', 'iworks_orphan_init');

?>
