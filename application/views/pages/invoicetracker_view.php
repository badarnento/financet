<div>

  <div class="white-box boxshadow">   

    <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
          <label>Innvoice Date From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="ddlInnvoiceDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>

      <div class="col-sm-3">
        <div class="form-group">
          <label>Innvoice Date To</label>
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
              <th class="text-center">Total Amount</th>
              <th class="text-center">Description</th>
              <th class="text-center">No Kontrak</th>
              <th class="text-center">No FPJP</th>
              <th class="text-center">Dpp</th>
              <th class="text-center">Ppn</th>
              <th class="text-center">Pph</th>
              <th class="text-center">Total</th>
              <th class="text-center">Nama Rekening</th>
              <th class="text-center">Nama Bank</th>
              <th class="text-center">Acct Number</th>
              <th class="text-center">Rkap Name</th>
              <th class="text-center">Top</th>
              <th class="text-center">Tax Verification Date</th>
              <th class="text-center">No Seri Faktur Pajak</th>
              <th class="text-center">Journal AP By</th>
              <th class="text-center">Nature</th>
              <th class="text-center">Coa Parent</th>
              <th class="text-center">Due Date</th>
              <th class="text-center">Hand Over To Treasury By</th>
              <th class="text-center">Payment Create</th>
              <th class="text-center">Transfer Amount</th>
              <th class="text-center">Payment Date</th>
              <th class="text-center">Difference</th>
              <th class="text-center">No NPWP</th>

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

  $('.mydatepicker').datepicker({
    format: 'dd/mm/yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
  });

  
  $('.mydatepicker').datepicker({
    format: 'dd/mm/yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
  });


  let url  = baseURL + 'invoicetracker/load_data_invoice_tracker_inquiry';

    let ajaxData = {

      "url"  : url,

      "type" : "POST",

      "data"    : function ( d ) {
        d.invoice_date_from = invoice_date_from;
        d.invoice_date_to   = invoice_date_to;
      }

    }

    let jsonData = [
      { "data": "no", "width": "10px", "class": "text-center" },
      { "data": "tanggal", "width": "150px", "class": "text-left" },
      { "data": "no_journal", "width": "100px", "class": "text-left" },
      { "data": "nama_vendor", "width": "150px", "class": "text-left" },
      { "data": "no_invoice", "width": "150px", "class": "text-left" },
      { "data": "total_amount", "width": "150px", "class": "text-left" },
      { "data": "description", "width": "150px", "class": "text-left" },
      { "data": "no_kontrak", "width": "100px", "class": "text-left" },
      { "data": "no_fpjp", "width": "100px", "class": "text-left" },
      { "data": "dpp", "class":"text-letf", "width": "100px" },
      { "data": "ppn", "width": "250px", "class": "text-left" },
      { "data": "pph", "width": "100px", "class": "text-left" },
      { "data": "total", "width": "100px", "class": "text-left" },
      { "data": "nama_rekening", "width": "100px", "class": "text-left" },
      { "data": "nama_bank", "width": "200px", "class": "text-left" },
      { "data": "acct_number", "width": "100px", "class": "text-center" },
      { "data": "rkap_name", "width": "100px", "class": "text-left" },
      { "data": "top", "width": "100px", "class": "text-left" },
      { "data": "tax_verification_date", "width": "100px", "class": "text-left" },
      { "data": "no_seri_faktur_pajak", "width": "100px", "class": "text-left" },
      { "data": "journal_ap_by", "width": "100px", "class": "text-left" },
      { "data": "nature", "width": "100px", "class": "text-left" },
      { "data": "coa_parent", "width": "100px", "class": "text-left" },
      { "data": "due_date", "width": "100px", "class": "text-left" },
      { "data": "hand_over_to_treasury_by", "width": "100px", "class": "text-left" },
      { "data": "payment_create", "width": "100px", "class": "text-left" },
      { "data": "transfer_amount", "width": "100px", "class": "text-left" },
      { "data": "payment_date", "width": "100px", "class": "text-left" },
      { "data": "difference", "width": "100px", "class": "text-left" },
      { "data": "nomor_npwp", "width": "100px", "class": "text-left" }
  ];

  data_table(ajaxData,jsonData);

  table = $('#table_data').DataTable();

  $("#btnPrint").on("click", function(){

   let vdate_from = '';
   let vdate_to = '';

   let url   ="<?php echo site_url(); ?>invoicetracker/download_data_invoice_tracker_inquiry";

   vdate_from = $("#ddlInnvoiceDateFrom").val();
   vdate_to = $("#ddlInnvoiceDateTo").val();

   window.open(url+'?date_from='+ vdate_from+"&date_to="+ vdate_to, '_blank');

   window.focus();

 });

  $('#btnView').on( 'click', function () {

    invoice_date_from = $("#ddlInnvoiceDateFrom").val();
    invoice_date_to = $("#ddlInnvoiceDateTo").val();
    table.draw();
    $('#tblDataInquiry').css( 'display', 'block' );
    table.columns.adjust().draw();
  });

});

</script>