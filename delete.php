<?php

require_once("functions.php");

if(isset($_GET['c'])) {
	$code = $_GET['c'];
	
	mysql_connect($mysql_host, $mysql_user, $mysql_pass)
	or die("Can't connect: " . mysql_connect_error());
	
	mysql_select_db($mysql_db);
	
	$sql = "SELECT count(*)
		FROM email_verify
		WHERE verify_code = '$code'";
	$query = mysql_query($sql);
	if($query)) {
		print_r(mysql_fetch_row($query));
	} else {
		echo "ko";
	}
}

?>
