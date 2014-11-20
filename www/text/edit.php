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
<select name="access">
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

  function generateUserList() {
    global $translators, $moderators, $administrators;
    global $text_row;
    $translators = array();
    $moderators = array();
    $administrators = array();

    $query = 'SELECT `user_id`, `name`, `role` FROM `text_roles` INNER JOIN `user` USING(`user_id`) WHERE `text_id` = ' . $text_row["text_id"] . ' ORDER BY `name`';
    $result = mysql_query($query);

    while ($row = mysql_fetch_assoc($result)) {
      $new_user = array("user_id" => $row["user_id"], "name" => $row["name"]);
      switch ($row["role"]) {
        case 0:
          array_push($translators, $new_user);
          break;
        case 1:
          array_push($moderators, $new_user);
          break;
        case 2:
          array_push($administrators, $new_user);
          break;
      }
    }
    mysql_free_result($result);
  }

  //=================================== Основной код

  $language = loadLanguage();
  $text_type = generateTextTypeLabel();
  if ($text_type_code == 0) {
    $book = generateBookInfo();
  } else if ($text_type_code == 1) {
    $subtitle_duration = generateSubtitleInfo();
  }
  generateUserList();

  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/text_edit.css">
  <script type="text/javascript" src="/scripts/jquery.validate.min.js"></script>
  <script type="text/javascript" src="/scripts/jquery.textchange.min.js"></script>
  <script type="text/javascript" src="/scripts/text_edit.js"></script>
<?}

  $title = 'Редактирование';
  include('../header.php');
?>

  <div class="content" style="border: 0px;">
    <h1>Редактирование настроек<? if ($_GET["status"] == "ok") print " (сохранено)";?></h1>
    <form id="login_form" action="edit_save.php" method="post">
      <input type="hidden" name="operation" value="save" />
      <input type="hidden" name="id" value="<? print $text_id; ?>" />
      <fieldset>
        <legend>Основные</legend>
        <label>
          <div class="form_input">
            <span class="param_title">Название:</span>
            <input class="wide_input" name="title" type="text" value="<? print $text_row["title"];?>"/>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title">Оригинальное название:</span>
            <input class="wide_input" name="original_title" type="text" value="<? print $text_row["original_title"];?>"/>
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
            <input name="isbn" type="text" value="<? print $book["isbn"]; ?>"/>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title">Автор:</span>
            <input name="author" type="text" value="<? print $book["author"]; ?>"/>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title">Автор (ориг.):</span>
            <input name="native_author" type="text" value="<? print $book["native_author"]; ?>"/>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title">Год создания:</span>
            <input name="release_date" type="text" value="<? print $book["release_date"]; ?>"/>
          </div>
        </label>
<?
  } else if ($text_type_code == 1) {
?>
        <label>
          <div class="form_input">
            <span class="param_title">Длительность:</span>
            <input name="duration" type="text" value="<? print $subtitle_duration; ?>"/>
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
    <form id="access_form" action="edit_save.php" method="post">
      <input type="hidden" name="operation" value="save_access" />
      <input type="hidden" name="id" value="<? print $text_id; ?>" />
      <fieldset>
        <legend>Настройка доступа</legend>
        <label>
          <div class="form_input">
            <span class="param_title">Открытость</span>
            <? print generateAccessSelect(); ?> <input type="submit" style="margin-left: 50px;" value="Сохранить" />
          </div>
        </label>
      </fieldset>
    </form>
    <ul class="search_list"></ul>
    <fieldset>
      <legend>Пользователи</legend>
<?
  if ($text_row["access"] > 1) {
?>
      <form id="translator_form" class="user_form">
        <label>
          <div class="form_input">
            <span class="param_title">Переводчики:</span>
            <input id="translator_search" class="search_input" type="text" size="40"/>
            <input id="translator_new_button" type="button" class="button add_function" value="Добавить"/>
            <span class="ajax_status"></span>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title"></span>
            <select id="translators" size="8">
<?
    foreach ($translators as $user) {
?>
              <option value="<? print $user["user_id"]; ?>"><? print $user["name"]; ?></option>
<?
    }
?>
            </select>
            <input id="translator_delete_button" type="button" class="button delete_function" value="Удалить"/>
            <span class="ajax_status"></span>
          </div>
        </label>
      </form>
<?
  }
?>
      <form id="moderator_form" class="user_form">
        <label>
          <div class="form_input">
            <span class="param_title">Модераторы:</span>
            <input id="moderator_search" class="search_input" type="text" size="40"/>
            <input id="moderator_new_button" type="button" class="button add_function" value="Добавить"/>
            <span class="ajax_status"></span>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title"></span>
            <select id="moderators" size="5">
<?
    foreach ($moderators as $user) {
?>
              <option value="<? print $user["user_id"]; ?>"><? print $user["name"]; ?></option>
<?
    }
?>
            </select>
            <input id="moderator_delete_button" type="button" class="button delete_function" value="Удалить"/>
            <span class="ajax_status"></span>
          </div>
        </label>
      </form>
      <form id="administrator_form" class="user_form">
        <label>
          <div class="form_input">
            <span class="param_title">Администраторы:</span>
            <input id="administrator_search" class="search_input" type="text" size="40"/>
            <input id="administrator_new_button" type="button" class="button add_function" value="Добавить"/>
            <span class="ajax_status"></span>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title"></span>
            <select id="administrators" size="5">
<?
    foreach ($administrators as $user) {
?>
              <option value="<? print $user["user_id"]; ?>"><? print $user["name"]; ?></option>
<?
    }
?>
            </select>
            <input id="administrator_delete_button" type="button" class="button delete_function" value="Удалить"/>
            <span class="ajax_status"></span>
          </div>
        </label>
      </form>
    </fieldset>
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
