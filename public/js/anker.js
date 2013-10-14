/**
 * Knowledgeroot
 * Frank Habermann <lordlamer@lordlamer.de>
 *
 * bsd license
 * fix anker with using base href
 */

$(document).ready(function() {
	// add icons to each accordion title
	$.each($('a[href]'), function(key, value) {
		if($(this).attr('href')[0] == '#')
			$(this).attr('href', window.location.pathname + $(this).attr('href'));
	});
});