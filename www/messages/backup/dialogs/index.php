<?
  include('../../mod_db.php');
  include('../../mod_auth.php');

  // $conn - соединение с базой данных
  // $user - информация о текущем пользователе
  
  //Код компонента - здесь

  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/message_dialogs_index.css">
<?}

  $title = "Диалоги";
  include('../../header.php');
 
  //$id=$_GET['dlgid'];
?>
<script>  
	var intervalID;

	function getParameterByName(name)
	{
	  name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
	  var regexS = "[\\?&]" + name + "=([^&#]*)";
	  var regex = new RegExp(regexS);
	  var results = regex.exec(window.location.search);
	  if(results == null)
		return "";
	  else
		return decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	
	function SetDialogCount(){
		$.ajax({  
			url: "dialogs_count.php",  
			cache: false,  
			success: function(html){  
				$("#inbox_label").html(html);  
			}  
		});	
	}
	
	function showInMessages()  
	{  		
		SetMessagesCount();	
		var dlgid=getParameterByName("dlgid");
		$.ajax({  
			type: "GET",
			url: "dialog.php",  
			data: "dlgid="+dlgid,	
			cache: false,  
			success: function(html){  
				$("#message_content").html(html);  
			}  
		});	
	}  
	
	function loadDialogs()  
	{  		
		$.ajax({  
			type: "POST",
			url: "ajax_dialogs.php",  
			//data: "dlgid="+dlgid,	
			cache: false,  
			success: function(html){  
				$("#message_content").html(html);  
			}  
		});	
	} 
	
	function scrollDialogToBottom() {
		var area = document.getElementById("message_content");
		//alert(area.scrollHeight);		
		area.scrollTop += area.scrollHeight;
		
	}
	
	function changePath(temp){
		document.location.search ="?dlgid="+temp;
	}
	
	function OpenDialog(e)  
	{  
		e = e || window.event;
		var el = e.target || e.srcElement;
		//changePath(el.id);
		$.ajax({  
			type: "POST",
			url: "dialog.php", 
			data: "dlgid="+el.id,			
			cache: false,  
			success: function(html){  
				$("#message_content").html(html); 
				scrollDialogToBottom();
				changePath(el.id);
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
		//DeleteMessages();
		//далее код наказания злостного спамера
		alert("Злостный спамер будет наказан!")
	}
	
	function click23() {  	
		var cbx = document.getElementById("item_2");
		var area = document.getElementById("message_content");
		//window.scrollBy(0,10);
		//cbx.scrollTop+= cbx.scrollHeight;
		//cbx.scrollIntoView(false);
		var a = cbx.offsetTop;
		alert(a);			
		area.scrollTop=a-25;
		

	}	
	
	$(document).ready(function(){  	
		loadDialogs();
		intervalID = setInterval('loadDialogs()',100000);
		//alert(intervalID);
		//SetMessagesCount();	
		
		//var dlgid = getParameterByName("dlgid");
		
		//if (dlgid == "") alert ("пуст"); else alert (dlgid);
		document.getElementById("dlgs_label").style.backgroundColor= '#3366CC';
		document.getElementById("dlgs_label").style.fontSize= 'large';
		
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
					<td class="menu_header"><div id="dlgs_label" class="menu_item" onclick="location.href='index.php';">Диалоги</div> </td>
					<td class="menu_header"><div id="dlg_label" class="menu_item" ></div> </td>
					<td class="menu_header">&nbsp;</td>
					<td class="menu_header"><div class="menu_item" onclick="location.href='../write_message.php';">Написать письмо</div></td>
				</tr>			
			</table>
		</div>
		
		<div id="message_content" class="message_area"> </div>

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
  
 	
<? include('../../footer.php');?>