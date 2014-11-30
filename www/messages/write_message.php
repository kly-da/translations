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
?>



<div class="content">
	<div class="menu_header">
		<table class="menu_header">
			<tr class="menu_header">
				<td class="menu_header">			<div class="menu_item" onclick="location.href='./inbox/';">Входящие</div> 					</td>
				<td class="menu_header">			<div class="menu_item" onclick="location.href='./sent/';">Отправленные</a>					</td>
				<td class="menu_header">			&nbsp;																						</td>
				<td class="menu_header_current">			<div class="menu_item">Написать письмо<div>													</td>
			</tr>
		</table>
	</div>
	<div id="message_content" class="message_area" >
		<form id='answer_form' name='answer_form' method='post' action='send_message.php'>
				</br>
				<span class="text">Имя получателя <input type='text' name='recipient' id='recipient' class='recipient'/></span>
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