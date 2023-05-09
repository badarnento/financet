<div>

  <div class="white-box boxshadow">  

    <form role="form" id="form-save" data-toggle="validator">

      <div class="row">

       <div class="col-sm-12">

        <div class="col-sm-3">
          <div class="form-group">
            <label>Invoice Date From</label>
            <div class="input-group">
              <input type="text" class="form-control mydatepicker" id="ddlInnvoiceDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
              <span class="input-group-addon"><i class="icon-calender"></i></span>
            </div>
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <label>Invoice Date To</label>
            <div class="input-group">
              <input type="text" class="form-control mydatepicker" id="ddlInnvoiceDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
              <span class="input-group-addon"><i class="icon-calender"></i></span>
            </div>
          </div>
        </div>

        <div class="col-sm-3">
          <div class="form-group">
            <label>Batch Date</label>
            <div class="input-group">
              <input type="text" class="form-control mydatepicker" id="ddlBatchDate" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
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
          <input type="text" class="form-control" id="txtBatchName" name="txtBatchName" placeholder="Batch Name" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required />
          <div class="help-block with-errors"></div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label class="form-control-label" for="lblBatchNumber">Batch Number</label>
          <input type="text" class="form-control" id="txtBatchNumber" name="txtBatchNumber" placeholder="Batch Number" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required />
          <div class="help-block with-errors"></div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label class="form-control-label" for="lblJournalPaymentNumber">Journal Payment Number</label>
          <input type="text" class="form-control" id="txtJournalPaymentNumber" name="txtJournalPaymentNumber" placeholder="Journal Payment Number" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" required />
          <div class="help-block with-errors"></div>
        </div>
      </div>

    </div>

  </div>

  <div class="row">
    <div class="col-sm-12">

      <div class="col-sm-6">
        <div class="form-group">
          <label class="form-control-label" for="lblDescription">Journal Description</label>
          <textarea name="txtDescription" id="txtDescription" class="form-control" rows="3" autocomplete="off" data-toggle="validator" data-error="Please fill out this field" placeholder="Journal Description" required ></textarea>
          <div class="help-block with-errors"></div>
        </div>
      </div>

    </div>
  </div>

  <div class="row">

    <div class="col-sm-12">

      <div class="col-sm-3">
        <label class="form-control-label" for="lblVendorName">Vendor Name</label>
        <select class="form-control" id="ddlVendorName" name="ddlVendorName" data-toggle="validator" data-error="Please choose one" required>
          <option value=""> Choose Vendor </option> 
        </select> 
        <div class="help-block with-errors"></div>
      </div>

      <div class="col-sm-3">
        <label class="form-control-label" for="lblBankName">Bank Name</label>
        <select class="form-control" id="ddlBankName" name="ddlBankName" data-toggle="validator" data-error="Please choose one" required disabled="true">
          <option value=""> Choose Bank </option> 
        </select> 
        <div class="help-block with-errors"></div>
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
  </div>

</form>

</div>

</div>


<div class="row" id="tblData">

  <div class="col-md-12">

    <div class="white-box">

      <hr>

      <h4 id="table_detail_title">Payment Batch</h4>

      <table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover w-full dataTable small">

        <thead>

          <tr>

            <th class="text-center">No.</th>

            <th class="text-center">Tanggal Invoice</th>

            <th class="text-center">Batch Name</th>

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

            <!--  <th class="text-center">RKAP Name</th> -->

            <th class="text-center">TOP</th>

            <th class="text-center">Due Date</th>

            <!-- <th class="text-center">Nature</th> -->

            <th> 
              <div class="checkbox checkbox-inverse" class="text-center">
                <input id="checkboxAll" type="checkbox">
                <label for="checkboxAll">  Bank Charge </label>
              </div>
            </th> 

          </tr>

        </thead>

      </table>

      <div class="form-group">

        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
         <button type="button" class="btn btn-info waves-effect" id="btn_save"><i class="fa fa-save"></i> Save</button>
       </div>
       
     </div>

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

    getVendorName();
    getBankName();

    let batch_date = $("#ddlBatchDate").val();
    let invoice_date_from = $("#ddlInnvoiceDateFrom").val();
    let invoice_date_to = $("#ddlInnvoiceDateTo").val();
    let vendor_name = $("#ddlVendorName").val();
    let bank_name = $("#ddlBankName").val();
    let batch_name = $("#txtBatchName").val();
    let batch_number = $("#txtBatchNumber").val();
    let journal_description = $("#txtDescription").val();

    $('#tblData').hide();
    $('#tblDatadownload').hide();
    $('#tblDataJournal').hide();
    $('#tblDatadownload2').hide();

    $('.mydatepicker').datepicker({
      format: 'dd/mm/yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });

    let url  = baseURL + 'paymentbatch/load_data_payment_batch';

    let ajaxData = {

      "url"  : url,

      "type" : "POST",

      "data"    : function ( d ) {

        d.invoice_date_from = invoice_date_from;
        d.invoice_date_to   = invoice_date_to;
        d.vendor_name = vendor_name;
        d.bank_name = bank_name;
      }

    }

    let jsonData = [
    { "data": "no", "width": "10px", "class": "text-center" },
    { "data": "tanggal_invoice", "width": "100px", "class": "text-left" },
    { "data": "batch_name", "width": "150px", "class": "text-left" },
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

   let vinvoice_date_from = '';
   let vinvoice_date_to = '';
   let vvendor_name = '';
   let vbank_name = '';

   let url   ="<?php echo site_url(); ?>paymentbatch/download_data_payment_batch";

   vinvoice_date_from = $("#ddlInnvoiceDateFrom").val();
   vinvoice_date_to = $("#ddlInnvoiceDateTo").val();
   vvendor_name = $("#ddlVendorName").val();
   vbank_name = $("#ddlBankName").val();

   window.open(url+'?date_from='+vinvoice_date_from +"&date_to="+ vinvoice_date_to +"&vendor_name="+ vvendor_name +"&bank_name="+ vbank_name, '_blank');

   window.focus();

 });



  let url2  = baseURL + 'paymentbatch/load_data_journal_payment_batch';

  let ajaxData2 = {

    "url"  : url2,

    "type" : "POST",

    "data"    : function ( d ) {

      d.invoice_date_from = invoice_date_from;
      d.invoice_date_to   = invoice_date_to;
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

   let vinvoice_date_from = '';
   let vinvoice_date_to = '';
   let vbatchname = '';
   let vbatchnumber = '';

   let url2   ="<?php echo site_url(); ?>paymentbatch/download_data_journal_payment_batch";

   vinvoice_date_from = $("#ddlInnvoiceDateFrom").val();
   vinvoice_date_to = $("#ddlInnvoiceDateTo").val();
   vbatchname = $("#txtBatchName").val();
   vbatchnumber = $("#txtBatchNumber").val();

   window.open(url+'?date_from='+vinvoice_date_from +"&date_to="+ vinvoice_date_to +"&batch_name="+ vbatchname +"&batch_number="+ vbatchnumber, '_blank');

   window.focus();

 });



  $('#btnView').on( 'click', function () {

    invoice_date_from = $("#ddlInnvoiceDateFrom").val();
    invoice_date_to = $("#ddlInnvoiceDateTo").val();
    vendor_name = $("#ddlVendorName").val();
    bank_name = $("#ddlBankName").val();
    batch_name = $("#txtBatchName").val();
    batch_number = $("#txtBatchNumber").val();

    table.draw();

    $('#tblData').slideDown(700);
    $('#tblData').css( 'display', 'block' );
    table.columns.adjust().draw();
    $('#tblDatadownload').slideDown(700);
  });

  function getVendorName(){
    $.ajax({
      url   : baseURL + 'gl/load_ddl_vendor',
      type  : "POST",
      dataType: "html",
      success : function(result){
        $("#ddlVendorName").html("");       
        $("#ddlVendorName").html(result);          
      }
    });     
  }

  function getBankName()
  {
    let param_vendor_name = $("#ddlVendorName").val();

    $.ajax({
      url   : baseURL + 'gl/load_ddl_bank',
      type  : "POST",
      data  : {param_vendor_name :  param_vendor_name},
      dataType: "html",
      success : function(result){
        $("#ddlBankName").html("");       
        $("#ddlBankName").html(result);          
      }
    });     
  }

  $("#ddlVendorName").on("change", function(){
    getBankName();
    $("#ddlBankName").attr("disabled",false); 
  });

  $("#checkboxAll").on("click", function(){
    if($(this).prop('checked') == false){      
      $(".checklist").prop('checked',false);
    } else {
     $(".checklist").prop('checked',true);
   } 
 });

  // $("#btn_save").on('click', function () {
  //   let detail_data_all   = [];
  //   let detail_data  = [];
  //   let total_data = table.data().count();
  //   let data_line  = [];
  //   let bank_charge  ='';
  //   let remarks ='';
  //   let jurnal ='';
  //   let replacejurnal ='';
  //   let i = 1;
  //   detail_data_all.push(detail_data);

  //   table.column(3).data().each( function (value, index) 
  //   { 
  //     jurnal = value;
  //     replacejurnal = jurnal.replace(/[^a-zA-Z0-9]/g, '');
  //     get_checkbox = document.getElementById("checkbox-"+ replacejurnal);

  //     if (get_checkbox.checked == true) 
  //     {
  //       bank_charge  = 'Y';
  //     }
  //     else
  //     {
  //       bank_charge  = 'N';
  //     }

  //     console.log(bank_charge);
  //     console.log(value);

  //     data_line.push({'no_journal' : value, 'bank_charge' : bank_charge , 'detail_data' : detail_data_all[i]});

  //   });

  //   data = {
  //     data_line : data_line
  //   }

  //   $.ajax({
  //     url   : baseURL + 'paymentbatch/save_payment_batch',
  //     type  : "POST",
  //     data  : data,
  //     dataType: "json",
  //     success : function(result){
  //       console.log(result);
  //       if(result == 1)
  //       {
  //         customNotif('Success', "Data has change", 'success');
  //         table.ajax.reload(null, false);
  //       }
  //       else {
  //         customNotif('Failed', "Data not change", 'error');
  //       }
  //     }
  //   });

  // });

  $("#btn_save").validator().on('click', function (e) {
    $( "#form-save" ).submit();
  });

  $('#form-save').validator().on('submit', function(e) {
    if (!e.isDefaultPrevented()){

      vbatch_name = $("#txtBatchName").val();
      vbatch_number = $("#txtBatchNumber").val();
      vjournalpaymentnumber = $("#txtJournalPaymentNumber").val();
      vjournal_description = $("#txtDescription").val();
      vbatch_date = $("#ddlBatchDate").val();
      vinvoice_date_from = $("#ddlInnvoiceDateFrom").val();
      vinvoice_date_to = $("#ddlInnvoiceDateTo").val();
      vvendor_name = $("#ddlVendorName").val();
      vbank_name = $("#ddlBankName").val();


      let detail_data_all   = [];
      let detail_data  = [];
      let total_data = table.data().count();
      let data_line  = [];
      let bank_charge  ='';
      let remarks ='';
      let jurnal ='';
      let replacejurnal ='';
      let i = 1;
      detail_data_all.push(detail_data);

      table.column(3).data().each( function (value, index) 
      { 
        jurnal = value;
        replacejurnal = jurnal.replace(/[^a-zA-Z0-9]/g, '');
        get_checkbox = document.getElementById("checkbox-"+ replacejurnal);

        if (get_checkbox.checked == true) 
        {
          bank_charge  = 'Y';
        }
        else
        {
          bank_charge  = 'N';
        }

        console.log(bank_charge);
        console.log(value);

        data_line.push({'no_journal' : value, 'bank_charge' : bank_charge , 'detail_data' : detail_data_all[i]});

      });

      data = {
        data_line : data_line,
        batch_name : vbatch_name,
        batch_number : vbatch_number,
        journal_payment_number : vjournalpaymentnumber,
        jurnal_description : vjournal_description,
        batch_date : vbatch_date,
        invoice_date_from : vinvoice_date_from,
        invoice_date_to : vinvoice_date_to,
        vendor_name : vvendor_name,
        bank_name : vbank_name
      }

      $.ajax({
        url   : baseURL + 'paymentbatch/save_payment_batch',
        type  : "POST",
        data  : data,
        dataType: "json",
        success : function(result){
          console.log(result);
          if(result == 1)
          {
            customNotif('Success', "Data payment batch success fully created", 'success');
            table.ajax.reload(null, false);
            table2.ajax.reload(null, false);
          }
          else {
            customNotif('Failed', "Data payment batch failed to create", 'error');
          }
        }
      });

      table2.draw();

      $('#tblDataJournal').slideDown(700);
      $('#tblDataJournal').css( 'display', 'block' );
      table2.columns.adjust().draw();
      $('#tblDatadownload2').slideDown(700);

    }
    e.preventDefault();
  });



});

</script>