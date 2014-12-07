<?PHP
	include('../../mod_db.php');
	include('../../mod_auth.php');
	
	$id=$user -> uid;
	
	$sqlQuery = "SELECT * FROM `message` WHERE `id_user_to` = '$id' AND `date_sending` IN (SELECT max(`date_sending`) FROM `message` GROUP BY `id_user_from`)";

	$rs = mysql_query($sqlQuery);
	
	if (mysql_num_rows($rs) > 0)
	{
		while($row = mysql_fetch_array($rs))
		{
				$id_user = $row['id_user_from'];
				$sqlQueryUserName = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$id_user'";			
				$rsUserName =  mysql_query($sqlQueryUserName);
				$name_row = mysql_fetch_row($rsUserName);
				$name = $name_row[0];
		
				$date = date("d.m.y", strtotime($row['date_sending']));

				if ($date == date("d.m.y")) 
				{
					$date = "Сегодня,";
				}
							
				echo " <div class='message_item' id=item_".$row['id_user_from'].">
							<div class='chbox'> <input type='checkbox' class='chbox' name='cb[]' id=".$row['id_user_from']." /> </div>";	
							
				if ($row['is_readed'] == 1)
					echo 	"<div class='message' id =".$row['id_user_from']." onClick='OpenDialog()'>";
				else
					echo	"<div class='message_unreaded' id =".$row['id_user_from']." onClick='OpenDialog()'>";
					
				echo "			
								<div class='message_name' id =".$row['id_user_from'].">" . $name. " </div>"; 				
				echo "				
								<div class='message_date' id =".$row['id_user_from'].">" . $date . " в " . date("H:i:s", strtotime($row['date_sending'])) . "</div>
								<div class='message_text' id =".$row['id_user_from'].">" . substr($row['text'],0,100) . "</div> 
							</div>			
						</div>";


		}
	}
?>