<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id=$user -> uid;
	
	$text=$_POST["answer_area"];
	//$id_user_from=$_POST["id_user_from"];
	
	$id_user_from=5;
	
	if($text<>null) {
		$sqlQueryInsert = "INSERT INTO `message` VALUES (NULL,'$id','$id_user_from','$text',NULL,0,0,0)";
		$rs = mysql_query($sqlQueryInsert);
	}
	
	header('Location: index.php');
?>
