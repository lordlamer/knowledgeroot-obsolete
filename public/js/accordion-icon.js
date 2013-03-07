/**
 * Knowledgeroot
 * Frank Habermann <lordlamer@lordlamer.de>
 *
 * bsd license
 * add icon to accodion title and toogle icon on accordion toggle
 */

$(document).ready(function() {
	// add icons to each accordion title
	$.each($('.accordion-toggle'), function(key, value) {
		// check if accordion is open
		if($($(this).attr('href')).hasClass('in'))
			$(value).append('<i class="icon-chevron-down pull-left accordion-icon"></i>');
		else
			$(value).append('<i class="icon-chevron-right pull-left accordion-icon"></i>');
	});

	// set onclick to toggle icon
	$('.accordion-toggle').on('click', function() {
		// toggle icon
		$(this).children().toggleClass('icon-chevron-right icon-chevron-down');

		// collapse body
		$($(this).attr('href')).collapse('toggle');
	});
});