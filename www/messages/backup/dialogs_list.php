<?

	#==== Load page ===========================
   
    #==== Load page ===========================
    
    
    #==== Load database =======================

	include('../mod_db.php');
	include('../mod_auth.php');
	include('../code/text_format.php');

	//mysql_query("SET NAMES utf8");
	
	//$id=$user -> uid;
	
	//$dialogs_query = "SELECT * FROM `message` WHERE `id_user_to` = '$id' AND `date_sending` IN (SELECT max(`date_sending`) FROM `message` GROUP BY `id_user_from`)";
		
	//$dialogs_result = mysql_query($dialogs_query);
	
	//$dialogs_row = mysql_fetch_assoc($dialogs_result);
	
	/*if (!$dialogs_row) {
		header('Location: /error/text_not_found.php');  //поменять на свое
		die();
	}*/
	
	
	#==== Load database =======================
?>
<script>
	var intervalID;
	
	function loadDialogs()  
	{  		
		$.ajax({  
			type: "POST",
			url: "../ajax/dialogs_list.php",  
			cache: false,  
			success: function(html){  
				$("#message_content").html(html);  
			}  
		});	
	} 
	
	$(document).ready(function(){
		loadDialogs();
		intervalID = setInterval('loadDialogs()',10000);
	}
	
	$(document).ready(function(){
		$("#maincbox").click( function() { 
		if($('#maincbox').attr('checked')){ 
			$('.chbox').attr('checked', true); 
		} else {
			$('.chbox').attr('checked', false); 
		}
	});	
	});
</script>

<? 

function gen_dialogs_content($dialogs_result)
{
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
			echo	"<div class='message_unreaded' id =".$dialogs_row['id_user_from']." onClick='OpenDialog()'>";
			
		echo "			
						<div class='message_name' id =".$dialogs_row['id_user_from'].">" . $name. " </div>"; 				
		echo "				
						<div class='message_date' id =".$dialogs_row['id_user_from'].">" . $date . " в " . date("H:i:s", strtotime($dialogs_row['date_sending'])) . "</div>
						<div class='message_text' id =".$dialogs_row['id_user_from'].">" . substr($dialogs_row['text'],0,100) . "</div> 
					</div>			
				</div>";	
	}
	
}
  
?>

	<link rel="stylesheet" type="text/css" href="/styles/message_dialogs_list.css">
<?
	$title = 'Диалоги';
	include('../header.php');
?>

 <div class="content">
		<div class="menu_header">
			<table class="menu_header">
				<tr class="menu_header">
					<td class="menu_header"><div id="dlgs_label" class="menu_item" onclick="location.href='index.php';">Диалоги</div> </td>
					<td class="menu_header"><div id="dlg_label" class="menu_item"></div> </td>
					<td class="menu_header">&nbsp;</td>
					<td class="menu_header"><div class="menu_item" onclick="location.href='../write_message.php';">Написать письмо</div></td>
				</tr>			
			</table>
		</div>
		
		<div id="dialogs_content" class="dialogs_content"> 
			<?
				gen_dialogs_content($dialogs_result);
			?>
		</div>

		<div class="menu_footer">
			<table class="menu_header">
				<tr class="menu_header">
					<td class="menu_footer_chbox"><div class='mainchbox'><input type='checkbox' name='cb[]' id='maincbox'/></div><label for="maincbox" class='chbox_label'>Выделить все</label></td>
					<td class="menu_footer">&nbsp;</td>
					<td class="menu_footer">&nbsp;</td>
					<td class="menu_footer"><div class="menu_item" onclick='DeleteMessages()'>Удалить</div></td>
					<td class="menu_footer"><div class="menu_item" onclick='MarkMessagesAsSpam()'>Спам</div></td>
				</tr>			
			</table>
		</div>
	</div>
  
  <div class="user">
    <div class="middle_text">Пользователь</div>
  </div>
  <div style="clear:right;"/></div>
  
  <div class="news">
    <div class="middle_text">Новости</div>
  </div>
  
 	
<? include('../footer.php');?>