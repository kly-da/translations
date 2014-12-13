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
	
	//var first_load = true;
	var dialog,message,offTop,last_id;
	
	function getLastMessageId(){
		var cbx = document.getElementById("dialog_content").getElementsByTagName("input");
		return cbx[cbx.length - 1].id;
	}
	/*
	function markMessageAsReaded(id) {
		$.ajax({  
			type: "POST",
			url: "../ajax/mark_message_as_readed.php",  
			data: {id:id},
			cache: false,  
		});	
	}*/
	
	function scrollDialog(first_load, new_message, html) 
	{		
		new_message = new_message || false;
		if (first_load)
		{	
			first_load = false;	
			if (msgid == "")
			{		
				dialog = document.getElementById("dialog_content")			
				dialog.scrollTop += dialog.scrollHeight;						
			}
			else {
				message = document.getElementById("item_"+msgid);
				offTop = message.offsetTop;				
				dialog = document.getElementById("dialog_content");	
				dialog.scrollTop += offTop-25;
			}
		}	
		else
		{
			if (new_message)
			{	
				dialog = document.getElementById("dialog_content");	
				if (dialog.scrollTop == (dialog.scrollHeight - dialog.clientHeight))	
				{
					$("#dialog_content").append(html); 					
					dialog.scrollTop += dialog.scrollHeight;					
					setTimeout('changeClassMessages()',2000);	
				}
				else {
					$("#dialog_content").append(html); 
				}				
			}
		}
		
	}
	
	function loadDialog(first_load)  
	{  		
		$.ajax({  
			type: "POST",
			url: "../ajax/dialog.php",  
			data: {user_id:user_id},
			cache: false,  
			success: function(html){  
				$("#dialog_content").html(html); 
				scrollDialog(first_load); 				
			}  
		});	
	} 

	
	function refreshDialog()  
	{  		
		last_id = getLastMessageId();
		$.ajax({  
			type: "POST",
			url: "../ajax/dialog.php",  
			data: {user_id:user_id,last_id:last_id},
			cache: false,  
			success: function(html){ 
				if (html.length != 1) { scrollDialog(false,true,html);};			
			}  
		});	
	} 

	
	function changeClassMessages(){
		var mas_messages = document.getElementById("dialog_content").getElementsByClassName("message_unreaded"),mas = [];	
		if 	(mas_messages !=0) 
		{
			for (var j=0; j < mas_messages.length; j++)			
			{
				mas.push(mas_messages[j]);
			}
			for (var k=0; k < mas.length; k++)			
			{
				mas[k].className = 'message';
			}
		}	
	}
	
	function sendMessage(){
		var msg  = document.getElementById("answer_area").value;
        $.ajax({
          type: "POST",
          url: "send_message.php",
          data: {answer_area:msg,id_user_to:user_id},
          error:  function(xhr, str){
                alert('Возникла ошибка: ' + xhr.responseCode);
            }
        });
		document.getElementById("answer_area").value = "";
	};
	
	function checkForm(){ 
		if (document.getElementById("answer_area").value == "")
		return false; 
		else
		return true;
	};
		
	function DeleteMessages() {
		var cbx = document.getElementById("messages_content").getElementsByTagName("input"), mas = [],mes;
		for (i=0; i < cbx.length; i++) {
			if (cbx[i].type == "checkbox" && cbx[i].checked) {
				mas.push(cbx[i].id);
				mes = document.getElementById("item_"+cbx[i].id);
				mes.style.display = "none";
			}
		}
		if (mas.length == 0) alert("Не выбрал ни одного! Обмануть вздумал?!");
		else {
			$.ajax({  
			type: "POST",
			url: "../deleting.php", 
			data: {mas:mas,from:"dialog"},			
			cache: false,  
			//success: function(html){  
			//	$("#test").html(html);  
			//}  
		}); 
		};
	}

		
	$(document).ready(function(){
		if (mode==0) 
		{
			var label1 = document.getElementById("label_1");
			label1.innerHTML = "Диалоги";
			label1.onclick = function(){ window.location = "dialogs_list.php";};
			var label2 = document.getElementById("label_2");
			label2.innerHTML = name;
			var td2 = document.getElementById("td_2");
			td2.style.backgroundColor= '#3366CC';
			td2.style.fontSize= 'large';
		}
		else
		{
			var label1 = document.getElementById("label_1");
			label1.innerHTML = "Входящие";
			label1.onclick = function(){ window.location = "inbox.php";};
			var label2 = document.getElementById("label_2");
			label2.innerHTML = "Отправленные";
			label2.onclick = function(){ window.location = "sent.php";};
			var label3 = document.getElementById("label_3");
			label3.innerHTML = name;		
			var td3 = document.getElementById("td_3");
			td3.style.backgroundColor= '#3366CC';
			td3.style.fontSize= 'large';
		}
	});
	
	$(document).ready(function(){
		loadDialog(true);		
		intervalID = setInterval('refreshDialog()',1000);
	});
	
	$(document).ready(function(){
		$("#maincbox").click( function() { 
			if($('#maincbox').attr('checked')){ 
				$('.chbox').attr('checked', true); 
			} else {
				$('.chbox').attr('checked', false); 
			}
		});	
		
		//$('#dialog_content').on('scroll', function() {
			//if ((this.scrollTop + this.clientHeight) == this.scrollHeight) {alert("конец");}
		//});			
	});
	
	$(document).ready(function(){
		$('#answer_area').on('click',function() {
			setTimeout('changeClassMessages()',2000);
		});	
	});	
	
	/*$(document).ready(function(){
		document.onkeyup = function (e) {
			e = e || window.event;
			if (e.keyCode === 13) { привязать
			}
		}
	});*/
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
			<form id="answer_form" name="answer_form" onSubmit="return checkForm();" action="javascript:sendMessage();">			
					<textarea name="answer_area" id="answer_area" class="answer_area" placeholder="Введите ваше сообщение"></textarea> 
					<div class="submit_button">
						<input type="submit" name="submit" id="submit" value="Ответить" class="submit_button" />
					</div>				
				<input type="hidden" name="id_user_to" value="<? print $user_id; ?>"/> 	
			</form>
		</div>
		<div id="test"></div>
		<div class="menu_footer">
			<table class="menu_header">
				<tr class="menu_header">
					<td class="menu_footer_chbox"><div class='mainchbox'><input type='checkbox' name='cb[]' id='maincbox'/></div><label for="maincbox" class='chbox_label'>Выделить все</label></td>
					<td class="menu_footer"><div class="menu_item" id="delete_button">Удалить</div></td>
					<td class="menu_footer"><div class="menu_item" onclick='MarkMessagesAsSpam()'>Спам</div></td>
					<td class="menu_footer">&nbsp;</td>
					<td class="menu_footer">&nbsp;</td>					
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