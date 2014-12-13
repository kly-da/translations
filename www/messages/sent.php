<?
  include('../mod_db.php');
  include('../mod_auth.php');
  
  // $conn - соединение с базой данных
  // $user - информация о текущем пользователе
  
  //Код компонента - здесь

  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/message_inbox.css">
<?}

  $title = "Исходящие сообщения";
  include('../header.php');
 
?>
<script>  
	var intervalID,intervalIDCount;

	function SetMessagesCount(){
		$.ajax({  
			url: "messages_count.php",  
			cache: false,  
			success: function(html){  
				$("#label_1").html(html);  
			}  
		});	
	}	
	
	function showOutMessages()  
	{  
		$.ajax({  
			url: "../ajax/sent.php",  
			cache: false,  
			success: function(html){  
				$("#messages_content").html(html);  
			}  
		});  
	} 

	function DeleteMessages() {
		var cbx = document.getElementById("message_content").getElementsByTagName("input"), mas = [],mes;
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
			data: {mas:mas,from:"sent"},			
			cache: false,  
			//success: function(html){  
			//	$("#test").html(html);  
			//}  
		}); 
		};
	}
	
	function MarkMessagesAsSpam() {
		DeleteMessages();
		//далее код наказания злостного спамера
		alert("Злостный спамер будет наказан!")
	}
   
	$(document).ready(function(){  		
	
		showOutMessages();
		
		SetMessagesCount();	
		
		intervalID = setInterval('showOutMessages()',15000);
		
		setInterval('SetMessagesCount()',10000);
	}); 
	
	$(document).ready(function(){  
		$("#maincbox").click( function() { // при клике по главному чекбоксу
            if($('#maincbox').attr('checked')){ // проверяем его значение
                $('.chbox').attr('checked', true); // если чекбокс отмечен, отмечаем все чекбоксы
            } else {
                $('.chbox').attr('checked', false); // если чекбокс не отмечен, снимаем отметку со всех чекбоксов
            }
		});
		
		$('#messages_content').on('click', '.messages_data', function(event) {
			var userid = $(this).attr('user-id');
			var msgid  = $(this).attr('id');
			window.location = "dialog.php?mode=1&user_id="+userid+"&msgid="+msgid;
		});	
	}); 
	
</script>

  <div class="content">
	<div class="menu_header">
		<table class="menu_header">
			<tr class="menu_header">
				<td class="menu_header"><div id="label_1" class="menu_item" onclick="location.href='./inbox.php';">Входящие</div> </td>
				<td class="menu_header_current"><div class="menu_item" onclick="location.href='./sent.php';">Отправленные</a></td>
				<td class="menu_header">&nbsp;</td>
				<td class="menu_header"><div class="menu_item" onclick="location.href='./write_message.php';">Написать письмо<div></td>
			</tr>
		</table>
	</div>
	<div id="messages_content" class="messages_content" >
	</div>
 	<div class="menu_footer">
		<table class="menu_header">
			<tr class="menu_header">
				<td class="menu_footer_chbox"><div class='mainchbox'><input type='checkbox' name='cb[]' id='maincbox'/></div><label for="maincbox" class='chbox_label'>Выделить все</label></td>
				<td class="menu_footer"><div class="menu_item" onclick='DeleteMessages()'>Удалить</div></td>
				<td class="menu_footer">&nbsp;</td>
				<td class="menu_footer"><div class="menu_item" onclick="location.href='./dialogs_list.php';">В режим диалогов</div></td>
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