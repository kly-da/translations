var last_search_input = null;
var last_search_index = 0;
var text_id = 0;
var search_list = null;
var id = [ 0, 0, 0 ];

function position_element() {
  search_list.css('left', last_search_input.position().left);
  search_list.css('top', last_search_input.position().top + last_search_input.outerHeight(true) - 1);
}

function get_user_list(e) {
  var element = $(e.target);
  var input = element.val();
  if (element[0] != last_search_input[0]) {
    last_search_input = element;
    switch (element.attr('id')) {
      case "moderator_search":
        last_search_index = 1;
        break
      case "administrator_search":
        last_search_index = 2;
        break
      default:
        last_search_index = 0;
    }
    position_element();
  }

  id[last_search_index] = 0;

  if (input.length >= 3 && input.length < 150) {
    $.ajax({
      type: "POST",
      url: "modules/user_search.php",
      data: "q=" + input,
      dataType: "json",
      cache: false,
      success: function(data) {
        search_list.empty();
        if (data.users.length == 0)
          search_list.append("<li class=\"not_found\">Пользователь не найден</li>");
        else
          data.users.forEach(function(e) {
            search_list.append("<li id=\"" + e.user_id + "\">" + e.name + "</li>");
          });
        search_list.show();
      }
    });
  } else {
    search_list.hide();
  }
}

function getComponent(index) {
  var component = new Object();
  switch (index) {
    case 0:
      component.element = $('#translators');
      component.list_name = "translators";
      break
    case 1:
      component.element = $('#moderators');
      component.list_name = "moderators";
      break
    case 2:
      component.element = $('#administrators');
      component.list_name = "administrators";
      break
    default:
      return null;
  }
  return component;
}

function add_user(e) {
  var index = e.data.button_index;
  var input = id[index];
  if (input > 0) {
    var status = $(e.target).next('.ajax_status');
    var component = getComponent(index);
    if (component == null)
      return;

    $.ajax({
      type: "POST",
      url: "modules/user_edit.php",
      data: "com=add&list=" + component.list_name + "&text_id=" + text_id + "&user_id=" + input,
      dataType: "json",
      cache: false,
      beforeSend: function() {
        status.html('Сохранение...');
      },
      error: function() {
        status.html('Ошибка запроса');
      },
      success: function(data) {
        switch (data.status) {
          case "ok":
            status.html('Пользователь добавлен');
            component.element.append("<option value=\"" + data.user.id + "\">" + data.user.name + "</option>");
            break
          case "fail":
            status.html('Ошибка запроса');
            break
          case "user_null":
            status.html('Пользователь не выбран');
            break
          case "already":
            status.html('Пользователь уже добавлен');
            break
          case "list":
            status.html('Пользователь добавлен');
            $('option[value="' + data.user.id + '"]').remove();
            component.element.append("<option value=\"" + data.user.id + "\">" + data.user.name + "</option>");
            break
          default:
            status.html('');
        }
      }
    });
  } else {
    $(e.target).next('.ajax_status').html('');
  }
}

function delete_user(e) {
  var component = getComponent(e.data.button_index);
  if (component == null)
    return;

  var input = component.element.val();
  var status = $(e.target).next('.ajax_status');
  if (input == null || input <= 0) {
    status.html('');
    return;
  }

  $.ajax({
    type: "POST",
    url: "modules/user_edit.php",
    data: "com=delete&list=" + component.list_name + "&text_id=" + text_id + "&user_id=" + input,
    dataType: "json",
    cache: false,
    beforeSend: function() {
      status.html('Сохранение...');
    },
    error: function() {
      status.html('Ошибка запроса');
    },
    success: function(data) {
      switch (data.status) {
        case "ok":
          status.html("Удалено");
          component.element.find('option[value="' + input + '"]').remove();
          break
        case "fail":
          status.html('Ошибка запроса');
          break
        case "user_null":
          status.html('Пользователь не выбран');
          break
        case "not_found":
          status.html('Пользователь не найден');
          break
        default:
          status.html('');
      }
    }
  });
}

$(document).ready(function(){
  search_list = $('.search_list');

  $.validator.addMethod("time", function(value, element) {
    return this.optional(element) || /^([0-9]{1,3}:)?[0-5]?[0-9]:[0-5][0-9]$/i.test(value);
  }, "Please enter a valid time.");
  $("#login_form").validate({
    errorClass: "form_error",
    errorElement: "span",
    highlight: "",
    unhighlight: "",
    rules:{
      title:{
        required: true,
        minlength: 4,
        maxlength: 80,
      },
      original_title:{
        required: true,
        minlength: 4,
        maxlength: 80,
      },
      isbn:{
        maxlength: 20,
      },
      author:{
        maxlength: 100,
      },
      native_author:{
        maxlength: 100,
      },
      release_date:{
        number: true,
      },
      duration: "time",
    },
    messages:{
      title:{
        required: "* Поле обязательно для заполнения",
        minlength: "* Слишком короткое название",
        maxlength: "* Слишком длинное название",
      },
      original_title:{
        required: "* Поле обязательно для заполнения",
        minlength: "* Слишком короткое название",
        maxlength: "* Слишком длинное название",
      },
      isbn: "* Слишком длинное число",
      author: "* Слишком длинное имя",
      native_author: "* Слишком длинное имя",
      release_date: "* Введите число",
      duration: "* Введите продолжительность в формате [ч:]м:сс",
    }
  });

  $('.search_input').each(function() {
    $(this).bind('textchange', get_user_list);
  });

  $('.add_function').each(function(index) {
    $(this).click({button_index: index}, add_user);
  });

  $('.delete_function').each(function(index) {
    $(this).click({button_index: index}, delete_user);
  });

  search_list.click(function(e) {
    var element = $(e.target);
    var element_id = element.attr("id");
    if (typeof element_id !== "undefined") {
      id[last_search_index] = element_id;
      last_search_input.val($(e.target).html());
      search_list.hide();
    }
  });

  text_id = $("input:hidden:[name='id']:first").val();

  last_search_input = $("#translator_search");
  last_search_index = 0;
  position_element();
});