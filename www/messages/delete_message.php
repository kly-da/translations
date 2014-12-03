<?PHP
	include('../mod_db.php');

	$arr_id=$_POST["mas"];
	
	$is_inbox=$_POST["inbox"];
	
	
	if ($is_inbox == "true" )
		$sqlQueryDelete = "UPDATE `message` SET `is_recipient_delete`='1' WHERE `id` IN (".implode(',',$arr_id).")";
	else
		$sqlQueryDelete = "UPDATE `message` SET `is_sender_delete`='1' WHERE `id` IN (".implode(',',$arr_id).")";

	$rs=mysql_query($sqlQueryDelete);
	
?>
