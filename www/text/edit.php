<?
  //=================================== Обработка параметров
  $text_id = intval($_GET["id"]);
  if ($text_id <= 0) {
    header('Location: /');
    die();
  }

  //=================================== Авторизация

  include('../mod_db.php');
  include('../mod_auth.php');
  include('../code/text_format.php');

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

  <div class="content" style="border: 0px;">
    <div>
      <div id="title_header"><? print $text_row["title"];?></div>
    </div>
  </div>
  <div class="user">
    <div class="middle_text">Пользователь</div>
  </div>
  <div style="clear:right;"/></div>
  <div class="news">
    <div class="middle_text">Новости</div>
  </div>

<? include('../footer.php');?>