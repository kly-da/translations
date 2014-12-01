<?
	
	$tid = intval($_POST["tid"]);
	$uid = intval($_POST["uid"]);
	$mark = intval($_POST["mark"]);
	if ($tid <= 0 || $uid <= 0 || ($mark != 1 && $mark != -1))
	{
		echo 0;
		die();
    }  

	include('../../mod_db.php');
	include('../../mod_auth.php');
	include('../../code/text_format.php');

	mysql_query("SET NAMES utf8");

	$select_query = 'SELECT * FROM `rating` WHERE `translation_id` = ' . $tid . ' AND `user_id` = ' . $uid;
	$select_result = mysql_query($select_query);
	
	if (mysql_num_rows($select_result))
	{
		echo 0;
		die();
	}
	
	$rate_query = 'INSERT INTO `rating` (`translation_id`, `user_id`, `mark`)
					VALUES (\'' . $tid . '\', 
					\'' . $uid . '\', 
					\'' . $mark . '\')';
	$ok = mysql_query($rate_query);
	if ($ok)
	{
		if ($mark == 1)
			$trans_query = 'UPDATE `translation` SET `likes` = `likes` + 1, `rating` = `rating` + 1 
				WHERE `translation_id` = ' . $tid;
		else
			$trans_query = 'UPDATE `translation` SET `dislikes` = `dislikes` + 1, `rating` = `rating` + 1 
				WHERE `translation_id` = ' . $tid;
		$ok = mysql_query($trans_query);
	}
	echo $ok;

?>
