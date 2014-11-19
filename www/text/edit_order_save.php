<?
  //=================================== Обработка параметров
  $text_id = intval($_POST["id"]);
  $chapter_id = intval($_POST["cid"]);
  if ($text_id <= 0) {
    header('Location: /');
    die();
  }

  if ($chapter_id <= 0) {
    header('Location: /text/edit_order.php?id=' . $text_id);
    die();
  }

  if (isset($_POST["up"])) {
    $command = "up";
  } else if (isset($_POST["down"])) {
    $command = "down";
  } else {
    header('Location: /text/edit_order.php?id=' . $text_id);
    die();
  }

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

  function endScript() {
    global $text_id, $chapter_id;
    header('Location: /text/edit_order.php?id=' . $text_id . '&cid=' . $chapter_id);
    die();
  }

  function loadChapter() {
    global $text_id, $chapter_id;
    global $old_number;
    $chapters = array();

    $query = 'SELECT
      (SELECT COUNT(*) FROM `chapter` WHERE `text_id` = ' . $text_id . ') AS `chapters_count`,
      (SELECT `chapter_number` FROM `chapter` WHERE `chapter_id` = ' . $chapter_id . ') AS `chapter_number`';
    $result = mysql_query($query);

    $chapter_row = mysql_fetch_assoc($result);

    if (!$chapter_row) {
      header('Location: /error/internal.php');
      die();
    }

    $old_number = $chapter_row["chapter_number"];

    if ($chapter_row["chapters_count"] == 1)
      endScript();

    mysql_free_result($result);
  }

  function changeNumber() {
    global $text_id, $chapter_id;
    global $old_number, $new_number;
    $chapters = array();

    $query = 'UPDATE
      `chapter` AS c1
      JOIN
      `chapter` AS c2
      ON (c1.chapter_id = ' . $chapter_id . ' AND c1.text_id = c2.text_id AND c2.chapter_number = ' . $new_number . ')
    SET
      c1.chapter_number = c2.chapter_number,
      c2.chapter_number = c1.chapter_number;';
    $result = mysql_query($query);
  }

  //=================================== Основной код

  loadChapter();

  if ($command == "up")
    $new_number = $old_number - 1;
  else
    $new_number = $old_number + 1;

  changeNumber();
  endScript();
?>