<?PHP
	//header('Location: index.php');
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id=$user -> uid;
	
	$text=$_POST["answer_area"];
	
	$id_user_to=$_POST["id_user_to"];

	/*if (empty ($id_user_to)) 
	{	
		$name = $_POST["recipient"];

		$sqlQueryUserId = "SELECT `user_id` FROM `user` WHERE `name` = '$name'";
		$rsUserId= mysql_query($sqlQueryUserId);
		if (mysql_num_rows($rsUserId) > 0)
		{
			$idrow = mysql_fetch_row($rsUserId);
			$id_user_to = $idrow[0];
		}
		else $id_user_to = 1;
	}*/
	if($text<>null) {
		$insert_query = "INSERT INTO `message` VALUES (NULL,'$id','$id_user_to','$text',NULL,0,0,0)";
		$insert_result = mysql_query($insert_query);
	}	
?>