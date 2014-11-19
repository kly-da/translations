<?
  //=================================== Обработка параметров
  $text_id = intval($_GET["id"]);
  if ($text_id <= 0) {
    header('Location: /');
    die();
  }

  $chapter_id = intval($_GET["cid"]);

  //=================================== Авторизация

  include('../mod_db.php');
  include('../mod_auth.php');

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

  if (!($user -> isTextAdministratorOrCreator() || $user -> isManageUser())) {
    header('Location: /error/no_access.php');
    die();
  }

  //=================================== Описание функций

  function generateChapterList() {
    global $chapters, $text_row;
    $chapters = array();

    $query = 'SELECT
      `chapter_id`,
      `chapter_name`
    FROM
      `chapter`
    WHERE
      `text_id` = ' . $text_row["text_id"] . '
    ORDER BY
      `chapter_number`';
    $result = mysql_query($query);

    while ($row = mysql_fetch_assoc($result)) {
      array_push($chapters, $row);
    }

    mysql_free_result($result);
  }

  //=================================== Основной код

  generateChapterList();

  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/text_edit_order.css">
<?}

  $title = 'Порядок глав';
  include('../header.php');
?>

  <div class="content" style="border: 0px;">
    <h1>Порядок глав</h1>
    <form action="edit_order_save.php" method="post">
      <input type="hidden" name="id" value="<? print $text_id; ?>" />
      <fieldset>
        <legend>Настройка</legend>
        <label>
          <div class="form_input">
            <select name="cid" size="10">
<?
    foreach ($chapters as $chapter) {
?>
              <option value="<? print $chapter["chapter_id"]; ?>"<? if ($chapter_id == $chapter["chapter_id"]) print " selected";?>><? print $chapter["chapter_name"]; ?></option>
<?
    }
?>
            </select>
            <span class="buttons">
              <input name="up" type="submit" value="Вверх"/>
              <input name="down" type="submit" value="Вниз"/>
            </span>
          </div>
        </label>
      </fieldset>
    </form>
    <a href="view.php?id=<? print $text_row["text_id"]; ?>">Вернуться назад...</a>
  </div>
  <div class="user">
    <div class="middle_text">Пользователь</div>
  </div>
  <div style="clear:right;"/></div>
  <div class="news">
    <div class="middle_text">Новости</div>
  </div>

<? include('../footer.php');?>