<div>

  <div class="white-box boxshadow">  

    <form role="form" id="form-save" data-toggle="validator">

      <div class="row">

       <div class="col-sm-12">

        <div class="col-sm-3">
          <div class="form-group">
            <label>Batch Date From</label>
            <div class="input-group">
              <input type="text" class="form-control mydatepicker" id="ddlBatchDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
              <span class="input-group-addon"><i class="icon-calender"></i></span>
            </div>
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <label>Batch Date To</label>
            <div class="input-group">
              <input type="text" class="form-control mydatepicker" id="ddlBatchDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
              <span class="input-group-addon"><i class="icon-calender"></i></span>
            </div>
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <label>Invoice Date</label>
            <div class="input-group">
              <input type="text" class="form-control mydatepicker" id="ddlInvoiceDate" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
              <span class="input-group-addon"><i class="icon-calender"></i></span>
            </div>
          </div>
        </div>

      </div>

    </div>

    <div class="row">

      <div class="col-sm-12">

       <div class="col-sm-3">
        <div class="form-group">
          <label class="form-control-label" for="lblBatchName">Batch Name</label>
          <select class="form-control" id="ddlBatchName" name="ddlBatchName" data-toggle="validator" data-error="Please choose one" required>
            <option value="">-- Choose Batch Name --</option> 
          </select>
          <div class="help-block with-errors"></div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label class="form-control-label" for="lblBatchNumber">Batch Number</label>
          <select class="form-control" id="ddlBatchNumber" name="ddlBatchNumber" data-toggle="validator" data-error="Please choose one" required>
            <option value="">-- Choose Batch Number --</option> 
          </select>
          <div class="help-block with-errors"></div>
        </div>
      </div>

      <div class="col-sm-4">
        <div class="form-group">
          <label class="form-control-label" for="lblJournalPaymentNumber">Journal Payment Number</label>
          <select class="form-control" id="ddlJournalPaymentNumber" name="ddlJournalPaymentNumber" data-toggle="validator" data-error="Please choose one" required>
            <option value="">-- Choose Journal Payment Number --</option> 
          </select>
          <div class="help-block with-errors"></div>
        </div>
      </div>

    </div>

  </div>

  <div class="row">
    <div class="col-sm-3">

      <div class="form-group">

        <label>&nbsp;</label>

        <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-bars"></i> <span>VIEW</span></button>

      </div>

    </div>

    <div class="col-sm-3">

      <div class="form-group">

        <label>&nbsp;</label>

        <button id="btn-create" class="btn btn-success btn-rounded custom-input-width btn-block" type="button"><span> Create Batch </span> <i class="fa fa-plus"></i> </button>

      </div>

    </div>
  </div>

</form>

</div>

</div>


<div class="row" id="tblData">

  <div class="col-md-12">

    <div class="white-box">

      <hr>

      <h4 id="table_detail_title2">inquiry</h4>

      <table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover w-full dataTable small">

        <thead>

          <tr>

            <th class="text-center">No.</th>

            <th class="text-center">Batch Date</th>

            <th class="text-center">Batch Name</th>

            <th class="text-center">Batch Number</th>

            <th class="text-center">Journal Payment Number</th>

            <th class="text-center">Tanggal Invoice</th>

            <th class="text-center">No Journal</th>

            <th class="text-center">Nama Vendor</th>

            <th class="text-center">No Invoice</th>

            <th class="text-center">No Kontrak</th>

            <th class="text-center">Description</th>

            <th class="text-center">DPP</th>

            <th class="text-center">No FPJP</th>

            <th class="text-center">Nama Rekening</th>

            <th class="text-center">Nama Bank</th>

            <th class="text-center">Acct Number</th>

            <!-- <th class="text-center">RKAP Name</th> -->

            <th class="text-center">TOP</th>

            <th class="text-center">Due Date</th>

            <!--  <th class="text-center">Nature</th> -->

            <th class="text-center">Bank Charge</th>

          </tr>

        </thead>

      </table>

    </div>

  </div>

</div>

<div class="row" id="tblDatadownload">
  <div class="white-box boxshadow">     
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <div class="col-md-offset-5 col-md-2 m-b-10">
            <button id="btnDownload" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row" id="tblDataJournal">

  <div class="col-md-12">

    <div class="white-box">

      <hr>

      <h4 id="table_detail_title2">Journal Payment Batch</h4>

      <table id="table_data2" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover w-full dataTable small">

        <thead>

          <tr>

            <th class="text-center">No.</th>
            <th class="text-center">Tanggal Accounting</th>
            <th class="text-center">Currency</th>
            <th class="text-center">Nature</th>
            <th class="text-center">Account Description</th>
            <th class="text-center">Debet</th>
            <th class="text-center">Kredit</th>
            <th class="text-center">Batch Name</th>
            <th class="text-center">Batch Description</th>

          </tr>

        </thead>

      </table>

    </div>

  </div>

  <div class="row" id="tblDatadownload2">
    <div class="white-box boxshadow">     
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <div class="col-md-offset-5 col-md-2 m-b-10">
              <button id="btnDownload2" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <script>

   $(document).ready(function(){

    getBatchName();
    getBatchNumber();
    getJPNumber();

    let batch_date_from = $("#ddlBatchDateFrom").val();
    let batch_date_to = $("#ddlBatchDateTo").val();
    let batch_name = $("#ddlBatchName").val();
    let batch_number = $("#ddlBatchNumber").val();
    let journal_payment_number = $("#ddlJournalPaymentNumber").val();
    let invoice_date = $("#ddlInvoiceDate").val();


    $('#tblData').hide();
    $('#tblDatadownload').hide();
    $('#tblDataJournal').hide();
    $('#tblDatadownload2').hide();

    $('.mydatepicker').datepicker({
      format: 'dd/mm/yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });

    let url  = baseURL + 'paymentbatch/load_data_payment_batch_inquiry';

    let ajaxData = {

      "url"  : url,

      "type" : "POST",

      "data"    : function ( d ) {

        d.batch_date_from = batch_date_from;
        d.batch_date_to   = batch_date_to;
        d.batch_name = batch_name;
        d.batch_number = batch_number;
        d.journal_payment_number = journal_payment_number;
        d.invoice_date = invoice_date;
      }

    }

    let jsonData = [
    { "data": "no", "width": "10px", "class": "text-center" },
    { "data": "batch_date", "width": "150px", "class": "text-left" },
    { "data": "batch_name", "width": "150px", "class": "text-left" },
    { "data": "batch_number", "width": "150px", "class": "text-left" },
    { "data": "journal_payment_number", "width": "150px", "class": "text-left" },
    { "data": "tanggal_invoice", "width": "100px", "class": "text-left" },
    { "data": "no_journal", "width": "150px", "class": "text-left" },
    { "data": "nama_vendor", "width": "150px", "class": "text-left" },
    { "data": "no_invoice", "width": "100px", "class": "text-left" },
    { "data": "no_kontrak", "width": "100px", "class": "text-left" },
    { "data": "description", "width": "250px", "class": "text-left" },
    { "data": "dpp", "width": "100px", "class": "text-left" },
    { "data": "no_fpjp", "width": "100px", "class": "text-left" },
    { "data": "nama_rekening", "width": "100px", "class": "text-left" },
    { "data": "nama_bank", "width": "100px", "class": "text-left" },
    { "data": "acct_number", "width": "100px", "class": "text-left" },
    // { "data": "rkap_name", "width": "100px", "class": "text-left" },
    { "data": "top", "width": "100px", "class": "text-left" },
    { "data": "due_date", "class":"text-letf", "width": "100px" },
    // { "data": "nature", "class":"text-letf", "width": "100px" },
    { "data": "bank_charge", "width": "100px", "class": "text-center" }

  ];

  data_table(ajaxData,jsonData);

  table = $('#table_data').DataTable();


  $("#btnDownload").on("click", function(){

   let vbatch_date_from = '';
   let vbatch_date_to = '';
   let vbatch_name = '';
   let vbatch_number = '';
   let vjournal_payment_number = '';
   let vinvoice_date = '';

   let url   ="<?php echo site_url(); ?>paymentbatch/download_data_payment_batch_inquiry";

   vbatch_date_from = $("#ddlBatchDateFrom").val();
   vbatch_date_to = $("#ddlBatchDateTo").val();
   vbatch_name = $("#ddlBatchName").val();
   vbatch_number = $("#ddlBatchNumber").val();
   vjournal_payment_number = $("#ddlJournalPaymentNumber").val();
   vinvoice_date = $("#ddlInvoiceDate").val();

   window.open(url+'?date_from='+vbatch_date_from +"&date_to="+ vbatch_date_to +"&batch_name="+ vbatch_name +"&batch_number="+ vbatch_number +"&journal_payment_number="+ vjournal_payment_number+"&invoice_date="+ vinvoice_date, '_blank');

   window.focus();

 });


  let url2  = baseURL + 'paymentbatch/load_data_journal_payment_batch_inquiry';

  let ajaxData2 = {

    "url"  : url2,

    "type" : "POST",

    "data"    : function ( d ) {

      d.batch_date_from = batch_date_from;
      d.batch_date_to   = batch_date_to;
      d.batch_name = batch_name;
      d.batch_number = batch_number;
    }

  }

  let jsonData2 = [
  { "data": "no", "width": "10px", "class": "text-center" },
  { "data": "accounting_date", "width": "100px", "class": "text-left" },
  { "data": "currency", "width": "150px", "class": "text-left" },
  { "data": "nature", "width": "150px", "class": "text-left" },
  { "data": "account_description", "width": "150px", "class": "text-left" },
  { "data": "debet", "width": "100px", "class": "text-left" },
  { "data": "kredit", "width": "100px", "class": "text-left" },
  { "data": "batch_name", "width": "250px", "class": "text-left" },
  { "data": "batch_desc", "width": "100px", "class": "text-left" },
  ];

  data_table(ajaxData2,jsonData2,"table_data2");

  table2 = $('#table_data2').DataTable();


  $("#btnDownload2").on("click", function(){

   let vbatch_date_from = '';
   let vbatch_date_to = '';
   let vinvoice_date = '';
   let vbatchname = '';
   let vbatchnumber = '';
   let vjournal_payment_number ='';

   let url2   ="<?php echo site_url(); ?>paymentbatch/download_data_journal_payment_batch_inquiry";

   vbatch_date_from = $("#ddlBatchDateFrom").val();
   vbatch_date_to = $("#ddlBatchDateTo").val();
   vbatch_name = $("#ddlBatchName").val();
   vbatch_number = $("#ddlBatchNumber").val();
   vjournal_payment_number = $("#ddlJournalPaymentNumber").val();

   window.open(url2+'?date_from='+vbatch_date_from +"&date_to="+ vbatch_date_to +"&batch_name="+ vbatch_name +"&batch_number="+ vbatch_number, '_blank');

   window.focus();

 });


  $('#btnView').on( 'click', function () {

    batch_date_from = $("#ddlBatchDateFrom").val();
    batch_date_to = $("#ddlBatchDateTo").val();
    batch_name = $("#ddlBatchName").val();
    batch_number = $("#ddlBatchNumber").val();
    journal_payment_number = $("#ddlJournalPaymentNumber").val();
    invoice_date = $("#ddlInvoiceDate").val();

    table.draw();

    $('#tblData').slideDown(700);

    $('#tblData').css( 'display', 'block' );
    table.columns.adjust().draw();
    $('#tblDatadownload').slideDown(700);

    table2.draw();

    $('#tblDataJournal').slideDown(700);

    $('#tblData').css( 'display', 'block' );
    table2.columns.adjust().draw();
    $('#tblDatadownload2').slideDown(700);
  });

  function getBatchName()
  {
    let batch_date_from = $("#ddlBatchDateFrom").val();
    let batch_date_to = $("#ddlBatchDateTo").val();

    $.ajax({
      url   : baseURL + 'paymentbatch/load_ddl_batch_name',
      type  : "POST",
      data  : {param_batch_date_from :  batch_date_from, param_batch_date_to :  batch_date_to},
      dataType: "html",
      success : function(result){
        $("#ddlBatchName").html("");       
        $("#ddlBatchName").html(result);          
      }
    });     
  }

  function getBatchNumber()
  {
    let batch_date_from = $("#ddlBatchDateFrom").val();
    let batch_date_to = $("#ddlBatchDateTo").val();

    $.ajax({
      url   : baseURL + 'paymentbatch/load_ddl_batch_number',
      type  : "POST",
      data  : {param_batch_date_from :  batch_date_from, param_batch_date_to :  batch_date_to},
      dataType: "html",
      success : function(result){
        $("#ddlBatchNumber").html("");       
        $("#ddlBatchNumber").html(result);          
      }
    });     
  }

  function getJPNumber()
  {
    let batch_date_from = $("#ddlBatchDateFrom").val();
    let batch_date_to = $("#ddlBatchDateTo").val();

    $.ajax({
      url   : baseURL + 'paymentbatch/load_ddl_pj_number',
      type  : "POST",
      data  : {param_batch_date_from :  batch_date_from, param_batch_date_to :  batch_date_to},
      dataType: "html",
      success : function(result){
        $("#ddlJournalPaymentNumber").html("");       
        $("#ddlJournalPaymentNumber").html(result);          
      }
    });     
  }

  $("#ddlBatchDateFrom").on("change", function(){
   getBatchName();
   getBatchNumber();
   getJPNumber();
 });

  $("#ddlBatchDateTo").on("change", function(){
   getBatchName();
   getBatchNumber();
   getJPNumber();
 });

  $('#btn-create').on( 'click', function () {
    customLoading('show');
    setTimeout(function(){
      $(location).attr('href', baseURL + 'paymentbatch/payment-batch');
    }, 300);
  });

});

</script>