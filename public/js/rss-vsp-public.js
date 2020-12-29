/*
Author: It Just Does
Author URI: http://www.itjustdoes.co.uk
Version: 0.0.1

License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2020 itjustdoes.co.uk

Notes Internet Explorer 11 and previous do not support classes, so this might need reworking
via http://babeljs.io

*/

/**
 * Class timer
 */
var rssTimerInstance = 0;
class rssTimer {

	constructor(callback, timeout, cb_context) {
		var _this = this;
		this.instance = rssTimerInstance++;
		this.timeout = timeout;
		this.callback = callback;
		this.cb_context = cb_context;
		setInterval( function() { _this.timedOut();}, this.timeout);

		console.log('Created rssTimer instance ' + this.instance);
	}

	timedOut() {
		this.callback(this.cb_context);
	}
}

/**
 * Class display N lines
 */
var rssDisplayNLinesInstance = 0;
class rssDisplayNLines {

	constructor() {
		var _this = this;
		this.instance = rssDisplayNLinesInstance++;
		this.topLine = 0;
		this.maxLinesDisplayed = options.lines;
		this.timer = new rssTimer(_this.callback, options.speed * 1000, _this);

		console.log('Created rssDisplayNLines instance ' + this.instance);
	}

	callback(cb_context) {

		var theDiv = document.getElementById('rss-vsp-public');

		// walk-through all lines, hiding them. This also allows us to count them.
		// note: we don't care what the line is (paragraph, header, image): a line is a line...
		var lineCount = 0;

		var maxExpected = theDiv.children.length;

		for (var i=0; i < theDiv.children.length; i++) {
			var child = theDiv.children[i];

			if (child.nodeType != Node.ELEMENT_NODE) {
				maxExpected--;
				continue;
			}

			if ( (i < cb_context.topLine) || 
				 (i >= (parseInt(cb_context.topLine, 10) + parseInt(cb_context.maxLinesDisplayed, 10))) ) {
				// hide the content
				child.style.display = 'none';
			} else {
				// display the content
				child.style.display = 'block';
			}
		}

		cb_context.topLine++;
		if ( cb_context.topLine >= maxExpected ) {
			cb_context.topLine = 0;
		}
	}

	run() {
		this.callback(this);
	}
}

var rssVspPublic = new rssDisplayNLines();
rssVspPublic.run();
