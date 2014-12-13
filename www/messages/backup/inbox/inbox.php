<?
  include('../../mod_db.php');
  include('../../mod_auth.php');

  // $conn - соединение с базой данных
  // $user - информация о текущем пользователе
  
  //Код компонента - здесь

  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/message_index.css">
<?}

  $title = "Входящие сообщения";
  include('../../header.php');
 
?>
<script>  
	var intervalID;

	function SetMessagesCount(){
		$.ajax({  
			url: "messages_count2.php",  
			cache: false,  
			success: function(html){  
				$("#inbox_label").html(html);  
			}  
		});	
	}
	
	function showInMessages()  
	{  		
		SetMessagesCount();		
		$.ajax({  
			url: "ajax_in_messages.php",  
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
		$.ajax({  
			type: "POST",
			url: "../open_message.php", 
			data: "id="+el.id,			
			cache: false,  
			success: function(html){  
				$("#message_content").html(html);  
			}  
		});  			
		clearInterval(intervalID);
		SetMessagesCount();
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
			url: "../delete_message.php", 
			data: {mas:mas,inbox:true},			
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
		showInMessages();
		intervalID = setInterval('showInMessages()',100000);
		//SetMessagesCount();	

		$("#maincbox").click( function() { // при клике по главному чекбоксу
            if($('#maincbox').attr('checked')){ // проверяем его значение
                $('.chbox').attr('checked', true); // если чекбокс отмечен, отмечаем все чекбоксы
            } else {
                $('.chbox').attr('checked', false); // если чекбокс не отмечен, снимаем отметку со всех чекбоксов
            }
		});
		
	});  
</script>

  <div class="content">
	<div class="menu_header">
		<table class="menu_header">
			<tr class="menu_header">
				<td class="menu_header_current"><div id="inbox_label" class="menu_item" onclick="location.href='../inbox/';">Входящие</div> </td>
				<td class="menu_header"><div class="menu_item" onclick="location.href='../sent/';">Отправленные</a></td>
				<td class="menu_header">&nbsp;</td>
				<td class="menu_header"><div class="menu_item" onclick="location.href='../write_message.php';">Написать письмо<div></td>
			</tr>			
		</table>
	</div>
	<div id="message_content" class="message_area" >
	</div>
	<!--<div id="test"> 
	</div> -->
	<div class="menu_footer">
		<table class="menu_header">
			<tr class="menu_header">
				<td class="menu_footer_chbox"><div class='mainchbox'><input type='checkbox' name='cb[]' id='maincbox'/></div><label for="maincbox" class='chbox_label'>Выделить все</label></td>
				<td class="menu_footer">&nbsp;</td>
				<td class="menu_footer">&nbsp;</td>
				<td class="menu_footer"><div class="menu_item" onclick='DeleteMessages()'>Удалить</div></td>
				<td class="menu_footer"><div class="menu_item" onclick='MarkMessagesAsSpam()'>Спам<div></td>
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
  
 	
<? include('../../footer.php');?>