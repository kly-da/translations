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
	$chapter_query = 'SELECT `chapter_name`, `chapter_number` FROM `chapter` WHERE `chapter_id` = ' . $chapter_id;
	$fragment_query = 'SELECT `fragment_id`, `text`, `new_par` FROM `fragment` WHERE `chapter_id` = ' . $chapter_id;

	$text_result = mysql_query($text_query);
	$chapter_result = mysql_query($chapter_query);
	$fragment_result = mysql_query($fragment_query);

	$chapter_row = mysql_fetch_assoc($chapter_result);
	$text_row = mysql_fetch_assoc($text_result);

	if (!$text_row) {
		header('Location: /error/text_not_found.php');
		die();
	}
	
	#==== Load database =======================
	
	$i = 0;
	$chapter_translation = "";
	$full = true;
	while ($fragment_row = mysql_fetch_assoc($fragment_result))
	{
		
		$i++;
		$best_query = 'SELECT `translation_id`, `fragment_id`, `rating`, `text` FROM `translation` WHERE `best` = 1 AND `fragment_id` = ' . $fragment_row["fragment_id"];
		$best_result = mysql_query($best_query);
		$row = mysql_fetch_assoc($best_result);
		if (!$row)
		{
			$inner_query = 'SELECT `fragment_id`, MAX(`rating`) AS `rating` FROM `translation` WHERE `banned` = 0 AND `fragment_id` = ' . $fragment_row["fragment_id"] . ' GROUP BY `fragment_id`';
			$trans_query = 'SELECT `t`.`translation_id`, `t`.`fragment_id`, `t`.`rating`, `t`.`text` FROM `translation` `t` INNER JOIN (' . $inner_query . ') `s` ON `s`.`fragment_id` = `t`.`fragment_id` AND `s`.`rating` = `t`.`rating`';
			$trans_result = mysql_query($trans_query);
			$row = mysql_fetch_assoc($trans_result);
		}

		$del = " ";
		if ($i == 1)
			$del = "<p>";
		else if ($fragment_row["new_par"])
			$del = "</p><p>";
		
		if ($row)
			$chapter_translation = $chapter_translation . $del . $row["text"];
		else
		{
			$full = false;
			$chapter_translation = $chapter_translation . ' <Отрывок ожидает перевода>';
		}
	}
	$chapter_translation .= "</p>";
	
	$title = 'Глава';
	include('./templates/compile.tpl');
?>
