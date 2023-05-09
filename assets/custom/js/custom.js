function customLoading(action, message="Please Wait..."){

    if($("#customLoading").length == 0) {

      html =   '<div id="customLoading" class="customLoading hide">';
      html +=   '<div class="customLoadingContainer"><img src="' + imageLoading + '" alt="" /><h4 class="text-white m-0">'+message+'</h4></div</div>';
      $("body").append(html);
    }

    if(action == "show"){
      $("#customLoading").removeClass('hide');
      
      setTimeout(function() {
        $("#customLoading").addClass('hide');
      }, 120000);
    }
    else{
          $("#customLoading").addClass('hide');
    }

  }

function customNotif(title, message, type, time=3500){

    $.toast({
      heading: title,
      text: message,
      position: 'top-right',
      loaderBg:'#ff6849',
      icon: type,
      hideAfter: time,
      stack: 6
    });

}

function formatNumber(nStr){
  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  x2 = x.length > 1 ? '.' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
  x1 = x1.replace(rgx, '$1' + '.' + '$2');
  }
  return x1 + x2;
}

function generateRandomKey(length) {
  let total            = length-1;
  let result           = '';
  let characters       = 'abcdefghijklmnopqrstuvwxyz0123456789';
  let charactersLength = characters.length;
  for (i = 0; i < total; i++ ) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return "k" + result;
}

function customNotifFUllWidth(title, message, type, time=2500){
	
	if($("#exampleTopFullWidth").length == 0) {
		html =   '<a class="btn btn-primary btn-block" id="exampleTopFullWidth" data-plugin="toastr"';
		html +=   'data-container-id="toast-topFullWidth"';
		html +=   'data-position-class="toast-top-full-width"';
		html +=   'data-show-method="slideDown"';
		html +=   'data-close-button="true"';
		html +=   'href="javascript:void(0)" role="role">Top Full Width</a>';
		$("body").append(html);
	}

  element = $('#exampleTopFullWidth');

  element.data('message', "<strong>" + title + "</strong> " + message)
  element.data('icon-class', "toast-" + type);
  element.data('time-out', time);

  element.trigger('click');

}

function deleteData(link){

  $('#button-delete').on( 'click', function () {

      $.ajax({
        url       : baseURL + link + id_delete,
        type      : 'DELETE',
        beforeSend  : function(){
                        customLoading('show');
                      },
        dataType : 'json',
        success : function(result){
          id_delete = 0;
          $("#modal-delete").modal('hide');
          customLoading('hide');

          if (result.status == true) {
            table.ajax.reload(null, false);
            customNotif('Success', result.messages, 'success');
          } else {
            customNotif('Failed', result.messages, 'error');
          }
        }
      });
  });
}

function openLink(link){
    win = window.open(link, '_blank');
    win.focus();
}


function data_table(ajaxData, json, table="table_data"){
  Pace.track(function(){
    $('#'+table).DataTable({
      "serverSide"      : true,
      "searchDelay"      : 900,
      "processing"      : true,
      "ajax"            : ajaxData,
      "language"        : {
                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
                            "infoEmpty"   : "Data Kosong",
                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
                            "search"      : "_INPUT_"
                          },
      "columns"         : json,
      "pageLength"      : 10,
      "ordering"        : false,
      "scrollY"         : 480,
      "scrollCollapse"  : true,
      "scrollX"         : true,
      "autoWidth"       : true,
      "bAutoWidth"      : true
    });
  });

  $('input[type="search"]').attr('placeholder','Search here...').addClass('form-control input-sm m-0');

}


function createCookie(name,value,days=1) {
  if (days) {
    var date = new Date();
      date.setTime(date.getTime()+(24*60*60*1000));
      var expires = "; expires="+date.toUTCString();
  }
  else var expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
      var c = ca[i];
      while (c.charAt(0)==' ') c = c.substring(1,c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

function eraseCookie(name) {
  createCookie(name,"",-1);
}

function open_url(url, string) {
  $.ajax({
      url   : baseURL + 'api-enc',
      data  : {string: string},
      type  : "POST",
      dataType: "json",
      success : function(result){
        if(result.status == true){
          $(location).attr('href', baseURL + url + result.data);
        }
      }
  });
}