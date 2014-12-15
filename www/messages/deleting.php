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
			$delete_query_recipient = "UPDATE `message` SET `is_recipient_delete`='1' WHERE `id_user_to` ='$id' AND `id_user_from`= '$id_user'";
			$delete_result=mysql_query($delete_query_recipient);
			
			$delete_query_sender = "UPDATE `message` SET `is_sender_delete`='1' WHERE `id_user_to` ='$id_user' AND `id_user_from`= '$id'";
			$delete_result=mysql_query($delete_query_sender);						
		}
	}
	elseif ($delete_from == "dialog")
	{
		foreach  ($arr_id as $id_message)
		{
			//echo($id_message);
			$select_query = "SELECT `id_user_from` FROM message WHERE `id` = '$id_message'";
			$select_result = mysql_query($select_query);
			$id_from_row = mysql_fetch_array($select_result);
			$id_from = $id_from_row[0];
			//echo($id_from);
			if ($id_from == $id) 
			{
				$delete_query_message = "UPDATE `message` SET `is_sender_delete`='1' WHERE `id` ='$id_message'";
				$delete_result=mysql_query($delete_query_message);
			}
			else
			{
				$delete_query_message = "UPDATE `message` SET `is_recipient_delete`='1' WHERE `id`='$id_message'";
				$delete_result=mysql_query($delete_query_message);
			}
		}
	}
	elseif ($delete_from == "inbox")
	{
		foreach  ($arr_id as $id_message)
		{
			$delete_query_message = "UPDATE `message` SET `is_recipient_delete`='1' WHERE `id` ='$id_message'";
			$delete_result=mysql_query($delete_query_message);
		}
	}
	elseif ($delete_from == "sent")
	{
		foreach  ($arr_id as $id_message)
		{
			$delete_query_message = "UPDATE `message` SET `is_sender_delete`='1' WHERE `id` ='$id_message'";
			$delete_result=mysql_query($delete_query_message);
		}
	}
?>
