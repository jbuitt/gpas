<?php
	$msg = "";
	$status = "";
	if (isset($_GET)) {
		if (isset($_GET['status'])) {
			$status = $_GET['status'];
		}
	}
	switch($status) {
		case 'invalid_token':
			$msg = '<span style="color: red;">Invalid authentication token.</span><br /><br />';
			break;
	
		case 'logged_out':
			$msg = '<span style="color: green;">Logged out successfully.</span><br /><br />';
			break;

	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="charset" content="utf-8" />
	<title>General-Purpose Authentication System (GPAS) - User Admin</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
	<script src="js/grid.locale-en.js" type="text/javascript"></script>
	<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>
	<script src="js/main.js" type="text/javascript"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
</head>
<body>
<table width="100%">
	<tr>
		<td align="center">
			<img src="images/logo.png" />
			<div id="returnMsg"><?php echo $msg ?></div>
			<table>
				<tr>
					<td>Username</td>
					<td><input type="TEXT" id="username" size="20" /></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="PASSWORD" id="password" size="20" /></td>
				</tr>
			</table>
			<br />
			<input type="BUTTON" id="signInButton" value="Sign in" />
		</td>
	</tr>
</table>
</body>
</html>
