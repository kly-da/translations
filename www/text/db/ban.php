<?
	
	$tid = intval($_POST["tid"]);
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
	
	echo $ok;

?>
