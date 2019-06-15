$('a[href="#login"]').on('click', function(){
  $(this).attr('disabled', 'disabled');
  var missing = false;
  $('#form').find('input').each(function(){
      if($(this).prop('required')){
          if($(this).val() == ''){
              var placeholder = $(this).attr('placeholder');
              $.notify({
                  icon: 'glyphicon glyphicon-info-sign',
                  message: placeholder +' field could not be empty.'
              }, {
                  type: 'warning',
                  delay: 1000,
                  timer: 500,
                  placement: {
                    from: 'top',
                    align: 'center'
                  }
              });
              $(this).focus();
              missing = true;
              return false;
          }
      }
  });

  $(this).removeAttr('disabled');
  if(missing){
      return;
  }

  $.ajax({
    type: 'POST',
    url: baseurl + 'login/post/',
    data: $('#form').serialize(),
    dataType: 'json',
    success: function(response){
      if(response.result){
        window.location.replace(response.target);
      } else{
        var csrf = response.csrf;
        $('input[name="'+ csrf.name +'"]').val(csrf.hash);

        $('#form input[name="password"]').val('');
        $('#form input[name="password"]').focus();
        $.notify({
            icon: "glyphicon glyphicon-warning-sign",
            message: response.msg
        }, {
            type: 'danger',
            delay: 3000,
            timer: 1000,
            placement: {
              from: 'top',
              align: 'center'
            }
        });
      }
    }
  });
});
