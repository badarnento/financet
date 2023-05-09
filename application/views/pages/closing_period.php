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
        <table width="100%" class="table display table-bordered table-striped table-responsive w-full" id="table_data"> 
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th class="text-center">Year</th>
              <th class="text-center">Month</th>
              <th class="text-center">Status</th>
              <th class="text-center">Description</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

  <div class="row" id="tblDatadownload">
    <div class="white-box boxshadow">     
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <div class="col-md-5"></div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
              <button id="btnPrint" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
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
                    <label class="form-control-label" for="ddlYear">Year</label>
                    <select class="form-control" id="ddlYear" name="ddlYear" data-toggle="validator" data-error="Please choose one" required>
                      <option value=""> Choose Year </option> 
                    </select> 
                    <input type="hidden" name="isNewRecord" id="isNewRecord" val="0">
                    <input type="hidden" name="id_closing" id="id_closing">
                    <div class="help-block with-errors"></div>
                  </div>
                </div>
              </div>
            </div> 

          <div class="row">
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="col-lg-8">
                    <label class="form-control-label" for="ddlMonth">Month</label>
                    <select class="form-control" id="ddlMonth" name="ddlMonth" data-toggle="validator" data-error="Please choose one" required>
                      <option value=""> Choose Month </option> 
                    </select> 
                    <div class="help-block with-errors"></div>
                  </div>
                </div>
              </div>
            </div>

          <div class="row">
              <div class="form-group">
                <div class="col-lg-12">
                  <div class="col-lg-8">
                    <label class="form-control-label" for="ddlStatus">Status</label>
                    <select class="form-control" id="ddlStatus" name="ddlStatus" data-toggle="validator" data-error="Please choose one" required>
                      <option value=""> Choose Status </option> 
                    </select> 
                    <div class="help-block with-errors"></div>
                  </div>
                </div>
              </div>
            </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtDescription">Description</label>
                  <textarea name="txtDescription" id="txtDescription" class="form-control" rows="3" autocomplete="off" placeholder="Description" required ></textarea>
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


 </div>



 <script>

   $(document).ready(function(){

    getYearClosing();
    getMonthClosing();
    getStatusClosing();

    let url  = baseURL + 'Closing_period/load_data_closing';

    let ajaxData = {
      "url"  : url,
      "type" : "POST"
    }
    let jsonData = [
    { "data": "no", "width":"5px", "class":"text-center"},
    { "data": "year", "width":"10px", "class":"text-left"},
    { "data": "month_text", "width":"10px", "class":"text-left"},
    { "data": "status", "width":"40px", "class":"text-left"},
    { "data": "description", "width":"80px", "class":"text-left"},
    { 
      "data": "",
      "width":"50px",
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
      url     : baseURL + 'Closing_period/save_closing',
      type    : "POST",
      data    : $('#form-edit').serialize(),
      beforeSend  : function()
          {
            customLoading('show');
          },
      success : function(result){
        console.log(result);
        customLoading('hide');
        if (result==1) {
         table.ajax.reload(null, false);
         $("#modal-edit").modal('hide');
         customLoading('hide');
         customNotif('Success','Record changed!','success', 4000 );
       } 
       else if (result==0)
       {
        $("body").removeClass("loading");
        customLoading('hide');
        customNotif('Failed', result,'error', 4000 );
       } 
       else 
       {
       $("body").removeClass("loading");
         customLoading('hide');
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
    $("#id_closing").val(data.id_closing);
    $("#ddlYear").val(data.year);
    $("#ddlMonth").val(data.month);
    $("#ddlStatus").val(data.status_value);
    $("#txtDescription").val(data.description_value);
    $("#modal-edit-label").html('Edit : ' +  data.month_text + " " + data.year);
    $("#modal-edit").modal('show');

  });


   $('#table_data ').on( 'click', 'a.action-delete', function () {

    data      = table.row( $(this).parents('tr') ).data();
    id_delete = data.id_closing;


    $("#modal-delete-label").html('Delete Data : ' +  data.month_text + " " + data.year );
    $("#modal-delete").modal('show');

  });

   $('#button-delete').on( 'click', function () {

    $.ajax({
      url       : baseURL + 'Closing_period/delete_closing/' + id_delete,
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

     let url   ="<?php echo site_url(); ?>Closing_period/download_data_closing";

     window.open(url, '_blank');

     window.focus();

   });


    $('#btnView').on( 'click', function () {

     table.draw();

   });

    function getYearClosing()

    {

      $.ajax({

        url   : baseURL + 'Closing_period/load_ddl_closing_year',

        type  : "POST",

        dataType: "html",

        success : function(result){

          $("#ddlYear").html("");       
          $("#ddlYear").html(result);          

        }

      });     

    }

    function getMonthClosing()

    {

      $.ajax({

        url   : baseURL + 'Closing_period/load_ddl_month',

        type  : "POST",

        dataType: "html",

        success : function(result){

          $("#ddlMonth").html("");       
          $("#ddlMonth").html(result);          

        }

      });     

    }

    function getStatusClosing()

    {

      $.ajax({

        url   : baseURL + 'Closing_period/load_ddl_closing',

        type  : "POST",

        dataType: "html",

        success : function(result){

          $("#ddlStatus").html("");       
          $("#ddlStatus").html(result);         
        }

      });     

    }

    $('#modal-edit').on('hidden.bs.modal', function () {
    if ($('#form-edit').find('.has-error, .has-danger').length > 0) {
      $(".help-block").html('');
      $('.has-error').removeClass('has-error');
    }

  });




});

</script>