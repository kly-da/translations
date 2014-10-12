<?
  include('../mod_db.php');
  include('../mod_auth.php');

  // $conn - соединение с базой данных
  // $user - информация о текущем пользователе

  //Код компонента - здесь

  function additionalPageHeader() {
?>
  <link rel="stylesheet" type="text/css" href="/styles/text_main.css">
  <script type="text/javascript" src="/scripts/text_view.js"></script>
<?}

  $title = "Белое солнце пустыни";
  include('../header.php');
?>

  <div class="content" style="border: 0px;">
    <div>
      <div id="text_status" class="closed">закрытый</div>
      <div id="title_header">Белое солнце пустыни</div>
      <div id="original_title">White Sun of the Desert</div>
      <div style="clear: both;"></div>
    </div>
    <div>
      <table id="information">
        <tr>
          <td class="info_parameter">Перевод</td>
          <td class="info_value">английский &rarr; русский</td>
        </tr>
        <tr>
          <td class="info_parameter">Тип</td>
          <td class="info_value">Книга</td>
        </tr>
        <tr>
          <td class="info_parameter">Автор</td>
          <td class="info_value">Ежов В.И. <span class="light_gray">(V. Yezhov)</span></td>
        </tr>
        <tr>
          <td class="info_parameter">ISBN</td>
          <td class="info_value">5-264-00694-6</td>
        </tr>
        <tr>
          <td class="info_parameter">Описание</td>
          <td class="info_value">
            <p>
              Действие книги происходит приблизительно в начале 1920-х годов на восточном берегу Каспийского моря. Красноармеец Фёдор Иванович Сухов (Анатолий Кузнецов) возвращается домой через пустыню. По пути он встречает местного жителя Саида (Спартак Мишулин), который закопан по шею в песок.</p><p>Оказывается, на мучительную смерть Саида оставил бандит Джавдет, который убил его отца и забрал всё имущество, а самого Саида связал и закопал в песок.
            </p>
          </td>
        </tr>
        <tr>
          <td class="info_parameter">Создатель</td>
          <td class="info_value"><a href="/user/view.php?id=1">Администратор</a></td>
        </tr>
        <tr>
          <td class="info_parameter">Ваша роль</td>
          <td class="info_value">Модератор перевода</td>
        </tr>
      </table>
      <a id="text_complaint" class="complaint" href="111">Пожаловаться на перевод</a>
      <ul class="text_menu">
        <li class="text_menu_component"><img src="/images/icon-menu-small.png" class="image">Управление
          <ul>
            <li><a href="add_chapter.php">Добавить главу</a></li>
            <li><a href="add_chapter.php">Редактировать настройки</a></li>
            <li><a href="add_chapter.php">Запрос на публикацию</a></li>
            <li><a href="add_chapter.php">Удалить</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <div>
      <div class="chapter_header">Главы</div>
      <ul class="chapter_menu">
        <li><a href="add_chapter.php">Перезагрузить главу</a></li>
        <li><a href="add_chapter.php">Установить проверку</a></li>
        <li><a href="add_chapter.php">Завершить проверку</a></li>
        <li><a href="add_chapter.php">Подтвердить изменения</a></li>
        <li><a href="add_chapter.php">Удалить</a></li>
      </ul>
      <table id="chapter_table">
        <col class="title"/>
        <col/>
        <col/>
        <col/>
        <col/>
        <col/>
        <tr>
          <th class="title">Название</th>
          <th class="left">Переведено</th>
          <th>Изменено</th>
          <th>Статус</th>
          <th>&nbsp;</th>
          <th>&nbsp;</th>
        </tr>
        <tr>
          <td class="title"><a href="343">Вступление</a></td>
          <td class="left">350/350 <span class="light_gray">(100%)</span></td>
          <td>20 сен</td>
          <td>завершено</td>
          <td><a href="34">скачать</a></td>
          <td><a class="show_menu" cid="1">меню</a></td>
        </tr>
        <tr>
          <td class="title"><a href="343">Завязка</a></td>
          <td class="left">35000/35000 <span class="light_gray">(100%)</span></td>
          <td>25 сен</td>
          <td>завершено</td>
          <td><a href="34">скачать</a><br><a href="34">черновик</a></td>
          <td><a class="show_menu" cid="2">меню</a></td>
        </tr>
        <tr>
          <td class="title"><a href="343">Развитие</a></td>
          <td class="left">1300/1300 <span class="light_gray">(100%)</span></td>
          <td>сегодня</td>
          <td>проверка</td>
          <td></td>
          <td><a class="show_menu" cid="3">меню</a></td>
        </tr>
        <tr>
          <td class="title"><a href="343">Кульминация</a></td>
          <td class="left">800/1921 <span class="light_gray">(42%)</span></td>
          <td>сегодня</td>
          <td>в процессе</td>
          <td></td>
          <td><a class="show_menu" cid="4">меню</a></td>
        </tr>
        <tr>
          <td class="title"><a href="343">Развязка</a></td>
          <td class="left">0/821 <span class="light_gray">(0%)</span></td>
          <td>нет</td>
          <td>в процессе</td>
          <td></td>
          <td><a class="show_menu" cid="5">меню</a></td>
        </tr>
      </table>
    </div>
    <div>
      <a href="11">Скачать всё</a>
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