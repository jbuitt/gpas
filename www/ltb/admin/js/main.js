//
// main.js
//

$(function () {

	$("#username").focus();

	$("#signInButton").click(function() {
		signIn();
	});

	$('#username').keydown(function (e) {
		if (e.keyCode == 13) {
			signIn();
		}
	});

	$('#password').keydown(function (e) {
		if (e.keyCode == 13) {
			signIn();
		}
	});

	$("#signOutButton").click(function() {
		var token = $("#token").val();
		$.ajax({
			type: "POST",
			url: "api.php",
			data: {
				'task': 'sign_out',
				'token': token
			},
			success: function(msg) {
				if (msg.match(/OK/)) {
					location.href = 'index.php?status=logged_out';
				}
			},
			error: function(jqXHR, statusText, errorThrown) {
				console.log(statusText+": "+errorThrown);
			}
		});
	});

	$("#userList").jqGrid({
		url: "api.php",
		datatype: "json",
		mtype: "POST",
		postData: {
			"task": "list_users",
			"token": getToken
		},
		colNames: [ "First Name", "Last Name", "User Name", "Password", "UID", "GID", "E-Mail" ],
		colModel: [
			{ name: "givenName", index: "givenName", width: 100, align: 'center', editable: true, editrules: {required: true} },
			{ name: "sn", index: "sn", width: 100, align: 'center', editable: true, editrules: {required: true} },
			{ name: "uid", index: "uid", width: 150, align: "center", editable: true, editrules: {required: true} },
			{ name: 'userPassword', index: 'userPassword', width: '150', align: 'center', editable: true, hidden: true, edittype:'password', editrules: {required: true, edithidden:true } },
			{ name: "uidNumber", index: "uidNumber", width: 80, align: "center", editable: true, editrules: {required: true} },
			{ name: "gidNumber", index: "gidNumber", width: 80, align: "center", editable: true, editrules: {required: true} },
			{ name: "mail", index: "mail", width: 300, align: 'left', editable: true, editrules: {required: true} },
		],
		pager: "#pager",
		rowNum: 100,
		height: 400,
		viewrecords: true,
		viewrecords: true,
		ondblClickRow: function() {
			var uid = getUID();
			$("#addEditUserDialog").html('<img src="/images/loading.gif" />');
			$("#addEditUserDialog").load('add_edit_user.php', {'type': 'edit', 'user_id': uid}).dialog('option', 'title', 'Edit User').dialog('open');
		},
		jsonReader: {
			root: "rows",
			page: "page",
			total: "total",
			records: "records",
			repeatitems: false,
			id: 'id'
		},
	}).navGrid('#pager', {edit:false, add:false, del:false, view:false, search:false},
		{ },	// edit
		{ },	// add
		{ },	// delete
		{ },    // view
		{ }     // search
	);

	jQuery.extend(jQuery.jgrid.edit, {
		savekey: [true, 13],
		closeOnEscape: true,
		closeAfterEdit: true,
		closeAfterAdd: true,
		recreateForm: true
	});

	$('#addEditUserDialog').dialog({
                autoOpen: false,
                width: 550,
                modal: true,
                position: ['top', 20],
                buttons: {
                        "Ok": function() {
				var type		= $("#type").val();
				var givenName		= $("#givenName").val();
				var sn			= $("#sn").val();
				var cn			= $("#cn").val();
				var uid			= $("#uid").val();
				var userPassword1	= $("#userPassword1").val();
				var userPassword2	= $("#userPassword1").val();
				var mail		= $("#mail").val();
				var uidNumber		= $("#uidNumber").val();
				var gidNumber		= $("#gidNumber").val();
				var homeDirectory	= $("#homeDirectory").val();
				var loginShell		= $("#loginShell").val();
				var gecos		= $("#gecos").val();
				var sambaSID		= $("#sambaSID").val();
				var sambaLMPassword	= $("#sambaLMPassword").val();
				var sambaNTPassword	= $("#sambaNTPassword").val();
				var sambaPwdLastSet	= $("#sambaPwdLastSet").val();
				if (givenName == "") {
					alert("Please enter a first name.");
					return false;
				}
				if (sn == "") {
					alert("Please enter a last name.");
					return false;
				}
				if (cn == "") {
					alert("Please enter a full name. (first & last)");
					return false;
				}
				if (uid == "") {
					alert("Please enter a user ID.");
					return false;
				}
				if (userPassword1 == "") {
					alert("Please enter a password.");
					return false;
				}
				if (userPassword2 == "") {
					alert("Please enter same password again.");
					return false;
				}
				if (mail == "") {
					alert("Please enter an e-mail address.");
					return false;
				}
				if (uidNumber == "") {
					alert("Please enter a UID number.");
					return false;
				}
				if (gidNumber == "") {
					alert("Please enter a GID number.");
					return false;
				}
				if (homeDirectory == "") {
					alert("Please enter a home directory.");
					return false;
				}
				if (loginShell == "") {
					alert("Please enter a login shell.");
					return false;
				}
				if (sambaSID == "") {
					alert("Please enter a samba SID.");
					return false;
				}
				if (sambaLMPassword == "") {
					alert("Please enter an LM password.");
					return false;
				}
				if (sambaNTPassword == "") {
					alert("Please enter an NT password.");
					return false;
				}
				if (sambaPwdLastSet == "") {
					alert("Please enter an time for Samba last set.");
					return false;
				}
				if (userPassword1 != userPassword2) {
					alert("Passwords do not match.");
					return false;
				}
				var task = type + '_user';
				$.ajax({
					type: "POST",
					url: "api.php",
					data: {
						'task': task,
						'token': getToken,
						'givenName': givenName,
						'sn': sn,
						'cn': cn,
						'uid': uid,
						'userPassword': userPassword1,
						'mail': mail,
						'uidNumber': uidNumber,
						'gidNumber': gidNumber,
						'homeDirectory': homeDirectory,
						'loginShell': loginShell,
						'gecos': gecos,
						'sambaSID': sambaSID,
						'sambaLMPassword': sambaLMPassword,
						'sambaNTPassword': sambaNTPassword,
						'sambaPwdLastSet': sambaPwdLastSet
					},
					success: function(msg) {
						console.log("msg: "+msg);
						if (msg.match(/OK/)) {
							$("#userList").trigger('reloadGrid');
							$("#addEditUserDialog").dialog("close");
						} else {
							alert(msg);
						}
					},
				});
			},
			"Cancel": function() {
				$("#addEditUserDialog").dialog("close");
			}
		}
	});

	$("#userList").jqGrid('navButtonAdd', '#pager', {
                caption: "",
                buttonicon: "ui-icon-trash",
                title: "Delete User",
                position: "first",
                id: "deleteUserButton",
                onClickButton: function() {
			var uid = $("#userList").getCell($("#userList").getGridParam('selrow'), 'uid');
			if (!uid) {
				alert("Please select an user to delete.");
				return false;
			} else {
				if (confirm("Are you sure you want to delete this user?")) {
					$.ajax({
						type: "POST",
						url: "api.php",
						data: {
							'task': 'delete_user',
							'token': getToken,
							'uid': uid
						},
						success: function(msg) {
							if (msg.match(/OK/)) {
								$("#userList").trigger('reloadGrid');
								$("#addEditUserDialog").dialog("close");
							} else {
								alert(msg);
							}
						}
					});
				}
			}
		}
        });

	$("#userList").jqGrid('navButtonAdd', '#pager', {
                caption: "",
                buttonicon: "ui-icon-pencil",
                title: "Edit User",
                position: "first",
                id: "editUserButton",
                onClickButton: function() {
			var uid = $("#userList").getCell($("#userList").getGridParam('selrow'), 'uid');
			if (!uid) {
				alert("Please select an user to edit.");
				return false;
			} else {
				$("#addEditUserDialog").html('<img src="/images/loading.gif" />');
				$("#addEditUserDialog").load('add_edit_user.php', {'type': 'edit', 'user_id': uid}).dialog('option', 'title', 'Edit User').dialog('open');
			}
		}
        });

	$("#userList").jqGrid('navButtonAdd', '#pager', {
                caption: "",
                buttonicon: "ui-icon-plus",
                title: "Add User",
                position: "first",
                id: "addUserButton",
                onClickButton: function() {
			$("#addEditUserDialog").html('<img src="/images/loading.gif" />');
			$("#addEditUserDialog").load('add_edit_user.php', {'type': 'add'}).dialog('option', 'title', 'Add New User').dialog('open');
		}
        });


}); 

function signIn() {
	var username = $("#username").val();
	var password = $("#password").val();
	if (username == '') {
		alert("Please enter a username.");
		return false;
	}
	if (password == '') {
		alert("Please enter a password.");
		return false;
	}
	$.ajax({
		type: "POST",
		url: "api.php",
		data: {
			'task': 'sign_in',
			'username': username,
			'password': password
		},
		success: function(msg) {
			console.log("msg: "+msg);
			if (msg.match(/OK/)) {
				var tmpArray = msg.split(/ /);
				location.href = 'admin.php?token='+tmpArray[1];
			} else {
				$("#returnMsg").html(msg);
				$("#returnMsg").css('color', '#ff0000');
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(textStatus+": "+errorThrown);
		}
	});
}

function getUID() {
	return $("#userList").getCell($("#userList").getGridParam('selrow'), 'uid');
}

function getToken() {
	return $("#token").val();
}

