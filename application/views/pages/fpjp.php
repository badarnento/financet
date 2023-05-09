<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label>Status</label>
          <select class="form-control" id="status">
            <option value="0">--All Status--</option>
            <?php foreach ($fpjp_status as $key => $value): ?>
            <option value='<?= $key ?>'><?= $value ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <!-- <div class="col-md-3">
        <div class="form-group">
          <label>Directorat</label>
          <select class="form-control" id="directorat">
            <option value="0">--Pilih--</option>
            <?php foreach($directorat as $value): ?>
            <option value='<?= $value['ID_DIR_CODE'] ?>'><?= $value['DIRECTORAT_NAME'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div> -->
      <div class="col-sm-2">
      <div class="form-group">
        <label>FPJP Date From</label>
        <div class="input-group">
          <input type="text" class="form-control mydatepicker" id="ddlInnvoiceDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
          <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
      </div>
    </div>

    <div class="col-sm-2">
      <div class="form-group">
        <label>FPJP Date To</label>
        <div class="input-group">
          <input type="text" class="form-control mydatepicker" id="ddlInnvoiceDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
          <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
      </div>
    </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btn-create" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-plus"></i> <span> CREATE NEW FPJP</span></button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-md-12">
        <table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe small">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">Direktorat</th>
                <th class="text-center">FPJP Number</th>
                <th class="text-center">FPJP Name</th>
                <th class="text-center">FPJP Date</th>
                <th class="text-center">Total Amount</th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>

<!-- added by adi baskoro -->
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

<script>
  $(document).ready(function(){

  const c_date_from   = readCookie('fpjp_date_from');
  const c_date_to     = readCookie('fpjp_date_to');
  const line_selected = readCookie('fpjp_line_selected');

  if(c_date_from != null){
    $("#ddlInnvoiceDateFrom").val(c_date_from);
    eraseCookie("fpjp_date_from");
  }
  if(c_date_to != null){
    $("#ddlInnvoiceDateTo").val(c_date_to);
    eraseCookie("fpjp_date_to");
  }

  let fpjp_status       = $("#status").val();
  let invoice_date_from = $("#ddlInnvoiceDateFrom").val();
  let invoice_date_to   = $("#ddlInnvoiceDateTo").val();

  let directorat = $("#directorat").val();
  let url        = baseURL + 'fpjp/load_data_header';

  Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                                       d.status            = fpjp_status;
                                       d.invoice_date_from = invoice_date_from;
                                       d.invoice_date_to   = invoice_date_to;
                                      }
                          },
        "language"        : {
                              "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
                              "infoEmpty"   : "Data Kosong",
                              "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
                              "search"      : "_INPUT_"
                            },
        "columns"         : [
                              { "data": "no", "width": "10px", "class": "text-center" },
                              { "data": "directorat", "width": "150px" },
                              { "data": "fpjp_number", "width": "150px", "class": "text-center" },
                              { "data": "fpjp_name", "width": "150px" },
                              { "data": "fpjp_date", "width": "100px", "class": "text-center" },
                              { "data": "total_amount", "width": "150px", "class": "text-right" },
                              {
                                "data": "status",
                                "width":"150px",
                                "class":"text-center",
                                "render": function (data) {
                                  <?php if(in_array("FPJP Inquiry", $group_name)) : ?>
                                  let status_change = '<div class="form-group m-0"><select class="form-control action-status input-sm" disabled>';
                                  if(data.toLowerCase() == "approved"){
                                    status_change += '<option value="Approved" selected>Approved <i class="fa fa-check-circle text-success" aria-hidden="true"></i></option>';
                                    status_change += '<option value="Canceled">Canceled <i class="fa fa-times-circle text-danger" aria-hidden="true"></i></option>';
                                  }
                                  else if(data.toLowerCase()== "canceled"){
                                    status_change += '<option value="Approved">Approved <i class="fa fa-check-circle text-success" aria-hidden="true"></i></option>';
                                    status_change += '<option value="Canceled" selected>Canceled <i class="fa fa-times-circle text-danger" aria-hidden="true"></i></option>';
                                  }

                                  status_change += '</select></div>';
                                  return status_change;

                                  <?php else : ?>
                                  let status_on = '<div class="form-group m-0"><select class="form-control action-status input-sm">';
                                  if(data.toLowerCase() == "approved"){
                                    status_on += '<option value="Approved" selected>Approved <i class="fa fa-check-circle text-success" aria-hidden="true"></i></option>';
                                    status_on += '<option value="Canceled">Canceled <i class="fa fa-times-circle text-danger" aria-hidden="true"></i></option>';
                                  }
                                  else if(data.toLowerCase()== "canceled"){
                                    status_on += '<option value="Approved">Approved <i class="fa fa-check-circle text-success" aria-hidden="true"></i></option>';
                                    status_on += '<option value="Canceled" selected>Canceled <i class="fa fa-times-circle text-danger" aria-hidden="true"></i></option>';
                                  }

                                  status_on += '</select></div>';
                                  return status_on;
                                <?php endif ?>
                                }
                              },
                              { 
                                "data": "fpjp_header_id",
                                "width":"80px",
                                "class":"text-center",
                                "render": function (data) {
                                 let $show = '<a href="javascript:void(0)" class="action-view" title="Click to view FPJP" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-edit" title="Click to edit FPJP" data-id="' + data + '"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-cetak" title="Click to Download PDF File" data-id="' + data + '"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';

                                 let $hide = '<a href="javascript:void(0)" class="action-view" title="Click to view FPJP" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-edit" title="Click to edit FPJP" data-id="' + data + '" hidden><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-cetak" title="Click to Download PDF File" data-id="' + data + '" hidden><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';

                                 let $hasi = "";

                                 <?php if(in_array("FPJP Inquiry", $group_name)) : ?>
                                    let $hasil = $hide;
                                    return $hasil;

                                  <?php else : ?>
                                    let $hasil = $show;
                                    return $hasil;
                                  <?php endif ?>
                              }
                            }
                ],
          "drawCallback": function ( settings ) {
            if(line_selected != null){
              setTimeout(function(){
                $('#table_data tbody tr').eq(line_selected).addClass('selected-grey');
                eraseCookie("fpjp_line_selected");
              }, 300);
            }
          },
        "pageLength"      : 50,
        "ordering"        : false,
        "scrollY"         : 480,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "bAutoWidth"      : true,
      });
  });

  let table = $('#table_data').DataTable();
   /* data_table(ajaxData,jsonData);

    table = $('#table_data').DataTable();*/

    $('#btnView').on( 'click', function () {
      //directorat = $("#directorat").val();
      fpjp_status       = $("#status").val();
      invoice_date_from = $("#ddlInnvoiceDateFrom").val();
      invoice_date_to   = $("#ddlInnvoiceDateTo").val();
      table.draw();
    });

    $('#ddlInnvoiceDateFrom').on( 'change', function () {
      eraseCookie("fpjp_date_from");
      createCookie("fpjp_date_from", $(this).val());
    });

    $('#ddlInnvoiceDateTo').on( 'change', function () {
      eraseCookie("fpjp_date_to");
      createCookie("fpjp_date_to", $(this).val());
    });

    $('#table_data').on('click', 'a.action-view', function () {
      let fpjp_header_id = $(this).data('id');
      $(location).attr('href', baseURL + 'fpjp/fpjp_view/' + fpjp_header_id);
    });

    $('#table_data').on('click', 'a.action-edit', function () {
      let fpjp_header_id = $(this).data('id');
      $(location).attr('href', baseURL + 'fpjp/fpjp_edit/' + fpjp_header_id);
    });

    $('#btn-create').on( 'click', function () {

    // let directorat = $("#directorat").val();

  //    if(directorat == 0){
  //      customNotif('Error','Please select Directorat','error' );
    // }
    // else{

      $.ajax({
        url     : baseURL + 'fpjp/create_fpjp',
        type    : "POST",
        data      : {id_dir :  directorat},
        dataType  : "json",
        beforeSend  : function(){
                customLoading('show');
              },
        success   : function(result){
                if(result.status == true){
                  
                  setTimeout(function(){
                    $(location).attr('href', baseURL + 'fpjp/new_fpjp/'/* + result.pr_number*/);
                  }, 3000);
                }
                else{
                  customNotif('Error', result.messages, 'error');
                }
              }
        });
    // }
    });

    $('#table_data').on('click', 'a.action-delete', function () {
      data      = table.row( $(this).parents('tr') ).data();
      id_delete = data.id;
      status    = data.status
      if(status.toLowerCase() != "canceled"){
        customNotif('Warning', 'Tidak bisa di hapus karena status '+status, 'warning');
      }else{
        $("#modal-delete-label").html('Delete Data : ' +  data.fpjp_name);
        $("#modal-delete").modal('show');
      }
    });

    $('#table_data').on('change', 'select.action-status', function () {
      data         = table.row( $(this).parents('tr') ).data();
      fpjp_header_id = data.id;
      status = $(this).val();

      $.ajax({
          url       : baseURL + 'fpjp/change_status_fpjp',
          type      : 'post',
          data      : { fpjp_header_id: fpjp_header_id, status: status },
          beforeSend  : function(){
                          customLoading('show');
                        },
          dataType : 'json',
          success : function(result){
            $("#modal-delete").modal('hide');
            customLoading('hide');
            if (result.status == true) {
              table.ajax.reload(null, false);
              customNotif('Success', 'Update Succes', 'success');
            } else {
              customNotif('Failed', result.messages, 'error');
            }
          }
        });
    });

    $('#button-delete').on( 'click', function () {

        $.ajax({
          url       : baseURL + 'fpjp/delete',
          type      : 'post',
          data      : { id: id_delete, category: 'header' },
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

    //added by adi baskoro
    $("#btnPrint").on("click", function(){

       let vddlInnvoiceDateFrom = '';
       let vddlInnvoiceDateTo = '';

       let url   ="<?php echo site_url(); ?>fpjp/download_data_fpjp_header";

       vddlInnvoiceDateFrom = $("#ddlInnvoiceDateFrom").val();
       vddlInnvoiceDateTo = $("#ddlInnvoiceDateTo").val();
       vdir = $("#directorate").val();
       vstat = $("#status").val();

       window.open(url+'?date_from='+vddlInnvoiceDateFrom +"&date_to="+vddlInnvoiceDateTo, '_blank');

       window.focus();

 });

    $('.mydatepicker').datepicker({
    format: 'dd/mm/yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
    });

    $('#table_data').on('click', 'a.action-cetak', function () {
       let fpjp_header_id = $(this).data('id');
    var url   ="<?php echo site_url(); ?>fpjp/printPDF";
    if (!table.data().any()){
       customNotif('Info','Data Kosong!','warning' );
       exit();
    }else{
      window.open(url+'?fpjp_header_id='+fpjp_header_id, '_blank');
      window.focus();
    }


  });

    <?php if(in_array("FPJP Inquiry", $group_name)) : ?>
     $("#btn-create").hide();
    <?php endif ?>

  });
</script>