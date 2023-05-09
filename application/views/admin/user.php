<div class="row">   
  <div id="accordion" class="panel panel-info boxshadow animated slideInDown">
    <div class="panel-heading">
      <div class="row">
        <div class="col-xs-6">
          <a id="aTitleList" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-data">List User</a>
        </div>
        <div class="col-xs-6">
          <div class="pull-right">
            <button id="btnAdd" class="btn btn-success custom-input-width" data-toggle="modal" data-target="#modal-edit" type="button" ><i class="fa fa-plus"></i> Add New</button>
          </div>
        </div>
      </div>
    </div>
    <div id="collapse-data" class="panel-collapse collapse in">
      <div class="panel-body">
        <table class="table display table-bordered table-striped table-responsive w-full cell-border" id="table_data"> 
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th class="text-center">USERNAME</th>
              <th class="text-center">DISPLAY NAME</th>
              <th class="text-center">EMAIL</th>
              <th class="text-center">DIRECTORATE</th>
              <th class="text-center">USER GROUP</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

  <div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <form class="form-horizontal" role="form" id="form-edit" data-toggle="validator">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-edit-label" >Add New User</h2>
        </div>
        <div class="modal-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group m-b-15">
                  <label for="username" class="col-sm-3 control-label">Username:</label>
                  <div class="col-sm-9">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="is_new_record" name="is_new_record" value="1">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group m-b-15">
                  <label for="display_name" class="col-sm-3 control-label">Display Name:</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="display_name" id="display_name" placeholder="Display Name">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group m-b-15">
                  <label for="email" class="col-sm-3 control-label">Email:</label>
                  <div class="col-sm-9">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12">
                <div class="form-group m-b-15">
                  <label for="ddlDirectoratName" class="col-sm-3 control-label">Directorate:</label>
                  <div class="col-sm-9">
                     <select class="form-control" id="ddlDirectoratName" name="ddlDirectoratName" data-toggle="validator" data-error="Please choose one" required>
                      <option value=""> Choose Directorate </option> 
                    </select> 
                    <div class="help-block with-errors"></div>
                  </div>
                </div>
              </div>
            </div>
                   

            <div class="row">
              <div class="col-lg-12">
                <div class="form-group m-b-15">
                  <label for="group-list" class="col-sm-3 control-label">Assign Groups:</label>
                  <div class="col-sm-9">
                    <select multiple id="group-list" name="groups[]" data-toggle="validator" data-error="Please fill out this field" required>
                      <?php foreach ($groups as $group):?>
                        <option value="<?= $group['ID'] ?>"><?= $group['NAME'] ?></option>
                      <?php endforeach?>
                    </select>
                    <div class="button-box m-t-20">
                      <a id="select-all" class="btn btn-danger btn-outline" href="#">select all</a>
                      <a id="deselect-all" class="btn btn-info btn-outline" href="#">deselect all</a>
                      <a id="refresh" class="btn btn-warning btn-outline hidden" href="#">rollback</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row status d-none">
              <div class="col-lg-12">
                <div class="form-group m-b-15">
                  <label for="group-list" class="col-sm-3 control-label">Status:</label>
                  <div class="col-sm-9">
                    <div>
                      <div class="radio radio-info">
                          <input type="radio" id="active" name="active" value="1"/>
                          <label for="active">Active</label>
                      </div>
                      <div class="radio radio-info">
                          <input type="radio" id="not_active" name="active" value="0"/>
                          <label for="not_active">Not Active</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="reset" class="btn btn-default"><i class="fa fa-trash-o"></i> Clear</button>
          <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i class="fa fa-times-circle"></i> CANCEL</button>
          <button type="submit" class="btn btn-info waves-effect" id="btnSave"><i class="fa fa-save"></i> Save</button>
        </div>
    </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modal-reset" role="dialog" aria-labelledby="modal-reset">
  <div class="modal-dialog">
    <div class="modal-content">
       <form role="form" id="form-reset">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 id="modal-reset-label" class="modal-title text-white">Confirmation</h4>
            </div>
            <div class="modal-body">
                Do you want to reset?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">CANCEL</button>
                <button type="button" class="btn btn-info" id="button-reset" name="confirm" value="Yes">YES</button>
            </div>
        </form>
    </div>
  </div>
</div>
<script>
  $(document).ready(function () {

  	getDirectorate();

    let url = baseURL + 'api/users/load_data_user';
   	let ajaxData = {
      "url"  : url,
      "type" : "POST",
      "data"    : function ( d ) {
      }
    }
    let jsonData = [
           { "data": "no", "width":"10px", "class":"text-center"},
           { "data": "username", "width":"200px"},
           { "data": "display_name", "width":"200px"},
           { "data": "email", "width":"300px"},
           { "data": "directorate", "width":"300px"},
           { "data": "user_group", "width":"300px"},
           { 
             "data": "",
             "width":"80px",
             "class":"text-center",
             "render": function (data) {
             return '<a href="javascript:void(0)" class="action-edit" title="Click to edit ' + data + '"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete ' + data + '"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-reset" title="Click to reset password ' + data + '"><i class="fa fa-refresh text-info" aria-hidden="true"></i></a>';
             }
           }
   ];

   data_table(ajaxData,jsonData);

   let table = $('#table_data').DataTable();


   $('#btnAdd').on( 'click', function () {
    $("#deselect-all").trigger('click');

    $("#is_new_record").val("1");
    $("#modal-edit-label").html('Add New User');
    $('#username').removeAttr('readonly');
    $(".status").addClass("d-none");
    
  });

  $('#modal-edit').on('hidden.bs.modal', function () {
    $(this).find("input,textarea,select").val('').end()
            .find("input[type =checkbox], input[type=radio]").prop("checked", "").end();
    
    form = $('#form-edit')[0];
    form.reset();
  });

  $('#form-edit').validator().on('submit', function(e) {
    if (!e.isDefaultPrevented()) {
      $.ajax({
        url         : baseURL + 'api/users/save_user',
        type        : "POST",
        data        : $('#form-edit').serialize(),
        beforeSend  : function(){
          customLoading('show');
        },
        dataType: 'json',
        success : function(result){
          console.log(result);
          customLoading('hide');
          if(result.status == true){
            $("#modal-edit").modal('hide');
            customNotif('Success!', result.messages, 'success');
            table.draw();
          }
          else{
            customNotif('Failed!', result.messages, 'error');
          }
        }
      });
    }
    e.preventDefault();
  });

  $('#table_data').on( 'click', 'a.action-edit', function () {

    data = table.row( $(this).parents('tr') ).data();

    $("#is_new_record").val("0");
    $("#modal-edit-label").html('Edit : ' +  data.display_name);
    $("#modal-edit").modal('show');
    $("#username").attr("readonly", true);
    $("#display_name").trigger("focus");

    status   = data.status;
    group_id = data.group_id;
    groups   = group_id.split(", ");

    $("#ddlDirectoratName").val(data.id_dir_code);
    console.log(data.id_dir_code);
    $("#id").val(data.id);
    $("#username").val(data.username);
    $("#email").val(data.email);
    $("#display_name").val(data.display_name);
    $("#status").val(data.status);

    $('#group-list').val(groups);
    $('#group-list').multiSelect('refresh');

    $('#refresh').attr('style','display: inline-block !important');
    $('#refresh').on('click', function () {
      $('#group-list').val(groups);
      $('#group-list').multiSelect('refresh');
      return false;
    });

    if (status == 'Active') {
        $("#active").prop("checked", true );
    }
    else{
        $("#not_active").prop("checked", true );
    }

    $(".status").removeClass("d-none");
    $("#modal-edit").modal('show');
  });

  $('#table_data ').on( 'click', 'a.action-delete', function () {

    data      = table.row( $(this).parents('tr') ).data();
    id_delete = data.id;

    $("#modal-delete-label").html('Delete Data : ' + data.display_name);
    $("#modal-delete").modal('show');

  });

  $('#button-delete').on( 'click', function () {

    $.ajax({
      url       : baseURL + 'api/users/delete_user',
      type      : 'POST',
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

  $('#table_data ').on( 'click', 'a.action-reset', function () {

    data      = table.row( $(this).parents('tr') ).data();
    id_reset = data.id;

    $("#modal-reset-label").html('Reset password : ' + data.display_name);
    $("#modal-reset").modal('show');

  });

  function getDirectorate(){
    $.ajax({
      url   : baseURL + 'master/load_ddl_directorat',
      type  : "POST",
      dataType: "html",
      success : function(result){
        $("#ddlDirectoratName").html("");       
        $("#ddlDirectoratName").html(result);          
      }
    });     
  }

  $('#button-reset').on( 'click', function () {

    $.ajax({
      url       : baseURL + 'api/users/reset_user_pass',
      type      : 'POST',
      data      : { id: id_reset},
      beforeSend  : function(){
        customLoading('show');
      },
      dataType : 'json',
      success : function(result){
        id_reset = 0;
        $("#modal-reset").modal('hide');
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

  $('#group-list, #group-list_add').multiSelect();
  $('#select-all').click(function () {
      $('#group-list').multiSelect('select_all');
      return false;
  });
  $('#deselect-all').click(function () {
      $('#group-list').multiSelect('deselect_all');
      return false;
  });

});



</script>