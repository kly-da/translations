<?PHP
session_start();
error_reporting("E_ALL");
mysql_connect("localhost", "root", "") or die (mysql_error ());
mysql_select_db("translations") or die(mysql_error());
$nick=$_SESSION["nick"];
// SQL-запрос
$sqlQuery = "SELECT * FROM messages WHERE `to` = '$nick'";

// Выполнить запрос (набор данных $rs содержит результат)
$rs = mysql_query($sqlQuery);

// Цикл по recordset $rs
// Каждый ряд становится массивом ($row) с помощью функции mysql_fetch_array
while($row = mysql_fetch_array($rs)) {

   // Записать значение столбца FirstName (который является теперь массивом $row)
  echo "<font color=\"red\">".$row['from']."</font><br /><br /> " . $row['message'] . "<br /> <hr>";

  }
// Закрыть соединение с БД
mysql_close();
?>
<META http-equiv='refresh' content='5,chattext.php#chatanchor'> 
<a name="chatanchor" id="chatanchor"></a>
<body onLoad="scroll(0,100%)"></body>