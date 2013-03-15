/**
 * onclick event for selected user in permissionpanel
 */
function onClickUser() {
    // empty existing onclick functions
    $( ".permission-panel-roles ul li a" ).attr('onclick','').unbind('click');

    // set new onclick
    $( ".permission-panel-roles ul li a" ).on( "click", function(event) {
	$( ".permission-panel-roles ul li a" ).removeClass('active');
	$(this).addClass('active');

	showUserRights($(this).attr('data-panel-name'), $(this).attr('data-panel-userid'));
    });
}

// run onclick now
onClickUser();

/**
 * removeUser event in permission panel that removes user
 */
$('[data-panel-event="removeUser"]').on( "click", function( event ) {
    // get panelName
    panelName = $(this).parent().attr('data-panel-name');

    // get selected user
    userId = $('[data-panel-name="'+panelName+'"] .active').attr('data-panel-userid');

    // remove user
    if(userId != undefined)
	removeUserFromList(panelName, userId);
});

/**
 * addUser event for modal
 */
$('[data-panel-event="addUser"]').on( "click", function( event ) {
    // get panelName
    panelName = $(this).parent().parent().attr('data-panel-name');

    $('#permissionPanelRoles_'+panelName+' select option:selected').each(function( index ) {
	addUserToList(panelName, this.value);
    });

    // hide modal
    $('#permissionPanelRoles_'+panelName).modal('hide');
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
	$('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="allow"]').attr('data-panel-userid', userName);
	$('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="deny"]').attr('data-panel-userid', userName);

	if(value == 'allow') {
	    $('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="allow"]').addClass('active');
	    $('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="deny"]').removeClass('active');
	} else {
	    $('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="deny"]').addClass('active');
	    $('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="allow"]').removeClass('active');
	}
    });
}

/**
 * add user to permission store
 * @param string panelName
 * @param string userId
 */
function addUserToStore(panelName, userId) {
    var permissionStore = window['permissionStore_' + panelName];
    var roleStore = window['roleStore_' + panelName];
    var actionStore = window['actionStore_' + panelName];

    // set username
    window['permissionStore_' + panelName][userId] = new Object();
    window['permissionStore_' + panelName][userId]['name'] = roleStore[userId];

    // create array for permissions
    window['permissionStore_' + panelName][userId]['permissions'] = new Object();

    // set all actions to allow
    $.each( actionStore, function( key, value ) {
	window['permissionStore_' + panelName][userId]['permissions'][value] = 'allow';
    });
}

/**
 * remove user from permission store
 *
 * @param string panelName
 * @param string userId
 */
function removeUserFromStore(panelName, userId) {
    // unset user
    window['permissionStore_' + panelName][userId] = undefined;
}

/**
 * set permissions for user in store
 *
 * @param string panelName
 * @param string userId
 * @param string action
 * @param string permission
 */
function setUserPermission(panelName, userId, action, permission) {
    // set user permission in store
    window['permissionStore_' + panelName][userId]['permissions'][action] = permission;
}

/**
 * add user to list of roles for permission panel
 *
 * @param string panelName
 * @param string userId
 */
function addUserToList(panelName, userId) {
    //
    var roleStore = window['roleStore_' + panelName];

    //
    $('[data-panel-name="'+panelName+'"] div.permission-panel-roles ul').append('<li><a data-panel-name="'+panelName+'" data-panel-userid="'+userId+'" href="javascript:;">'+roleStore[userId]+'</a></li>');

    // run onclickuser to set onclick for roles
    onClickUser();

    //
    addUserToStore(panelName, userId);
}

/**
 * remove user from list of roles for permission panel
 *
 * @param string panelName
 * @param string userId
 */
function removeUserFromList(panelName, userId) {
    //
    $('[data-panel-name="'+panelName+'"] [data-panel-userid="'+userId+'"]').parent().remove();

    //
    removeUserFromStore(panelName, userId);
}

/**
 * set action on right click
 */
$('[data-panel-action-right]').on('click', function() {
    // define vars
    var panelName = $(this).attr('data-panel-name');
    var userId = $(this).attr('data-panel-userid');
    var action = $(this).attr('data-panel-action-name');
    var permission = $(this).attr('data-panel-action-right');

    // set user permissions
    setUserPermission(panelName, userId, action, permission);
});