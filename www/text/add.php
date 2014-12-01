<?
  //=================================== Авторизация

  include('../mod_db.php');
  include('../mod_auth.php');

  if (!$user -> isRegistered()) {
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
    $text = <<<TEXT
<select name="access">
                <option value="1" selected>открытый</option>
                <option value="2">по приглашениям</option>
                <option value="3">закрытый</option>
                <option value="4">секретный</option>
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
    $text .= "            </select>\n";
    return $text;
  }

  //=================================== Основной код

  $language = loadLanguage();

  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/text_add.css">
  <script type="text/javascript" src="/scripts/jquery.validate.min.js"></script>
  <script type="text/javascript" src="/scripts/text_add.js"></script>
<?}

  $title = 'Создание перевода';
  include('../header.php');
?>

  <div class="content" style="border: 0px;">
    <h1>Создание перевода</h1>
    <form id="info_form" action="add_save.php" method="post">
      <fieldset>
        <legend>Информация</legend>
        <label>
          <div class="form_input">
            <span class="param_title">Название:</span>
            <input class="wide_input" name="title" type="text" placeholder="Введите название перевода"/>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title">Оригинальное название:</span>
            <input class="wide_input" name="original_title" type="text" placeholder="Введите оригинальное название перевода"/>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title">Открытость:</span>
            <? print generateAccessSelect(); ?>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title">Язык оригинала:</span>
            <? print generateLanguageSelect("original_language", 2); ?>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title">Язык перевода:</span>
            <? print generateLanguageSelect("language", 1); ?>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title">Тип:</span>
            <select id="type" name="type">
                <option value="0" selected>книга</option>
                <option value="1">субтитры</option>
                <option value="2">прочее</option>
            </select>
          </div>
        </label>
        <label>
          <div class="form_input book_field">
            <span class="param_title">ISBN:</span>
            <input name="isbn" type="text" placeholder="Введите ISBN книги"/>
          </div>
        </label>
        <label>
          <div class="form_input book_field">
            <span class="param_title">Автор:</span>
            <input name="author" type="text" placeholder="Введите имя автора"/>
          </div>
        </label>
        <label>
          <div class="form_input book_field">
            <span class="param_title">Автор (ориг.):</span>
            <input name="native_author" type="text" placeholder="Имя автора в оригинале"/>
          </div>
        </label>
        <label>
          <div class="form_input book_field">
            <span class="param_title">Год создания:</span>
            <input name="release_date" type="text" placeholder="Введите год создания"/>
          </div>
        </label>
        <label>
          <div class="form_input subtitle_field" style="display: none;">
            <span class="param_title">Длительность:</span>
            <input name="duration" type="text" placeholder="Длительность субтитров"/>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title">Описание:</span>
            <textarea name="description" rows="5"  placeholder="Введите описание перевода"></textarea>
          </div>
        </label>
        <label>
          <div class="form_input">
            <span class="param_title"></span>
            <input type="submit" value="Создать" />
          </div>
        </label>
      </fieldset>
    </form>
  </div>
  <div class="user">
    <div class="middle_text">Пользователь</div>
  </div>
  <div style="clear:right;"/></div>
  <div class="news">
    <div class="middle_text">Новости</div>
  </div>

<? include('../footer.php');?>