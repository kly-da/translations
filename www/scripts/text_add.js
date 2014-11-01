$(document).ready(function(){
  $.validator.addMethod("time", function(value, element) {
    return this.optional(element) || /^([0-9]{1,3}:)?[0-5]?[0-9]:[0-5][0-9]$/i.test(value);
  }, "Please enter a valid time.");
  $("#info_form").validate({
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

  var booktype = 0;
  var book_field = $(".book_field");
  var book_input = book_field.find("input");
  var subtitle_field = $(".subtitle_field");
  var subtitle_input = subtitle_field.find("input");
  
  switch ($("#type").val()) {
    case "1":
      subtitle_field.show();
    case "2":
      book_field.hide();
      break;
  }

  $("#type").change(function(e){
    var element = $(e.target);
    var val = parseInt(element.val());
    if (val == booktype)
      return;
    switch (val) {
      case 0:
        book_field.show();
        if (booktype == 1)
          subtitle_field.hide();
          subtitle_input.val('');
        break;
      case 1:
        subtitle_field.show();
        if (booktype == 0)
          book_field.hide();
          book_input.val('');
        break;
      case 2:
        if (booktype == 0) {
          book_field.hide();
          book_input.val('');
        }
        else if (booktype == 1) {
          subtitle_field.hide();
          subtitle_input.val('');
        }
    }
    booktype = val;
  });

});