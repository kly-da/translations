var last_search_input = null;
var last_search_index = 0;
var search_list = null;
var id = [ 0, 0, 0 ];

function get_user_list(e) {
  var element = $(e.target);
  var input = element.val();
  if (element != last_search_input) {
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
    search_list.css('left', element.position().left);
    search_list.css('top', element.position().top + element.outerHeight(true) - 1);
  }

  if (input.length >= 3 && input.length < 150) {
    $.ajax({
      type: "POST",
      url: "search.php",
      data: "q=" + input,
      dataType: "html",
      cache: false,
      success: function(data) {
        search_list.empty();
        var json = jQuery.parseJSON(data);
        if (json.users.length == 0)
          search_list.append("<li class=\"not_found\">Пользователь не найден</li>");
        else
          json.users.forEach(function(e) {
            search_list.append("<li id=\"" + e.id + "\">" + e.name + "</li>");
          });
        search_list.show();
      }
    });
  } else {
    search_list.hide();
  }
}

function add_user(e) {
  var index = e.data.button_index;
  var input = id[index];
  if (input > 0) {
    var status = $(e.target).next('.ajax_status');
    var component;
    switch (index) {
      case 0:
        component = $('#translators');
        break
      case 1:
        component = $('#moderators');
        break
      case 2:
        component = $('#administrators');
        break
      default:
        return;
    }
    $.ajax({
      type: "POST",
      url: "search.php",
      data: "q=" + input,
      dataType: "html",
      cache: false,
      beforeSend: function() {
        status.html('Сохранение...');
      },
      error: function() {
        status.html('Ошибка запроса');
      },
      success: function(data) {
        status.html('Пользователь добавлен');
        var json = jQuery.parseJSON(data);
        component.append("<option value=\"" + json.users[2].id + "\">" + json.users[2].name + "</option>");
      }
    });
  }
}

function delete_user(e) {
  var index = e.data.button_index;
  var component;
  switch (index) {
    case 0:
      component = $('#translators');
      break
    case 1:
      component = $('#moderators');
      break
    case 2:
      component = $('#administrators');
      break
    default:
      return;
  }
  var input = component.val();
  var status = $(e.target).next('.ajax_status');
  $.ajax({
    type: "POST",
    url: "search.php",
    data: "q=" + input,
    dataType: "html",
    cache: false,
    beforeSend: function() {
      status.html('Сохранение...');
    },
    error: function() {
      status.html('Ошибка запроса');
    },
    success: function(data) {
      status.html("Удалено");
      component.find('option[value="' + input + '"]').remove();
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
});