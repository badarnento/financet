<div class="row">   
  <div id="accordion" class="panel panel-info boxshadow animated slideInDown">
    <div class="panel-heading">
      <div class="row">
        <div class="col-md-6">
          <a id="aTitleList" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-data"></a>
        </div>
        <div class="col-md-6">
          <div class="pull-right">
            <button id="btnAdd" class="btn btn-success custom-input-width" data-toggle="modal" data-target="#modal-edit" type="button" ><i class="fa fa-plus"></i> Add New</button>
          </div>
        </div>
      </div>
    </div>
    <div id="collapse-data" class="panel-collapse collapse in">
      <div class="panel-body">
        <table width="100%" class="table display table-bordered table-striped table-responsive" id="table_data"> 
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th class="text-center">TAHUN</th>
              <th class="text-center">PROGRAM</th>
              <th class="text-center">DESCRIPTION</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="col-md-offset-5 col-md-2 m-b-10">
            <button id="btnPrint" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa fa-file-excel-o"></i> <span>Download</span></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form role="form" id="form-edit" data-toggle="validator">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2 class="modal-title text-white" id="modal-edit-label">Edit Data</h2>
        </div>
        <div class="modal-body"> 

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="lblProgram">Program</label>
                  <input type="text" class="form-control" id="txtProgram" name="txtProgram" placeholder="Program" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
                  <div class="help-block with-errors"></div>
                  <input type="hidden" name="isNewRecord" id="isNewRecord" val="0">
                  <input type="hidden" name="id_program" id="id_program">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="lblDescription">Description</label>
                  <input type="text" class="form-control" id="txtDescription" name="txtDescription" placeholder="Description" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
                  <div class="help-block with-errors"></div>
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
      </form>  
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {

    let url = baseURL + 'master/load_data_program';
    let ajaxData = {
      "url"  : url,
      "type" : "POST"
    }
    let jsonData = [
    { "data": "no", "width":"10px", "class":"text-center"},
    { "data": "year", "width":"100px", "class":"text-left"},
    { "data": "program", "width":"100px", "class":"text-left"},
    { "data": "description", "width":"200px", "class":"text-left"},
    { 
      "data": "",
      "width":"80px",
      "class":"text-center",
      "render": function (data) {
       return '<a href="javascript:void(0)" class="action-edit" title="Click to edit ' + data + '"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete ' + data + '"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';
     }
   }
   ];
   data_table(ajaxData,jsonData);

   let table = $('#table_data').DataTable();

    $('#btnAdd').on( 'click', function () {
    $("#modal-edit-label").html('Add New');
    $("#isNewRecord").val("1");

    form = $('#form-edit')[0];
    form.reset();

  });

   $('#form-edit').validator().on('submit', function(e) {
    if (!e.isDefaultPrevented()){
      $.ajax({
        url     : baseURL + 'master/save_program',
        type    : "POST",
        data    : $('#form-edit').serialize(),
        success : function(result){
          if (result==1) {
            table.ajax.reload(null, false);
            $("#modal-edit").modal('hide');
            customNotif('Success','Record changed!','success', 4000 );
          }
          else if (result==0) {
            $("body").removeClass("loading");
            customNotif('Failed', result,'error', 4000 );
          }
          else{
            $("body").removeClass("loading");
            customNotif('Failed', result,'error', 4000 );
          }
        }
      });
    }
    e.preventDefault();
  });

   $('#table_data').on( 'click', 'a.action-edit', function () {

    data = table.row( $(this).parents('tr') ).data();

    $("#isNewRecord").val("0");
    $("#id_program").val(data.id_program);
    $("#txtProgram").val(data.program);
    $("#txtDescription").val(data.description);
    $("#modal-edit-label").html('Edit : ' +  data.description + '( ' + data.program + ' )');
    $("#modal-edit").modal('show');
  });

   $('#table_data ').on( 'click', 'a.action-delete', function () {

    data      = table.row( $(this).parents('tr') ).data();
    id_delete = data.id_program;

    $("#modal-delete-label").html('Delete Data : ' + data.description + '( ' + data.program + ' )');
    $("#modal-delete").modal('show');
  });

   $('#button-delete').on( 'click', function () {

    $.ajax({
      url       : baseURL + 'master/delete_program/' + id_delete,
      type      : 'GET',
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

   $("#btnPrint").on("click", function(){
    var url   ="<?php echo site_url(); ?>Master/cetak_data_program";
    window.open(url,'_blank');
    window.focus();
  });


   $('#modal-edit').on('hidden.bs.modal', function () {
    if ($('#form-edit').find('.has-error, .has-danger').length > 0) {
      $(".help-block").html('');
      $('.has-error').removeClass('has-error');
    }

  });

 });



</script>