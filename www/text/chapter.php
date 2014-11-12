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
?>

<script type="text/javascript" src="../scripts/jquery-1.7.2.min.js"></script>
<script>
	$(document).ready(function(){
		$('#translate_table').on('click', '.add', function(event) {
			var $num = $(this).attr('data-id');
			jQuery("#form_" + $num).toggle('show');
			$('#add_content_' + $num).toggle('show');
		});
	});
	
	$(document).ready(function(){
		$('#translate_table').on('click', '.cancel', function(event) {
			var $num = $(this).attr('data-id');
			jQuery("#add_content_" + $num).toggle('show');
			$("#form_" + $num).toggle('show');
		});
	});
</script>
	
<?
	
function like()
{
	echo "Like!";
}

function gen_add_row($rows_count, $frag_num)
{
	if ($rows_count == 1)
		print "<td colspan=3><div id=\"add_content_" . $frag_num . "\">Пока переводов нет.";
	else
		print "<td colspan=3><div id=\"add_content_" . $frag_num . "\">";
	print "<input type=\"button\" class=\"add\" value=\"Add...\" data-id=\"" . $frag_num . "\"></div>
		<div id=\"form_" . $frag_num . "\" style=\"display: none\">
			<form method=\"POST\" action=\"chapter.php\">
				Перевод:<br>
				<textarea style=\"width: 100%\" rows=\"10\" name=\"first_name\"></textarea><br>
				<input type=\"submit\" value=\"Ок\">
				<input type=\"button\" class=\"cancel\" value=\"Отмена\" data-id=\"" . $frag_num . "\">
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
		gen_add_row($rows_count, $i);
						
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
