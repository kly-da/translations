<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	include('../code/text_format.php');
	
	$id=$user -> uid;
	
	$dialogs_query = "SELECT * FROM `message` WHERE `id_user_to` = '$id' AND `date_sending` IN (SELECT max(`date_sending`) FROM `message` WHERE `is_recipient_delete`= '0' GROUP BY `id_user_from`) ORDER BY `date_sending` DESC";
		
	$dialogs_result = mysql_query($dialogs_query);
	
	$formatter = new Formatter();

	$rows_count = mysql_num_rows($dialogs_result);
	
	while ($dialogs_row = mysql_fetch_array($dialogs_result))
	{	
		$user_id = $dialogs_row['id_user_from'];
		$name_query = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$user_id'";		
		$name_result =  mysql_query($name_query);
		$name_row = mysql_fetch_row($name_result);
		$name = $name_row[0];
		
		$date = $formatter -> toStringChangedDateWithYear($dialogs_row['date_sending']);
		
		

		echo " <div class='dialogs_item' id=item_".$dialogs_row['id_user_from']." >
					<div class='chbox'> <input type='checkbox' class='chbox' name='cb[]' input-id=".$dialogs_row['id_user_from']."  /> </div>";	
		echo " 	<div class='dialogs_data' data-id=".$dialogs_row['id_user_from'].">";	
			if ($dialogs_row['is_readed'] == 1)
				echo 	"<div class='message'>";
			else
				echo	"<div class='message_unreaded'>";
				
			echo "			
							<div class='message_name'>" . $name. " </div>"; 				
			echo "				
							<div class='message_date'>" . $date . " в " . date("H:i:s", strtotime($dialogs_row['date_sending'])) . "</div>
							<div class='message_text'>" . substr($dialogs_row['text'],0,100) . "</div> 
						</div>	
				  </div>	
				</div>";	
	}
?>