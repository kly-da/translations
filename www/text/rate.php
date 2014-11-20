<?
	
	$trans_id = intval($_GET["trans_id"]);
	$user_id = intval($_GET["user_id"]);
	$mark = intval($_GET["mark"]);
	if ($trans_id <= 0 || $user_id <= 0 || ($mark != 1 && $mark != -1))
	{
		die();
    }  

	include('../mod_db.php');
	include('../mod_auth.php');
	include('../code/text_format.php');

	mysql_query("SET NAMES utf8");

	$rate_query = 'INSERT INTO `rating` (translation_id, user_id, mark)
					VALUES (\'' . $trans_id . '\', 
					\'' . $user_id . '\', 
					\'' . $mark . '\')';
	$ok = mysql_query($rate_query);
	echo $ok;

?>
