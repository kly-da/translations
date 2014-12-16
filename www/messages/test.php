<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	 $dialogs_query = "select * from
	(SELECT `id` as id1,`id_user_from` as id_user_from1,`id_user_to` as id_user_to1,`text` as text1,`date_sending` as date1,`is_readed` as 
	is_readed1,`is_sender_delete` as is_sender_delete1,`is_recipient_delete` as is_recipient_delete1 FROM `message` WHERE `date_sending` IN(SELECT 
	max(`date_sending`) FROM `message` WHERE `id_user_to`='1' and`is_recipient_delete`= '0' GROUP BY `id_user_from`)) as Table1
	left join
	(SELECT `id` as id2,`id_user_from` as id_user_from2,`id_user_to` as id_user_to2,`text` as text2,`date_sending` as date2,`is_readed` as 
	is_readed2,`is_sender_delete` as is_sender_delete2,`is_recipient_delete` as is_recipient_delete2 FROM `message` WHERE `date_sending` IN(SELECT 
	max(`date_sending`) FROM `message` WHERE `id_user_from`='1' and`is_sender_delete`= '0' GROUP BY `id_user_to`)) as Table2 
	on Table1.id_user_to1 = Table2.id_user_from2 and Table2.id_user_to2 = Table1.id_user_from1
	union
	select * from
	(SELECT `id` as id1,`id_user_from` as id_user_from1,`id_user_to` as id_user_to1,`text` as text1,`date_sending` as date1,`is_readed` as 
	is_readed1,`is_sender_delete` as is_sender_delete1,`is_recipient_delete` as is_recipient_delete1 FROM `message` WHERE `date_sending` IN(SELECT 
	max(`date_sending`) FROM `message` WHERE `id_user_to`='1' and`is_recipient_delete`= '0' GROUP BY `id_user_from`)) as Table1
	right join
	(SELECT `id` as id2,`id_user_from` as id_user_from2,`id_user_to` as id_user_to2,`text` as text2,`date_sending` as date2,`is_readed` as 
	is_readed2,`is_sender_delete` as is_sender_delete2,`is_recipient_delete` as is_recipient_delete2 FROM `message` WHERE `date_sending` IN(SELECT 
	max(`date_sending`) FROM `message` WHERE `id_user_from`='1' and`is_sender_delete`= '0' GROUP BY `id_user_to`)) as Table2 
	on Table1.id_user_to1 = Table2.id_user_from2 and Table2.id_user_to2 = Table1.id_user_from1";
	$dialogs_result = mysql_query($dialogs_query);
	//$rows_count = mysql_num_rows($dialogs_result);
	while ($dialogs_row = mysql_fetch_array($dialogs_result)){
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
		echo($text);
		echo("\n");
	}
?>

