<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id=$user -> uid;
	
	$idMessage=$_REQUEST['id'];
	
	$sqlQuery = "SELECT * FROM message WHERE `id` = '$idMessage'";
	
	$rs = mysql_query($sqlQuery);
	$row = mysql_fetch_array($rs);
	$id_user_from = $row['id_user_from'];
	$sqlQueryUserName = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$id_user_from'";
	$rsUserName =  mysql_query($sqlQueryUserName);
	$name = mysql_fetch_row($rsUserName);
	echo "<div class=\"full_message\">Сообщение от: <font color=\"red\">". $name[0]."</font> в " . $row['date_sending'] . "<br /> " . $row['text'] . "<br /></div>";
?>
<div>Написать ответ </div>
<form id="answer_form" name="answer_form" method="post" action="write_into_base.php">
<textarea name="answer_area" id="answer_area" class="answer_area"> </textarea> 
</br></br>
&nbsp;<input type="submit" name="submit" id="submit" value="Отправить" />
<input type="hidden" name="id_user_from" value="<? print $id_user_from; ?>"/>
</form>
