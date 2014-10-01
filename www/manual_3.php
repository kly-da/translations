<?
  //считываем параметр, переданный через POST
  $command = $_POST["command"];
  
  if (!isset($command)) {
    header('Location: manual.php');
    die();
  }
  
  $a = 5;
  $b = 3;
  $c = $a + $b;
  
  if ($command == "without_pause") {
    //Здесь выполнился скрипт, логика программы, но этого никто не увидел
    //Перенаправление на главную
    header('Location: manual.php');
  } else {
    // Здесь перенаправление после паузы
    header('Refresh: 3; URL=manual.php');
    print "Перенаправление - через 3 секунды. Результат операции - $c.";
  }  
?>