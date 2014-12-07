<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	include('../code/text_format.php');
	
	$id=$user -> uid;
	
	$dialogs_query = "SELECT * FROM `message` WHERE `id_user_to` = '$id' AND `date_sending` IN (SELECT max(`date_sending`) FROM `message` GROUP BY `id_user_from`) ORDER BY `date_sending` DESC";
		
	$dialogs_result = mysql_query($dialogs_query);
	
	$formatter = new Formatter();

	$rows_count = mysql_num_rows($dialogs_result);
	
	while ($dialogs_row = mysql_fetch_array($dialogs_result))
	{	
		$id_user = $dialogs_row['id_user_from'];
		$name_query = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$id_user'";		
		$name_result =  mysql_query($name_query);
		$name_row = mysql_fetch_row($name_result);
		$name = $name_row[0];
		
		$date = $formatter -> toStringChangedDateWithYear($dialogs_row['date_sending']);
		
		echo " <div class='dialogs_item' id=item_".$dialogs_row['id_user_from'].">
					<div class='chbox'> <input type='checkbox' class='chbox' name='cb[]' id=".$dialogs_row['id_user_from']." /> </div>";	
					
		if ($dialogs_row['is_readed'] == 1)
			echo 	"<div class='message' id =".$dialogs_row['id_user_from']." onClick='OpenDialog()'>";
		else
			echo	"<div class='message_unreaded' id =".$dialogs_row['id_user_from'].">";
			
		echo "			
						<div class='message_name' id =".$dialogs_row['id_user_from'].">" . $name. " </div>"; 				
		echo "				
						<div class='message_date' id =".$dialogs_row['id_user_from'].">" . $date . " в " . date("H:i:s", strtotime($dialogs_row['date_sending'])) . "</div>
						<div class='message_text' id =".$dialogs_row['id_user_from'].">" . substr($dialogs_row['text'],0,100) . "</div> 
					</div>			
				</div>";	
	}
?>