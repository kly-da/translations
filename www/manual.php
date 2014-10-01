<html>
<head>
<title>Мануал</title>
</head>

<body>
<h1>
<? print "Заголовок!" ?>
</h1>

===============================================================================================================<br>
<?
  $a = 3;
  $b = 6;
  $c = 4;

  print "Функция = " . formula($c) . "<br>";

  // a * 10 + x
  function formula($x) {
    // $a - глобальная
    global $a;
    // $b - локальная
    $b = 10;

    return $a * $b + $x;
  }
?>

<? print "A = $a"; ?> ($a всё ещё определена)<br>
Формула = <? print formula($c); ?> (формула тоже)<br>

===============================================================================================================
<h2>Цикл - вывод таблицы</h2>

<?
  print "<table border=\"1\" style='padding: 1px;'>";
  for ($i = 1; $i <= 5; $i++) {
    print "<tr>";
    for ($j = 1; $j <= 10; $j++) {
      print "<td>Ячейка " . $i . " - " . $j . "</td>\n";
    }
    // для читабельности html - перенос кода но новую строку
    print "\n";
  }
  print "</table>";
?>
<br>
===============================================================================================================
<h2>Многострочный вывод</h2>
<?
  $text = "Переменная \$text";
  print <<< TEXT
Многострочный вывод текста<br>
12345<br>
$text<br><br>
TEXT;

  print <<< ANY_WORD
Для старта ввода используется любое слово<br>
<font color="red">Красный цвет</font><br>
ANY_WORD;
?>

===============================================================================================================
<h2>Форма, GET-запрос</h2>
<form method="GET" action="manual_2.php">
  Имя:<br>
  <input type="text" name="first_name"><br>
  Фамилия:<br>
  <input type="text" name="last_name"><br><br>
  <input type="submit" value="Кнопка">
</form>
===============================================================================================================
<h2>Форма, POST-запрос</h2>
<form method="POST" action="manual_2.php">
  Имя:<br>
  <input type="text" name="first_name"><br>
  E-mail:<br>
  <input type="text" name="e_mail"><br>
  Комментарий:<br>
  <textarea name="comment" rows="10" cols="30">Комментарий пишется здесь</textarea>
  <br><br>
  <input type="submit" value="Ещё кнопка">
</form>

</body>
</html>