<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id=$user -> uid;

	$sqlQuery = "SELECT COUNT( * ) FROM  `message` WHERE  `is_readed` =  '0' AND `id_user_to` = '$id'";
	$rs = mysql_query($sqlQuery);
	
	$row = mysql_fetch_row($rs);
	
	if (($row[0]) > 0)
	{
		$count = $row[0];
		print ("Входящие (" . $count . ")");
	}
	else
	{
		print ("Входящие");
	}
?>

