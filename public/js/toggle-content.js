/**
 * plugin that checks url for contentid anker and will collapse content
 */

/**
 * open content accordion
 *
 * @param int contentId
 */
function openContent(contentId) {
    $('#collapse' + contentId).collapse('show')
}

/**
 * on page load check url for content anker
 */
$(document).ready(function () {
	var contentId = self.location.href.match(/#content(\d+)/);
	if (contentId)
	{
	     openContent(contentId[1]);
	}
});
