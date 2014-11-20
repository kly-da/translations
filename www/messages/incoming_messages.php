<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id=$user -> uid;

	$sqlQuery = "SELECT * FROM message WHERE `id_user_to` = '$id'";
	$rs = mysql_query($sqlQuery);
	if (mysql_num_rows($rs) > 0)
	{
		while($row = mysql_fetch_array($rs))
		{
			$id_user = $row['id_user_from'];
			$sqlQueryUserName = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$id_user'";
			$rsUserName =  mysql_query($sqlQueryUserName);
			$name = mysql_fetch_row($rsUserName);
			echo "<div class=\"message\" id =".$row['id']." onClick=\"OpenMessage()\">Сообщение от: <font color=\"red\">". $name[0]."</font> в " . $row['date_sending'] . "<br /> " . $row['text'] . "<br /></div>";
		} 
	}
	else echo 'В данный момент у вас нету входящих сообщений';
?>
<body onLoad="scroll(0,100%)"></body>