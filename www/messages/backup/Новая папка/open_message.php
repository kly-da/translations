<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id=$user -> uid;
	
	$idMessage=$_REQUEST['id'];
	
	if (!empty ($idMessage))
	{
		$sqlQuery = "SELECT * FROM message WHERE `id` = '$idMessage'";		
		$rs = mysql_query($sqlQuery);
		
		$row = mysql_fetch_array($rs);
		
		$id_user_from = $row['id_user_from'];
		
		$sqlQueryUserName = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$id_user_from'";
		$rsUserName =  mysql_query($sqlQueryUserName);
		
		$name = mysql_fetch_row($rsUserName);
		
		echo "<div class='header_message'> <span class='text'>Сообщение от: <font color='red'>". $name[0]."</font></span> </br><span class='text'>" . $row['date_sending'] . "</span></div><div class='full_message'><span class='text'>" . $row['text'] . "</span></div>";
		
		if ($row['is_readed'] == 0) {
			$sqlQueryUpdate = "UPDATE `message` SET `is_readed`= '1' WHERE `id` = '" . $row['id'] ."'" ;		
			$rsUpdate = mysql_query($sqlQueryUpdate);
		}
	}	
?>

<div><span class="text">Текст ответа</span></div>
<form id='answer_form' name='answer_form' method='post' action='send_message.php'>
	</br></br>
	<textarea name="answer_area" id="answer_area" class="answer_area" placeholder="Введите ваше сообщение"></textarea> 
	</br></br>
	<div class="submit_button">
		<input type="submit" name="submit" id="submit" value="Отправить" class="submit_button" />
	</div>

	<input type="hidden" name="id_user_to" value="<? if (!empty ($idMessage)) print $id_user_from; ?>"/> 
</form>
