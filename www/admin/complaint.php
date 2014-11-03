<?
  //=================================== Авторизация

  include('../mod_db.php');
  include('../mod_auth.php');
  include('../code/text_format.php');

  if (!$user -> isManageUser()) {
    header('Location: /error/no_access.php');
    die();
  }

  //=================================== Описание функций

  function loadComplaints() {
    global $user;
    if ($user -> isAdministrator())
      $user_filter = "NULL";
    else
      $user_filter = $user -> uid;
    $query = "SELECT
      CL.*, U1.`name` AS `complainter`, U2.`name` AS `violator`
    FROM
      (SELECT
        C.`complaint_id`,
        C.`date`,
        C.`type`,
        C.`object_id`,
        C.`user_id`,
        CASE WHEN C.`type` = 1 THEN T.`title` END AS `content`,
        CASE WHEN C.`type` = 1 THEN T.`creator` END AS `violator_id`
      FROM
        `complaint` C
        LEFT JOIN
        `text` T
        ON (C.`type` = 1 AND C.`object_id` = T.`text_id`)) CL
      LEFT JOIN
      `user` U1
      ON(CL.`user_id` = U1.`user_id`)
      LEFT JOIN
      `user` U2
      ON(CL.`violator_id` = U2.`user_id`)
    WHERE
      $user_filter IS NULL OR CL.`violator_id` != $user_filter
    ORDER BY
      `date`";
    return mysql_query($query);
  }

  function generateContent($row) {
    switch ($row["type"]) {
      case 0:
        break;
      case 1:
        return "<a href=\"/text/view.php?id=" . $row["object_id"] . "\">" . $row["content"] . "</a>";
      case 2:
        break;
      case 3:
        break;
      default:
        return 'нет';
    }
  }

  function deleteComplaint($ignore_id) {
    global $delete_text;
    $query = "DELETE FROM `complaint` WHERE `complaint_id` = $ignore_id";
    $result = mysql_query($query);
  }

  function deleteRequest($text_id) {
    global $delete_text;
    $query = "DELETE FROM `request` WHERE `complaint_id` = $text_id";
    $result = mysql_query($query);
  }

  //=================================== Основной код

  $formatter = new Formatter();

  if (isset($_GET["ignore_id"]))
    deleteComplaint(intval($_GET["ignore_id"]));
  if (isset($_GET["delete_id"]))
    deleteRequest(intval($_GET["delete_id"]));

  $complaint_result = loadComplaints();
  $complaint_type = array("сообщение", "текст", "отрывок", "комментарий");

  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/admin_complaint.css">
<?}

  $title = "Список жалоб и запросов";
  include('../header.php');
?>
  <h1>Жалобы пользователей</h1>
  <table id="complaint_table">
    <tr>
      <th>№</th>
      <th>Дата жалобы</th>
      <th class="table_user">От</th>
      <th>Тип</th>
      <th class="table_user">Пользователь</th>
      <th class="table_fill">Содержание</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
    </tr>
<?
  $complaint_counter = 1;
  while ($row = mysql_fetch_assoc($complaint_result)) {
?>
    <tr>
      <td><? print $complaint_counter++;?></td>
      <td><? print $formatter -> toStringChangedDate($row["date"]);?></td>
      <td class="table_user"><a href="/user/view.php?id=<? print $row["user_id"]?>"><? print $row["complainter"];?></a></td>
      <td><? print $complaint_type[$row["type"]];?></td>
      <td class="table_user"><a href="/user/view.php?id=<? print $row["violator_id"]?>"><? print $row["violator"];?></a></td>
      <td class="table_fill"><?print generateContent($row);?></td>
      <td><a href="/user/add_violation.php?id=<? print $row["violator_id"]?>">предупредить</a></td>
      <td><a href="complaint.php?ignore_id=<? print $row["complaint_id"]?>">игнорировать</a></td>
    </tr>
<?
  }
  if ($complaint_counter == 1) {
?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="table_user">&nbsp;</td>
      <td>&nbsp;</td>
      <td class="table_user">&nbsp;</a></td>
      <td class="table_fill">жалоб нет</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
<?
  }
?>
  </table>
  <br><br>
  <h1>Запросы пользователей</h1>
  <table id="request_table">
    <tr>
      <th>№</th>
      <th>Тип</th>
      <th class="table_title">Текст</th>
      <th>&nbsp;</th>
    </tr>
    <tr>
      <td>1</td>
      <td>публиковать</td>
      <td class="table_title"><a href="/user/view.php?id=1">Белое солнце пустыни</a></td>
      <td><a href="complaint.php?delete_id=1">удалить из списка</a></td>
    </tr>
  </table>
  <br>
  <a href="/admin/">Назад на панель администратора...</a>
<? include('../footer.php');?>