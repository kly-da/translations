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
	$fragment_query = 'SELECT `fragment_id`, `text` FROM `fragment` WHERE `text_id` = ' . $text_id;

	$text_result = mysql_query($text_query);
	$fragment_result = mysql_query($fragment_query);

	$text_row = mysql_fetch_assoc($text_result);

	if (!$text_row) {
		header('Location: /error/text_not_found.php');
		die();
	}

	$user -> loadTextOut($text_row);

	if ($text_row["is_deleted"])
	{
		header('Location: /error/text_not_found.php');
		die();
	}
	
	#==== Load database =======================
	
function like()
{
	echo "Like!";
}

function gen_add_row($rows_count)
{
	if ($rows_count == 1)
		print "<td colspan=3>Пока переводов нет.";
	else
		print "<td colspan=3>";
	print "<input type=\"button\" value=\"Add...\">
		<div>
		<form method=\"POST\" action=\"chapter.php\">
		Перевод:<br>
		<textarea style=\"width: 100%\" rows=\"10\" name=\"first_name\"></textarea><br>
		<input type=\"submit\" value=\"Ок\">
		<input type=\"submit\" value=\"Отмена\">
		</form>
		</div>
	</td>";	
}

function gen_chapter_table_content($text_result, $fragment_result)
{
	
	$i = 0;
	while ($fragment_row = mysql_fetch_assoc($fragment_result))
	{
		
		$trans_query = 'SELECT `translation_id`, `fragment_id`, `text` FROM `translation` WHERE 
			`fragment_id` = ' . $fragment_row["fragment_id"];
		$trans_result = mysql_query($trans_query);
		
		$rows_count = mysql_num_rows($trans_result) + 1;

		$i++;
		print "<tr><td rowspan=\"" . $rows_count . "\">" . $i . "</td>
			<td rowspan=\"" . $rows_count . "\">" . $fragment_row["text"] . "</td>";
		
		while ($trans_row = mysql_fetch_assoc($trans_result))
		{
			print "<td>" . $trans_row["text"] . "</td>
				<td><input type=\"button\" value=\"+\"></td>
				<td><input type=\"button\" value=\"-\"></td>
				</tr>";
			print "<tr>";
		}
		gen_add_row($rows_count);
						
		print "</tr>\n";
	}
	
}
  
?>
  <link rel="stylesheet" type="text/css" href="/styles/chapter_main.css">
<?
  $title = 'Редактирование';
  include('../header.php');
?>

  <div class="edit_content" style="border: 0px;">
    <div>
      <div id="title_header"><? print $text_row["title"] . " — Глава " . $chapter_id;?></div>
    </div>
    <div>
		
		<table id="translate_table" width=100% border="1" style='padding: 1px;'>
			
			<tr>
				<th>№</th>
				<th>Отрывок</th>
				<th colspan=3>Перевод</th>
			</tr>
		
		<?
			gen_chapter_table_content($text_result, $fragment_result);
	    ?>
		
		</table>
		
		<br>
		
    </div>
  </div>
  <div style="clear:right;"/></div>
  

<? include('../footer.php');?>
