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
			url: "incoming_messages.php",  
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
	<div class="menu_head">
		<a href="#" onclick="showIncMessagesAndSetInterval()">Входящие</a>
	</div>
	<div class="menu_head">
		<a href="#" onclick="showSentMessagesAndUnsetInterval()">Отправленные</a>
	</div>
	<div class="menu_head">
		<a href="write_message.php">Написать письмо</a>
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