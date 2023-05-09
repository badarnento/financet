<div>

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

      <div class="col-sm-3">
        <div class="form-group">
          <label>Date From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="ddlInnvoiceDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Date To</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="ddlInnvoiceDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>

      <div class="col-sm-3">

        <div class="form-group">

          <label>&nbsp;</label>

          <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-bars"></i> <span>VIEW</span></button>

        </div>

      </div>

    </div>  

  </div>


  <div class="row" id="tblDataInquiry">

    <div class="col-md-12">

      <div class="white-box">

        <label> Inquiry </label>
        <br>

        <table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">

          <thead>

            <tr>

              <th class="text-center">No.</th>
              <th class="text-center">Tanggal Invoice</th>
              <th class="text-center">No Journal</th>
              <th class="text-center">Nama Vendor</th>
              <th class="text-center">No Invoice</th>
              <th class="text-center">Currency</th>
              <th class="text-center">Total Amount</th>
              <th class="text-center">Description</th>
              <th class="text-center">No Kontrak</th>
              <th class="text-center">No FPJP</th>
              <th class="text-center">Dpp</th>
              <th class="text-center">Ppn</th>
              <th class="text-center">Sub Total</th>
              <th class="text-center">Pph</th>
              <th class="text-center">Total</th>
              <th class="text-center">Nama Rekening</th>
              <th class="text-center">Nama Bank</th>
              <th class="text-center">Acct Number</th>
              <th class="text-center">Rkap Name</th>
              <th class="text-center">Top</th>
              <th class="text-center">Tax Verification Date</th>
              <th class="text-center">No Seri Faktur Pajak</th>
              <th class="text-center">Nomor Npwp</th>
              <th class="text-center">Journal AP By</th>
              <th class="text-center">Nature</th>
              <th class="text-center">Coa Parent</th>
              <th class="text-center">Due Date</th>
              <th class="text-center">Hand Over To Treasury By</th>
              <th class="text-center">Payment Create</th>
              <th class="text-center">Transfer Amount</th>
              <th class="text-center">Payment Date</th>
              <th class="text-center">Difference</th>
              <th class="text-center">Status</th>
              <th class="text-center">AR Netting</th>
              <th class="text-center">AR Amount</th>
              <th class="text-center">AR Invoice Description</th>

            </tr>

          </thead>

        </table>

      </div>

    </div>

  </div>

  <div class="row" id="tblDatadownload">
    <div class="col-md-12">
      <div class="white-box boxshadow">
        <div class="row">
         <div class="col-md-6 col-md-offset-3 text-center">
           <button id="btnPrint" class="btn btn-success btn-rounded w-150p m-b-10" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
         </div>
       </div>
     </div>
   </div>
 </div>


</div>



<script>

 $(document).ready(function(){

  let invoice_date_from = $("#ddlInnvoiceDateFrom").val();
  let invoice_date_to = $("#ddlInnvoiceDateTo").val();
  let filterdateby = $("#ddlfilterdateby").val();
  getFilterDate();
  $("#btnView").attr('disabled', true);

  $('.mydatepicker').datepicker({
    format: 'dd/mm/yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
  });

  let url  = baseURL + 'Invoicetracker/load_data_invoice_tracker_inquiry';

  /*console.log('testing' + url);

  let ajaxData = {

        "url"  : url,

        "type" : "POST",

        "data"    : function ( d ) {

          d.invoice_date_from = invoice_date_from;
          d.invoice_date_to   = invoice_date_to;

        }

      }

      let jsonData     = [
      
      { "data": "no", "width": "10px", "class": "text-center" },
      { "data": "TANGGAL", "width": "150px", "class": "text-left" },
      { "data": "NO_JOURNAL", "width": "100px", "class": "text-left" },
      { "data": "NAMA_VENDOR", "width": "150px", "class": "text-left" },
      { "data": "NO_INVOICE", "width": "150px", "class": "text-left" },
      { "data": "TOTAL_AMOUNT", "width": "150px", "class": "text-left" },
      { "data": "DESCRIPTION", "width": "150px", "class": "text-left" },
      { "data": "NO_KONTRAK", "width": "100px", "class": "text-left" },
      { "data": "NO_FPJP", "width": "100px", "class": "text-left" },
      { "data": "DPP", "class":"text-letf", "width": "100px" },
      { "data": "PPN", "width": "250px", "class": "text-left" },
      { "data": "SUB_TOTAL", "width": "250px", "class": "text-left" },
      { "data": "PPH", "width": "100px", "class": "text-left" },
      { "data": "TOTAL", "width": "100px", "class": "text-left" },
      { "data": "NAMA_REKENING", "width": "100px", "class": "text-left" },
      { "data": "NAMA_BANK", "width": "100px", "class": "text-left" },
      { "data": "ACCT_NUMBER", "width": "200px", "class": "text-left" },
      { "data": "RKAP_NAME", "width": "100px", "class": "text-center" },
      { "data": "TOP", "width": "100px", "class": "text-left" },
      { "data": "TAX_VERIFICATION_DATE", "width": "100px", "class": "text-left" },
      { "data": "NO_SERI_FAKTUR_PAJAK", "width": "100px", "class": "text-left" },
      { "data": "NOMOR_NPWP", "width": "100px", "class": "text-left" },
      { "data": "JOURNAL_AP_BY", "width": "100px", "class": "text-left" },
      { "data": "NATURE", "width": "100px", "class": "text-left" },
      { "data": "COA_PARENT", "width": "100px", "class": "text-left" },
      { "data": "DUE_DATE", "width": "100px", "class": "text-left" },
      { "data": "HAND_OVER_TO_TREASURY_BY", "width": "100px", "class": "text-left" },
      { "data": "PAYMENT_CREATE", "width": "100px", "class": "text-left" },
      { "data": "TRANSFER_AMOUNT", "width": "100px", "class": "text-left" },
      { "data": "PAYMENT_DATE", "width": "100px", "class": "text-left" },
      { "data": "DIFFERENCE", "width": "100px", "class": "text-left" },
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
          d.invoice_date_from = invoice_date_from;
          d.invoice_date_to   = invoice_date_to;
          d.filterdateby      = filterdateby;
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
      { "data": "TANGGAL", "width": "100px", "class": "text-left" },
      { "data": "NO_JOURNAL", "width": "100px", "class": "text-left" },
      { "data": "NAMA_VENDOR", "width": "150px", "class": "text-left" },
      { "data": "NO_INVOICE", "width": "150px", "class": "text-left" },
      { "data": "CURRENCY", "width": "50px", "class": "text-left" },
      { "data": "TOTAL_AMOUNT", "width": "100px", "class": "text-left" },
      { "data": "DESCRIPTION", "width": "250px", "class": "text-left" },
      { "data": "NO_KONTRAK", "width": "250px", "class": "text-left" },
      { "data": "NO_FPJP", "width": "100px", "class": "text-left" },
      { "data": "DPP", "class":"text-letf", "width": "100px" },
      { "data": "PPN", "width": "100px", "class": "text-left" },
      { "data": "SUB_TOTAL", "width": "100px", "class": "text-left" },
      { "data": "PPH", "width": "100px", "class": "text-left" },
      { "data": "TOTAL", "width": "100px", "class": "text-left" },
      { "data": "NAMA_REKENING", "width": "100px", "class": "text-left" },
      { "data": "NAMA_BANK", "width": "100px", "class": "text-left" },
      { "data": "ACCT_NUMBER", "width": "100px", "class": "text-left" },
      { "data": "RKAP_NAME", "width": "100px", "class": "text-center" },
      { "data": "TOP", "width": "50px", "class": "text-left" },
      { "data": "TAX_VERIFICATION_DATE", "width": "100px", "class": "text-left" },
      { "data": "NO_SERI_FAKTUR_PAJAK", "width": "250px", "class": "text-left" },
      { "data": "NOMOR_NPWP", "width": "250px", "class": "text-left" },
      { "data": "JOURNAL_AP_BY", "width": "100px", "class": "text-left" },
      { "data": "NATURE", "width": "100px", "class": "text-left" },
      { "data": "COA_PARENT", "width": "100px", "class": "text-left" },
      { "data": "DUE_DATE", "width": "100px", "class": "text-left" },
      { "data": "HAND_OVER_TO_TREASURY_BY", "width": "100px", "class": "text-left" },
      { "data": "PAYMENT_CREATE", "width": "100px", "class": "text-left" },
      { "data": "TRANSFER_AMOUNT", "width": "100px", "class": "text-left" },
      { "data": "PAYMENT_DATE", "width": "100px", "class": "text-left" },
      { "data": "DIFFERENCE", "width": "100px", "class": "text-left" },
      { "data": "STATUS", "width": "100px", "class": "text-left" },
      { "data": "AR_NETTING", "width": "100px", "class": "text-left" },
      { "data": "AR_AMOUNT", "width": "100px", "class": "text-left" },
      { "data": "AR_INVOICE_DESCRIPTION", "width": "200px", "class": "text-left" }
      ],
      "pageLength"      : 100,
      "ordering"        : false,
      "scrollY"         : 480,
      "scrollX"         : true,
      "scrollCollapse"  : true,
      "autoWidth"       : true,
      "bAutoWidth"      : true
    });
  });

  table = $('#table_data').DataTable();

  $("#btnPrint").on("click", function(){

   let vdate_from = '';
   let vdate_to = '';

   let url   ="<?php echo site_url(); ?>Invoicetracker/download_data_invoice_tracker_inquiry";

   vdate_from = $("#ddlInnvoiceDateFrom").val();
   vdate_to = $("#ddlInnvoiceDateTo").val();
   vfilterdateby = $("#ddlfilterdateby").val();

   window.open(url+'?date_from='+ vdate_from+"&date_to="+ vdate_to+"&filterdateby="+ vfilterdateby, '_blank');

   window.focus();

 });

  $('#btnView').on( 'click', function () {

    invoice_date_from = $("#ddlInnvoiceDateFrom").val();
    invoice_date_to = $("#ddlInnvoiceDateTo").val();
    filterdateby = $("#ddlfilterdateby").val();
    table.draw();
    $('#tblDataInquiry').css( 'display', 'block' );
    table.columns.adjust().draw();
  });

  $("#ddlfilterdateby").on("change", function(){
    filter_date = $("#ddlfilterdateby").val();
     $("#btnView").attr('disabled', false);
  });

  function getFilterDate()

  {

    $.ajax({

      url   : baseURL + 'Invoicetracker/load_ddl_filter_date_by',

      type  : "POST",

      dataType: "html",

      success : function(result){

        $("#ddlfilterdateby").html("");       
        $("#ddlfilterdateby").html(result);          

      }

    });     

  }

});

</script>