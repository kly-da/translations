<html>
<head>
<title>Мануал 2</title>
<style>
 a:link {
  color: #0000d0;
 }
 a:visited {
  color: #0000d0;
 }
</style>
</head>

<body>
<h1>
Запрос обработан!
</h1>

Тип запроса: <?
  $is_post = count($_POST) > 0;

  if (!$is_post && count($_GET) == 0) {
    print "Параметров нет. Конец.";
    exit("\n</body>\n</html>");
  }

  if ($is_post) {
    print "POST (в адресной строке нет параметров)<br>";
    print "Скопировать адрес формы, вставить и перейти = вызов формы без параметров (как <a href=\"manual_2.php\">здесь</a>)";
  }
  else
    print "GET (посмотрите параметры в адресной строке)";
?><br><br>

<?
  if ($is_post) {
    print "<b>Имя:</b> " . $_POST["first_name"] . "<br>";
    print "<b>E-mail:</b> " . $_POST["e_mail"] . "<br>";
    // Замена символов переноса на <br>
    $comment = str_replace("\n", "<br>", $_POST["comment"]);
    print "<b>Коммент:</b> " . $comment . "<br>";

  } else {
    $last_name = $_GET["last_name"];
    $first_name = $_GET["first_name"];
    print <<<TEXT
<b>Фамилия:</b> $last_name<br>
<b>Имя:</b> $first_name<br>
TEXT;
  }
?><br>

Список параметров целиком:<br><br>
<?
  $array = $is_post? $_POST: $_GET;
  foreach($array as $key => $value) {
    print "<b>" . $key . "</b> = " . $value . "<br>";
  }
?><br><br>

<form method="POST" action="manual_3.php">
  <input type="hidden" name="command" value="without_pause">
  <input type="submit" value="По щелчку выполняется некий скрипт и переносит на исходную страницу">
</form>

<form method="POST" action="manual_3.php">
  <input type="hidden" name="command" value="with_pause">
  <input type="submit" value="А здесь скрипт доложил о своём выполнении, после чего перенаправился">
</form>

</body>
</html>