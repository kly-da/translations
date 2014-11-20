<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id=$user -> uid;

	$sqlQuery = "SELECT * FROM message WHERE `id_user_from` = '$id'";
	$rs = mysql_query($sqlQuery);
	while($row = mysql_fetch_array($rs))
	{
		$id_user = $row['id_user_to'];
		$sqlQueryUserName = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$id_user'";
		$rsUserName =  mysql_query($sqlQueryUserName);
		$name = mysql_fetch_row($rsUserName);
		echo "<div class=\"message\">Отправлено: <font color=\"red\">".$name[0]."</font> в " . $row['date_sending'] . "<br />" . substr($row['text'],0,30) . "<br /></div>";
	}
?>
<body onLoad="scroll(0,100%)"></body>