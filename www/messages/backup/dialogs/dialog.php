<?PHP
	include('../../mod_db.php');
	include('../../mod_auth.php');
	
	$my_id		=	$user -> uid;
	$my_name	=	$user -> name;
	
	$chat_with_id=$_POST['dlgid'];
	//echo($chat_with_id);
	//print_r($_POST);
	
	if (!empty ($chat_with_id))
	{
	
		$sqlQueryUserName = "SELECT `name` FROM  `user` WHERE  `user_id` = '$chat_with_id'";			
		$rsUserName =  mysql_query($sqlQueryUserName);
		$name_row = mysql_fetch_row($rsUserName);
		$chat_with_name = $name_row[0];
		
		$sqlQuery = "SELECT * FROM message WHERE `id_user_from` IN ('$my_id','$chat_with_id') AND `id_user_to` IN ('$my_id','$chat_with_id') ORDER BY `date_sending` ASC";
		//echo($sqlQuery );
		$rs = mysql_query($sqlQuery);
		
		if (mysql_num_rows($rs) > 0)
		{
			while($row = mysql_fetch_array($rs))
			{
			
				$date = date("d.m.y", strtotime($row['date_sending']));

				if ($date == date("d.m.y")) 
				{
					$date = "Сегодня,";
				}
			
				if ((($my_id == $row['id_user_from']) and ($row['id_sender_delete'] == 0)) or (($my_id != $row['id_user_from']) and ($row['id_recipient_delete'] == 0)))
				{
						echo " <div class='message_item' id=item_".$row['id'].">
									<div class='chbox'> <input type='checkbox' class='chbox' name='cb[]' id=".$row['id']." /> </div>";	
									
						if ($row['is_readed'] == 1)
							echo 	"<div class='message' id =".$row['id']." onClick='OpenMessage()'>";
						else
							echo	"<div class='message_unreaded' id =".$row['id']." onClick='OpenMessage()'>";
							
						if ($my_id == $row['id_user_from'])	
							echo "			
										<div class='message_name' id =".$row['id'].">" . $my_name. " </div>"; 
						else
							echo "			
										<div class='message_name' id =".$row['id'].">" . $chat_with_name. " </div>"; 				
						echo "				
										<div class='message_date' id =".$row['id'].">" . $date . " в " . date("H:i:s", strtotime($row['date_sending'])) . "</div>
										<div class='message_text' id =".$row['id'].">" . substr($row['text'],0,100) . "</div> 
									</div>			
								</div>";
						}
				}					
		} 
	}	
?>
<form id='answer_form' name='answer_form' method='post' action='../write_message.php'>
	<textarea name="answer_area" id="answer_area" class="answer_area" placeholder="Введите ваше сообщение"></textarea> 
	<div class="submit_button">
		<input type="submit" name="submit" id="submit" value="Ответить" class="submit_button" />
	</div>
	<input type="hidden" name="id_user_to" value="<? if (!empty ($idMessage)) print $id_user_from; ?>"/> 	
</form>