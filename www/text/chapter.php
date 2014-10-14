<?
  //=================================== Обработка параметров
  $text_id = intval($_GET["text_id"]);
  $chapter_id = intval($_GET["chapter_id"]);
  if ($text_id <= 0 || $chapter_id <= 0) {
    header('Location: /');
    die();
  }

  //=================================== Авторизация

  include('../mod_db.php');
  include('../mod_auth.php');
  include('../code/text_format.php');
  
  mysql_query("SET NAMES utf8");

  $query = 'SELECT `text`.*, `user`.`name` AS `creator_name` FROM `text` JOIN `user` ON(`creator` = `user_id`) WHERE `text_id` = ' . $text_id;
  $result = mysql_query($query);
  $text_row = mysql_fetch_assoc($result);
  if (!$text_row) {
    header('Location: /error/text_not_found.php');
    die();
  }

  $user -> loadTextOut($text_row);

  if ($text_row["is_deleted"]) {
    header('Location: /error/text_not_found.php');
    die();
  }
  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/text_main.css">
<?}

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
				<th>Отрывок</th>
				<th>Перевод</th>
			</tr>
			<tr>
				<td rowspan="3">London is the capital of Great Britain.</td>
				<td>Лондон — столица Великобритании</td>
			</tr>
			<tr>
				<td>Лондон — это столица Великобритании.</td>
			</tr>
			<tr>
				<td>Лондон — это столица Англии.</td>
			</tr>
			<tr>
				<td rowspan="3">Hello! My name is Petr.</td>
				<td>Привет! Меня зовут Пётр.</td>
			</tr>
			<tr>
				<td>Привет, я Петр.</td>
			</tr>
			<tr>
				<td>Привет, я Денис.</td>
			</tr>
		</table>
		<br>
		
    </div>
  </div>
  <div style="clear:right;"/></div>
  

<? include('../footer.php');?>
