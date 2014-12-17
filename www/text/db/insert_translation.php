<?
	
	include('../../mod_db.php');
	include('../../mod_auth.php');
	include('../../code/text_format.php');

	mysql_query("SET NAMES utf8");

	$ok = 0;
	if ($_POST['text'])
	{
		$insert_query = 'INSERT INTO `translation` (`fragment_id`, `chapter_id`, `length`, `user_id`, `text`)
						VALUES (\'' . intval($_POST['fid']) . '\', 
						\'' . intval($_POST['cid']) . '\',
						\'' . intval($_POST['len']) . '\', 
						\'' . intval($_POST['uid']) . '\', 
						\'' . $_POST['text'] . '\')';
		$ok = mysql_query($insert_query);
		
		$cid = intval($_POST['cid']);
		$count_query = 'SELECT count( DISTINCT(`fragment_id`) ) as `total` FROM `translation` WHERE `banned` = 0 AND `chapter_id` = '. $cid;
		$count_result = mysql_query($count_query);
		$count_row = mysql_fetch_assoc($count_result);
		$total = $count_row["total"];
		$update_query = 'UPDATE `chapter` SET `translated_fragments_count` = ' . $total . ' WHERE `chapter_id` = ' . $cid;
		mysql_query($update_query);
		
	}
	else
	{
		echo 0;
		die();
	}
	echo $ok;

?>
