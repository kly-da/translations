<?PHP
	//header('Location: index.php');
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id=$user -> uid;
	$text=$_POST["answer_area"];
	$id_user_from=$_POST["id_user_from"];
	
	//$id_user_from=5;
	
	if($text<>null) {
		$sqlQueryInsert = "INSERT INTO `message` VALUES (NULL,'$id','$id_user_from','$text',NULL,0,0,0)";
		$rs = mysql_query($sqlQueryInsert);
		//echo("Mail successed sented!");
	}
	
	include('../header.php');
	
?>
  <div class="content">
	<div class="menu_head">
		<a href="index.php">Входящие</a>
	</div>
	<div id="message_content" class="message_area" > Письмо успешно отправлено!
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