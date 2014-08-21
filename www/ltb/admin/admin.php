<?php
	// Check for valid token
	$token = "";
	if (isset($_GET)) {
		if (isset($_GET['token'])) {
			$token = $_GET['token'];
		}
	}
	if ($token == "") {
		header("Location: index.php");
		exit;
	}
	$redis = new Redis();
	$redis->connect('127.0.0.1', 6379);
	if ($redis->get($token) == "") {
		//header("Location: index.php?status=invalid_token");
		header("Location: index.php");
		exit;
	}
?>
<?php
        $msg = "";
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
<input type="HIDDEN" id="token" value="<?php echo $token ?>" />
<table>
	<tr>
		<td valign="top" align="center">
			<img src="images/logo.png" />
			<br />
			<input type="BUTTON" id="signOutButton" value="Sign Out" />
		</td>
		<td>&nbsp;</td>
		<td>
			<h1>User List</h1>
			<table id="userList"><tr><td></td></tr></table> 
			<div id="pager"></div> 
			<div id="addEditUserDialog"></div>
		</td>
	</tr>
</table>
</body>
</html>
