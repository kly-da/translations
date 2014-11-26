<?
	
	$trans_id = intval($_POST["trans_id"]);
	$user_id = intval($_POST["user_id"]);
	$mark = intval($_POST["mark"]);
	if ($trans_id <= 0 || $user_id <= 0 || ($mark != 1 && $mark != -1))
	{
		die();
    }  

	include('../../mod_db.php');
	include('../../mod_auth.php');
	include('../../code/text_format.php');

	mysql_query("SET NAMES utf8");

	$rate_query = 'INSERT INTO `rating` (translation_id, user_id, mark)
					VALUES (\'' . $trans_id . '\', 
					\'' . $user_id . '\', 
					\'' . $mark . '\')';
	$ok = mysql_query($rate_query);
	if ($ok)
	{
		if ($mark == 1)
			$trans_query = 'UPDATE `translation` SET `likes` = `likes` + 1, `rating` = `rating` + 1 
				WHERE `translation_id` = ' . $trans_id;
		else
			$trans_query = 'UPDATE `translation` SET `dislikes` = `dislikes` + 1, `rating` = `rating` + 1 
				WHERE `translation_id` = ' . $trans_id;
		$ok = mysql_query($trans_query);
	}
	echo $ok;

?>
