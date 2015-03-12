=== Sierotki ===
Contributors: iworks
Donate link: http://iworks.pl/donate/sierotki.php
Tags: sierotki, spójniki, twarda spacja, spójniki
Requires at least: 3.3
Tested up to: 4.1.1
Stable tag: 2.4

Wtyczka poprawia sierotki, tak żeby nie mogły zostać na końcu linii.

== Description ==

Wtyczka poprawia sierotki, tak żeby nie mogły zostać na końcu linii, zastępując spacje znajdujące się za sierotkami na jedną twardą spację.

= EN =

Plugin supports some of the grammatical rules of the Polish language.

= Asset image =

[Manuscript by Muffet, on Flickr](http://www.flickr.com/photos/calliope/306564541/)

== Installation ==

1. Upload plugin to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Configure options 'Apperance' => 'Orphan' (default all options are turn on).

== Frequently Asked Questions ==

= When this plugin replace spaces? =

Plugin works when viewing the content and does not modify your content.

= How to use this plugin on custom field? =

Use this code:

`
$orphan = new iworks_orphan();
echo $orphan->replace( get_post_meta($post_id, 'meta_key', true ) );
`

= How to use this plugin on any string? =

Use this code:

`
$orphan = new iworks_orphan();
echo $orphan->replace( 'any_string' );
`


== Screenshots ==

1. Opcje Sierotek
1. Orphan Options

== Changelog ==

= 2.4 - 2015-02-12 =

* IMPROVEMENT: add hard space between number (year) and polish year shortcut "r."
* IMPROVEMENT: add WooCommerce product title and short description to available options. thx to [Dominik Kawula](https://www.facebook.com/dominik.kawula)

= 2.3.2 - 2014-09-12 =

* BUGFIX: fixed error in options array

= 2.3.1 - 2014-09-12 =

* BUGFIX: check option array for non existing hash.
* IMPROVEMENT: update screenshot.

= 2.3 - 2014-07-10 =

* IMPROVEMENT: add all forms of word number

= 2.2 - 2014-01-24 =

* IMPROVEMENT:add links to forum
* IMPROVEMENT:checked capability with WP 3.8

= 2.1 - 2013-11-09 =

* IMPROVEMENT:checked capability with WP 3.6
* REFACTORING: implement PSR-0 rules to orphan class

= 2.0.2 - 2013-08-20 =

* BUGFIX: fixed replacement for single letter orphan after orphan thx to [Szymon Skulimowski](http://wpninja.pl/autorzy/szymon-skulimowski/)
* IMPROVEMENT:checked capability with WP 3.6
* IMPROVEMENT:added help and related section

= 2.0.1 - 2013-07-10 =

* IMPROVEMENT:add numbers

= 2.0 - 2012-08-12 =

* BUGFIX: fixed permissions to configuration page
* BUGFIX: fixed replacement for strings starting with a orphan
* REFACTORING: rewrite code to the class
* IMPROVEMENT:add some shorts of academic degree
* IMPROVEMENT:massive increase orphans dictionary thx to [adpawl](http://podbabiogorze.info.pl)

= 1.4.2 - 2012-03-02 =

* NEW: Add the_title filter.

= 1.4.1 - 2011-02-24 =

* NEW: Trim chars.
* BUGFIX: Fixed multi coma use.

= 1.4 - 2011-02-24 =

* NEW: Added user defined orphans.
* BUGFIX: Corrected capability name.

= 1.3 - 2011-02-19 =

* NEW: Added option page to turn on/off filtering in content, excerpt or comments.
* NEW: Added "(" as char before a orphan.

= 1.2 - 2011-02-18 =

* NEW: Added filter comment_text.
* BUGFIX: Capital letters was missing by plugin.

= 1.1 - 2011-02-17 =

* Abandoning elegant anonymous function, which requires PHP 5.3.0 :(
* NEW: Added filter to the_excerpt.

= 1.0.2 - 2011-02-17 =

* NEW: Added ">" as char before a orphan.

= 1.0.1 - 2011-02-16 =

* NEW: Added word "to".

= 1.0 - 2011-02-16 =

* INIT
