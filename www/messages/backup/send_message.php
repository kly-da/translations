<?PHP
	//header('Location: index.php');
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id=$user -> uid;
	
	$text=$_POST["answer_area"];
	
	$id_user_to=$_POST["id_user_to"];

	if($text<>null) {
		$insert_query = "INSERT INTO `message` VALUES (NULL,'$id','$id_user_to','$text',NULL,0,0,0)";
		$insert_result = mysql_query($insert_query);
	}	
?>