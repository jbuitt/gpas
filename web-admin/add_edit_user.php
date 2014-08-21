<?php
	$type = "";
	if (isset($_POST)) {
		if (isset($_POST['type'])) {
			$type = $_POST['type'];
		}
		if (isset($_POST['user_id'])) {
			$user_id = $_POST['user_id'];
		}
	}

	if ($type == "add") {
		$given_name		= "";
		$sur_name		= "";
		$common_name		= "";
		$user_id		= "";
		$user_password		= "";
		$email			= "";
		$uid_number		= "";
		$gid_number		= "";
		$home_directory		= "";
		$login_shell		= "/bin/bash";
		$gecos_field		= "";
		$samba_gid		= "";
		$samba_lm_password	= "";
		$samba_nt_password	= "";
		$samba_pwd_last_set	= "";
	} elseif ($type == "edit") {
		include "conf/ldap.php";
		$ds = ldap_connect($LDAP_HOST);  // must be a valid LDAP server!
		$lb = ldap_bind($ds, $LDAP_BIND_DN, $LDAP_BIND_PW);
		$sr = ldap_search($ds, $LDAP_BASE_DN, "uid=" . $user_id);
		$info = ldap_get_entries($ds, $sr);
		//print "<pre>\n";
		//print_r($info);
		//print "</pre>\n";
		$given_name		= $info[0]['givenname'][0];
		$sur_name		= $info[0]['sn'][0];
		$common_name		= $info[0]['cn'][0];
		$user_id		= $info[0]['uid'][0];
		$user_password		= $info[0]['userpassword'][0];
		$email			= $info[0]['mail'][0];
		$uid_number		= $info[0]['uidnumber'][0];
		$gid_number		= $info[0]['gidnumber'][0];
		$home_directory		= $info[0]['homedirectory'][0];
		$login_shell		= $info[0]['loginshell'][0];
		$gecos_field		= $info[0]['gecos'][0];
		$samba_sid		= $info[0]['sambasid'][0];
		$samba_lm_password	= $info[0]['sambalmpassword'][0];
		$samba_nt_password	= $info[0]['sambantpassword'][0];
		$samba_pwd_last_set	= $info[0]['sambapwdlastset'][0];
	}

?>
<script src="js/add_edit_user.js" type="text/javascript"></script>
<input type="HIDDEN" id="type" value="<?php echo $type ?>" />
<br />
<?php
	if ($type == "edit") {
?>
DN: uid=<?php echo $user_id ?>,<?php echo $LDAP_BASE_DN ?><br /><br />
<?php
	}
?>
<table width="100%">
	<tr>
		<td valign="top">
			<select size="13" id="accountAttrType" style="width: 100px;">
				<option value="user" SELECTED>User</option>
				<option value="posix">Posix</option>
				<option value="samba">Samba</option>
			</select>
		</td>
		<td>&nbsp;</td>
		<td valign="top">
			<div id="userAttrDiv" style="display: inline;">
				<table width="100%">
					<tr>
						<td align="right">First Name</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="givenName" size="30" value="<?php echo $given_name ?>" /></td>
					</tr>
					<tr>
						<td align="right">Last Name</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="sn" size="30" value="<?php echo $sur_name ?>" /></td>
					</tr>
					<tr>
						<td align="right">Common Name</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="cn" size="30" value="<?php echo $common_name ?>" /></td>
					</tr>
					<tr>
						<td align="right">User ID</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="uid" size="30" value="<?php echo $user_id ?>" /></td>
					</tr>
					<tr>
						<td align="right">Password</td>
						<td>&nbsp;</td>
						<td><input type="PASSWORD" id="userPassword1" size="30" value="<?php echo $user_password ?>" /></td>
					</tr>
					<tr>
						<td align="right">Password (again)</td>
						<td>&nbsp;</td>
						<td><input type="PASSWORD" id="userPassword2" size="30" value="<?php echo $user_password ?>" /></td>
					</tr>
					<tr>
						<td align="right">E-Mail</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="mail" size="30" value="<?php echo $email ?>" /></td>
					</tr>
				</table>
			</div>
			<div id="posixAttrDiv" style="display: none;">
				<table width="100%">
					<tr>
						<td align="right">UID Number</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="uidNumber" size="30" value="<?php echo $uid_number ?>" /></td>
					</tr>
					<tr>
						<td align="right" valign="top">GID Number</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="gidNumber" size="30" value="<?php echo $gid_number ?>" /></td>
					</tr>
					<tr>
						<td align="right">Home Directory</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="homeDirectory" size="30" value="<?php echo $home_directory ?>" /></td>
					</tr>
					<tr>
						<td align="right">Login Shell</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="loginShell" size="30" value="<?php echo $login_shell ?>" /></td>
					</tr>
					<tr>
						<td align="right">Gecos</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="gecos" size="30" value="<?php echo $gecos_field ?>" /></td>
					</tr>
				</table>
			</div>
			<div id="sambaAttrDiv" style="display: none;">
				<table width="100%">
					<tr>
						<td align="right">Samba SID</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="sambaSID" size="30" value="<?php echo $samba_sid ?>" /></td>
					</tr>
					<tr>
						<td align="right">LM Password</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="sambaLMPassword" size="30" value="<?php echo $samba_lm_password ?>" /></td>
					</tr>
					<tr>
						<td align="right">NT Password</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="sambaNTPassword" size="30" value="<?php echo $samba_nt_password ?>" /></td>
					</tr>
					<tr>
						<td align="right">Password Last Set</td>
						<td>&nbsp;</td>
						<td><input type="TEXT" id="sambaPwdLastSet" size="30" value="<?php echo $samba_pwd_last_set ?>" /></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
