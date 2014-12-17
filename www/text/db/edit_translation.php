<?
	
	include('../../mod_db.php');
	include('../../mod_auth.php');
	include('../../code/text_format.php');

	mysql_query("SET NAMES utf8");

	$ok = 0;
	if ($_POST['text'])
	{
		$tid = intval($_POST['tid']);
		$clear_query = 'DELETE from `rating` WHERE `translation_id` = ' . $tid;
		mysql_query($clear_query);
		$update_query = 'UPDATE `translation` SET `likes` = 0, `dislikes` = 0, `rating` = 0, 
				`length` = ' . intval($_POST['len']) . ', 
				`last_activity` = \'' . date("Y-m-d H:i:s") . '\', 
				`text` = \'' . $_POST['text'] . '\' 
				WHERE `translation_id` = ' . $tid;
		$ok = mysql_query($update_query);
	}
	else
	{
		echo 0;
		die();
	}
	echo $ok;

?>
