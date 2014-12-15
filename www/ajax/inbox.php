<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	include('../code/text_format.php');
	
	$id=$user -> uid;
	
	$messages_on_page="11";
	
	// считаем сообщения
	$count=mysql_fetch_array(mysql_query("SELECT count(id) FROM message WHERE `id_user_to` = '$id' AND `is_recipient_delete` = '0' ORDER BY `date_sending` DESC"));
	
	// считаем страницы
	$total=ceil($count[0]/$messages_on_page);
	
	// страницы
	if(empty($_GET["p"])){$_GET["p"]="1";}
	$p=$_GET["p"];

	$p=mysql_real_escape_string($p);
	if(!ctype_digit($p) or $p>$total):
		$p="1";
	endif;
	
	$first=$p*$messages_on_page-$messages_on_page;
	
	$inbox_query = "SELECT * FROM message WHERE `id_user_to` = '$id' AND `is_recipient_delete` = '0' ORDER BY `date_sending` DESC limit $first, $messages_on_page";
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
						<div class='chbox'> <input type='checkbox' class='chbox' name='cb[]' id=".$inbox_row['id']." /> </div>";	
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
	
	if($total>1):
		#две назад
		print "<br><div>";
		if(($p-2)>0):
		  $ptwoleft="<a class='first_page_link' href='inbox.php?p=".($p-2)."'>".($p-2)."</a>  ";
		else:
		  $ptwoleft=null;
		endif;
				
		#одна назад
		if(($p-1)>0):
		  $poneleft="<a class='first_page_link' href='inbox.php?p=".($p-1)."'>".($p-1)."</a>  ";
		  $ptemp=($p-1);
		else:
		  $poneleft=null;
		  $ptemp=null;
		endif;
				
		#две вперед
		if(($p+2)<=$total):
		  $ptworight="  <a class='first_page_link' href='inbox.php?p=".($p+2)."'>".($p+2)."</a>";
		else:
		  $ptworight=null;
		endif;
				
		#одна вперед
		if(($p+1)<=$total):
		  $poneright="  <a class='first_page_link' href='inbox.php?p=".($p+1)."'>".($p+1)."</a>";
		  $ptemp2=($p+1);
		else:
		  $poneright=null;
		  $ptemp2=null;
		endif;		
				
		# в начало
		if($p!=1 && $ptemp!=1 && $ptemp!=2):
		  $prevp="<a href='inbox.php?p=1' class='first_page_link' title='В начало'><<</a> ";
		else:
		  $prevp=null;
		endif;   
				
		#в конец (последняя)
		if($p!=$total && $ptemp2!=($total-1) && $ptemp2!=$total):
		  $nextp=" ...  <a href='inbox.php?p=".$total."'".$total."' class='first_page_link'>$total</a>";
		else:
		  $nextp=null;
		endif;
			
		print "<br>".$prevp.$ptwoleft.$poneleft.'<span class="num_page_not_link"><b>'.$p.'</b></span>'.$poneright.$ptworight.$nextp; 
		print "</div>";
	endif;


?>
