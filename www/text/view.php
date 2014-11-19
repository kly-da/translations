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
  
  mysql_query("SET NAMES utf8");

  $query = 'SELECT `text`.*, `user`.`name` AS `creator_name` FROM `text` JOIN `user` ON(`creator` = `user_id`) WHERE `text_id` = ' . $text_id;
  $result = mysql_query($query);
  $text_row = mysql_fetch_assoc($result);
  if (!$text_row) {
    header('Location: /error/text_not_found.php');
    die();
  }

  $user -> loadTextOut($text_row);

  if ($text_row["is_deleted"] && !$user -> isAdministrator()) {
    header('Location: /error/text_not_found.php');
    die();
  }

  //=================================== Описание функций

  function generateAccessLabel() {
    global $text_row;
    if ($text_row["is_deleted"]) {
      $access = array("deleted", "удалено");
    } else {
      switch ($text_row["access"]) {
        case 0:
          $access = array("publicate", "опубликовано");
          break;
        case 1:
          $access = array("open", "открытый");
          break;
        case 2:
          $access = array("invite", "по приглашениям");
          break;
        case 3:
          $access = array("closed", "закрытый");
          break;
        case 4:
          $access = array("secret", "секретный");
          break;
      }
    }
    return $access;
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

  function generateRoleLabel() {
    global $user;
    if ($user -> isTextCreator()) {
      return "Создатель перевода";
    } else if ($user -> isTextTranslator()) {
      return "Переводчик";
    } else if ($user -> isTextModerator()) {
      return "Модератор перевода";
    } else if ($user -> isTextAdministrator()) {
      return "Администратор перевода";
    } else {
      return "-";
    }
  }

  function generatePublishMenuItem() {
    global $text_id, $user;
    if ($user -> isManageUser()) {
      if($text_type_code >= 0) {
        $publish_menu_item = array(
          "href" => "process.php?type=publish&id=$text_id",
          "text" => "Опубликовать",
        );
      } else {
        $publish_menu_item = array(
          "href" => "process.php?type=unpublish&id=$text_id",
          "text" => "Снять с публикации",
        );
      }
    } elseif ($text_type_code >= 0) {
        $publish_menu_item = array(
          "href" => "/request.php?type=publish&id=$text_id",
          "text" => "Запрос на публикацию",
        );
    } else {
        $publish_menu_item = array(
          "href" => "/request.php?type=unpublish&id=$text_id",
          "text" => "Запрос на отмену публикации",
        );
    }
    return $publish_menu_item;
  }

  function generateDeleteMenuItem() {
    global $text_id, $text_row, $user;
    if ($text_row["is_deleted"] || $text_row["access"] > 0) {
        $delete_menu_item = array(
          "href" => "process.php?type=delete&id=$text_id",
          "text" => "Удалить",
        );
    } else {
        $delete_menu_item = array(
          "href" => "/request.php?type=delete&id=$text_id",
          "text" => "Запрос на удаление",
        );
    }
    return $delete_menu_item;
  }

  function getLanguage($language_id) {
    $query = 'SELECT `title` FROM `language` WHERE `language` = ' . $language_id;
    $result = mysql_query($query);

    $row = mysql_fetch_assoc($result);
    if (!$row) {
      $title = "нет";
    } else {
      $title = $row["title"];
    }
    mysql_free_result($result);

    return $title;
  }

  //=================================== Основной код

  $formatter = new Formatter();

  $access = generateAccessLabel();
  $text_type = generateTextTypeLabel();
  if ($text_type_code == 0) {
    $book = generateBookInfo();
  } else if ($text_type_code == 1) {
    $subtitle_duration = generateSubtitleInfo();
  }

  $description = str_replace("\n", "</p><p>", $text_row["description"]);

  if (!$user -> isTextNoRole()) {
    $role = generateRoleLabel();
  }

  if ($user -> isManageUser() || $user -> isTextAdministratorOrCreator()) {
    $publish_menu_item = generatePublishMenuItem();
    $delete_menu_item = generateDeleteMenuItem();
  }

  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/text_main.css">
  <script type="text/javascript" src="/scripts/text_view.js"></script>
<?}

  $title = $text_row["title"];
  include('../header.php');
?>

  <div class="content" style="border: 0px;">
    <div>
      <div id="text_status" class="<? print $access[0];?>"><? print $access[1];?></div>
      <div id="title_header"><? print $text_row["title"];?></div>
      <div id="original_title"><? print $text_row["original_title"];?></div>
      <div style="clear: both;"></div>
    </div>
    <div>
      <table id="information">
        <tr>
          <td class="info_parameter">Перевод</td>
          <td class="info_value"><? print getLanguage($text_row["original_language"]); ?> &rarr; <? print getLanguage($text_row["language"]); ?></td>
        </tr>
<?
  if ($text_type_code < 2) {
?>
        <tr>
          <td class="info_parameter">Тип</td>
          <td class="info_value"><? print $text_type; ?></td>
        </tr>
<?
  }
  if ($text_type_code == 0) {
?>
        <tr>
          <td class="info_parameter">Автор</td>
          <td class="info_value"><? print $book["author"];?> <span class="light_gray">(<? print $book["native_author"];?>)</span></td>
        </tr>
        <tr>
          <td class="info_parameter">ISBN</td>
          <td class="info_value"><? print $book["isbn"];?></td>
        </tr>
        <tr>
          <td class="info_parameter">Год выпуска</td>
          <td class="info_value"><? print $book["release_date"];?></td>
        </tr>
<?
  } else if ($text_type_code == 1) {
?>
        <tr>
          <td class="info_parameter">Длительность</td>
          <td class="info_value"><? print $subtitle_duration; ?></td>
        </tr>
<?
  }
?>
        <tr>
          <td class="info_parameter">Описание</td>
          <td class="info_value">
            <p>
              <? print $description . "\n";?>
            </p>
          </td>
        </tr>
        <tr>
          <td class="info_parameter">Создатель</td>
          <td class="info_value"><a href="/user/view.php?id=<? print $text_row["creator"]?>"><? print $text_row["creator_name"]?></a> <span class="light_gray">(создано – <? print $formatter -> toStringChangedDate($text_row["date_created"]); ?>)</span></td>
        </tr>
<?
  if (!$user -> isTextNoRole()) {
?>
        <tr>
          <td class="info_parameter">Ваша роль</td>
          <td class="info_value"><? print $role;?></td>
        </tr>
<?
  }
?>
      </table>
      <a id="text_complaint" class="complaint" href="111">Пожаловаться на перевод</a>
      <ul class="text_menu">
<?
  if ($user -> isManageUser() || $user -> isTextAdministratorOrCreator()) {
?>
        <li class="text_menu_component"><img src="/images/icon-menu-small.png" class="image">Управление
          <ul>
<?  if (!$text_row["is_deleted"]) { ?>
            <a href="add_chapter.php?id=<? print $text_id;?>"><li>Добавить главу</li></a>
            <a href="edit.php?id=<? print $text_id;?>"><li>Редактировать настройки</li></a>
            <a href="<? print $publish_menu_item["href"];?>"><li><? print $publish_menu_item["text"];?></li></a>
<?  }?>
            <a href="<? print $delete_menu_item["href"];?>"><li><? print $delete_menu_item["text"];?></li></a>
          </ul>
        </li>
<?
  }
?>
      </ul>
    </div>
    <div>
      <div class="chapter_header">Главы</div>
      <ul class="chapter_menu">
        <a href="add_chapter.php?cid=1"><li>Перезагрузить главу</li></a>
        <a href="process.php?type=set_edit&cid=1"><li>Установить проверку</li></a>
        <a href="process.php?type=end_edit&cid=1"><li>Завершить проверку</li></a>
        <a href="process.php?type=confirm_change&cid=1"><li>Подтвердить изменения</li></a>
        <a href="delete.php?cid=1"><li>Удалить</li></a>
      </ul>
      <table id="chapter_table">
        <tr>
          <th class="title">Название</th>
          <th class="left">Переведено</th>
          <th>Изменено</th>
          <th>Статус</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>
        <tr>
          <td class="title"><a href="343">Вступление</a></td>
          <td class="left">350/350 <span class="light_gray">(100%)</span></td>
          <td>20 сен</td>
          <td>завершено</td>
          <td><a href="34">скачать</a></td>
          <td><a class="show_menu" cid="1">меню</a></td>
        </tr>
        <tr>
          <td class="title"><a href="343">Завязка</a></td>
          <td class="left">35000/35000 <span class="light_gray">(100%)</span></td>
          <td>25 сен</td>
          <td>завершено</td>
          <td><a href="34">скачать</a><br><a href="34">черновик</a></td>
          <td><a class="show_menu" cid="2">меню</a></td>
        </tr>
        <tr>
          <td class="title"><a href="343">Развитие</a></td>
          <td class="left">1300/1300 <span class="light_gray">(100%)</span></td>
          <td>сегодня</td>
          <td>проверка</td>
          <td></td>
          <td><a class="show_menu" cid="3">меню</a></td>
        </tr>
        <tr>
          <td class="title"><a href="343">Кульминация</a></td>
          <td class="left">800/1921 <span class="light_gray">(42%)</span></td>
          <td>сегодня</td>
          <td>в процессе</td>
          <td></td>
          <td><a class="show_menu" cid="4">меню</a></td>
        </tr>
        <tr>
          <td class="title"><a href="343">Развязка</a></td>
          <td class="left">0/821 <span class="light_gray">(0%)</span></td>
          <td>нет</td>
          <td>в процессе</td>
          <td></td>
          <td><a class="show_menu" cid="5">меню</a></td>
        </tr>
      </table>
    </div>
    <div>
      <a href="11">Скачать всё</a>
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
