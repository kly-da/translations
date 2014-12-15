<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id	= $user -> uid;
	
	$arr_id=$_POST["mas"];
	
	//$delete_from=$_POST["from"];

	foreach  ($arr_id as $id_message)
	{
			$spam_message_query = "INSERT INTO `complaint` VALUES (NULL,NULL,'0','$id_message','$id')";
			$spam_message_result=mysql_query($spam_message_query);
	}

?>
