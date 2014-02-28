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

	// show right panel
	$('.permission-panel-rights[data-panel-name="'+$(this).attr('data-panel-name')+'"]').show(20);

	// show user rights
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
    panelName = $(this).parent().parent().parent().parent().attr('data-panel-name');

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
	    $('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="allow"]').parent().button('toggle');
	} else {
	    $('[data-panel-name="'+panelName+'"] [data-panel-action-name="'+key+'"] [data-panel-action-right="deny"]').parent().button('toggle');
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

    updateAclField(panelName);
}

/**
 * check if given userId is in store
 *
 * @param string panelName
 * @param string userId
 * @returns {undefined}
 */
function isUserInStore(panelName, userId) {
    var permissionStore = window['permissionStore_' + panelName];
    if(typeof permissionStore[userId] == "object") {
	return true;
    } else {
	return false;
    }
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

    updateAclField(panelName);
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
    updateAclField(panelName);
}

/**
 * add user to list of roles for permission panel
 *
 * @param string panelName
 * @param string userId
 */
function addUserToList(panelName, userId) {
    // get role store
    var roleStore = window['roleStore_' + panelName];

    if(!isUserInStore(panelName, userId)) {
	// append user
	$('[data-panel-name="'+panelName+'"] div.permission-panel-roles ul').append('<li><a data-panel-name="'+panelName+'" data-panel-userid="'+userId+'" href="javascript:;">'+roleStore[userId]+'</a></li>');

	// run onclickuser to set onclick for roles
	onClickUser();

	// add user to storage
	addUserToStore(panelName, userId);
    }
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
$('[data-panel-action-right]').parent().on('click', function() {
    // define vars
    var panelName = $(this).children().attr('data-panel-name');
    var userId = $(this).children().attr('data-panel-userid');
    var action = $(this).children().attr('data-panel-action-name');
    var permission = $(this).children().attr('data-panel-action-right');

    // set user permissions
    setUserPermission(panelName, userId, action, permission);
});

/**
 * added save action for save button
 */
$('.permission-panel-save-button').on('click', function() {
    $.ajax({
	type: "POST",
	url: $(this).attr('data-panel-save-url'),
	dataType: "json",
	data: {
	    panelName: $(this).attr('data-panel-name'),
	    panelStore: window['permissionStore_' + $(this).attr('data-panel-name')]
	},
	dataType: 'html',
	success: function(dataPacket) {
	   /* process the received dataPacket */
	   alert("Data saved");
	},
	error: function(data) {
	    alert('error' + data);
	}
    });
});

function updateAclField(panelName) {
    $('#inputAcl'+panelName).val(JSON.stringify(window['permissionStore_' + panelName]));
}
