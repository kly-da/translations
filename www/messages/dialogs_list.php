<?
	include('../mod_db.php');
	include('../mod_auth.php');
	include('../code/text_format.php');
?>
	<link rel="stylesheet" type="text/css" href="/styles/message_dialogs_list.css">
<?
	$title = 'Диалоги';
	include('../header.php');
?>
<script>
	function loadDialogs()  
	{  		
		$.ajax({  
			type: "POST",
			url: "../ajax/dialogs_list.php",  
			cache: false,  
			success: function(html){  
				$("#dialogs_content").html(html);  
			}  
		});	
	} 
	
	function DeleteDialog() {
		var cbx = document.getElementById("dialogs_content").getElementsByTagName("input"), mas = [],el,input_item;
		for (i=0; i < cbx.length; i++) {			
			if (cbx[i].type == "checkbox" && cbx[i].checked) {
				var num = cbx[i].getAttribute("input-id");
				mas.push(num);
				el = document.getElementById("item_"+num);
				el.style.display = "none";
			}
		}
		if (mas.length != 0) 
		{
			$.ajax({  
			type: "POST",
			url: "deleting.php", 
			data: {mas:mas,from:"dialogs_list"},			
			cache: false,  
			//success: function(html){  
			//	$("#test").html(html);  
			//}  
			}); 
		}
	};
	
	$(document).ready(function(){
		loadDialogs();
		intervalID = setInterval('loadDialogs()',5000);
	});
	
	$(document).ready(function(){
		$("#maincbox").click( function() { 
			if($('#maincbox').attr('checked')){ 
				$('.chbox').attr('checked', true); 
			} else {
				$('.chbox').attr('checked', false); 
			}
		});	
		
		$("#delete_button").click( function() { 
			DeleteDialog();
		});
		
		$('#dialogs_content').on('click', '.dialogs_data', function(event) {
			var $num = $(this).attr('data-id');
			window.location = "dialog.php?mode=0&user_id="+$num;
		});	
	});
</script>

 <div class="content">
		<div class="menu_header">
			<table class="menu_header">
				<tr class="menu_header">
					<td class="menu_header"><div id="dlgs_label" class="menu_item" onclick="location.href='index.php';">Диалоги</div> </td>
					<td class="menu_header"><div id="dlg_label" class="menu_item"></div> </td>
					<td class="menu_header">&nbsp;</td>
					<td class="menu_header"><div class="menu_item" onclick="location.href='write_message.php?mode=0';">Написать письмо</div></td>
				</tr>			
			</table>
		</div>
		
		<div id="dialogs_content" class="dialogs_content"></div>
	<div id="test"></div>
		<div class="menu_footer">
			<table class="menu_header">
				<tr class="menu_header">
					<td class="menu_footer_chbox"><div class='mainchbox'><input type='checkbox' name='cb[]' id='maincbox'/></div><label for="maincbox" class='chbox_label'>Выделить все</label></td>
					<td class="menu_footer"><div class="menu_item" id="delete_button">Удалить</div></td>
					<td class="menu_footer"><div class="menu_item">&nbsp;</div></td>
					<td class="menu_footer"><div class="menu_item" onclick="location.href='./inbox.php';">В режим сообщений</div></td>
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