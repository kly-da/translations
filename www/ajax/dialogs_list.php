<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	include('../code/text_format.php');
	
	$my_id=$user -> uid;
	
	//$dialogs_query = "SELECT * FROM `message` WHERE `id_user_to` = '$id' AND `date_sending` IN (SELECT max(`date_sending`) FROM `message` WHERE `is_recipient_delete`= '0' GROUP BY `id_user_from`) ORDER BY `date_sending` DESC";
	
  	 $dialogs_query = "select * from
	(SELECT `id` as id1,`id_user_from` as id_user_from1,`id_user_to` as id_user_to1,`text` as text1,`date_sending` as date1,`is_readed` as 
	is_readed1,`is_sender_delete` as is_sender_delete1,`is_recipient_delete` as is_recipient_delete1 FROM `message` WHERE `date_sending` IN(SELECT 
	max(`date_sending`) FROM `message` WHERE `id_user_to`='$my_id' and`is_recipient_delete`= '0' GROUP BY `id_user_from`)) as Table1
	left join
	(SELECT `id` as id2,`id_user_from` as id_user_from2,`id_user_to` as id_user_to2,`text` as text2,`date_sending` as date2,`is_readed` as 
	is_readed2,`is_sender_delete` as is_sender_delete2,`is_recipient_delete` as is_recipient_delete2 FROM `message` WHERE `date_sending` IN(SELECT 
	max(`date_sending`) FROM `message` WHERE `id_user_from`='$my_id' and`is_sender_delete`= '0' GROUP BY `id_user_to`)) as Table2 
	on Table1.id_user_to1 = Table2.id_user_from2 and Table2.id_user_to2 = Table1.id_user_from1
	union
	select * from
	(SELECT `id` as id1,`id_user_from` as id_user_from1,`id_user_to` as id_user_to1,`text` as text1,`date_sending` as date1,`is_readed` as 
	is_readed1,`is_sender_delete` as is_sender_delete1,`is_recipient_delete` as is_recipient_delete1 FROM `message` WHERE `date_sending` IN(SELECT 
	max(`date_sending`) FROM `message` WHERE `id_user_to`='$my_id' and`is_recipient_delete`= '0' GROUP BY `id_user_from`)) as Table1
	right join
	(SELECT `id` as id2,`id_user_from` as id_user_from2,`id_user_to` as id_user_to2,`text` as text2,`date_sending` as date2,`is_readed` as 
	is_readed2,`is_sender_delete` as is_sender_delete2,`is_recipient_delete` as is_recipient_delete2 FROM `message` WHERE `date_sending` IN(SELECT 
	max(`date_sending`) FROM `message` WHERE `id_user_from`='$my_id' and`is_sender_delete`= '0' GROUP BY `id_user_to`)) as Table2 
	on Table1.id_user_to1 = Table2.id_user_from2 and Table2.id_user_to2 = Table1.id_user_from1";
	
	$dialogs_result = mysql_query($dialogs_query);
	
	$formatter = new Formatter();

	$rows_count = mysql_num_rows($dialogs_result);
	
	while ($dialogs_row = mysql_fetch_array($dialogs_result))
	{	
		if ($dialogs_row['date1'] > $dialogs_row['date2']) {
			$id 					= $dialogs_row['id1'];
			$id_user_from 			= $dialogs_row['id_user_from1'];
			$id_user_to 			= $dialogs_row['id_user_to1'];
			$text 					= $dialogs_row['text1'];	 
			$date_sending 			= $dialogs_row['date1'];		
			$is_readed 				= $dialogs_row['is_readed1'];			
			$is_sender_delete 		= $dialogs_row['is_sender_delete1'];				
			$is_recipient_delete 	= $dialogs_row['is_recipient_delete1'];													
		}
		else
		{
			$id 					= $dialogs_row['id2'];
			$id_user_from 			= $dialogs_row['id_user_from2'];
			$id_user_to 			= $dialogs_row['id_user_to2'];
			$text 					= $dialogs_row['text2'];	 
			$date_sending 			= $dialogs_row['date2'];		
			$is_readed 				= $dialogs_row['is_readed2'];			
			$is_sender_delete 		= $dialogs_row['is_sender_delete2'];				
			$is_recipient_delete 	= $dialogs_row['is_recipient_delete2'];													
		}
		if ($id_user_from == $my_id)
		{
			$user_id = $id_user_to;
			$prefix = "Кому: ";
		}
		else 
		{
			$user_id = $id_user_from;
			$prefix = "От: ";
		}
		$name_query = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$user_id'";		
		$name_result =  mysql_query($name_query);
		$name_row = mysql_fetch_row($name_result);
		$name = strval($name_row[0]);
		
		
		$date = $formatter -> toStringChangedDateWithYear($date_sending);
		
		echo " <div class='dialogs_item' id=item_".$user_id." >
					<div class='chbox'> <input type='checkbox' class='chbox' name='cb[]' input-id=".$user_id." /> </div>";	
		echo " 	<div class='dialogs_data' data-id=".$user_id.">";	
			if ($is_readed == 1)
				echo 	"<div class='message'>";
			else
				echo	"<div class='message_unreaded'>";
				
			echo "			
							<div class='message_name'>" . $prefix . $name . " </div>"; 				
			echo "				
							<div class='message_date'>" . $date . " в " . date("H:i:s", strtotime($date_sending)) . "</div>
							<div class='message_text'>" . mb_substr($text,0,80,'UTF-8') . "</div> 
						</div>	
				  </div>	
				</div>";	
	}
?>