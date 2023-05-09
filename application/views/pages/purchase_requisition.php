<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label>Directorate</label>
          <select class="form-control" id="directorate">
            <option value="0">--All Directorate--</option>
            <?php foreach($directorat as $value): ?>
            <option value='<?= $value['ID_DIR_CODE'] ?>'><?= $value['DIRECTORAT_NAME'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>Status</label>
          <select class="form-control" id="status">
            <option value="0">--All Status--</option>
            <?php foreach ($pr_status as $key => $value): ?>
            <option value='<?= $key ?>'><?= $value ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="form-group">
          <label>PR Date From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="pr_date_from" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
            </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="form-group">
          <label>PR Date To</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="pr_date_to" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
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
    </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-sm-6 col-sm-offset-6 col-md-3 col-md-offset-9">
        <div class="form-group pull-right">
          <label>&nbsp;</label>
          <button id="btn-create" class="btn btn-success btn-rounded w-200p" type="button" ><i class="fa  fa-plus"></i> <span> CREATE NEW</span></button>
        </div>
      </div>
    </div>
    <div class="row">
    	<div class="col-md-12">
    		<table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe small">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">Directorate</th>
                <th class="text-center">PR Number</th>
                <th class="text-center">PR Name</th>
                <th class="text-center">PR Date</th>
                <th class="text-center">Currency</th>
                <!-- <th class="text-center">Status</th> -->
                <th class="text-center">Total Amount</th>
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
              </tr
>            </thead>
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

  const c_date_from   = readCookie('pr_date_from');
  const c_date_to     = readCookie('pr_date_to');
  const line_selected = readCookie('pr_line_selected');

  if(c_date_from != null){
    $("#pr_date_from").val(c_date_from);
    eraseCookie("pr_date_from");
  }
  if(c_date_to != null){
    $("#pr_date_to").val(c_date_to);
    eraseCookie("pr_date_to");
  }

  let directorate = $("#directorate").val();
  let pr_status   = $("#status").val();
  let date_from   = $("#pr_date_from").val();
  let date_to     = $("#pr_date_to").val();
                   
	let url        = baseURL + 'purchase/load_data_header';

  Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                                        d.status     = pr_status;
                                        d.directorat = directorate;
                                        d.date_from  = date_from;
                                        d.date_to    = date_to;
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
                      { "data": "directorat", "width": "100px" },
                      { "data": "pr_number", "width": "100px", "class": "text-center" },
                      { "data": "pr_name", "width": "150px" },
                      { "data": "pr_date", "width": "80px", "class": "text-center" },
                      { "data": "currency", "width": "80px", "class": "text-center" },
                      { "data": "total_amount", "width": "100px", "class": "text-right" },
                      {
                        "data": "status",
                        "width":"150px",
                        "class":"text-center",
                        "render": function (data) {
                          <?php if(in_array("PR Inquiry", $group_name)) : ?>
                          let status_change = '<div class="form-group m-0"><select class="form-control input-sm action-status" disabled>';
                          if(data.toLowerCase() == "approved"){
                            status_change += '<option value="Approved" selected>Approved <i class="fa fa-check-circle text-success" aria-hidden="true"></i></option>';
                            status_change += '<option value="Canceled">Canceled <i class="fa fa-times-circle text-danger" aria-hidden="true"></i></option>';
                            status_change += '</select></div>';
                          }
                          else if(data.toLowerCase()== "canceled"){
                            status_change += '<option value="Approved">Approved <i class="fa fa-check-circle text-success" aria-hidden="true"></i></option>';
                            status_change += '<option value="Canceled" selected>Canceled <i class="fa fa-times-circle text-danger" aria-hidden="true"></i></option>';
                            status_change += '</select></div>';
                          }
                          else if(data.toLowerCase()== "po created"){
                            status_change = data;
                          }
                          return status_change;

                          <?php else : ?>
                          let status_on = '<div class="form-group m-0"><select class="form-control input-sm action-status">';
                          if(data.toLowerCase() == "approved"){
                            status_on += '<option value="Approved" selected>Approved <i class="fa fa-check-circle text-success" aria-hidden="true"></i></option>';
                            status_on += '<option value="Canceled">Canceled <i class="fa fa-times-circle text-danger" aria-hidden="true"></i></option>';
                            status_on += '</select></div>';
                          }
                          else if(data.toLowerCase()== "canceled"){
                            status_on += '<option value="Approved">Approved <i class="fa fa-check-circle text-success" aria-hidden="true"></i></option>';
                            status_on += '<option value="Canceled" selected>Canceled <i class="fa fa-times-circle text-danger" aria-hidden="true"></i></option>';
                            status_on += '</select></div>';
                          }
                          else if(data.toLowerCase()== "po created"){
                            status_on = data;
                          }
                          return status_on;
                        <?php endif ?>
                        }
                      },
                      { 
                        "data": "pr_header_id",
                        "width":"80px",
                        "class":"text-center",
                        "render": function (data) {
                           let $show = '<a href="javascript:void(0)" class="action-view" title="Click to view PR" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-edit" title="Click to edit PR" data-id="' + data + '"><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete"><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';

                           let $hide = '<a href="javascript:void(0)" class="action-view" title="Click to view PR" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-edit" title="Click to edit PR" data-id="' + data + '" hidden><i class="fa fa-edit text-warning" aria-hidden="true"></i></a>&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="action-delete" title="Click to delete" hidden><i class="fa fa-trash text-danger" aria-hidden="true"></i></a>';

                           let $hasi = "";

                           <?php if(in_array("PR Inquiry", $group_name)) : ?>
                            $hasil = $hide;
                            return $hasil;

                            <?php else : ?>
                              $hasil = $show;
                              return $hasil;
                            <?php endif ?>
                        }
                      }
                ],
          "drawCallback": function ( settings ) {
            if(line_selected != null){
              setTimeout(function(){
                $('#table_data tbody tr').eq(line_selected).addClass('selected-grey');
                eraseCookie("pr_line_selected");
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

  /*	data_table(ajaxData,jsonData);

    table = $('#table_data').DataTable();*/

    $('#btnView').on( 'click', function () {
      directorate = $("#directorate").val();
      pr_status   = $("#status").val();
      date_from   = $("#pr_date_from").val();
      date_to     = $("#pr_date_to").val();
      table.draw();
    });

    $('#pr_date_from').on( 'change', function () {
      eraseCookie("pr_date_from");
      createCookie("pr_date_from", $(this).val());
    });

    $('#pr_date_to').on( 'change', function () {
      eraseCookie("pr_date_to");
      createCookie("pr_date_to", $(this).val());
    });

    $('#table_data').on('click', 'a.action-view', function () {
      pr_header_id = $(this).data('id');
      get_table    = table.row( $(this).parents('tr') );
      index        = get_table.index();
      eraseCookie("pr_line_selected");
      createCookie("pr_line_selected", index);
      $(location).attr('href', baseURL + 'budget/purchase-requisition/' + pr_header_id);
    });

    $('#table_data').on('click', 'a.action-edit', function () {
      pr_header_id = $(this).data('id');
      get_table    = table.row( $(this).parents('tr') );
      index        = get_table.index();
      data         = get_table.data();
      status       = data.status;

      if(status.toLowerCase() == "po created"){
        customNotif('Warning', 'Tidak bisa di edit karena status '+status, 'warning');
      }else{
        eraseCookie("pr_line_selected");
        createCookie("pr_line_selected", index);
        $(location).attr('href', baseURL + 'budget/purchase-requisition/edit/' + pr_header_id);
      }
    });

    $('#table_data').on('click', 'a.action-delete', function () {
      data      = table.row( $(this).parents('tr') ).data();
      id_delete = data.id;
      status    = data.status

      if(status.toLowerCase() != "canceled"){
        customNotif('Warning', 'Tidak bisa di hapus karena status '+status, 'warning');
      }else{
        $("#modal-delete-label").html('Delete Data : ' +  data.pr_name);
        $("#modal-delete").modal('show');
      }
      
    });

    $('#table_data').on('change', 'select.action-status', function () {
      data         = table.row( $(this).parents('tr') ).data();
      pr_header_id = data.id;
      status = $(this).val();

      $.ajax({
          url       : baseURL + 'purchase/change_status_pr',
          type      : 'post',
          data      : { pr_header_id: pr_header_id, status: status },
          dataType : 'json',
          success : function(result){
            if (result.status == true) {
              table.ajax.reload(null, false);
              customNotif('Success', result.messages, 'success');
            } else {
              customNotif('Failed', result.messages, 'error');
            }
          }
        });
    });

    $('#button-delete').on( 'click', function () {

        $.ajax({
          url       : baseURL + 'purchase/delete_pr',
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

    $('#btn-create').on( 'click', function () {
      customLoading('show');
      setTimeout(function(){
        $(location).attr('href', baseURL + 'budget/purchase-requisition/create'/* + result.pr_number*/);
      }, 300);
    });

    $('.mydatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });


    //added by adi baskoro
    $("#btnPrint").on("click", function(){

       let vpr_date_from = '';
       let vpr_date_to = '';

       let url   ="<?php echo site_url(); ?>purchase/download_data_pr_header";

       vpr_date_from = $("#pr_date_from").val();
       vpr_date_to = $("#pr_date_to").val();
       vdir = $("#directorate").val();
       vstat = $("#status").val();

       window.open(url+'?date_from='+vpr_date_from +"&date_to="+vpr_date_to+"&directorate="+vdir+"&status="+vstat, '_blank');

       window.focus();

 });

    <?php if($this->session->flashdata('messages') != ""): ?>
      customNotif('Error', '<?= $this->session->flashdata('messages') ?>', 'error');
    <?php endif; ?>

    <?php if(in_array("PR Inquiry", $group_name)) : ?>
      $("#btn-create").hide();
    <?php endif ?>

  });
</script>