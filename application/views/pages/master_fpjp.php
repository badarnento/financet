<div class="row">   
  <div class="col-lg-12">
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
          <div class="table-responsive">
              <table width="100%" class="table display table-bordered table-striped table-responsive hover" id="table_data"> 
                <thead>
                  <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">FPJP CODE</th>
                    <th class="text-center">FPJP NAME</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
              </table>
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
                      <label class="form-control-label" for="lblFPJPCode">FPJP Code</label>
                      <input type="text" class="form-control" id="txtFPJPCode" name="txtFPJPCode" placeholder="FPJP Code" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required />
                      <div class="help-block with-errors"></div>
                      <input type="hidden" name="isNewRecord" id="isNewRecord" val="0">
                      <input type="hidden" name="id_master_fpjp" id="id_master_fpjp">
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="form-group">
                  <div class="col-lg-12">
                    <div class="col-lg-8">
                      <label class="form-control-label" for="lblFPJPName">FPJP Name</label>
                      <input type="text" class="form-control" id="txtFPJPName" name="txtFPJPName" placeholder="FPJP Name" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
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

    <div class="modal fade" id="modal-delete" role="dialog" aria-labelledby="modal-delete">
      <div class="modal-dialog">
        <div class="modal-content">
         <form role="form" id="form-delete">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
          </div>
          <div class="modal-body">
            Do you want to delete?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">CANCEL</button>
            <button type="button" class="btn btn-info" id="button-delete" name="confirm" value="Yes">YES</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
<div class="white-box boxshadow">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <div class="col-md-5"></div>
        <div class="col-md-2 col-sm-12 m-b-10">
          <button id="btnPrint" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa fa-file-excel-o"></i> <span>Download</span></button>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<script>
  $(document).ready(function () {

    let url = baseURL + 'master/load_data_fpjp';
    let ajaxData = {
      "url"  : url,
      "type" : "POST",
      "data"    : function ( d ) {
      }
    }
    let jsonData = [
    { "data": "no", "width":"10px", "class":"text-center"},
    { "data": "fpjp_code", "width":"200px", "class":"text-left"},
    { "data": "fpjp_name", "width":"100px", "class":"text-left"},
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
    if (e.isDefaultPrevented()) {
      console.log('tidak valid');
    }
    else {
     $.ajax({
      url     : baseURL + 'master/save_fpjp',
      type    : "POST",
      data    : $('#form-edit').serialize(),

      success : function(result){
        console.log(result);
        if (result==1) {
         table.ajax.reload(null, false);
         $("#modal-edit").modal('hide');
         customNotif('Success','Record changed!','success', 4000 );
       } 
       else if (result==0)
       {
        $("body").removeClass("loading");
         customNotif('Failed', result,'error', 4000 );
       } 
       else 
       {
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
    $("#id_master_fpjp").val(data.id_master_fpjp);
    $("#txtFPJPCode").val(data.fpjp_code);
    $("#txtFPJPName").val(data.fpjp_name);
    $("#modal-edit-label").html('Edit : ' +  data.fpjp_name + '( ' + data.fpjp_code + ' )');
    $("#modal-edit").modal('show');

  });


   $('#table_data ').on( 'click', 'a.action-delete', function () {

    data      = table.row( $(this).parents('tr') ).data();
    id_delete = data.id_master_fpjp;


    $("#modal-delete-label").html('Delete Data : ' + data.fpjp_name + '( ' + data.fpjp_code + ' )');
    $("#modal-delete").modal('show');

  });

   $('#button-delete').on( 'click', function () {

    $.ajax({
      url       : baseURL + 'master/delete_fpjp/' + id_delete,
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
    var url   ="<?php echo site_url(); ?>Master/cetak_data_fpjp";
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