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
	$fragment_query = 'SELECT `fragment_id`, `text` FROM `fragment` WHERE `chapter_id` = ' . $chapter_id;

	$text_result = mysql_query($text_query);
	$chapter_result = mysql_query($chapter_query);
	$fragment_result = mysql_query($fragment_query);

	$chapter_row = mysql_fetch_assoc($chapter_result);
	$text_row = mysql_fetch_assoc($text_result);

	if (!$text_row) {
		header('Location: /error/text_not_found.php');
		die();
	}

	$user -> loadTextOut($text_row);
	$user_id = $user->uid;
	$can_translate = $user->isTextTranslator() || $user->isAdministrator();

	if ($text_row["is_deleted"])
	{
		header('Location: /error/text_not_found.php');
		die();
	}
	
	#==== Load database =======================
	
	$fragments = array();
	$trans_count = array();
	$translations = array();
	$trans_like = array();
	$trans_dislike = array();
	$translated = array();
	
	$frag_num = 1;
	while ($fragment_row = mysql_fetch_assoc($fragment_result))
	{		
		$fragments[$frag_num] = $fragment_row;
		$trans_query = 'SELECT `translation_id`, `fragment_id`, `user_id`, `text` FROM `translation` WHERE 
			`fragment_id` = ' . $fragment_row["fragment_id"];
		$trans_result = mysql_query($trans_query);
		$trans_count[$frag_num] = mysql_num_rows($trans_result);
		$translations[$frag_num] = array();
		$translated[$frag_num] = false;
		
		$trans_num = 1;
		while ($trans_row = mysql_fetch_assoc($trans_result))
		{
			$translations[$frag_num][$trans_num] = $trans_row;
			$trans_id = $trans_row["translation_id"];
			if ($trans_row["user_id"] == $user_id)
			{
				$translated[$frag_num] = true;
			}
			$trans_like[$trans_id] = mysql_num_rows(mysql_query('SELECT `rating_id`, `translation_id`, `mark` FROM `rating` WHERE
				`translation_id` = ' . $trans_id . ' AND `mark` = 1'));
			$trans_dislike[$trans_id] = mysql_num_rows(mysql_query('SELECT `rating_id`, `translation_id`, `mark` FROM `rating` WHERE
				`translation_id` = ' . $trans_id . ' AND `mark` = -1'));
			$trans_num++;
		}
		$frag_num++;
	}
	$title = 'Редактирование';
	include('./templates/chapter.tpl');
?>

