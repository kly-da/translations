<?
	
	include('../../mod_db.php');
	include('../../mod_auth.php');
	include('../../code/text_format.php');

	mysql_query("SET NAMES utf8");

	$ok = 0;
	if ($_POST['text'])
	{
		$cid = intval($_POST['cid']);
		$update_query = 'UPDATE `chapter` SET `full` = \'' . nl2br($_POST['text']) . '\' 
				WHERE `chapter_id` = ' . $cid;
		$ok = mysql_query($update_query);
	}
	else
	{
		echo 0;
		die();
	}
	echo $ok;

?>
