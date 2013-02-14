/**
 * onclick event for selected user in permissionpanel
 */
$( ".permission-panel-roles ul li a" ).on( "click", function( event ) {
    $( ".permission-panel-roles ul li a" ).removeClass('active');
    $(this).addClass('active');

    showUserRights($(this).attr('data-panel-name'), $(this).attr('data-panel-userid'));
});

/**
 * set user rights in permission panel
 *
 * @param string panelName
 * @param string userName
 */
function showUserRights(panelName, userName) {
    var store = window['permissionStore_' + panelName];

    $.each( store[userName]['permissions'], function( key, value ) {
	if(value == 'allow') {
	    $('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="allow"]').addClass('active');
	    $('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="deny"]').removeClass('active');
	} else {
	    $('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="deny"]').addClass('active');
	    $('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="allow"]').removeClass('active');
	}
    });
}