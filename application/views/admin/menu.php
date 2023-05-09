<div class="row">   
  <div id="accordion" class="panel panel-info boxshadow animated slideInDown">
    <div class="panel-heading">
      <div class="row">
        <div class="col-xs-6">
          <a id="aTitleList" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-data">List Group</a>
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
               <th class="text-center">NO</th>
               <th class="text-center">TITLE</th>
               <th class="text-center">URL</th>
               <th class="text-center">ACTIONS</th>
               <th class="text-center">SWAP ORDER</th>
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
          <h2 class="modal-title text-white" id="modal-edit-label" >Add New Group</h2>
        </div>
        <div class="modal-body">
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group m-b-15">
                  <label for="group_name" class="col-sm-3 control-label">Name:</label>
                  <div class="col-sm-9">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="is_new_record" name="is_new_record" value="1">
                    <input type="text" class="form-control" name="group_name" id="group_name" placeholder="Group Name">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group m-b-15">
                  <label for="description" class="col-sm-3 control-label">Description:</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" name="description" id="description" rows="5" autocomplete="off" ></textarea>
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

<div class="modal fade" id="modal-assign" role="dialog" aria-labelledby="modal-assign">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-white" id="modal-assign-title" >Assign to Menus</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="error-assign" class="alert alert-danger alert-dismissable hidden"></div>
        <form role="form" id="form-assign" autocomplete="off">
            <input type="hidden" class="form-control" id="id-assign" name="id">
            <div class="form-group">
                <label class="form-control-label" for="pre-selected-options">Assign Menus</label>
                <select class="w-full" multiple id="menu-list" name="menus[]">
                <?php
                  foreach ($menus as $menu):
                    if($menu['PARENT_ID'] == 0){
                      if ($menu['IS_PARENT'] == 1) {
                        echo '<optgroup label="'.$menu['TITLE'].'">';
                        foreach ($menus as $value) {
                          if($value['PARENT_ID'] == $menu['ID']){
                            echo '<option value="'.$value['ID'].'">'.$value['TITLE'].'</option>';
                          }
                        }
                        echo '</optgroup>';
                      }
                      else{
                        echo '<optgroup label="'.$menu['TITLE'].'">';
                        echo '<option value="'.$menu['ID'].'">'.$menu['TITLE'].'</option>';
                        echo '</optgroup>';
                      }
                    }
                  endforeach;
                ?>
                </select>
                <div class="button-box m-t-20">
                    <a id="select-all" class="btn btn-danger btn-outline" href="#">select all</a>
                    <a id="deselect-all" class="btn btn-info btn-outline" href="#">deselect all</a>
                    <a id="refresh" class="btn btn-warning btn-outline hidden" href="#">rollback</a>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">CANCEL</button>
          <button type="button" class="btn btn-info" id="btnAssign">SUBMIT</button>
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function () {

    let url = baseURL + 'api/groups/load_data_group';
    let ajaxData = {
      "url"  : url,
      "type" : "POST",
      "data"    : function ( d ) {
      }

    }

    $("#add_style").click(function(){
        $("#modal-radio").modal('show');
    });

    Pace.track(function(){
       $('#table_data').DataTable({
        "serverSide"    : true,
        "processing"    : true,
        "ajax"          : {
                             "url"          : baseURL + 'api/menu-management/load_data_menu',
                             "type"         : "POST"
                            },

         "language"     : {
                "emptyTable"    : "<span class='label label-danger'>Data Tidak Ditemukan!</span>",  
                "infoEmpty"     : "Data Kosong",
                "processing"    :' <img src="' + baseURL + 'assets/vendor/payrol/css/images/loading2.gif">',
                "search"        : "_INPUT_"
            },
           "columns": [
                { "data": "no", "width":"10px", "class":"text-center"},
                { "data": "title", "width":"200px"},
                { "data": "url", "width":"200px"},
                { 
                    "data": "title",
                    "width":"80px",
                    "class":"text-center",
                    "render": function (data) {
                     return '<a href="javascript:void(0)" class="action-edit" title="Edit ' + data + '" style="color:#ff6436"><i class="fa fa-edit" aria-hidden="true"></i></a> &nbsp;&nbsp;&nbsp; <a href="javascript:void(0)" class="action-delete" title="Hapus ' + data + '" style="color:#ff6436"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                },
                { "data": "showorder_icon", "width":"80px", "class":"text-center"}
            ],
            "scrollY"          : false, 
            "scrollCollapse"   : true,
            "scrollX"          : true,
            "ordering"         : false,
            "pageLength": 100,
            drawCallback: function (settings) {
                let api         = this.api();
                let row_datas   = api.rows({ page: 'current' }).data();
                let row_nodes   = api.rows({ page: 'current' }).nodes();
                let last_parent = "";
                row_datas.each(function (data, i) {
                    group_parent = data.parent;
                    if (last_parent != group_parent) {
                        if(group_parent == 'XXX'){
                            $(row_nodes).eq(i).before(
                                '<tr class="group"><td align="center" colspan="6" style="color:#fff;background-color:#4caf50;font-weight:700;padding:10px 0;">Parent / Single Link</td></tr>'
                            );
                        }
                        else{
                            $(row_nodes).eq(i).before(
                                '<tr class="group"><td align="center" colspan="6" style="color:#fff;background-color:#f44336;font-weight:700;padding:10px 0;">' +  group_parent  + '</td></tr>'
                            );
                        }
                    }
                    last_parent = group_parent;
                });
            }
        });
    });


  let table = $('#table_data').DataTable();

  $('#btnAdd').on( 'click', function () {

    $('#modal-edit').on('shown.bs.modal', function () {
      $("#deselect-all_add").trigger('click');
      $(this).find("input,textarea,select").val('').end()
              .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
      $('#group_name').trigger('focus');
    });
    
    $("#modal-edit-label").html('Add New User');
    $("#is_new_record").val("1");
    
    form = $('#form-edit')[0];
    form.reset();

  });

  $('#form-edit').validator().on('submit', function(e) {
    if (!e.isDefaultPrevented()) {
      $.ajax({
        url         : baseURL + 'api/groups/save_group',
        type        : "POST",
        data        : $('#form-edit').serialize(),
        beforeSend  : function(){
          customLoading('show');
        },
        dataType: 'json',
        success : function(result){
          $("#modal-edit").modal('hide');
          customLoading('hide');
          if(result.status == true){
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
    $("#modal-edit-label").html('Edit : ' +  data.group_name);
    $("#modal-edit").modal('show');

    $("#id").val(data.id);
    $("#group_name").val(data.group_name);
    $("#description").val(data.description);

    $("#modal-edit").modal('show');
  });

  $('#table_data ').on( 'click', 'a.action-delete', function () {

    data      = table.row( $(this).parents('tr') ).data();
    id_delete = data.id;

    $("#modal-delete-label").html('Delete Data : ' + data.group_name);
    $("#modal-delete").modal('show');

  });

  $('#button-delete').on( 'click', function () {
    $.ajax({
      url       : baseURL + 'api/groups/delete_group',
      type      : 'DELETE',
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

  // Form Assign
  $("#btnAssign").click(function(){
    $.ajax({
      url     : baseURL + 'api/groups/assign_menu',
      type    : "POST",
      data    : $('#form-assign').serialize(),
      beforeSend  : function(){
        customLoading('show');
      },
      dataType: 'json',
      success : function(result){
        customLoading('hide');
        if (result.status == true) {
          table.ajax.reload(null, false);
          $("#modal-assign").modal('hide');
          customNotif('Success', result.messages, 'success');
        } else {
          customNotif('Failed', result.messages, 'error');
        }
      }
    }); 
    return false;
  });


  $('#table_data tbody').on( 'click', 'a.action-assign', function () {
    data       = table.row( $(this).parents('tr') ).data();
    menu_id    = data.menu_id;
    group_name = data.group_name;
    menus      = menu_id.split(", ");

    $("#id-assign").val(data.id);
    $("#modal-assign-title").html("Assigns " + group_name + " to menu");

    $('#menu-list').val(menus);
    $('#menu-list').multiSelect('refresh');

    $('#refresh').attr('style','display: inline-block !important');
    $('#refresh').on('click', function () {
        $('#menu-list').val(menus);
        $('#menu-list').multiSelect('refresh');
        return false;
    });
    $("#modal-assign").modal('show');
  });

  $('#menu-list').multiSelect();
  $('#select-all').click(function () {
      $('#menu-list').multiSelect('select_all');
      return false;
  });
  $('#deselect-all').click(function () {
      $('#menu-list').multiSelect('deselect_all');
      return false;
  });


});



</script>