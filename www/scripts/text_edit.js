$(document).ready(function(){
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
});