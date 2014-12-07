<?
  include('../mod_db.php');
  include('../mod_auth.php');
  
  // $conn - соединение с базой данных
  // $user - информация о текущем пользователе
  
  //Код компонента - здесь

  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/message_index.css">
<?}

  $title = "Сообщения";
  include('../header.php');
 
?>
<script>  
	var intervalID;
	
	function showIncomingMessages()  
	{  	
		$.ajax({  
			url: "ajax_in_messages.php",  
			cache: false,  
			success: function(html){  
				$("#message_content").html(html);  
			}  
		});

		
	}  
	
	function showSentMessages()  
	{  
		$.ajax({  
			url: "sent_messages.php",  
			cache: false,  
			success: function(html){  
				$("#message_content").html(html);  
			}  
		});  
	} 
	
	function OpenMessage(e)  
	{  
		e = e || window.event;
		var el = e.target || e.srcElement;
		//alert(el.id);	
		$.ajax({  
			type: "POST",
			url: "write_message.php", 
			data: "id="+el.id,			
			cache: false,  
			success: function(html){  
				$("#message_content").html(html);  
			}  
		});  
		clearInterval(intervalID);
	} 
	
	function showIncMessagesAndSetInterval() {
		showIncomingMessages();
		intervalID = setInterval('showIncomingMessages()',5000); 	
	}
	function showSentMessagesAndUnsetInterval() {
		clearInterval(intervalID);
		showSentMessages();
	}
  
	$(document).ready(function(){  				
		showIncMessagesAndSetInterval();
	});  
</script>

  <div class="content">
	<div class="menu_header">
		<table class="menu_header">
			<tr class="menu_header">
				<td class="menu_header_current"><div class="menu_item">Входящие</div> </td>
				<td class="menu_header"><div class="menu_item" onclick="location.href='out_messages.php';">Отправленные</a></td>
				<td class="menu_header">&nbsp;</td>
				<td class="menu_header"><div class="menu_item" onclick="location.href='write_message.php';">Написать письмо<div></td>
			</tr>
		</table>
	</div>
	<div id="message_content" class="message_area" >
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