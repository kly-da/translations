<?
	
	$tid = intval($_POST["tid"]);
	$cid = intval($_POST["cid"]);
	$status = $_POST["status"];
	if ($tid <= 0)
	{
		echo 0;
		die();
    }  
    
    include('../../mod_db.php');
	include('../../mod_auth.php');
	include('../../code/text_format.php');

	mysql_query("SET NAMES utf8");
	
	$trans_query = '';
	if ($status == 'ban')
	{
		$trans_query = 'UPDATE `translation` SET `banned` = 1, `best` = 0 WHERE `translation_id` = ' . $tid;
	} else {
		$trans_query = 'UPDATE `translation` SET `banned` = 0 WHERE `translation_id` = ' . $tid;
	}
	$ok = mysql_query($trans_query);
	
	$count_query = 'SELECT count( DISTINCT(`fragment_id`) ) as `total` FROM `translation` WHERE `banned` = 0 AND `chapter_id` = '. $cid;
	$count_result = mysql_query($count_query);
	$count_row = mysql_fetch_assoc($count_result);
	$total = $count_row["total"];
	$update_query = 'UPDATE `chapter` SET `translated_fragments_count` = ' . $total . ' WHERE `chapter_id` = ' . $cid;
	mysql_query($update_query);
	
	echo $ok;

?>
