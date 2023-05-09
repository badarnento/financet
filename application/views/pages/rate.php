<div class="row">  

<div class="white-box boxshadow">     

    <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
          <label>Currency Date From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="ddlDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Currency Date To</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="ddlDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>

      <div class="col-sm-3">

        <div class="form-group">

          <label>&nbsp;</label>

          <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-bars"></i> <span>VIEW</span></button>

        </div>

      </div> 

    </div>

</div>

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
          <table width="100%" class="table display table-bordered table-striped table-responsive  w-full" id="table_data"> 
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Currency Date</th>
                <th class="text-center">USD</th>
                <th class="text-center">EUR</th>
                <th class="text-center">SGD</th>
                <th class="text-center">AUD</th>
                <th class="text-center">JPY</th>
                <th class="text-center">HKD</th>
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
                  <label>Date From</label>
                   <div class="input-group">
                  <input type="text" class="form-control mydatepicker" id="txtCurrencyDate" name="txtCurrencyDate" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
                  <span class="input-group-addon"><i class="icon-calender"></i></span>
                  </div>
                  <input type="hidden" name="isNewRecord" id="isNewRecord" val="0">
                  <input type="hidden" name="id_rate" id="id_rate">
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtUSD">USD</label>
                  <input  class="form-control" id="txtUSD" name="txtUSD" placeholder="USD" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtEUR">EUR</label>
                  <input  class="form-control" id="txtEUR" name="txtEUR" placeholder="EUR" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtSGD">SGD</label>
                  <input  class="form-control" id="txtSGD" name="txtSGD" placeholder="SGD" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtAUD">AUD</label>
                  <input  class="form-control" id="txtAUD" name="txtAUD" placeholder="AUD" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtJPY">JPY</label>
                  <input  class="form-control" id="txtJPY" name="txtJPY" placeholder="JPY" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <div class="col-lg-12">
                <div class="col-lg-8">
                  <label class="form-control-label" for="txtHKD">HKD</label>
                  <input  class="form-control" id="txtHKD" name="txtHKD" placeholder="HKD" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required/>
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

    let date_from = $("#ddlDateFrom").val();
    let date_to = $("#ddlDateTo").val();

    let url = baseURL + 'master/load_data_rate';

    Pace.track(function(){
    $('#table_data').DataTable({
      "serverSide"      : true,
      "processing"      : true,
      "ajax"            : {
        "url"  : url,
        "type" : "POST",
        "dataType": "json",
        "data"    : function ( d ) {
          d.date_from = date_from;
          d.date_to   = date_to;
        }
      },
      "language"        : {
        "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
        "infoEmpty"   : "Data Kosong",
        "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
        "search"      : "_INPUT_"
      },
      "columns"         : [
      
                    { "data": "no", "width":"10px", "class":"text-center"},
                    { "data": "currency_date", "width":"200px", "class":"text-left"},
                    { "data": "usd", "width":"200px", "class":"text-left"},
                    { "data": "eur", "width":"200px", "class":"text-left"},
                    { "data": "sgd", "width":"200px", "class":"text-left"},
                    { "data": "aud", "width":"200px", "class":"text-left"},
                    { "data": "jpy", "width":"200px", "class":"text-left"},
                    { "data": "hkd", "width":"200px", "class":"text-left"},
                    { 
                      "data": "",
                      "width":"80px",
                      "class":"text-center",
                      "render": function (data) {
                       return '<a href="javascript:void(0)" class="action-edit" title="Click to edit ' + data + '"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete ' + data + '"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';
                     }
                   }
      ],
      "pageLength"      : 100,
      "ordering"        : false,
      "scrollY"         : 480,
      "scrollX"         : true,
      "scrollCollapse"  : true,
      "autoWidth"       : true,
      "bAutoWidth"      : true
    });
  });

  table = $('#table_data').DataTable();

    $('#btnAdd').on( 'click', function () {

      $("#modal-edit-label").html('Add New');
      $("#isNewRecord").val("1");

      form = $('#form-edit')[0];
      form.reset();

    });

    $('.mydatepicker').datepicker({
      format: 'dd/mm/yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });

    $('#form-edit').validator().on('submit', function(e) {
      if (!e.isDefaultPrevented()){
        $.ajax({
          url     : baseURL + 'master/save_rate',
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
      $("#id_rate").val(data.id_rate);
      $("#txtCurrencyDate").val(data.currency_date);
      $("#txtUSD").val(data.usd);
      $("#txtHKD").val(data.hkd);
      $("#txtEUR").val(data.eur);
      $("#txtSGD").val(data.sgd);
      $("#txtJPY").val(data.jpy);
      $("#txtAUD").val(data.aud);
      $("#modal-edit-label").html('Edit Data Currency');
      $("#modal-edit").modal('show');
    });

    $('#table_data ').on( 'click', 'a.action-delete', function () {

      data      = table.row( $(this).parents('tr') ).data();
      id_delete = data.id_rate;

      $("#modal-delete-label").html('Delete Data Currency ');
      $("#modal-delete").modal('show');
    });

  $('#button-delete').on( 'click', function () {

    $.ajax({
      url       : baseURL + 'master/delete_rate/' + id_delete,
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
    let vdate_from = '';
    let vdate_to = '';

    var url   ="<?php echo site_url(); ?>Master/cetak_data_rate";

    vdate_from = $("#ddlDateFrom").val();
    vdate_to = $("#ddlDateTo").val();

    window.open(url+'?date_from='+ vdate_from+"&date_to="+ vdate_to, '_blank');
    window.focus();
  });

   $('#btnView').on( 'click', function () {

    date_from = $("#ddlDateFrom").val();
    date_to = $("#ddlDateTo").val();
    table.draw();

    // $('#tblDataInquiry').css( 'display', 'block' );
    // table.columns.adjust().draw();
  });


   $('#modal-edit').on('hidden.bs.modal', function () {
    if ($('#form-edit').find('.has-error, .has-danger').length > 0) {
      $(".help-block").html('');
      $('.has-error').removeClass('has-error');
    }

  });

 });



</script>