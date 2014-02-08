/**
 * onclick event for selected user in memberpanel
 */
function mem_onClickUser() {
    // empty existing onclick functions
    $( ".member-panel-roles ul li a" ).attr('onclick','').unbind('click');

    // set new onclick
    $( ".member-panel-roles ul li a" ).on( "click", function(event) {
	$( ".member-panel-roles ul li a" ).removeClass('active');
	$(this).addClass('active');
    });
}

// run onclick now
mem_onClickUser();

/**
 * removeUser event in permission panel that removes user
 */
$('[data-panel-event="mem_removeUser"]').on( "click", function( event ) {
    // get panelName
    panelName = $(this).parent().attr('data-panel-name');

    // get selected user
    userId = $('[data-panel-name="'+panelName+'"] .active').attr('data-panel-userid');

    // remove user
    if(userId != undefined)
	mem_removeUserFromList(panelName, userId);
});

/**
 * addUser event for modal
 */
$('[data-panel-event="mem_addUser"]').on( "click", function( event ) {
    // get panelName
    panelName = $(this).parent().parent().parent().parent().attr('data-panel-name');

    $('#memberPanelRoles_'+panelName+' select option:selected').each(function( index ) {
	mem_addUserToList(panelName, this.value);
    });

    // hide modal
    $('#memberPanelRoles_'+panelName).modal('hide');
});

/**
 * set user rights in permission panel
 *
 * @param string panelName
 * @param string userName
 */
function mem_showUserRights(panelName, userName) {
    var store = window['memberStore_' + panelName];

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
function mem_addUserToStore(panelName, userId) {
    var memberStore = window['memberStore_' + panelName];
    var roleStore = window['roleStore_' + panelName];
    //var actionStore = window['actionStore_' + panelName];

    // set username
    window['memberStore_' + panelName][userId] = new Object();
    window['memberStore_' + panelName][userId]['name'] = roleStore[userId];

    mem_updateAclField(panelName);
}

/**
 * check if given userId is in store
 *
 * @param string panelName
 * @param string userId
 * @returns {undefined}
 */
function mem_isUserInStore(panelName, userId) {
    var memberStore = window['memberStore_' + panelName];
    if(typeof memberStore[userId] == "object") {
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
function mem_removeUserFromStore(panelName, userId) {
    // unset user
    window['memberStore_' + panelName][userId] = undefined;

    mem_updateAclField(panelName);
}

/**
 * add user to list of roles for permission panel
 *
 * @param string panelName
 * @param string userId
 */
function mem_addUserToList(panelName, userId) {
    // get role store
    var roleStore = window['roleStore_' + panelName];

    if(!mem_isUserInStore(panelName, userId)) {
	// append user
	$('[data-panel-name="'+panelName+'"] div.member-panel-roles ul').append('<li><a data-panel-name="'+panelName+'" data-panel-userid="'+userId+'" href="javascript:;">'+roleStore[userId]+'</a></li>');

	// run onclickuser to set onclick for roles
	mem_onClickUser();

	// add user to storage
	mem_addUserToStore(panelName, userId);
    }
}

/**
 * remove user from list of roles for permission panel
 *
 * @param string panelName
 * @param string userId
 */
function mem_removeUserFromList(panelName, userId) {
    //
    $('[data-panel-name="'+panelName+'"] [data-panel-userid="'+userId+'"]').parent().remove();

    //
    mem_removeUserFromStore(panelName, userId);
}

/**
 * added save action for save button
 */
$('.member-panel-save-button').on('click', function() {
    $.ajax({
	type: "POST",
	url: $(this).attr('data-panel-save-url'),
	dataType: "json",
	data: {
	    panelName: $(this).attr('data-panel-name'),
	    panelStore: window['memberStore_' + $(this).attr('data-panel-name')]
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

function mem_updateAclField(panelName) {
    $('#inputAcl'+panelName).val(JSON.stringify(window['memberStore_' + panelName]));
}