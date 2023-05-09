<div class="row">
  <div class="white-box boxshadow">
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
      <div class="col-md-2 col-sm-6">
        <div class="form-group">
          <label>PO Period From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="pr_date_from" placeholder="dd-mm-yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
            </div>
      </div>
      <div class="col-md-2 col-sm-6">
        <div class="form-group">
          <label>PO Period To</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="pr_date_to" placeholder="dd-mm-yyyy" value="<?= dateFormat(time(), 5)?>">
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
    		<table id="table_data" class="table table-responsive table-striped table-bordered display cell-border stripe w-full">
            <thead>
              <tr>
                <th class="text-center">No</th>
                <th class="text-center">PR Number</th>
                <th class="text-center">PO Number</th>
                <th class="text-center">PO Name</th>
                <th class="text-center">PO Date</th>
                <th class="text-center">Currency</th>
                <!-- <th class="text-center">Status</th> -->
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

  const c_date_from   = readCookie('po_pr_date_from');
  const c_date_to     = readCookie('po_pr_date_to');
  const line_selected = readCookie('po_pr_line_selected');
  
  const dis_status    = '<?= (in_array("PO Inquiry", $group_name)) ? " disabled" : "" ?>';
  const hide_action   = '<?= (in_array("PO Inquiry", $group_name)) ? " d-none" : "" ?>';

  if(c_date_from != null){
    $("#pr_date_from").val(c_date_from);
    eraseCookie("po_pr_date_from");
  }
  if(c_date_to != null){
    $("#pr_date_to").val(c_date_to);
    eraseCookie("po_pr_date_to");
  }

  let po_status = $("#status").val();
  let date_from = $("#pr_date_from").val();
  let date_to   = $("#pr_date_to").val();

	let url        = baseURL + 'purchase/load_data_po';

  Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                                        d.status    = po_status;
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
                              { "data": "no", "width": "10px", "class": "text-center" },
                              { "data": "pr_number", "width": "150px", "class": "text-center hide" },
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
                eraseCookie("po_pr_line_selected");
              }, 300);
            }
          },
        "pageLength"      : 100,
        "ordering"        : false,
        "scrollY"         : 480,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "rowsGroup"       : [1,7,8],
        "bAutoWidth"      : true
      });
  });

  let table = $('#table_data').DataTable();
  $('input[type="search"]').attr('placeholder','Search here...').addClass('form-control input-sm m-0');

    $('#btnView').on( 'click', function () {
  		po_status = $("#status").val();
  		date_from = $("#pr_date_from").val();
  		date_to   = $("#pr_date_to").val();
  		
  		table.draw();
    });

    $('#pr_date_from').on( 'change', function () {
      eraseCookie("po_pr_date_from");
      createCookie("po_pr_date_from", $(this).val());
    });

    $('#pr_date_to').on( 'change', function () {
      eraseCookie("po_pr_date_to");
      createCookie("po_pr_date_to", $(this).val());
    });

    $('#table_data').on('click', 'a.action-view', function () {
      pr_header_id = $(this).data('id');
      get_table    = table.row( $(this).parents('tr') );
      index        = get_table.index();
      eraseCookie("po_pr_line_selected");
      createCookie("po_pr_line_selected", index);
      $(location).attr('href', baseURL + 'budget/purchase-order/' + pr_header_id);
    });

    $('#table_data').on('click', 'a.action-edit', function () {
      pr_header_id = $(this).data('id');
      get_table    = table.row( $(this).parents('tr') );
      index        = get_table.index();
      eraseCookie("po_pr_line_selected");
      createCookie("po_pr_line_selected", index);
      $(location).attr('href', baseURL + 'budget/purchase-order/edit/' + pr_header_id);
    });

    $('#table_data').on('click', 'a.action-delete', function () {
      data      = table.row( $(this).parents('tr') ).data();
      id_delete = data.id;
      status    = data.status;

      if(status.toLowerCase() != "canceled"){
        customNotif('Warning', 'Tidak bisa di hapus karena status '+status, 'warning');
      }else{
        $("#modal-delete-label").html('Delete Data : ' +  data.po_name);
        $("#modal-delete").modal('show');
      }
    });

    $('#table_data').on('change', 'select.action-status', function () {
      data         = table.row( $(this).parents('tr') ).data();
      po_header_id = data.id;
      pr_header_id = data.pr_header_id;
      status       = $(this).val();

      $.ajax({
          url       : baseURL + 'purchase/change_status_po',
          type      : 'post',
          data      : { po_header_id: po_header_id, pr_header_id: pr_header_id, status: status },
          beforeSend  : function(){
                          customLoading('show');
                        },
          dataType : 'json',
          success : function(result){
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

    $('#button-delete').on( 'click', function () {

        $.ajax({
          url       : baseURL + 'purchase/delete_po',
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
        $(location).attr('href', baseURL + 'budget/purchase-order/create'/* + result.pr_number*/);
      }, 300);
    });

    $('.mydatepicker').datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });

    //added by adi baskoro
    $("#btnPrint").on("click", function(){

		let vpo_period_from = '';
		let vpo_period_to = '';

		let url   ="<?php echo site_url(); ?>purchase/download_data_po_header";

		vpo_period_from = $("#pr_date_from").val();
		vpo_period_to = $("#pr_date_to").val();
		vstat = $("#status").val();

		window.open(url+'?date_from='+vpo_period_from +"&date_to="+vpo_period_to+"&status="+vstat, '_blank');

		window.focus();
	});

    <?php if($this->session->flashdata('messages') != ""): ?>
      customNotif('Error', '<?= $this->session->flashdata('messages') ?>', 'error');
    <?php endif; ?>

    <?php if(in_array("PO Inquiry", $group_name)): ?>
      $("#btn-create").hide();
    <?php endif ?>

  });
</script>