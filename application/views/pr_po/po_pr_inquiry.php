
<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Filter</h5>
    </div>
  </div>
  <div class="white-box boxshadow mt-0 z-index-2 py-10 border-bottom-only-5">
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label>Directorate</label>
          <select class="form-control" id="directorate">
            <?php
              if(count($directorat) > 1):
                echo '<option value="0">--All Directorate--</option>';
              endif;
              foreach($directorat as $value): ?>
              <option value="<?= $value['ID_DIR_CODE'] ?>" data-name="<?= $value['DIRECTORAT_NAME'] ?>"><?= $value['DIRECTORAT_NAME'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>PR Date</label>
          <div class="input-group">
            <input class="form-control input-daterange-datepicker" type="text" id="pr_date" value="<?= dateFormat(strtotime('-3 days'), 'date') ?> - <?= dateFormat(time(), 'date') ?>"/>
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btnView" class="btn btn-default btn-outline border-radius-5 w-150p btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div id="tbl_search" class="col-md-12 positon-relative">
          <i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
          <input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
      </div>
      <div class="col-md-12">
          <table id="table_data" class="table table-hover small display table-striped table-responsive dataTable w-full">
            <thead>
              <tr>
                <th>Directorate</th>
                <th>PR Number</th>
                <th>PR Name</th>
                <th>PR Date</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
          </thead>
          </table>
    	</div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){

  const c_pr_date     = readCookie('pr_date');
  const line_selected = readCookie('po_inq_line_selected');
  const pr_category         = <?= $id_pr_category ?>;

  if(c_pr_date != null){
    $("#pr_date").val(c_pr_date);
    eraseCookie("pr_date");
  }

	let directorate = $("#directorate").val();
	let pr_date     = $("#pr_date").val();
	let url         = baseURL + 'purchase-order/api/load_pr_header';

  Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                  "url"  : url,
                  "type" : "POST",
                  "dataType": "json",
                  "data"    : function ( d ) {
                                        d.directorat  = directorate;
                                        d.pr_date     = pr_date;
                                        d.pr_category = pr_category;
                                      }
                          },
        "language"        : {
                              "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
                              "infoEmpty"   : "Data Kosong",
                              "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
                              "search"      : "_INPUT_",
                              "paginate": {
                                          'previous': '<i class="fa fa-angle-left"></i>',
                                          'next': '<i class="fa fa-angle-right"></i>',
                                        },
                              "lengthMenu": " &nbsp; _MENU_ &nbsp; rows per page"
                            },
        "columns"         : [
                      { "data": "directorat", "width": "90px" },
                      { "data": "pr_number", "width": "90px"},
                      { "data": "pr_name", "width": "150px" },
                      { "data": "pr_date", "width": "80px"},
                      { "data": "total_amount", "width": "100px"},
                      {
                        "data": "pr_header_id",
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
          "fnDrawCallback": function () {
            $('#table_data_length').prepend($('#table_data_info'));
          },
           "dom": '<"top"i>rt<"bottom"flp><"clear">',
        "ordering"        : false,
        "scrollY"         : 520,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "bAutoWidth"      : true,
      });
  });

  let table = $('#table_data').DataTable();
  $("#table_data_filter").remove();
  $("#tbl_search").on('keyup', "input[type='search']", function(){
      table.search( $(this).val() ).draw();
  });

   $('#table_data tbody').on( 'click', 'tr', function () {
    if (! $(this).hasClass('selected') ) {
      table.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });

    $('#btnView').on( 'click', function () {
      directorate = $("#directorate").val();
      pr_date     = $("#pr_date").val();
      table.draw();
    });

    let change_date = false;
    $("#pr_date").bind("click", function(e) {
      change_date = true;
    }).bind("change", function(e) {
      if(change_date){
        eraseCookie("pr_date");
        createCookie("pr_date", $(this).val());
      }
    });

    $('#table_data').on('click', 'a.action-create', function () {
      link = $(this).data('id');
      get_table = table.row( $(this).parents('tr') );
      index     = get_table.index();
      eraseCookie("po_inq_line_selected");
      createCookie("po_inq_line_selected", index);
      $(location).attr('href', baseURL + 'purchase-order/create/' + link);
    });

  });
</script>