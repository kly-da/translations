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

  function loadLanguage() {
    $query = 'SELECT * FROM `language` ORDER BY `language`';
    $result = mysql_query($query);
    $language = array();
    while ($row = mysql_fetch_assoc($result)) {
      $language[$row["language"]] = $row["title"];
    }
    mysql_free_result($result);
    return $language;
  }

  function generateAccessSelect() {
    global $text_row;
    $access_code = $text_row["access"];
    $selected = array(1 => "", 2 => "", 3 => "", 4 => "",);
    $selected[$text_row["access"]] = " selected";
    $text = <<<TEXT
<select name="original_language">
                <option value="1"$selected[1]>открытый</option>
                <option value="2"$selected[2]>по приглашениям</option>
                <option value="3"$selected[3]>закрытый</option>
                <option value="4"$selected[4]>секретный</option>
              </select>
TEXT;
    return $text;
  }

  function generateLanguageSelect($field_name, $language_code) {
    global $language;
    $text = "<select name=\"$field_name\">\n";
    foreach ($language as $index => $value) {
      if ($index == $language_code)
        $text .= "                <option value=\"$index\" selected>" . $value . "</option>\n";
      else
        $text .= "                <option value=\"$index\">" . $value . "</option>\n";
    }
    $text .= "              </select>\n";
    return $text;
  }

  function generateTextTypeLabel() {
    global $text_row, $text_type_code;
    $text_type_code = $text_row["type"];
    switch ($text_type_code) {
      case 0:
        $text_type = "книга";
        break;
      case 1:
        $text_type = "субтитры";
        break;
      default:
        $text_type = "прочий текст";
        break;
    }
    return $text_type;
  }

  function generateBookInfo() {
    global $text_row;

    $book = array(
      "isbn" => "-",
      "author" => "-",
      "native_author" => "-",
      "release_date" => "-",
    );

    $query = 'SELECT * FROM `book` WHERE `text_id` = ' . $text_row["text_id"];
    $result = mysql_query($query);

    $row = mysql_fetch_assoc($result);
    if ($row) {
      $book["isbn"] = $row["isbn"];
      $book["author"] = $row["author"];
      $book["native_author"] = $row["native_author"];
      $book["release_date"] = $row["release_date"];
    }

    mysql_free_result($result);
    return $book;
  }

  function generateSubtitleInfo() {
    global $text_row;

    $subtitle_duration = "-";

    $query = 'SELECT `duration` FROM `subtitles` WHERE `text_id` = ' . $text_row["text_id"];
    $result = mysql_query($query);

    $row = mysql_fetch_assoc($result);
    if ($row) {
      $subtitle_duration = $row["duration"];
    }

    mysql_free_result($result);
    return $subtitle_duration;
  }

  //=================================== Основной код

  $language = loadLanguage();
  $text_type = generateTextTypeLabel();
  if ($text_type_code == 0) {
    $book = generateBookInfo();
  } else if ($text_type_code == 1) {
    $subtitle_duration = generateSubtitleInfo();
  }

  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/text_edit.css">
  <script type="text/javascript" src="/scripts/jquery.validate.min.js"></script>
  <script type="text/javascript" src="/scripts/text_edit.js"></script>
<?}

  $title = 'Редактирование';
  include('../header.php');
?>

  <div class="content" style="border: 0px;">
    <div>
      <h1>Редактирование настроек<? if ($_GET["status"] == "ok") print " (сохранено)";?></h1>
      <form id="login_form" action="edit_save.php" method="post">
        <input type="hidden" name="operation" value="save" style="display: none;"/>
        <input type="hidden" name="id" value="<? print $text_id; ?>" style="display: none;"/>
        <fieldset>
          <legend>Основные</legend>
          <label>
            <div class="form_input">
              <span class="param_title">Название:</span>
              <input class="wide_input" name="title" type="text" size="40" value="<? print $text_row["title"];?>"/>
            </div>
          </label>
          <label>
            <div class="form_input">
              <span class="param_title">Оригинальное название:</span>
              <input class="wide_input" name="original_title" type="text" size="40" value="<? print $text_row["original_title"];?>"/>
            </div>
          </label>
          <label>
            <div class="form_input">
              <span class="param_title">Язык оригинала:</span>
              <? print generateLanguageSelect("original_language", $text_row["original_language"]); ?>
            </div>
          </label>
          <label>
            <div class="form_input">
              <span class="param_title">Язык перевода:</span>
              <? print generateLanguageSelect("language", $text_row["language"]); ?>
            </div>
          </label>
          <label>
            <div class="form_input">
              <span class="param_title">Тип:</span>
              <span class="text_type"><? print $text_type; ?></span>
            </div>
          </label>
<?
  if ($text_type_code == 0) {
?>
          <label>
            <div class="form_input">
              <span class="param_title">ISBN:</span>
              <input name="isbn" type="text" size="40" value="<? print $book["isbn"]; ?>"/>
            </div>
          </label>
          <label>
            <div class="form_input">
              <span class="param_title">Автор:</span>
              <input name="author" type="text" size="40" value="<? print $book["author"]; ?>"/>
            </div>
          </label>
          <label>
            <div class="form_input">
              <span class="param_title">Автор (ориг.):</span>
              <input name="native_author" type="text" size="40" value="<? print $book["native_author"]; ?>"/>
            </div>
          </label>
          <label>
            <div class="form_input">
              <span class="param_title">Год создания:</span>
              <input name="release_date" type="text" size="40" value="<? print $book["release_date"]; ?>"/>
            </div>
          </label>
<?
  } else if ($text_type_code == 1) {
?>
          <label>
            <div class="form_input">
              <span class="param_title">Длительность:</span>
              <input name="duration" type="text" size="40" value="<? print $subtitle_duration; ?>"/>
            </div>
          </label>
<?
  }
?>
          <label>
            <div class="form_input">
              <span class="param_title">Описание:</span>
              <textarea name="description" rows="5"><? print $text_row["description"]; ?></textarea>
            </div>
          </label>
          <label>
            <div class="form_input">
              <span class="param_title"></span>
              <input type="submit" value="Сохранить" />
            </div>
          </label>
        </fieldset>
      </form>
      <fieldset>
        <legend>Настройка доступа</legend>
        <form id="accessform" action="edit_save.php" method="post">
          <label>
            <div class="form_input">
              <span class="param_title">Открытость</span>
              <? print generateAccessSelect(); ?> <input type="submit" style="margin-left: 50px;" value="Сохранить" />
            </div>
          </label>
        </form>
      </fieldset>
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