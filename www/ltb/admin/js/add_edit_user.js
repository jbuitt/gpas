//
// add_edit_user.js
//

$(function () {

	$("#givenName").focus();

	$("#accountAttrType").change(function() {
		var attrType = $("#accountAttrType option:selected").val();
		if (attrType == "user") {
			$("#posixAttrDiv").hide();
			$("#sambaAttrDiv").hide();
			$("#userAttrDiv").show();
		} else if (attrType == "posix") {
			$("#sambaAttrDiv").hide();
			$("#userAttrDiv").hide();
			$("#posixAttrDiv").show();
		} else if (attrType == "samba") {
			$("#posixAttrDiv").hide();
			$("#userAttrDiv").hide();
			$("#sambaAttrDiv").show();
		}
	});

	$("#givenName").blur(function() {
		if ($("#givenName").val() != "" && $("#sn").val() != "" && $("#mail").val() == "") {
			$("#cn").val( $("#givenName").val() + ' ' + $("#sn").val() );
			$("#gecos").val( $("#givenName").val() + ' ' + $("#sn").val() );
			$("#mail").val( $("#givenName").val().toLowerCase() + '.' + $("#sn").val().toLowerCase() + '@punchkickinteractive.com' );
		}
	});

	$("#sn").blur(function() {
		if ($("#givenName").val() != "" && $("#sn").val() != "") {
			$("#cn").val( $("#givenName").val() + ' ' + $("#sn").val() );
			$("#uid").val( $("#givenName").val().toLowerCase().replace(/ /g, '') + $("#sn").val().toLowerCase().replace(/ /g, '') );
			$("#gecos").val( $("#givenName").val().replace(/ /g, '') + ' ' + $("#sn").val().replace(/ /g, '') );
			$("#mail").val( $("#givenName").val().toLowerCase().replace(/ /g, '') + '.' + $("#sn").val().toLowerCase().replace(/ /g, '') + '@punchkickinteractive.com' );
		}
	});

	$("#uid").blur(function() {
		if ($("#uid").val() != "") {
			$("#homeDirectory").val( '/home/' + $("#uid").val().toLowerCase() );
			$("#userPassword1").val( $("#uid").val().toLowerCase()+'2' );
			$("#userPassword2").val( $("#uid").val().toLowerCase()+'2' );
		}
	});

	$("#uidNumber").blur(function() {
		if ($("#uidNumber").val() != "") {
			$("#sambaSID").val( 'S-1-0-0-' + (($("#uidNumber").val()*2)+1000) );
		}
	});

	$("#userPassword2").blur(function() {
		var userPassword = $("#userPassword2").val();
		if (userPassword != "") {
			$.ajax({
				type: "POST",
				url: "api.php",
				data: {
					'task': 'get_ntlm_password',
					'userPassword': userPassword,
					'token': getToken
				},
				success: function(msg) {
					if (msg.match(/OK/)) {
						var tmpArray1 = msg.split(/ /);
						var tmpArray2 = tmpArray1[1].split(/:/);
						$("#sambaNTPassword").val(tmpArray2[0]);
						$("#sambaLMPassword").val(tmpArray2[1]);
					}
				},
			});
		}
	});

	$("#sambaPwdLastSet").val( +new Date );

	// get next UID & GID
	if ($("#uidNumber").val() == "" && $("#gidNumber").val() == "") {
		$.ajax({
			type: "POST",
			url: "api.php",
			data: {
				'task': 'get_next_uid_gid',
				'token': getToken
			},
			success: function(msg) {
				if (msg.match(/OK/)) {
					var tmpArray1 = msg.split(/ /);
					var tmpArray2 = tmpArray1[1].split(/:/);
					$("#uidNumber").val(tmpArray2[0]);
					$("#gidNumber").val(tmpArray2[1]);
					$("#sambaSID").val( 'S-1-0-0-' + (($("#uidNumber").val()*2)+1000) );
				}
			},
		});
	}

});
