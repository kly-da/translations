<?
  $title_full_replace = true;
  $title = "Система коллективных переводов";
  include('header.php');
?>

  <div class="content" style="border: 0px;">
    <h1>Система коллективных переводов</h1>
    <br>
    <h2>Активные страницы. Служебные</h2>
    <a href="login_as.php">залогиниться</a><br><br><br>
    <h2>Текст</h2>
    Просмотр страницы перевода: <a href="text/view.php?id=1">номер 1</a>, <a href="text/view.php?id=2">номер 2</a>, <a href="text/view.php?id=3">номер 3</a><br>
    <small>Активны страницы редактирования перевода, списка участников, порядка глав, просмотра главы</small><br><br>
    <a href="text/add.php">Добавить новый перевод</a><br><br><br>
    <h2>Администрирование</h2>
    <a href="admin/complaint.php">Список жалоб и запросов</a><br><br><br>
    <h2>Сообщения</h2>
    <a href="messages/">Список</a><br>
    <small>Работает приём и отправка сообщений</small><br><br>
    <h2>Новости</h2>
    <a href="news/adminisrating.php">Создание</a><br><br><br>
  </div>
  <div class="user">
    <div class="middle_text">Пользователь</div>
  </div>
  <div style="clear:right;"/></div>
  <div class="news">
    <div class="middle_text">Новости</div>
  </div>

<? include('footer.php');?>
