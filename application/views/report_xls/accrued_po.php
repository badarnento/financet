<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-md-2 col-sm-6">
        <div class="form-group">
          <label>Accrue Type</label>
          <select class="form-control" id="type">
            <option value="0">-- Choose All Type --</option>
              <?php 
                  foreach($get_accr as $value):
                      echo "<option value='".$value['TYPE']."'>".$value['TYPE']."</option>";
                  endforeach
              ?>
          </select>
        </div>
      </div>
      <!-- <div class="col-md-3 col-sm-6">
        <div class="form-group">
          <label>Date From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="date_from" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="form-group">
          <label>Date To</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="date_to" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div> -->
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
      <div id="tbl_search" class="col-md-12 positon-relative">
        <i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
        <input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
      </div>
      <div class="col-md-12">
        <table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe small">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th class="text-center">Accounting Date</th>
              <th class="text-center">Batch Name</th>
              <th class="text-center">Journal Name</th>
              <th class="text-center">Journal Description</th>
              <th class="text-center">Account Description</th>
              <th class="text-center">nature</th>
              <th class="text-center">Debit</th>
              <th class="text-center">Credit</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- added by adi baskoro -->
<div class="row">
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

<script>
  $(document).ready(function(){

    /*let date_from     = $("#date_from").val();
    let date_to       = $("#date_to").val();*/
    let type          = $("#type").val();

    let url        = baseURL + 'report/accrued_po/load_data';
    Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
          "url"  : url,
          "type" : "POST",
          "dataType": "json",
          "data"    : function ( d ) {
               /*d.date_from        = date_from;
               d.date_to          = date_to;*/
               d.type             = type;
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
                            { "data": "accounting_date", "width": "100px", "class": "text-center" },
                            { "data": "batch_name", "width": "100px", "class": "text-center" },
                            { "data": "journal_name", "width": "180px", "class": "text-center" },
                            { "data": "journal_description", "width": "300px", "class": "text-left" },
                            { "data": "account_description", "width": "300px", "class": "text-left" },
                            { "data": "nature", "width": "80px", "class": "text-center" },
                            { "data": "debit", "width": "100px", "class": "text-right" },
                            { "data": "credit", "width": "100px", "class": "text-right" }
        ],
        "pageLength"      : 100,
        "ordering"        : false,
        "scrollY"         : 480,
        "scrollCollapse"  : true,
        "scrollX"         : true,
        "autoWidth"       : true,
        "bAutoWidth"      : false
      });
    });

   table = $('#table_data').DataTable();
  $('#table_data_filter').remove();
  $('#table_data_length').remove();
  $("#tbl_search").on('keyup', "input[type='search']", function(){
      table.search( $(this).val() ).draw();
  });

  $('#btnView').on( 'click', function () {
      /*date_from     = $("#date_from").val();
      date_to       = $("#date_to").val();*/
      type          = $("#type").val();
    table.draw();
  });

  
  $("#btnPrint").on("click", function(){

      /*date_from     = $("#date_from").val();
      date_to       = $("#date_to").val();*/
      type          = $("#type").val();

    let url = baseURL + "report/accrued_po/cetak_report";
    window.open(url+'?type='+ type, '_blank');
    window.focus();

  });


  $('.mydatepicker').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
  });

  });
</script>