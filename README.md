# rss-vsp
=== Plugin Name ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: http://itjustdoes.co.uk
Tags: scrolling, content
Requires at least: 5.0
Tested up to: 5.6
Stable tag: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Vertical scrolling of a few lines of N-most recent posts of a specified category.

== Description ==

This plugin will display a chosen number of lines taken from the N-most recently
modified posts of a specified category. 

It uses a shortcode which is intended to be added to a post (of a different category)
to add dynamic content to that post.

The use-case is a post on the front page that has some surrounding text, perhaps images or
even a background image, and adds these lines as additional text.

Currently the user selects the relevent information from the RSS VSP Settings page in the 
Admin pages of WordPress. It has also been written to assume there's only one instance of
the shortcode on a given page - in fact, its only been tested on the front page! YMMV.


== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `rss-vsp.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add the shortcode [rss_vsp] to the post being extended.

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. No screenshots yet.

== Changelog ==

= 1.0 =
* Initial

== Upgrade Notice ==


