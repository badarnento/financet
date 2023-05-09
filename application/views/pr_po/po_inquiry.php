  <div class="row mt-20">
    <div class="col-xs-6 col-sm-3 md-2 w-auto">
      <button id="btnPrint" class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-download"></i> Download</button>
    </div>
    <div class="col-xs-6 col-sm-3 md-2">
      <button id="btn-create" class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-plus"></i> Create New</button>
    </div>
  </div>

<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Filter</h5>
    </div>
  </div>
  <div class="white-box boxshadow mt-0 z-index-2 py-10 border-bottom-only-5">
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label>Directorate</label>
          <select class="form-control" id="directorate">
            <?php
              if(count($directorat) > 1):
                echo '<option value="0">--All Directorate--</option>';
              endif;
              foreach($directorat as $value): ?>
              <option value="<?= $value['ID_DIR_CODE'] ?>" data-name="<?= $value['DIRECTORAT_NAME'] ?>"><?= $value['DIRECTORAT_NAME'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>Division</label>
          <select class="form-control" id="division">
          <?php
            if($binding == false || $data_binding['division'] == false):
              echo '<option value="0">-- All Division --</option>';
            else:
            
              foreach($data_binding['division'] as $value):
                $replace = str_replace("&","|AND|", $value['DIVISION_NAME']);
          ?>
              <option value="<?= $value['ID_DIVISION'] ?>" data-name="<?= $value['DIVISION_NAME'] ?>"><?= $value['DIVISION_NAME'] ?></option>
          <?php
              endforeach; 
            endif;
          ?>
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>Unit</label>
          <select class="form-control" id="unit">
            <?php
              if($binding == false || $data_binding['unit'] == false):
                echo '<option value="0">-- All Unit --</option>';
              else:
              
                foreach($data_binding['unit'] as $value):
                  $replace = str_replace("&","|AND|", $value['UNIT_NAME']);
            ?>
                <option value="<?= $value['ID_UNIT'] ?>" data-unt="<?= $replace ?>" data-name="<?= $value['UNIT_NAME'] ?>"><?= $value['UNIT_NAME'] ?></option>
            <?php
                endforeach; 
              endif;
            ?>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label>Status</label>
          <select class="form-control" id="status">
            <option value="0">--All Status--</option>
            <?php foreach ($po_status as $key => $value): ?>
            <option value='<?= $key ?>'><?= $value ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label>Date</label>
          <div class="input-group">
            <input class="form-control input-daterange-datepicker" type="text" id="po_date" value="<?= dateFormat(strtotime('-3 days'), 'date') ?> - <?= dateFormat(time(), 'date') ?>"/>
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btnView" class="btn btn-info btn-outline border-radius-5 w-150p btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div id="tbl_search" class="col-md-12 positon-relative">
          <i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
          <input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
      </div>
      <div class="col-md-12">
          <table id="table_data" class="table table-hover small display table-striped table-responsive dataTable w-full">
            <thead>
              <tr>
                <th>Directorate</th>
                <th>Division</th>
                <th>Unit</th>
                <th>PR Number</th>
                <th>PO Number</th>
                <th>PO Name</th>
                <th>PO Date</th>
                <th>Currency</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>



<div id="modal-cancel" class="modal fade" tabindex="-3" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-cancel-label">Are you sure to Cancel this PO?</h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group m-b-10">
                  <label for="approval_remark" class="control-label">Cancellation Reasons <span class="pull-right">:</span></label>
                  <textarea class="form-control" id="cancel_desc" rows="2" maxlength="150" placeholder="Optional"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group m-b-10">
                  <div class="attachment_group">
                    <label class="control-label">Attach File <span class="pull-right">:</span></label>
                      <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                          <div id="fileinput" class="form-control input-sm" data-trigger="fileinput"> <i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div><span class="input-group-addon btn btn-default btn-outline btn-file"> <span class="fileinput-new">Select file</span> <span class="fileinput-exists">Change</span>
                          <input id="attachment" type="file" data-name="pr" name="attachment" accept=".pdf" required="required"> </span> <a id="fileinput_remove" href="javascript:void(0)" class="input-group-addon btn btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
                      </div>
                        <div class="progress progress-lg d-none">
                            <div id="progress" class="progress-bar progress-bar-info active progress-bar-striped" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" role="progressbar"></div>
                        </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger w-100p border-radius-5 btn-outline waves-effect" id="btn-cancel"><i class="fa fa-times-circle"></i> Cancel</button>
          <button type="button" class="btn btn-success w-100p border-radius-5 waves-effect" id="btn-yes"><i class="fa fa-check-circle"></i> Yes</button>
        </div>
    </div>
  </div>
</div>




<script>
  $(document).ready(function(){

    const opt_default   = '<option value="0" data-name="">-- Choose --</option>';
    const c_po_date     = readCookie('po_date');
    const line_selected = readCookie('po_line_selected');
    let attachment_file = "";
    let attach_category = $('#attachment').data('name');

  if(c_po_date != null){
    $("#po_date").val(c_po_date);
    eraseCookie("po_date");
  }
  
  let directorate = $("#directorate").val();
  let division    = $("#division").val();
  let unit        = $("#unit").val();
  let po_status   = $("#status").val();
  let po_date     = $("#po_date").val();

  let url = baseURL + 'purchase-order/api/load_inquiry';

  let hide_directorat = '<?= ($binding == false || $data_binding['directorat'] == false) ? 'hide' : 'hide' ?>';
  let hide_division = '<?= ($binding == false || $data_binding['division'] == false) ? 'hide' : 'hide' ?>';
  let hide_unit = '<?= ($binding == false || $data_binding['unit'] == false) ? 'hide' : 'hide' ?>';

  Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                                  d.status     = po_status;
                                  d.directorat = directorate;
                                  d.division   = division;
                                  d.unit       = unit;
                                  d.po_date    = po_date;
                                  }
                          },
        "language"        : {
                              "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
                              "infoEmpty"   : "Data Kosong",
                              "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
                              "search"      : "_INPUT_",
                              "paginate": {
                                          'previous': '<i class="fa fa-angle-left"></i>',
                                          'next': '<i class="fa fa-angle-right"></i>',
                                        },
                              "lengthMenu": " &nbsp; _MENU_ &nbsp; rows per page"
                            },
        "columns"         : [
                              { "data": "directorat", "width": "100px", "class": hide_directorat },
                              { "data": "division", "width": "150px", "class": hide_division },
                              { "data": "unit", "width": "150px", "class": hide_unit },
                              { "data": "pr_number", "width": "150px", "class": "text-center" },
                              { "data": "po_number", "width": "150px", "class": "text-center" },
                              { "data": "po_name", "width": "200px" },
                              { "data": "po_date", "width": "80px", "class": "text-center" },
                              { "data": "currency", "width": "80px", "class": "text-center" },
                              { "data": "total_amount", "width": "150px", "class": "text-right" },
                              { "data": "status_act", "width": "150px", "class": "text-center" },
                              { "data": "action", "width": "80px", "class": "text-center" }
                ],
          "drawCallback": function ( settings ) {
            if(line_selected != null){
              setTimeout(function(){
                $('#table_data tbody tr').eq(line_selected).addClass('selected-grey');
                eraseCookie("po_line_selected");
              }, 300);
            }
          },
          "fnDrawCallback": function () {
            $('#table_data_length').prepend($('#table_data_info'));
          },
           "dom": '<"top"i>rt<"bottom"flp><"clear">',
        "ordering"        : false,
        "scrollY"         : 520,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "bAutoWidth"      : true,
      });
  });

  let table = $('#table_data').DataTable();
  
  $("#table_data_filter").remove();

  $("#tbl_search").on('keyup', "input[type='search']", function(evt){
      table.search( $(this).val() ).draw();
  });

  $('#table_data tbody').on( 'click', 'tr', function () {
    if (! $(this).hasClass('selected') ) {
      table.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });

    $('#btnView').on( 'click', function () {
      directorate = $("#directorate").val();
      division    = $("#division").val();
      unit        = $("#unit").val();
      po_status   = $("#status").val();     
      po_date     = $("#po_date").val();
      table.draw();
    });

    let change_date = false;
    $("#po_date").bind("click", function(e) {
      change_date = true;
    }).bind("change", function(e) {
      if(change_date){
        eraseCookie("po_date");
        createCookie("po_date", $(this).val());
      }
    });

    $('#directorate').on( 'change', function () {
      getDivision();
    });
    $('#division').on( 'change', function () {
      getUnit();
    });

    $('#table_data').on('click', 'a.action-view', function () {
      id_fs     = $(this).data('id');
      get_table = table.row( $(this).parents('tr') );
      index     = get_table.index();
      eraseCookie("po_line_selected");
      createCookie("po_line_selected", index);
      $(location).attr('href', baseURL + 'purchase-order/' + id_fs);
    });

    $('#table_data').on('click', 'a.status-info', function () {
      get_table = table.row( $(this).parents('tr') );
      data      = get_table.data();

      $("#submitter").html(data.submitter);
      $("#modal-status-label").html(data.fs_number);

      $.ajax({
        url       : baseURL + 'purchase-order/api/get_status_info',
        type      : 'post',
        data      : { id_fs: data.id_fs},
        beforeSend  : function(){
                        customLoading('show');
                      },
        dataType : 'json',
        success : function(result){
          id_delete = 0;
          $("#modal-delete").modal('hide');
          customLoading('hide');
          if (result.status == true) {
            $("#modal-status").modal('show')
          } else {
          }
        }
      });
    });

    $('#table_data').on('click', 'a.action-edit', function () {
      id_fs     = $(this).data('id');
      get_table = table.row( $(this).parents('tr') );
      index     = get_table.index();
      data      = get_table.data();

      status = data.status;
      if(status.toLowerCase() != "returned"){
        customNotif('Warning', 'Tidak bisa di edit karena status '+status, 'warning');
      }else{
        eraseCookie("po_line_selected");
        createCookie("po_line_selected", index);
        $(location).attr('href', baseURL + 'purchase-order/edit/' + id_fs);
      }
    });


    $('#btn-create').on( 'click', function () {
      customLoading('show');
      setTimeout(function(){
        $(location).attr('href', baseURL + 'purchase-order/create'/* + result.pr_number*/);
      }, 300);
    });

    $('.mydatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });

    $('#table_data').on('click', 'a.action-delete', function () {
      data      = table.row( $(this).parents('tr') ).data();
      id_delete = data.id;
      status    = data.status;

      if(status.toLowerCase() == "canceled"){
        $("#modal-delete-label").html('Delete Data : ' +  data.po_number);
        $("#modal-delete").modal('show');
      }else{
        customNotif('Warning', 'Tidak bisa di hapus karena status '+status, 'warning');
      }
    });

    $('#button-delete').on( 'click', function () {
        $.ajax({
          url       : baseURL + 'purchase-order/api/delete_po',
          type      : 'post',
          data      : { id: id_delete},
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

    $('#table_data tbody').on('click', 'select.action-status', function () {
      lastStatus = $(this).val();
    });

    $('#table_data').on('click', 'a.action-close', function () {
      data   = table.row( $(this).parents('tr') ).data();
      id_po  = data.id;
      id_pr  = data.pr_header_id;
      // status = $(this).val();
 
      status    = data.status;
   /*   if(status == "canceled"){
          $(this).val(lastStatus);
          $('#modal-cancel').modal('show');
          $('#btn-yes').on( 'click', function () {
            changeStatus('canceled');
          });

      }else{
        changeStatus('closed');
      }*/

      if(status.toLowerCase() == "approved"){
          // $(this).val(lastStatus);
          $('#modal-cancel').modal('show');
          $('#btn-yes').on( 'click', function () {
            changeStatus('canceled');
          });
      }else{
        customNotif('Warning', 'Tidak bisa di cancel karena status '+status, 'warning');
      }
    });

    function changeStatus(status){

      statusVal = status;
      cancel_reason = '';
      with_modal = false;

      if(statusVal == 'canceled'){
        cancel_reason = $("#cancel_desc").val();
        with_modal = true;
      }

      $.ajax({
          url       : baseURL + 'purchase-order/api/change_status_po',
          type      : 'post',
          data      : { po_header_id: id_po, pr_header_id: id_pr, status: statusVal, cancel_reason: cancel_reason, attachment: attachment_file },
          dataType : 'json',
          beforeSend  : function(){
                        if(with_modal){
                          customLoading('show');
                        }
                      },
          success : function(result){
            if (result.status == true) {
              table.ajax.reload(null, false);
              customNotif('Success', result.messages, 'success');
              if(with_modal){
                customLoading('hide');
                $('#modal-cancel').modal('hide');
              }
            } else {
              customNotif('Failed', result.messages, 'error');
            }
          }
      });

    }

    $('#btn-cancel').on( 'click', function () {
      $('#modal-cancel').modal('hide');
    });


    function getDivision(){

      let directorat    = $("#directorate").find(':selected').attr('data-name');

      $.ajax({
          url   : baseURL + 'api-budget/load_data_rkap_view',
          type  : "POST",
          data  : {category : "division", directorat:directorat},
          dataType: "json",
          success : function(result){
            let division_opt = '<option value="0">--All Division--</option>';
            if(result.status == true){
              let data     = result.data;
              for(var i = 0; i < data.length; i++) {
                  obj = data[i];
                  division_opt += '<option value="'+ obj.id_division +'" data-name="'+ obj.division +'">'+ obj.division +'</option>';
              }
            }
            $("#division").html(division_opt);        
          }
      });
    }

    function getUnit(){

      let directorat = $("#directorate").find(':selected').attr('data-name');
      let division   = $("#division").find(':selected').attr('data-name');

      $.ajax({
          url   : baseURL + 'api-budget/load_data_rkap_view',
          type  : "POST",
          data  : {category : "unit", directorat : directorat, division : division},
          dataType: "json",
          success : function(result){
            let unit_opt = '<option value="0">--All Unit--</option>';
            if(result.status == true){
              let data     = result.data;
              for(var i = 0; i < data.length; i++) {
                  obj = data[i];
                  unit_opt += '<option value="'+ obj.id_unit +'" >'+ obj.unit +'</option>';
              }
            }
            $("#unit").html(unit_opt);        
          }
      });
    }

    <?php if($this->session->flashdata('messages') != ""): ?>
      customNotif('Error', '<?= $this->session->flashdata('messages') ?>', 'error');
    <?php endif; ?>


    $("#btnPrint").on("click", function(){
      url   = 'purchase-order/api/download_po/';

      let params  = { 'id_dir_code' : $("#directorate").val(), 'id_division' : $("#division").val(), 'id_unit' : $("#unit").val(), 'status' : $("#status").val(), 'po_date' : $("#po_date").val() };

      open_url(url, params);
    });

    let jqXHR;
    $("#fileinput_remove").on("click", function(e) {
      deleteFile();
      jqXHR.abort();
      reset_proggress();
    });

    $("#attachment").bind("click", function(e) {
        lastValue = $(this).val();
        reset_proggress();
    }).bind("change", function(e) {
      upload = true;
      if(lastValue != ""){
        upload = false;
        if(deleteFile()){
          upload = true;
        }
      }
      if($(this).val() != ""){

        fileSize = this.files[0].size;
        fileSize = fileSize/1000
        j=0;

        if(fileSize > 5800){
          upload = false;
          alert('Max file size 5M')
              $(this).val(lastValue);
        }

        extension_allow = ['pdf'];
        extension       = this.files[0].name.split('.').pop().toLowerCase();
        if (extension_allow.indexOf(extension) < 0) {
          upload = false;
          alert('Extention not allowed');
          $(this).val(lastValue);
        }

        if(upload){
          $("#progress").parent().removeClass('d-none');
              
          file = $('#attachment')[0].files[0];
          formData = new FormData();
          formData.append('file', file);
          formData.append('category', attach_category);

              jqXHR  = $.ajax({
                  url: baseURL + "upload/do_upload",
                  type: 'POST',
                  data: formData,
                  cache: false,
                  dataType: 'json',
                  enctype   : 'multipart/form-data',
                  contentType: false,
                  processData: false,
                  xhr: function() {
                      var xhr = new window.XMLHttpRequest();
                      xhr.upload.addEventListener("progress", function(evt) {
                          if (evt.lengthComputable) {
                              percentage = Math.round((evt.loaded / evt.total) * 100);
                              if(percentage == 100){
                                percentage = 99;
                              }
                          $("#progress").css('width', percentage+'%');
                          $("#progress").html(percentage+'%');
                          
                          }
                      }, false);
                      return xhr;
                  },
                  success: function(result) {
                      $("#progress").css('width', '100%');
                      if(result.status == true){
                              $("#progress").html('100%');
                              setTimeout(function(){
                                $("#progress").html('Complete!');
                                $("#progress").removeClass('progress-bar-info');
                                $("#progress").addClass('progress-bar-success');
                                attachment_file = result.messages;
                      }, 1000);
                            }else{
                              setTimeout(function(){
                                $("#progress").html(result.messages);
                                $("#progress").removeClass('progress-bar-info');
                                $("#progress").addClass('progress-bar-danger');
                      }, 500);
                      }
                  },
              error: function (xhr, ajaxOptions, thrownError) {
                $("#progress").parent().addClass('d-none');
                    $("#progress").css('width', '100%');
                    $("#progress").removeClass('progress-bar-info');
                    setTimeout(function(){
                      $("#progress").addClass('progress-bar-danger');
                  $("#progress").parent().removeClass('d-none');
                      $("#progress").html('Error Connection');
            }, 200);
              }
              });
              return false;

        }
              
      }
    });


    function reset_proggress(){
        $('#file_input').val("");
        $("#delete_file").addClass('d-none');
        $("#progress").removeClass("progress-bar-success");
        $("#progress").removeClass("progress-bar-danger");
        $("#progress").parent().addClass('d-none');
        $("#progress").css('width', '0%');
        $("#progress").html('0%');
      }

    function deleteFile(){

      $.ajax({
            url   : baseURL + 'upload/delete',
            type  : "POST",
            data  : {file: attachment_file, category: attach_category},
            dataType: "json",
            success : function(result){
              attachment_file = '';
              return true;
            }
        });

        return true;
    }

    $('#table_data').on('click', 'a.action-cetak', function () {
      let po_header_id = $(this).data('id');
      var url   = baseURL + 'purchase-order/api/printPDF/';
      console.log(url);
      if (!table.data().any()){
         customNotif('Info','Data Kosong!','warning' );
         exit();
      }else{
        window.open(url+'/'+po_header_id, '_blank');
        window.focus();
      }
    });

  });
</script>