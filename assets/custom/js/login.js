let loginURL = BASE_URL + BASH_CODE;   
if(redirect != "dashboard"){
  let date_new = new Date();
  date_new.setTime(date_new.getTime() + (60 * 1000));
  let expires_new = "; expires="+date_new.toUTCString();
  document.cookie = "redirect_page="+redirect+expires_new+"; path=/";
}else{
  if(readCookie('redirect_page')){
    redirect = readCookie('redirect_page');
  }
}

$('#loginform').on('submit', function(e) {
    if (!e.isDefaultPrevented()) {
      form   = $('#loginform');
      $.ajax({
        url  : loginURL,
        type     : "POST",
        dataType : "json", 
        data     : form.serialize(),
        beforeSend  : function(){
                        customLoading('show');
                      },
        success : function(result){
            if (result.status == true) {
              $('#errorMsg').addClass('hide');
              window.location.href = BASE_URL + redirect;
              customLoading('hide');
            } else {
              customLoading('hide');
              $('#errorMsg').removeClass('hide');
              $('#errorMsg').html(result.description);
              form[0].reset();
              $('#inputIdentity').trigger('focus');
            }
        },
        error: function(jqXHR, exception) {
            if (jqXHR.status === 503 ) {
                errorMsg = 'Sorry too many reqeust';
            } else if (jqXHR.status == 404) {
                errorMsg = 'Requested page not found. [404]';
                location.reload();
            } else if (jqXHR.status == 500) {
                errorMsg = 'Internal Server Error [500].';
                location.reload();
            } else if (exception === 'parsererror') {
                errorMsg = 'Requested JSON parse failed.';
                location.reload();
            } else if (exception === 'timeout') {
                errorMsg = 'Time out error.';
            } else if (exception === 'abort') {
                errorMsg = 'Ajax request aborted.';
            } else {
                errorMsg = 'Failed to login';
            }

            customLoading('hide');
            $('#errorMsg').removeClass('hide');
            $('#errorMsg').html(errorMsg);
            form[0].reset();
            $('#inputIdentity').trigger('focus');
        }
      }); 
      return false;
    }
    e.preventDefault();
  });