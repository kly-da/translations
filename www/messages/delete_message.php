<?PHP
	include('../mod_db.php');

	$arr_id=$_POST["mas"];
	
	$sqlQueryDelete = "UPDATE `message` SET `is_recipient_delete`='1' WHERE `id` IN (".implode(',',$arr_id).")";

	$rs= mysql_query($sqlQueryDelete);
	
?>
