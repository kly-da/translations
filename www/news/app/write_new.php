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
    $tetle = clean($tetle);
    $message = clean($message);
    $author  = 1;
	$is_del = 0;
	$del_by = NULL;
    if(!empty($tetle) && !empty($message)){
        if(check_length($tetle, 2, 50)  && check_length($message, 10, 10000)) {// && check_length($author, 1, 25)) {
		$insert_sql = "INSERT INTO news (author_id,  title, news_text, is_deleted)VALUES('{$author}','{$tetle}', '{$message}', '{$is_del}')"; 
		if(!mysql_query($insert_sql))
			{echo '<center><p><b>Ошибка при добавлении данных!</b></p></center>';}
		else
			{echo '<center><p><b>Данные успешно добавлены!</b></p></center>';}

		mysql_query($insert_sql);
        } else {
            echo "Введенные данные некорректные";
        }
    } else {
        echo "Заполните пустые поля";
    }
  } else {
    header("Location: ../index.php");
  } 
  
  /*if(!isset($_GET['id'])){
				$id=11;
			}else
			{
				$id=$_GET['id'];
			}
			$result = mysql_query("SELECT * FROM news WHERE news_id='$id'") or die(mysql_error());
			$data = mysql_fetch_array($result);
			
			do {
				$news_id = $data["news_id"];
				$author = $data["author_id"];
				$date = $data["date"];
				$title = $data["title"]; 
				$text = $data["news_text"];
				$is_deleted = $data["is_deleted"];
				$deleted_by = $data["deleted_by"];
				echo $news_id;
				echo "<br />";
				echo $author;
				echo "<br />";
				echo $date;
				echo "<br />";
				echo $title;
				echo "<br />";
				echo $is_deleted;
				echo "<br />";
				echo $deleted_by;
			}
			while($data = mysql_fetch_array($result));
			$insert_sql = "INSERT INTO news (author_id, title, news_text, is_deleted) VALUES('{$author}','{$title}', '{$text}', '{$is_deleted}')"; 
		if(!mysql_query($insert_sql))
			{echo '<center><p><b>Ошибка при добавлении данных!</b></p></center>';}
		else
			{echo '<center><p><b>Данные успешно добавлены!</b></p></center>';}
		*/	
?>
  </div>
  <div class="user">
    <div class="middle_text">Пользователь</div>
  </div>
  <div style="clear:right;"/></div>
  <div class="news">
    <?
			include('../news_rigth.php');
			
		?>
  </div>

<? include('../../footer.php');?>