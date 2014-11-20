<?
  include('mod_db.php');

  if (isset($_GET["id"])){
    $uid = intval($_GET["id"]);
    if ($uid <= 0) {
      $uid = 1;
    }
    $query = 'SELECT `hash` FROM `user` WHERE `user_id` = ' . $uid;
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    if (!$row) {
      print "FAIL: User Not Found";
      die();
    }

    setcookie('uid', $uid, time() + 366 * 24 * 60 * 60);
    setcookie('hash', $row["hash"], time() + 366 * 24 * 60 * 60);
    header('Location: login_as.php');
    die();
  }

  include('mod_auth.php');

  $title = "Логин";
  include('header.php');
?>
  <div class="content" style="border: 0px;">
    <h1>Быстрое залогинивание</h1>
    <?
  print "Текущий пользователь: ";
  if ($user -> isRegistered()) {
    print $user -> name;
  } else {
    print "–";
  }
  print "<br><br>\n";
?>
    <form action="login_as.php" method="get">
      Зайти как:<br>
      <select name="id">
<?
  $query = "SELECT `user_id`, CONCAT(`name`, ' (',
  CASE WHEN `group` = 0 THEN 'переводчик'
       WHEN `group` = 1 THEN 'модератор'
       ELSE 'администратор' END,
  ')') AS `name`
FROM `user`
ORDER BY `group` DESC, `name`";
  $result = mysql_query($query);
  while ($row = mysql_fetch_assoc($result)) {
    print '        <option value="' . $row["user_id"] . '">' . $row["name"] . "</option>\n";
  }
?>
      </select>
      <input type="submit" value="Логин" />
    </form>
    <a href="/">На главную...</a>
  </div>
  <div class="user">
    <div class="middle_text">Пользователь</div>
  </div>
  <div style="clear:right;"/></div>
  <div class="news">
    <div class="middle_text">Новости</div>
  </div>

<? include('footer.php');?>