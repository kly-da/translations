<?php
  include('../../mod_db.php');
  include('../../mod_auth.php');

  function clean($value = "") {
      $value = trim($value);
      $value = stripslashes($value);
      $value = strip_tags($value);
      $value = htmlspecialchars($value);

      return $value;
  }
  function check_length($value = "", $min, $max) {
      $result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
      return !$result;
  }

  $title = "Сохранение новости";
  include('../../header.php');
?>
  <div class="content" style="border: 0px;">
<?
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tetle = $_POST['tetle'];
    $message = $_POST['message'];
    $author = $_POST['author'];
    $tetle = clean($tetle);
    $message = clean($message);
    $author  = clean($author);
    if(!empty($tetle) && !empty($message) && !empty($author)) {
        if(check_length($tetle, 2, 50)  && check_length($message, 10, 10000) && check_length($author, 1, 25)) {
        echo "Новость опубликована<br>";
        echo $tetle."<br>".$message."<br>".$author."<br>";
        } else {
            echo "Введенные данные некорректные";
        }
    } else {
        echo "Заполните пустые поля";
    }
  } else {
    header("Location: ../index.php");
  }
?>
  </div>
  <div class="user">
    <div class="middle_text">Пользователь</div>
  </div>
  <div style="clear:right;"/></div>
  <div class="news">
    <div class="middle_text">Новости</div>
  </div>

<? include('../../footer.php');?>