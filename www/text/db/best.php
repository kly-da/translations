<?
	
	$tid = intval($_POST["tid"]);
	$pid = intval($_POST["pid"]);
	if ($tid <= 0 || $pid < 0)
	{
		echo 0;
		die();
    }  
    
    include('../../mod_db.php');
	include('../../mod_auth.php');
	include('../../code/text_format.php');

	mysql_query("SET NAMES utf8");

	$ok = 0;	
	if ($pid > 0)
	{
		$prev_query = 'UPDATE `translation` SET `best` = 0 WHERE `translation_id` = ' . $pid;
		$ok = mysql_query($prev_query);
	}
	if ($pid != $tid)
	{
		$cur_query = 'UPDATE `translation` SET `best` = 1, `banned` = 0 WHERE `translation_id` = ' . $tid;
		$ok = mysql_query($cur_query);
	}
	
	echo $ok;

?>
