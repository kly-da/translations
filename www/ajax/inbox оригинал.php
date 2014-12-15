<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	include('../code/text_format.php');
	
	$id=$user -> uid;
	
	$inbox_query = "SELECT * FROM message WHERE `id_user_to` = '$id' AND `is_recipient_delete` = '0' ORDER BY `date_sending` DESC";
	$inbox_result = mysql_query($inbox_query);
	
	$formatter = new Formatter();
	
	if (mysql_num_rows($inbox_result) > 0)
	{
		while($inbox_row = mysql_fetch_array($inbox_result))
		{
			$user_id = $inbox_row['id_user_from'];
			$name_query = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$user_id'";		
			$name_result =  mysql_query($name_query);
			$name_row = mysql_fetch_row($name_result);
			$name = $name_row[0];
		
			$date = $formatter -> toStringChangedDateWithYear($inbox_row['date_sending']);
			
			echo " <div class='messages_item' id=item_".$inbox_row['id']." >
						<div class='chbox'> <input type='checkbox' class='chbox' name='cb[]' input-id=".$inbox_row['id_user_from']."  /> </div>";	
			echo " 	<div class='messages_data' user-id=".$inbox_row['id_user_from']." id=".$inbox_row['id'].">";	
				if ($inbox_row['is_readed'] == 1)
					echo 	"<div class='message'>";
				else
					echo	"<div class='message_unreaded'>";					
				echo "			
								<div class='message_name'>" . $name. " </div>"; 				
				echo "				
								<div class='message_date'>" . $date . " в " . date("H:i:s", strtotime($inbox_row['date_sending'])) . "</div>
								<div class='message_text'>" . substr($inbox_row['text'],0,100) . "</div> 
							</div>	
					  </div>	
					</div>";				
		} 
	}
	else echo 'В данный момент у вас нету входящих сообщений';
?>
