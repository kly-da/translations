<?php
  $sapi_type = php_sapi_name();
  if (substr($sapi_type, 0, 3) == 'cgi')
      header("Status: 404 Not Found");
  else
      header("HTTP/1.1 404 Not Found");

  include('../mod_db.php');
  include('../mod_auth.php');

  $title = "Ошибка";
  include('../header.php');
?>

  <div class="content">
    <h1>404 – Not Found</h1>
    По данному запросу ничего не найдено
  </div>
  <div class="user">
    <div class="middle_text">Пользователь</div>
  </div>
  <div style="clear:right;"/></div>
  <div class="news">
    <div class="middle_text">Новости</div>
  </div>

<? include('../footer.php');?>