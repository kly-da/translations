<?
  //=================================== Авторизация

  include('../mod_db.php');
  include('../mod_auth.php');

  if (!$user -> isRegistered()) {
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
    if (strlen($duration) == 0)
      return "NULL";
    if (preg_match('/^([0-9]{1,3}:)?[0-5]?[0-9]:[0-5][0-9]$/', $duration, $matches)) {
      if (strlen($matches[1]) == 0)
        return "\"00:" . $duration . "\"";
      else
        return "\"" . $duration . "\"";
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
      return;
    }
  }

  function checkLimit() {
    global $user;
    if ($user -> isAdministrator())
      return true;

    if ($user -> isModerator())
      $limit_count = 10;
    else
      $limit_count = 3;

    $uid = $user -> uid;

    $query = 'SELECT COUNT(*) < $limit_count AS is_exceeded
      FROM `text`
      WHERE `creator` = $uid AND CAST(`date_created` AS date) >= CURDATE() - INTERVAL 2 DAY';
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    if (!row) {
      array_push($error, "Ошибка базы данных. Попробуйте создать перевод позднее.");
      return false;
    }

    $ret = $row["is_exceeded"];
    mysql_free_result($result);

    if ($ret)
      return true;
    else {
      array_push($error, "Превышен лимит создания переводов за последние три дня.");
      return false;
    }
  }

  function createText() {
    global $error, $user;
    $title = checkString($_POST["title"], true, 4, 80, "название перевода");
    $original_title = checkString($_POST["original_title"], true, 4, 80, "оригинальное название перевода");
    $language = intval($_POST["language"]);
    $original_language = intval($_POST["original_language"]);
    $text_type_code = intval($_POST["type"]);
    $access = intval($_POST["access"]);
    $description = mysql_real_escape_string(htmlspecialchars($_POST["description"]));
    $uid = $user -> uid;

    if ($text_type_code == 0) {
      $isbn = checkString($_POST["isbn"], true, 0, 20, "ISBN");
      $author = checkString($_POST["author"], true, 0, 100, "имя автора");
      $native_author = checkString($_POST["native_author"], true, 0, 100, "оригинальное имя автора");
      $release_date = intval($_POST["release_date"]);
    } else if ($text_type_code == 1) {
      $duration = setDuration($_POST["duration"]);
    }

    if ($text_type_code < 0 || $text_type_code > 2 || $access < 1 || $access > 4) {
      $error = array("Ошибка отправки формы");
    }

    if (count($error) > 0)
      return;

    $query = "INSERT INTO `text`
      (`type`,
      `access`,
      `creator`,
      `title`,
      `original_title`,
      `language`,
      `original_language`,
      `description`)
    VALUES
      ($text_type_code,
      $access,
      $uid,
      \"$title\",
      \"$original_title\",
      $language,
      $original_language,
      \"$description\")";
    executeQuery($query);
    if (count($error) > 0)
      return;

    $text_id = mysql_insert_id();

    if ($text_type_code == 0) {
      $query = "INSERT INTO `book`
        (`text_id`,
        `isbn`,
        `author`,
        `native_author`,
        `release_date`)
      VALUES
        ($text_id,
        \"$isbn\",
        \"$author\",
        \"$native_author\",
        $release_date)";
      executeQuery($query);
    } else if ($text_type_code == 1) {
      $query = "INSERT INTO `subtitles`
        (`text_id`,
        `duration`)
      VALUES
        ($text_id,
        $duration)";
      executeQuery($query);
    }
    if (count($error) == 0) {
      header('Location: view.php?id=' . $text_id);
      die();
    }
  }

  //=================================== Основной код

  $error = array();
  if (checkLimit()) {
    createText();
  }

  $title = 'Ошибка создания';
  include('../header.php');
?>

  <div class="content" style="border: 0px;">
    <div>
      <h1>Ошибка создания</h1>
      <div style="line-height: 1.5">
<?
  foreach ($error as $value) {
    print "        " . $value . "<br>\n";
  }
?>
        <br>
        <a href="javascript:history.back()">Вернуться назад...</a>
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