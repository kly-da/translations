<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	
	//$id=$user -> uid;
	
    function additionalPageHeader() {
	?>
		<link rel="stylesheet" type="text/css" href="/styles/message_index.css">
	<?  }

    $title = "Написать письмо";
	include('../header.php');	
	
	
	$id_user_to=$_GET['id_user_to'];
	if (!empty ($id_user_to))
	{
		$sqlQueryUserName = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$id_user_to'";
		$rsUserName =  mysql_query($sqlQueryUserName);		
		$name_row = mysql_fetch_row($rsUserName);
		$name = $name_row[0];
	}
?>

<script>  

	function SetMessagesCount(){
		$.ajax({  
			url: "messages_count2.php",  
			cache: false,  
			success: function(html){  
				$("#inbox_label").html(html);  
			}  
		});	
	}
	
	$(document).ready(function(){  	
		SetMessagesCount();
		setInterval('SetMessagesCount()',100000);
	});  
</script>

<div class="content">
	<div class="menu_header">
		<table class="menu_header">
			<tr class="menu_header">
				<td class="menu_header">			<div id="inbox_label" class="menu_item" onclick="location.href='./inbox/';">Входящие</div>  </td>
				<td class="menu_header">			<div class="menu_item" onclick="location.href='./sent/';">Отправленные</a>					</td>
				<td class="menu_header">			&nbsp;																						</td>
				<td class="menu_header_current">	<div class="menu_item">Написать письмо<div>													</td>
			</tr>
		</table>
	</div>
	<div id="message_content" class="message_area" >
		<form id='answer_form' name='answer_form' method='post' action='send_message.php'>
				</br>
				<span class="text">Имя получателя 
				<? 
				if (!empty ($id_user_to)) 
					echo ("<input type='text' name='recipient' id='recipient' class='recipient' value=".$name." />");
				else 
					echo ("<input type='text' name='recipient' id='recipient' class='recipient' />");
				?>
				</span>
				</br></br>
				<div><span class="text">Текст письма</span></div>
				<textarea name="answer_area" id="answer_area" class="answer_area" placeholder="Введите ваше сообщение"></textarea> 
				</br></br>
				<div class="submit_button"> <input class="submit_button" type="submit" name="submit" id="submit" value="Отправить" />	</div>
		</form>
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