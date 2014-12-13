<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	include('../code/text_format.php');
	
	$my_id		=	$user -> uid;
	$my_name	=	$user -> name;
	
	$user_id=$_POST['user_id'];	
	$last_id=$_POST['last_id'];	
	
	$name_query = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$user_id'";		
	$name_result =  mysql_query($name_query);
	$name_row = mysql_fetch_row($name_result);
	$name = $name_row[0];
	
	$formatter = new Formatter();	
	
	if (empty($last_id))
		$dialog_query = "SELECT * FROM message WHERE `id_user_from` IN ('$my_id','$user_id') AND `id_user_to` IN ('$my_id','$user_id') ORDER BY `date_sending` ASC";
	else
		$dialog_query = "SELECT * FROM message WHERE `id` >'$last_id' AND `id_user_from` IN ('$my_id','$user_id') AND `id_user_to` IN ('$my_id','$user_id') ORDER BY `date_sending` ASC";
	$dialog_result = mysql_query($dialog_query);

	while($dialog_row = mysql_fetch_array($dialog_result))
	{
	
		$date = $formatter -> toStringChangedDateWithYear($dialog_row['date_sending']);
	
		if ((($my_id == $dialog_row['id_user_from']) and ($dialog_row['id_sender_delete'] == 0)) or 
			(($my_id != $dialog_row['id_user_from']) and ($dialog_row['id_recipient_delete'] == 0)))
		{
				echo " <div class='dialog_item' id=item_".$dialog_row['id'].">
							<div class='chbox'> <input type='checkbox' class='chbox' name='cb[]' id=".$dialog_row['id']." /> </div>";	
				
				echo "   <div class='dialogs_data' data-id=".$dialogs_row['id_user_from'].">";			
				if (($dialog_row['is_readed'] == 1) or ($dialog_row['id_user_from'] == $my_id))
					echo 	"<div class='message'>";
				else
				{
					echo	"<div class='message_unreaded'>";
					$change_query = "UPDATE `message` SET `is_readed`='1' WHERE `id` = ".$dialog_row['id'];
					$change_result = mysql_query($change_query);
				}	
				echo "			
								<div class='message_name'>" .( ($my_id == $dialog_row['id_user_from']) ? $my_name : $name). " </div>"; 							
				echo "				
								<div class='message_date'>" . $date . " в " . date("H:i:s", strtotime($dialog_row['date_sending'])) . "</div>
								<div class='message_text'>" . $dialog_row['text'] . "</div> 
							</div>	
					      </div>								
						</div>";
		}
	}

?>	