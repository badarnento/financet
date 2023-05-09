<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-sm-3">
        <div class="form-group">
          <label>PR Date From</label>
			<div class="input-group">
				<input type="text" class="form-control mydatepicker" id="pr_date_from" placeholder="dd-mm-yyyy" value="<?= dateFormat(time(), 5)?>">
				<span class="input-group-addon"><i class="icon-calender"></i></span>
			</div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group">
          <label>PR Date To</label>
			<div class="input-group">
				<input type="text" class="form-control mydatepicker" id="pr_date_to" placeholder="dd-mm-yyyy" value="<?= dateFormat(time(), 5)?>">
				<span class="input-group-addon"><i class="icon-calender"></i></span>
			</div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btn-view" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-search"></i> <span> VIEW</span></button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
    	<div class="col-md-12">
    		<table id="table_data" class="table table-responsive table-striped table-bordered display cell-border stripe w-full">
            <thead>
              <tr>
				<th class="text-center">PR Number</th>
				<th class="text-center">PR Name</th>
				<th class="text-center">PR Amount</th>
				<th class="text-center">Currency</th>
				<th class="text-center">Action</th>
              </tr>
            </thead>
          </table>
    	</div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function(){

  const c_date_from   = readCookie('po_inq_date_from');
  const c_date_to     = readCookie('po_inq_date_to');
  const line_selected = readCookie('po_inq_line_selected');

  if(c_date_from != null){
    $("#pr_date_from").val(c_date_from);
    eraseCookie("po_inq_date_from");
  }
  if(c_date_to != null){
    $("#pr_date_to").val(c_date_to);
    eraseCookie("po_inq_date_to");
  }

	let date_from  = $("#pr_date_from").val();
	let date_to    = $("#pr_date_to").val();
	
	let url_header = baseURL + 'purchase/load_data_pr_for_po_inquiry';

  Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url_header,
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
                      { "data": "pr_number", "width": "100px", "class": "text-center" },
                      { "data": "pr_name", "width": "150px" },
                      { "data": "pr_amount", "width": "100px", "class": "text-right" },
                      { "data": "currency", "width": "100px", "class": "text-center" },
                      {
                        "data": "pr_header_id_enc",
                        "width":"80px",
                        "class":"text-center",
                        "render": function (data) {
                           return '<a href="javascript:void(0)" class="action-create" title="Click to create PO" data-id="' + data + '"><i class="fa fa-pencil text-success" aria-hidden="true"></i> Create PO</a>';
                        }
                      }
                ],
          "drawCallback": function ( settings ) {
            if(line_selected != null){
              setTimeout(function(){
                $('#table_data tbody tr').eq(line_selected).addClass('selected-grey');
                eraseCookie("po_inq_line_selected");
              }, 300);
            }
          },
        "pageLength"      : 10,
        "ordering"        : false,
        "scrollY"         : 480,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "bAutoWidth"      : true
      });
  });

    table = $('#table_data').DataTable();

    $('#btn-view').on( 'click', function () {
		date_from = $("#pr_date_from").val();
		date_to   = $("#pr_date_to").val();
    	table.draw();
    });

    $('.mydatepicker').datepicker({
  		format: 'dd-mm-yyyy',
  		todayHighlight:'TRUE',
  		autoclose: true,
    });

    $('#pr_date_from').on( 'change', function () {
      eraseCookie("po_inq_date_from");
      createCookie("po_inq_date_from", $(this).val());
    });

    $('#pr_date_to').on( 'change', function () {
      eraseCookie("po_inq_date_to");
      createCookie("po_inq_date_to", $(this).val());
    });

    $('#table_data').on('click', 'a.action-create', function () {
      link = $(this).data('id');
      get_table = table.row( $(this).parents('tr') );
      index     = get_table.index();
      eraseCookie("po_inq_line_selected");
      createCookie("po_inq_line_selected", index);
      $(location).attr('href', baseURL + 'budget/purchase-order/create/' + link);
    });

    <?php if($this->session->flashdata('messages') != ""): ?>
      customNotif('Error', '<?= $this->session->flashdata('messages') ?>', 'error');
    <?php endif; ?>

  });
</script>