<!DOCTYPE html>
<html lang="en">
<head>
        <meta name="charset" content="utf-8" />
        <title>General-Purpose Authentication System (GPAS) - Installation</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js"></script>
        <script src="js/main.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="css/main.css" />
</head>
<body>
<input type="HIDDEN" id="configPage" value="1" />
<h1>Installation</h1>
<div id="page1" style="display: inline;">
<b>General:</b><br />
<br />
<table>
	<tr>
		<td>Host Name:</td>
		<td><input type="TEXT" id="hostname" size="30" value="ldap1" /></td>
	</tr>
	<tr>
		<td>Domain Name:</td>
		<td><input type="TEXT" id="domainName" size="30" value="example.com" /></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td>Directory Server UserID:</td>
		<td><input type="TEXT" id="suiteSpotUserID" size="30" value="dirsrv" /></td>
	</tr>
	<tr>
		<td>Directory Server Group:</td>
		<td><input type="TEXT" id="suiteSpotGroup" size="30" value="dirsrv" /></td>
	</tr>
	<tr>
		<td>Admin Domain:</td>
		<td><input type="TEXT" id="adminDomain" size="30" value="example.com" /></td>
	</tr>
	<tr>
		<td>Config Directory UserID:</td>
		<td><input type="TEXT" id="configDirectoryAdminID" size="30" value="diradmin" /></td>
	</tr>
	<tr>
		<td>Config Directory Password:</td>
		<td><input type="PASSWORD" id="configDirectoryAdminPwd1" size="30" /></td>
	</tr>
	<tr>
		<td>Confirm Password:</td>
		<td><input type="PASSWORD" id="configDirectoryAdminPwd2" size="30" /></td>
	</tr>
</table>
</div>
<div id="page2" style="display: none;">
<b>LDAP:</b><br />
<br />
<table>
	<tr>
		<td>Server Port:</td>
		<td><input type="TEXT" id="serverPort" size="30" value="389" /></td>
	</tr>
	<tr>
		<td>Suffix:</td>
		<td><input type="TEXT" id="suffix" size="30" value="dc=example,dc=com" /></td>
	</tr>
	<tr>
		<td>Root DN:</td>
		<td><input type="TEXT" id="rootDN" size="30" value="cn=Directory Manager" /></td>
	</tr>
	<tr>
		<td>Root DN Password:</td>
		<td><input type="PASSWORD" id="rootDNPassword1" size="30" /></td>
	</tr>
	<tr>
		<td>Confirm Password:</td>
		<td><input type="PASSWORD" id="rootDNPassword2" size="30" /></td>
	</tr>
</table>
</div>
<div id="page3" style="display: none;">
<b>Admin:</b><br />
<br />
<table>
	<tr>
		<td>Server Port:</td>
		<td><input type="TEXT" id="serverPort" size="30" value="389" /></td>
	</tr>
</table>
</div>
<br />
<input type="BUTTON" id="prevButton" value="< Prev" style="display: none;" />
<input type="BUTTON" id="nextButton" value="Next >" />

</body>
</html>
