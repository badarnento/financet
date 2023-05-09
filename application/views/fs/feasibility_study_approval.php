<div class="row">
  <form class="form-horizontal">
    <div class="form-group m-b-10">
            <div class="col-xs-4 col-sm-2">
             <select class="form-control" id="status">
               <option value="">-- All Status --</option>
               <option value="request_approve" selected>Need Approval</option>
               <option value="approved">Approved</option>
               <option value="returned">Returned</option>
               <option value="rejected">Rejected</option>
             </select>
            </div>
            <div class="col-xs-4 col-sm-2 w-auto">
              <button id="btnView" class="btn btn-info btn-outline border-radius-5 w-100p m-b-10" type="button"><i class="fa fa-search"></i> View</button>
            </div>
        </div>
  </form>
</div>

<?php $this->load->view('ilustration') ?>

<div class="row">
  <div class="white-box boxshadow border-radius-5 z-index-2 panel-title-custom">
    <div class="row">
      <div id="tbl_search" class="col-md-12 positon-relative">
          <i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
          <input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
      </div>
      <div class="col-md-12">
          <table id="table_data" class="table table-hover small display table-striped table-responsive dataTable w-full">
            <thead>
              <tr>
                <th>DIRECTORAT</th>
                <th>DIVISON</th>
                <th>UNIT</th>
                <th>Justif NUMBER</th>
                <th>Justif NAME</th>
                <th>TOTAL AMOUNT</th>
                <th>STATUS</th>
                <th>ACTION</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){


	let status        = $('#status').val();
	let dtl_clicked   = false;
	let id_fs = 0;

  let url        = baseURL + 'budget/approval/api/load_fs_to_approv';
  /*  let ajaxData = {
      "url"  : url,
      "type" : "POST",
      "dataType": "json",
      "data"    : function ( d ) {
       d.status    = status;
     }
   }

   let jsonData = [
			{ "data": "directorat", "width": "100px", "class": "p-5" },
			{ "data": "division", "width": "100px", "class": "p-5" },
			{ "data": "unit", "width": "100px", "class": "text-center p-5" },
			{ "data": "fs_number", "width": "100px", "class": "p-5" },
			{ "data": "fs_name", "width": "100px", "class": "p-5" },
			{ "data": "total_amount", "width": "100px", "class": "text-right p-5" },
			{ "data": "status_description", "width": "100px", "class": "text-center p-5" },
			{ 
                "data": "id_fs_approval",
                "width":"50px",
                "class":"text-center",
                "render": function (data) {
                   return '<a href="javascript:void(0)" class="action-view" title="Click to view FS Detail" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>';
                }
            }
         ];

	 data_table(ajaxData,jsonData);

	 table = $('#table_data').DataTable();*/

   Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
                              "url"  : url,
                              "type" : "POST",
                              "dataType": "json",
                              "data"    : function ( d ) {
                                           d.status = status;
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
                            { "data": "directorat", "width": "100px" },
                            { "data": "division", "width": "100px" },
                            { "data": "unit", "width": "100px"},
                            { "data": "fs_number", "width": "150px" },
                            { "data": "fs_name", "width": "150px" },
                            { "data": "total_amount", "width": "100px"},
                            { "data": "status_description", "width": "100px"},
                            { 
                                "data": "id_fs_approval",
                                "width":"50px",
                                "class":"text-center",
                                "render": function (data) {
                                   return '<a href="javascript:void(0)" class="action-view" title="Click to view Justification Detail" data-id="' + data + '"><i class="fa fa-search text-success" aria-hidden="true"></i></a>';
                                }
                            }
                ],
          "drawCallback": function ( settings ) {
            if(line_selected != null){
              setTimeout(function(){
                $('#table_data tbody tr').eq(line_selected).addClass('selected-grey');
                eraseCookie("fs_line_selected");
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
    }else{
      table.$('tr.selected').removeClass('selected');
    }
  });


	$('#btnView').on('click', function () {
		status = $('#status').val();
		table.draw();
	});


	$('#table_data').on('click', 'a.action-view', function () {
      id_fs_approval = $(this).data('id');
      $(location).attr('href', baseURL + 'budget/approval/' + id_fs_approval);
	});

});
</script>