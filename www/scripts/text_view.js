$(document).ready(function(){
    $('.chapter_menu').mouseleave(function(){
      $(this).hide();
    });
    $(document).on('click', function(event) {
      if (!$(event.target).closest('.chapter_menu').length) {
        $('.chapter_menu').hide();
      }
    });
    $('.show_menu').mouseleave(function(event){
      if (!$(event.relatedTarget).closest('.chapter_menu').length) {
        $('.chapter_menu').hide();
      }
    });
    $('.show_menu').mouseenter(function(){
      var obj = $('.chapter_menu');
      var offset = $(this).offset();
      var offset2 = $('.content').offset();
      obj.css('left', offset.left - offset2.left + 20);
      obj.css('top', offset.top - offset2.top + 10);

      var cid = $(this).attr('cid');
      obj.find('a').each(function(){
        $(this).attr('href', $(this).attr('href').replace(new RegExp("cid=\\d+"), "cid=" + cid));
      });

      obj.show();
    });

    var complaint = $('#text_complaint');
    var text_id = complaint.attr('text_id');
    $('#text_complaint').click(function() {
      $.ajax({
        type: "POST",
        url: "/module/make_complaint.php",
        data: "type=text&id=" + text_id,
        dataType: "json",
        error: function(e) {
          complaint.html('Ошибка отправки');
        },
        success: function(data) {
          switch (data.status) {
            case "ok":
              complaint.html("Жалоба отправлена");
              break
            case "fail":
              complaint.html('Ошибка отправки');
              break
            default:
              complaint.html('');
          }
        }
      });
    });
});