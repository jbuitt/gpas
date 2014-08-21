<?php

	include 'conf/ldap.php';

	$task = "";
	$token = "";

	if (isset($_POST)) {
		if (isset($_POST['task'])) {
			$task = $_POST['task'];
		}
		if (isset($_POST['token'])) {
			$token = $_POST['token'];
		}
	}
	if (isset($_GET)) {
		if (isset($_GET['task'])) {
			$task = $_GET['task'];
		}
		if (isset($_GET['token'])) {
			$token = $_GET['token'];
		}
	}

	// Check for valid token
	if ($task != "sign_in") {
		if ($token == "") {
			print "Error: invalid token\n";
			exit;
		} else {
			$redis = new Redis();
			$redis->connect('127.0.0.1', 6379);
			if ($redis->get($token) == "") {
				print "Error: invalid token\n";
				exit;
			}
		}
	}

	if ($task == "") {
		print "Error: wrong number of parameters supplied. (task)\n";
		exit;
	}

	switch($task) {
		case 'sign_in':
			$username = $_POST['username'];
			$password = $_POST['password'];
			$ds = ldap_connect($LDAP_HOST);  // must be a valid LDAP server!
			if (!$ds) {
				print "Could not connect to LDAP server.<br /><br />";
				return;
			}
			$lb = ldap_bind($ds, "uid=" . $username . ",cn=config", $password);
			if (!$lb) {
				print "Invalid username or password.<br /><br />";
				return;
			}
			// If we got here, user/pass was OK. Create auth token and put into Redis
			$token = rtrim(shell_exec("/usr/bin/uuidgen"));
			$redis = new Redis();
			$redis->connect('127.0.0.1', 6379);
			$redis->set($token, "$username");
			print "OK: $token";
			break;

		case 'sign_out':
			$token = $_POST['token'];
			$redis = new Redis();
			$redis->connect('127.0.0.1', 6379);
			$redis->del($token);
			print "OK";
			break;

		case 'list_users':
			header("Content-type: application/json");
			$user_list = array();
			$rows = array();
			$ds = ldap_connect($LDAP_HOST);  // must be a valid LDAP server!
			if (!$ds) {
				print "Error";
				return;
			}
			$lb = ldap_bind($ds, $LDAP_BIND_DN, $LDAP_BIND_PW);
			if (!$lb) {
				print "Error";
				return;
			}
			$sr = ldap_search($ds, $LDAP_BASE_DN, "uid=*");
			$info = ldap_get_entries($ds, $sr);
			//print "<pre>\n";
			//print_r($info);
			//print "</pre>\n";
			//return;
			for ($i=0; $i<$info['count']; $i++) {
				$rows[$i]['id']			= "$i";
				$rows[$i]['givenName']		= $info[$i]['givenname'][0];
				$rows[$i]['sn']			= $info[$i]['sn'][0];
				$rows[$i]['userPassword']	= $info[$i]['userpassword'][0];
				$rows[$i]['uid']		= $info[$i]['uid'][0];
				$rows[$i]['uidNumber']		= $info[$i]['uidnumber'][0];
				$rows[$i]['gidNumber']		= $info[$i]['gidnumber'][0];
				$rows[$i]['mail']		= $info[$i]['mail'][0];
			}
			ldap_close($ds);
			$count = $user_list['records'] = $info['count'];
			$user_list['total']   = "1";
			$user_list['page']    = "1";
			$user_list['records'] = "$count";
			$user_list['rows']    = $rows;
			print json_encode($user_list);
			break;

		case 'add_user':
			$info = array_merge($_POST);
			unset($info['id']);
			unset($info['oper']);
			unset($info['task']);
			unset($info['token']);
			$info['objectClass'][0]	= 'top';
			$info['objectClass'][1]	= 'person';
			$info['objectClass'][2]	= 'organizationalPerson';
			$info['objectClass'][3]	= 'inetOrgPerson';
			$info['objectClass'][4]	= 'posixAccount';
			$info['objectClass'][5]	= 'sambaSamAccount';
			//print "<pre>\n";
			//print_r($info);
			//print "</pre>\n";
			//return;
			$ds = ldap_connect($LDAP_HOST);  // must be a valid LDAP server!
			if (!$ds) {
				return;
			}
			$lb = ldap_bind($ds, $LDAP_BIND_DN, $LDAP_BIND_PW);
			if (!$lb) {
				return;
			}
			$r = ldap_add($ds, 'uid=' . $info['uid'] . ',' . $LDAP_BASE_DN, $info);
			if ($r == TRUE) {
				print "OK";
			} else {
				print "Error: " . ldap_err2str(ldap_errno($ds));
			}
			ldap_close($ds);
			break;

		case 'edit_user':
			$info = array_merge($_POST);
			unset($info['id']);
			unset($info['oper']);
			unset($info['task']);
			unset($info['token']);
			$info['objectClass'][0]	= 'top';
			$info['objectClass'][1]	= 'person';
			$info['objectClass'][2]	= 'organizationalPerson';
			$info['objectClass'][3]	= 'inetOrgPerson';
			$info['objectClass'][4]	= 'posixAccount';
			$info['objectClass'][5]	= 'sambaSamAccount';
			//print "<pre>\n";
			//print_r($info);
			//print "</pre>\n";
			//return;
			$ds = ldap_connect($LDAP_HOST);  // must be a valid LDAP server!
			if (!$ds) {
				print "Error";
				return;
			}
			$lb = ldap_bind($ds, $LDAP_BIND_DN, $LDAP_BIND_PW);
			if (!$lb) {
				print "Error";
				return;
			}
			$r = ldap_modify($ds, 'uid=' . $info['uid'] . ',' . $LDAP_BASE_DN, $info);
			if ($r == TRUE) {
				print "OK";
			} else {
				print "Error: " . ldap_err2str(ldap_errno($ds));
			}
			ldap_close($ds);
			break;

		case 'delete_user':
			//print "<pre>\n";
			//print_r($_POST);
			//print "</pre>\n";
			//return;
			$uid = $_POST['uid'];
			$ds = ldap_connect($LDAP_HOST);  // must be a valid LDAP server!
			if (!$ds) {
				print "Error";
				return;
			}
			$lb = ldap_bind($ds, $LDAP_BIND_DN, $LDAP_BIND_PW);
			if (!$lb) {
				print "Error";
				return;
			}
			ldap_delete($ds, 'uid=' . $uid . ',' . $LDAP_BASE_DN);
			ldap_close($ds);
			print "OK";
			break;

		case 'get_ntlm_password':
			$password = "";
			if (isset($_POST)) {
				if (isset($_POST['userPassword'])) {
					$password = $_POST['userPassword'];
				}
			}
			if ($password == "") {
				print "ERROR";
				return;
			}
			$password = $_POST['userPassword'];
			print "OK: " . rtrim(shell_exec("scripts/gen_ntlm_passwd.pl " . $password));
			break;

		case 'get_next_uid_gid':
			print "OK: " . rtrim(shell_exec("scripts/get_next_uid_gid.pl"));
			break;

		case $task:
			print "Error: unknown task $task";
			break;
	}

?>
