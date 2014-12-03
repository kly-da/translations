<?PHP
	include('../../mod_db.php');
	include('../../mod_auth.php');
	
	$id=$user -> uid;

	$sqlQuery = "SELECT * FROM message WHERE `id_user_to` = '$id' AND `is_recipient_delete` = '0' ORDER BY `date_sending` DESC";
	$rs = mysql_query($sqlQuery);
	
	if (mysql_num_rows($rs) > 0)
	{
		while($row = mysql_fetch_array($rs))
		{
			$id_user = $row['id_user_from'];
			$sqlQueryUserName = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$id_user'";			
			$rsUserName =  mysql_query($sqlQueryUserName);
			$name = mysql_fetch_row($rsUserName);
		
			$date = date("d.m.y", strtotime($row['date_sending']));
			
			if ($date == date("d.m.y")) 
			{
				$date = "Сегодня,";
			}
		
			if ($row['is_readed'] == 1)
			{
			echo " <div class='message_item' id=item_".$row['id'].">
						<div class='chbox'> <input type='checkbox' class='chbox' name='cb[]' id=".$row['id']." /> </div>			
						<div class='message' id =".$row['id']." onClick='OpenMessage()'>
							<div class='message_name' id =".$row['id'].">" . $name[0]. " </div> 
							<div class='message_date' id =".$row['id'].">" . $date . " в " . date("H:i:s", strtotime($row['date_sending'])) . "</div>
							<div class='message_text' id =".$row['id'].">" . substr($row['text'],0,100) . "</div> 
						</div>			
					</div>";
			}
			else
			{
			echo " <div class='message_item' id=item_".$row['id'].">
						<div class='chbox'> <input type='checkbox' class='chbox' name='cb[]' id=".$row['id']." /> </div>			
						<div class='message_unreaded' id =".$row['id']." onClick='OpenMessage()'>
							<div class='message_name' id =".$row['id'].">" . $name[0]. " </div> 
							<div class='message_date' id =".$row['id'].">" . $date . " в " . date("H:i:s", strtotime($row['date_sending'])) . "</div>
							<div class='message_text' id =".$row['id'].">" . substr($row['text'],0,100) . "</div> 
						</div>			
					</div>";	
			}				
		} 
	}
	else echo 'В данный момент у вас нету входящих сообщений';
?>
