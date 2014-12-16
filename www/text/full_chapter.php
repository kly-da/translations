<?

	#==== Load page ===========================
	
	$text_id = intval($_GET["text_id"]);
	$chapter_id = intval($_GET["chapter_id"]);
	if ($text_id <= 0 || $chapter_id <= 0)
	{
		header('Location: /');
		die();
    }
    
    #==== Load page ===========================
    
    
    #==== Load database =======================

	include('../mod_db.php');
	include('../mod_auth.php');
	include('../code/text_format.php');

	mysql_query("SET NAMES utf8");

	$text_query = 'SELECT `text`.*, `user`.`name` AS `creator_name` FROM `text` JOIN `user` ON(`creator` = `user_id`) WHERE `text_id` = ' . $text_id;
	$chapter_query = 'SELECT `chapter_name`, `chapter_number`, `full` FROM `chapter` WHERE `chapter_id` = ' . $chapter_id;

	$text_result = mysql_query($text_query);
	$chapter_result = mysql_query($chapter_query);

	$chapter_row = mysql_fetch_assoc($chapter_result);
	$text_row = mysql_fetch_assoc($text_result);

	if (!$text_row) {
		header('Location: /error/text_not_found.php');
		die();
	}
	
	#==== Load database =======================
	
	$i = 0;
	$chapter_translation = $chapter_row["full"];
	
	$title = 'Глава';
	include('./templates/full_chapter.tpl');
?>
