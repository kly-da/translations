<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id	= $user -> uid;
	
	$arr_id=$_POST["mas"];
	
	$delete_from=$_POST["from"];
	
	if ($delete_from == "dialogs_list")
	{
		foreach  ($arr_id as $id_user)
		{
			$delete_query = "UPDATE `message` SET `is_recipient_delete`='1' WHERE `id_user_to` IN ('$id','$id_user') AND `id_user_from` IN ('$id','$id_user')";
			$delete_result=mysql_query($delete_query);
		}
	}
	//else echo("не равно");
	//	$delete_query= "UPDATE `message` SET `is_sender_delete`='1' WHERE `id` IN (".implode(',',$arr_id).")";

	
	
?>
