<?PHP
	//header('Location: index.php');
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id=$user -> uid;
	
	$message_id = $_POST['id'];
	
	$update_query = "UPDATE `message` SET `is_readed`= '1' WHERE `id` = '$message_id'";		
	$update_result = mysql_query($update_query);
	
?>
