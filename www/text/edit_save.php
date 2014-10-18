<?
  //=================================== Обработка параметров
  $text_id = intval($_POST["id"]);
  if ($text_id <= 0) {
    header('Location: /');
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

  function checkString($text, $required, $min_length, $max_length, $error_message) {
    global $error;
    $text = mysql_real_escape_string(htmlspecialchars($text, ENT_COMPAT));
    $len = strlen($text);
    if ($len == 0)
      if ($required)
        array_push($error, "Не введено " . $error_message);
    else if ($len < $min_length)
      array_push($error, "Слишком короткое значение поля (" . $error_message . ")");
    else if ($len > $max_length)
      array_push($error, "Слишком длинное значение поля (" . $error_message . ")");
    return $text;
  }

  function setDuration($duration) {
    global $error;
    if (preg_match('/^([0-9]{1,3}:)?[0-5]?[0-9]:[0-5][0-9]$/', $duration, $matches)) {
      if (strlen($matches[1]) == 0)
        return "00:" . $duration;
      else
        return $duration;
    } else {
      array_push($error, "Неправильное значение продолжительности");
      return null;
    }
  }

  function executeQuery($query) {
    global $error;
    $result = mysql_query($query);
    if (!$result) {
      echo mysql_errno() . ": " . mysql_error() . "\n";
      array_push($error, "Во время сохранения произошла ошибка базы данных");
      die();
    }
  }

  function saveText() {
    global $error, $text_row, $text_id;
    $title = checkString($_POST["title"], true, 4, 80, "название перевода");
    $original_title = checkString($_POST["original_title"], true, 4, 80, "оригинальное название перевода");
    $language = intval($_POST["language"]);
    $original_language = intval($_POST["original_language"]);
    $description = mysql_real_escape_string(htmlspecialchars($_POST["description"]));

    $text_type_code = $text_row["type"];

    if ($text_type_code == 0) {
      $isbn = checkString($_POST["isbn"], true, 0, 20, "ISBN");
      $author = checkString($_POST["author"], true, 0, 100, "имя автора");
      $native_author = checkString($_POST["native_author"], true, 0, 100, "оригинальное имя автора");
      $release_date = intval($_POST["release_date"]);
    } else if ($text_type_code == 1) {
      $duration = setDuration($_POST["duration"]);
    }

    if (count($error) > 0)
      return;

    $query = "UPDATE `text`
    SET
      `title` = \"$title\",
      `original_title` = \"$original_title\",
      `language` = $language,
      `original_language` = $original_language,
      `description` = \"$description\"
    WHERE
      `text_id` = $text_id";
    executeQuery($query);
    if (count($error) > 0)
      return;

    if ($text_type_code == 0) {
      $query = "UPDATE `book`
      SET
        `isbn` = \"$isbn\",
        `author` = \"$author\",
        `native_author` = \"$native_author\",
        `release_date` = $release_date
      WHERE
        `text_id` = $text_id";
      executeQuery($query);
    } else if ($text_type_code == 1) {
      $query = "UPDATE `subtitles` SET `duration` = \"$duration\" WHERE `text_id` = $text_id";
      executeQuery($query);
    }
    if (count($error) == 0) {
      header('Location: edit.php?id=' . $text_id . '&status=ok');
      die();
    }
  }

  //=================================== Основной код

  $error = array();

  switch ($_POST["operation"]) {
    case "save":
      saveText();
      break;
  }

  $title = 'Ошибка сохранения';
  include('../header.php');
?>

  <div class="content" style="border: 0px;">
    <div>
      <h1>Ошибка сохранения</h1>
      <div style="line-height: 1.5">
<?
  foreach ($error as $value) {
    print "        " . $value . "<br>\n";
  }
?>
        <br>
        <a href="edit.php?id=<? print $text_id; ?>">Вернуться назад...</a>
      </div>
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