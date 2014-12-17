<?
	
	include('../../mod_db.php');
	include('../../mod_auth.php');
	include('../../code/text_format.php');

	mysql_query("SET NAMES utf8");

	$ok = 0;
	if ($_POST['text'])
	{
		$insert_query = 'INSERT INTO `translation` (fragment_id, length, user_id, text)
						VALUES (\'' . intval($_POST['fid']) . '\', 
						\'' . intval($_POST['len']) . '\', 
						\'' . intval($_POST['uid']) . '\', 
						\'' . $_POST['text'] . '\')';
		$ok = mysql_query($insert_query);
	}
	else
	{
		echo 0;
		die();
	}
	echo $ok;

?>
