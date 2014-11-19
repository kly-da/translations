<?
  include('../mod_db.php');
  include('../mod_auth.php');

  $title = "Ввод новости";
  include('../header.php');
?>

  <div class="content" style="border: 0px;">
    <form action="app/write_new.php" method="post">
			<p>Заголовок новости: <input name="tetle" type="text"></p>
			<p>Текст новости: <br /><textarea name="message" cols="30" rows="5"></textarea></p>
			<p>Автор: <br /><input name="author" type="text"></p>
			<p><input type='submit' value='Отпубликовать'></p>
		</form>
  </div>
  <div class="user">
    <div class="middle_text">Пользователь</div>
  </div>
  <div style="clear:right;"/></div>
  <div class="news">
    <div class="middle_text">Новости</div>
  </div>

<? include('../footer.php');?>