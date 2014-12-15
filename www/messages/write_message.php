<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	
	$id=$user -> uid;
	

		
    function additionalPageHeader() {
	?>
		<link rel="stylesheet" type="text/css" href="/styles/message_write_message.css">
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
	
	$action=trim(htmlspecialchars($_GET["action"]));
	if($action=="sentmsg") {
		$text=$_POST["answer_area"];
		if (!empty($text)) {
			if (empty ($id_user_to)) {
				$recipient = $_POST["recipient"];
				$userid_query = "SELECT `user_id` FROM `user` WHERE `name` = '$recipient'";
				$userid_result= mysql_query($userid_query);
				if (mysql_num_rows($userid_result) > 0)
				{
					$row_id = mysql_fetch_row($userid_result);
					$id_user_to = $row_id[0];
				}
			}
			$insert_query = "INSERT INTO `message` VALUES (NULL,'$id','$id_user_to','$text',NULL,0,0,0)";
			$insert_result = mysql_query($insert_query);
		}
	}	
	
	if(empty($_GET["mode"])){$_GET["mode"]="0";}

	$mode=$_GET['mode'];
?>

<script> 
	var mode= "<? echo $mode ?>";  
	
	function SetMessagesCount(){
		$.ajax({  
			url: "messages_count.php",  
			cache: false,  
			success: function(html){  
				$("#label_1").html(html);  
			}  
		});	
	}
	
	function SetDialogsCount(){
		$.ajax({  
			url: "dialogs_count.php",  
			cache: false,  
			success: function(html){  
				$("#label_1").html(html);  
			}  
		});	
	}
	
	$(document).ready(function(){  	
		if (mode==0) 
		setInterval('SetMessagesCount()',10000);
		else
		setInterval('SetDialogsCount()',10000);
	});  
	
	$(document).ready(function(){
		if (mode==0) 
		{
			var label1 = document.getElementById("label_1");
			label1.innerHTML = "Диалоги";
			label1.onclick = function(){ window.location = "dialogs_list.php";};
		}
		else
		{
			var label1 = document.getElementById("label_1");
			label1.innerHTML = "Входящие";
			label1.onclick = function(){ window.location = "inbox.php";};
			var label2 = document.getElementById("label_2");
			label2.innerHTML = "Отправленные";
			label2.onclick = function(){ window.location = "sent.php";};			
		}
	});
	
</script>

<div class="content">
	<div class="menu_header">
		<table class="menu_header">
			<tr class="menu_header">
				<td class="menu_header">			<div id="label_1" class="menu_item"></div>  </td>
				<td class="menu_header">			<div id="label_2" class="menu_item"></div>	</td>
				<td class="menu_header">			&nbsp;										</td>
				<td class="menu_header_current">	<div class="menu_item">Написать письмо<div>	</td>
			</tr>
		</table>
	</div>
	<div id="message_content" class="message_area" >
		<form id='answer_form' name='answer_form' method='post' action='?action=sentmsg'>
				<div style="margin-top: 15;"><span class="text">Имя получателя 
				<? 
				if (!empty ($id_user_to)) 
					echo ("<input type='text' name='recipient' id='recipient' class='recipient' value=".$name." />");
				else 
					echo ("<input type='text' name='recipient' id='recipient' class='recipient' />");
				?>
				</span></div>
				<div style="margin-top: 15;"><span class="text">Текст письма</span></div>
				<textarea name="answer_area" id="answer_area" class="answer_area" placeholder="Введите ваше сообщение"></textarea> 
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