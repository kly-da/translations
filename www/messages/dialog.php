<?PHP
	include('../mod_db.php');
	include('../mod_auth.php');
	include('../code/text_format.php');

	$my_id		=	$user -> uid;
	$my_name	=	$user -> name;
	
	$user_id=$_GET['user_id'];
	$msgid=$_GET['msgid'];
	$mode=$_GET['mode'];

	/*if (empty($user_id)) {
		header('Location: /error/404.php');
		die();
	}*/
	
	$name_query = "SELECT  `name` FROM  `user` WHERE  `user_id` = '$user_id'";		
	$name_result =  mysql_query($name_query);
	$name_row = mysql_fetch_row($name_result);
	$name = $name_row[0];
	
	
?>		
	<link rel="stylesheet" type="text/css" href="/styles/message_dialog.css">
<?	
	$title = 'Диалоги';
	include('../header.php');
?>
<script>
	var user_id = "<? echo $user_id ?>";
	var msgid	= "<? echo $msgid ?>";
	var mode	= "<? echo $mode ?>";   
	var name	= "<? echo $name ?>";
	
	var first_load = true;
	var dialog,message,offTop;
	
	function scrollDialog() 
	{		
		if (first_load)
		{	
			first_load = false;	
			if (msgid == "")
			{				
				dialog = document.getElementById("dialog_content");	
				dialog.scrollTop += dialog.scrollHeight;
							
			}
			else {
				message = document.getElementById("item_"+msgid);
				offTop = message.offsetTop;				
				dialog = document.getElementById("dialog_content");	
				dialog.scrollTop += offTop-25;
			}
		}	
	}
	
	function loadDialog()  
	{  		
		$.ajax({  
			type: "POST",
			url: "../ajax/dialog.php",  
			data: {user_id:user_id},
			cache: false,  
			success: function(html){  
				$("#dialog_content").html(html); 
				scrollDialog(); 				
			}  
		});	
	} 
	

	
	$(document).ready(function(){
		loadDialog();		
		intervalID = setInterval('loadDialog()',15000);

	});
	
	
	$(document).ready(function(){
		$("#maincbox").click( function() { 
			if($('#maincbox').attr('checked')){ 
				$('.chbox').attr('checked', true); 
			} else {
				$('.chbox').attr('checked', false); 
			}
		});	
		
		$('#dialog_content').on('click', '.dialog_data', function(event) {
			alert(this.id);
		});	
		
	});
	
	$(document).ready(function(){
		if (mode==0) 
		{
			var label1 = document.getElementById("label_1");
			label1.innerHTML = "Диалоги";
			var label2 = document.getElementById("label_2");
			label2.innerHTML = name;
			var td2 = document.getElementById("td_2");
			td2.style.backgroundColor= '#3366CC';
			td2.style.fontSize= 'large';
		}
		else
		{
			var label1 = document.getElementById("label_1");
			label1.innerText = "Входящие";
			var label2 = document.getElementById("label_2");
			label2.innerText = "Исходящие";
			var td3 = document.getElementById("td_3");
			td3.style.backgroundColor= '#3366CC';
			td3.style.fontSize= 'large';
		}
	});

</script>

<div class="content">
		<div class="menu_header">
			<table class="menu_header">
				<tr class="menu_header">
					<td id="td_1" class="menu_header"><div id="label_1" class="menu_item"></div> </td>
					<td id="td_2" class="menu_header"><div id="label_2" class="menu_item"></div> </td>
					<td id="td_3" class="menu_header"><div id="label_3" class="menu_item"></div> </td>
					<td class="menu_header"><div class="menu_item" onclick="location.href='write_message.php';">Написать письмо</div></td>
				</tr>			
			</table>
		</div>
		
		<div id="dialog_content" class="dialog_content"> </div>
		<div class="answer_area">
			<form id='answer_form' name='answer_form' method='post' action=''>
			
					<textarea name="answer_area" id="answer_area" class="answer_area" placeholder="Введите ваше сообщение"></textarea> 
					<div class="submit_button">
						<input type="submit" name="submit" id="submit" value="Ответить" class="submit_button" />
					</div>
				
				<input type="hidden" name="id_user_to" value="<? if (!empty ($idMessage)) print $id_user_from; ?>"/> 	
			</form>
		</div>
		<div class="menu_footer">
			<table class="menu_header">
				<tr class="menu_header">
					<td class="menu_footer_chbox"><div class='mainchbox'><input type='checkbox' name='cb[]' id='maincbox'/></div><label for="maincbox" class='chbox_label'>Выделить все</label></td>
					<td class="menu_footer">&nbsp;</td>
					<td class="menu_footer">&nbsp;</td>
					<td class="menu_footer"><div class="menu_item" id="delete_button">Удалить</div></td>
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