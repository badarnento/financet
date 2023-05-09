<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-sm-3">
      <div class="form-group">
        <label class="form-control-label" for="ddlfilterdateby">Filter Date By</label>
        <select class="form-control" id="ddlfilterdateby" name="ddlfilterdateby">
          <option value="">-- Choose filter date -- </option> 
        </select> 
      </div>
    </div>
      <div class="col-md-3 col-sm-6">
        <div class="form-group">
          <label>Date From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="invoice_date_from" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="form-group">
          <label>Date To</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="invoice_date_to" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
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
    	<div class="col-md-12">
    		<table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe small">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th class="text-center">Tgl Invoice</th>
              <th class="text-center">Batch Name</th>
              <th class="text-center">No Journal</th>
              <th class="text-center">Nama Vendor</th>
              <th class="text-center">No Invoice</th>
              <th class="text-center">No Kontrak</th>
              <th class="text-center">Description</th>
              <th class="text-center">Currency</th>
              <th class="text-center">AP Amount</th>
              <th class="text-center">No FPJP</th>
              <th class="text-center">Nama Rekening</th>
              <th class="text-center">Nama Bank</th>
              <th class="text-center">Acct Number</th>
              <th class="text-center">TOP</th>
              <th class="text-center">Due Date</th>
              <th class="text-center">Nature</th>
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
    let date_from   = $("#invoice_date_from").val();
    let date_to     = $("#invoice_date_to").val();
    // let date_from     = "";
    // let date_to       = "";
    let filterdateby  = $("#ddlfilterdateby").val();
    getFilterDate();
    $("#btnView").attr('disabled', true);

    let url        = baseURL + 'outstanding-ap/api/load_data_outstanding';
    let ajaxData = {
        "url"  : url,
        "type" : "POST",
        "dataType": "json",
        "data"    : function ( d ) {
         d.date_from        = date_from;
         d.date_to          = date_to;
         d.filterdateby     = filterdateby;
       }
     }

     let jsonData = [
              { "data": "no", "width": "10px", "class": "text-center" },
              { "data": "tgl_invoice", "width": "70px", "class": "text-center" },
              { "data": "batch_name", "width": "100px", "class": "text-center" },
              { "data": "no_journal", "width": "100px", "class": "text-center" },
              { "data": "nama_vendor", "width": "150px", "class": "text-left" },
              { "data": "no_invoice", "width": "150px", "class": "text-center" },
              { "data": "no_kontrak", "width": "150px", "class": "text-center" },
              { "data": "description", "width": "240px", "class": "text-left" },
              { "data": "currency", "width": "70px", "class": "text-center" },
              { "data": "dpp", "width": "150px", "class": "text-right" },
              { "data": "no_fpjp", "width": "150px", "class": "text-center" },
              { "data": "nama_rekening", "width": "150px", "class": "text-left" },
              { "data": "nama_bank", "width": "150px", "class": "text-left" },
              { "data": "acct_number", "width": "100px", "class": "text-center" },
              { "data": "top", "width": "70px", "class": "text-center" },
              { "data": "due_date", "width": "80px", "class": "text-center" },
              { "data": "nature", "width": "70px", "class": "text-center" }
           ];

   data_table(ajaxData,jsonData);

   table = $('#table_data').DataTable();


  $('#btnView').on( 'click', function () {
    date_from     = $("#invoice_date_from").val();
    date_to       = $("#invoice_date_to").val();
    filterdateby  = $("#ddlfilterdateby").val();
    table.draw();
  });


  $('#invoice_date_from').on( 'change', function () {
    date_from = $("#invoice_date_from").val();
    date_to   = $("#invoice_date_to").val();
  });
  $('#invoice_date_to').on( 'change', function () {
    date_from = $("#invoice_date_from").val();
    date_to   = $("#invoice_date_to").val();
  });

  $("#btnPrint").on("click", function(){

    filterdateby  = $("#ddlfilterdateby").val();
    let url = baseURL + "outstanding-ap/api/download_inquiry";
    window.open(url+'?invoice_date_from='+date_from +"&invoice_date_to="+ date_to+"&filterdateby="+ filterdateby, '_blank');
    window.focus();

  });


  $('.mydatepicker').datepicker({
    format: 'dd-mm-yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
  });

  function getFilterDate()
  {
    $.ajax({
      url   : baseURL + 'outstanding-ap/api/load_ddl_filter_date_by',
      type  : "POST",
      dataType: "html",
      success : function(result){
        $("#ddlfilterdateby").html("");       
        $("#ddlfilterdateby").html(result);          
      }
    });     
  }

  $("#ddlfilterdateby").on("change", function(){
    filter_date = $("#ddlfilterdateby").val();
     $("#btnView").attr('disabled', false);
  });

  });
</script>