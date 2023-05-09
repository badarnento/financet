<div class="row">
  <style>
  .custom-input-file{
    width: 100%;
    padding: 5px 10px;
    border-radius: 5px;
    background: #edf1f5;
    border: 1px solid #e4e7ea;
    outline: none;
  }
  .custom-input-file{
    width: 100%;
    padding: 5px 10px;
    border-radius: 5px;
    background: #edf1f5;
    border: 1px solid #e4e7ea;
    outline: none !important;
  }
  .select2.narrow {
      /* width: 300px; */
  }
  .wrap.select2-selection--single {
      height: 100%;
  }
  .wrap.select2{
      height: unset !important;
  }
  .wrap.select2 .select2-chosen,.wrap.select2 .select2-chosen{
      word-wrap: break-word !important;
      text-overflow: inherit !important;
      white-space: normal !important;
  }

</style>

<div class="white-box boxshadow">     

  <div class="row">

    <div class="col-sm-3">
      <div class="form-group">
        <label>Receive Date From</label>
        <div class="input-group">
          <input type="text" class="form-control mydatepicker" id="ddlInnvoiceDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
          <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label>Receive Date To</label>
        <div class="input-group">
          <input type="text" class="form-control mydatepicker" id="ddlInnvoiceDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
          <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label class="form-control-label" for="ddlVendorName">Vendor Name</label>
        <select class="form-control" id="ddlVendorName" name="ddlVendorName">
          <option value="">-- Choose Vendor -- </option> 
        </select> 
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label class="form-control-label" for="ddlstatus">Status</label>
        <select class="form-control" id="ddlstatus" name="ddlstatus">
          <option value="">-- Choose Status -- </option> 
        </select> 
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
<div class="row" id="tblDataJournalBeforeTax">
  <div class="col-md-12">
    <div class="white-box">
      <hr>
      <h4 id="table_detail_title">Journal Before Tax</h4>
      <table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">
        <thead>
          <tr>
           <th class="text-center">No</th>
           <th class="text-center">Transaction Date</th>
           <th class="text-center">Invoice Date</th>
           <th class="text-center">Due Date</th>
           <th class="text-center">Batch Name</th>
           <th class="text-center">Batch Description</th>
           <th class="text-center">Nama Vendor</th>
           <th class="text-center">Nama Journal</th>
           <th class="text-center">No Invoice</th>
           <th class="text-center">No Kontrak</th>
           <th class="text-center">Account Description</th>
           <th class="text-center">Nature</th>
           <th class="text-center">Currency</th>
           <th class="text-center">Debit</th>
           <th class="text-center">Credit</th>
           <th class="text-center">Journal Description</th>
           <th class="text-center">PPN</th>
           <th class="text-center">PPH</th>
           <th class="text-center">Lokasi Sewa</th>
           <th class="text-center">Tanggal Faktur Pajak</th>
           <th class="text-center">Faktur Pajak</th>
           <th class="text-center">NPWP</th>
           <th class="text-center">Amount Base Fee</th>
           <th class="text-center">Notes</th>
           <th class="text-center">Validated</th>
           <th class="text-center">Document</th>
           <th class="text-center">GL Period</th>
           <!-- <th> 
            <div class="checkbox checkbox-inverse" class="text-center">
              <input id="checkboxAll" type="checkbox">
              <label for="checkboxAll">  Validated </label>
            </div>
          </th> -->
        </tr>
      </thead>
    </table>
    <div class="row">
     <div class="col-lg-12 text-center">
      <div class="col-lg-4 text-center">
       <button type="button" id="save_data" class="btn btn-info m-10 w-100p"><i class="fa fa-save"></i> save </button>
     </div>
      <div class="col-lg-4 text-center">
       <button type="button" id="create_data" class="btn btn-primary m-10 w-100p"><i class="fa fa-plus"></i> Create </button>
     </div>
     <div class="col-lg-4 text-center">
      <button id="btndownloadbt" class="btn btn-success m-10 w-150p" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
    </div>

  </div>
</div>
</div>
</div>
</div>

<div class="row" id="tblDataJournalAfterTax">
  <div class="col-md-12">
    <div class="white-box">
      <hr>
      <h4 id="table_detail_title">Journal After Tax</h4>
      <table id="table_data1" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">
        <thead>
          <tr>
           <th class="text-center">No</th>
           <th class="text-center">Transaction Date</th>
           <th class="text-center">Invoice Date</th>
           <th class="text-center">Due Date</th>
           <th class="text-center">Batch Name</th>
           <th class="text-center">Batch Description</th>
           <th class="text-center">Nama Vendor</th>
           <th class="text-center">Nama Journal</th>
           <th class="text-center">No Invoice</th>
           <th class="text-center">No Kontrak</th>
           <th class="text-center">Account Description</th>
           <th class="text-center">Nature</th>
           <th class="text-center">Currency</th>
           <th class="text-center">Debit</th>
           <th class="text-center">Credit</th>
           <th class="text-center">Journal Description</th>
         </tr>
       </thead>
     </table>
     <div class="row">
       <div class="col-lg-12 text-center">
        <button id="btndownloadat" class="btn btn-success m-10 w-150p" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>   
      </div>
    </div>
  </div>
</div>
</div>

<script>

  $(document).ready(function(){

    //$('#tblDataJournalAfterTax').hide();

    getVendorName();
    getStatus();

    $('.mydatepicker').datepicker({
      format: 'dd/mm/yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
    });

    let invoice_date_from = $("#ddlInnvoiceDateFrom").val();
    let invoice_date_to = $("#ddlInnvoiceDateTo").val();
    let vendor_name = $("#ddlVendorName").val();
    let validate_status = $("#ddlstatus").val();

    $("#ddlVendorName").on("change", function(){
      vendor_name = $("#ddlVendorName").val();
    });

    $("#ddlstatus").on("change", function(){
      validate_status = $("#ddlstatus").val();
    });

    let url  = baseURL + 'user_tax/load_data';

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
            d.vendor_name   = vendor_name;
            d.validate_status   = validate_status;
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
        { "data": "tanggal_invoice", "width": "150px", "class": "text-left" },
        { "data": "invoice_date", "width": "150px", "class": "text-left" },
        { "data": "due_date", "width": "100px", "class": "text-left" },
        { "data": "batch_name", "width": "150px", "class": "text-left" },
        { "data": "batch_description", "width": "150px", "class": "text-left" },
        { "data": "nama_vendor", "width": "150px", "class": "text-left" },
        { "data": "journal_name", "width": "100px", "class": "text-left" },
        { "data": "no_invoice", "width": "100px", "class": "text-left" },
        { "data": "no_kontrak", "width": "100px", "class": "text-left" },
        { "data": "account_description", "width": "250px", "class": "text-left" },
        { "data": "nature", "class":"text-letf", "width": "100px" },
        { "data": "currency", "width": "100px", "class": "text-left" },
        { "data": "debet", "width": "100px", "class": "text-left" },
        { "data": "credit", "width": "100px", "class": "text-left" },
        { "data": "journal_description", "width": "200px", "class": "text-left" },
        { "data": "ppn", "width": "300px", "class": "text-left" },
        { "data": "pph", "width": "400px", "class": "text-left" },
        { "data": "lokasi_sewa", "width": "300px", "class": "text-left" },
        { "data": "tgl_faktur_pajak", "width": "200px", "class": "text-left" },
        { "data": "faktur_pajak", "width": "200px", "class": "text-left" },
        { "data": "npwp", "width": "200px", "class": "text-left" },
        { "data": "amount_base_fee", "width": "200px", "class": "text-left" },
        { "data": "notes", "width": "200px", "class": "text-left" },
        { "data": "validated", "width": "100px", "class": "text-center" },
        { "data": "action", "class":"text-center", "width": "80px" },
        { "data": "period_status", "width": "100px", "class": "text-center" }
        ],
        "pageLength"      : 100,
        "ordering"        : false,
        "scrollY"         : 480,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "bAutoWidth"      : true,
        "rowsGroup"       : [18, 19, 20, 21, 22, 23, 24, 25,26],
        drawCallback: function (settings) {
          var api = this.api();
          var rows = api.rows({ page: 'current' }).nodes();
          var last = null;
          api.column(7, { page: 'current' }).data().each(function (group, i) {
            if (last != group && i > 0) {

              $(rows).eq(i).before(
                '<tr class="group"><td align="center" colspan="27" style="color:#fff;background-color:#999;line-height: 0px;height: 5px;padding: 0px;">&nbsp;</td></tr>'
                );
            }
            last = group;
          });
          setTimeout(function(){
            $(".select2").select2({
                containerCssClass: "wrap"
            })
          }, 800);
        }
      });
    });

    table = $('#table_data').DataTable();

    $('#btnView').on( 'click', function () {
      invoice_date_from = $("#ddlInnvoiceDateFrom").val();
      invoice_date_to = $("#ddlInnvoiceDateTo").val();
      vendor_name = $("#ddlVendorName").val();
      validate_status = $("#ddlstatus").val();
      table.draw();
      table1.draw();
    });

    $("#btndownloadbt").on("click", function(){

     let vinvoice_date_from = '';
     let vinvoice_date_to = '';
     let vvendor_name = '';
     let vvalidate_status = '';

     let url   ="<?php echo site_url(); ?>user_tax/download_data_before_tax";

     vinvoice_date_from = $("#ddlInnvoiceDateFrom").val();
     vinvoice_date_to = $("#ddlInnvoiceDateTo").val();
     vvendor_name = $("#ddlVendorName").val();
     vvalidate_status = $("#ddlstatus").val();

     window.open(url+'?date_from='+vinvoice_date_from +"&date_to="+ vinvoice_date_to+"&vendor_name="+ vvendor_name+"&validate_status="+ vvalidate_status, '_blank');

     window.focus();

   });

    $("#btndownloadat").on("click", function(){

     let vinvoice_date_from = '';
     let vinvoice_date_to = '';
     let vvendor_name = '';
     let vvalidate_status = '';


     let url   ="<?php echo site_url(); ?>user_tax/download_data_after_tax";

     vinvoice_date_from = $("#ddlInnvoiceDateFrom").val();
     vinvoice_date_to = $("#ddlInnvoiceDateTo").val();
     vvendor_name = $("#ddlVendorName").val();
     vvalidate_status = $("#ddlstatus").val();

    window.open(url+'?date_from='+vinvoice_date_from +"&date_to="+ vinvoice_date_to+"&vendor_name="+ vvendor_name+"&validate_status="+ vvalidate_status, '_blank');

     window.focus();

   });

    function getStatus()

    {

      $.ajax({

        url   : baseURL + 'user_tax/load_ddl_validated',

        type  : "POST",

        dataType: "html",

        success : function(result){

          $("#ddlstatus").html("");       
          $("#ddlstatus").html(result);          

        }

      });     

    }

    function getVendorName()

    {

      $.ajax({

        url   : baseURL + 'gl/load_ddl_all_vendor',

        type  : "POST",

        dataType: "html",

        success : function(result){

          $("#ddlVendorName").html("");       
          $("#ddlVendorName").html(result);     

          setTimeout(function(){
            $("#ddlVendorName").select2();
        }, 500);            

        }

      });     

    }

    let detail_data_all   = [];

    $("#save_data").on('click', function () {
      $('#tblDataJournalAfterTax').slideDown();
      let id = $("#id").val();
      let validated  ='';
      let total_detail = table.data().count();
      let detail_data  = [];
      let data_line    = [];
      detail_data_all.push(detail_data);
      let today = new Date();
      let validated_date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+' '+today.getHours()+':'+today.getMinutes();
      // validated_date = date("Y-m-d");

      for (var i = 0; i < total_detail; i++) {
        j = i+1;
        get_data = table.row(i).data();
        journal_name = get_data.journal_name;
        header_id = get_data.gl_header_id;
        console.log(header_id);

        period_status = get_data.period_status;
        console.log('ini adalah value period status : ' + period_status);

        jurnal = journal_name;
        replacejurnal = jurnal.replace(/[^a-zA-Z0-9]/g, '');
        gl_pph        = $("#unit_opt-"+header_id).find(':selected').attr('data-gl');
        console.log(gl_pph);
        persen_pph    = $("#unit_opt-"+header_id).find(':selected').attr('data-persen');
        console.log(persen_pph);
        wht_pph       = $("#unit_opt-"+header_id).val();
        console.log(wht_pph);
        gl_ppn        = $("#unit_optppn-"+header_id).find(':selected').attr('data-glppn');
        persen_ppn    = $("#unit_optppn-"+header_id).find(':selected').attr('data-persenppn');
        mst_ppn       = $("#unit_optppn-"+header_id).val();
        npwp          = $("#npwp-"+replacejurnal).val();
        amount_base_fee  = $("#amount_base_fee-"+replacejurnal).val();
        notes          = $("#notes-"+replacejurnal).val();
        faktur_pajak  = $("#faktur_pajak-"+replacejurnal).val();
        lokasi_sewa  = $("#lokasi_sewa-"+replacejurnal).val();
        tgl_faktur_pajak = $("#tglfakturpajak-"+replacejurnal).val();
        
        valtgl_faktur_pajakzzz = $("#tglfakturpajak-"+replacejurnal).val();

        if(valtgl_faktur_pajakzzz == '')
        {
          valtgl_faktur_pajak = '00-00-0000';
        }
        else
        {
           valtgl_faktur_pajak = valtgl_faktur_pajakzzz;
        }
        
        date_split  = valtgl_faktur_pajak.split("-");
        tgl_faktur_pajak     = date_split[2]+"-"+date_split[1]+"-"+date_split[0];

        get_checkbox = document.getElementById("checkbox-"+ replacejurnal);

        if (get_checkbox.checked == true) 
        {
          validated  = 'Y';
        }
        else
        {
          validated  = 'N';
        }

        if(period_status != "CLOSE")
        {

        data_line.push({'journal_name' : journal_name, 'gl_pph' : gl_pph, 'persen_pph' : persen_pph, 'wht_pph' : wht_pph, 'gl_ppn' : gl_ppn, 'persen_ppn' : persen_ppn, 'mst_ppn' : mst_ppn, 'detail_data' : detail_data_all[i], 'validated' : validated, 'npwp' : npwp, 'lokasi_sewa' : lokasi_sewa , 'faktur_pajak' : faktur_pajak, 'tgl_faktur_pajak' : tgl_faktur_pajak, 'amount_base_fee' : amount_base_fee, 'notes' : notes, 'header_id' : header_id, 'validated_date' : validated_date});
        }

      }

      data = {
        data_line : data_line
      }
      $.ajax({
        url   : baseURL + 'user_tax/save_data',
        type  : "POST",
        data  : data,
        beforeSend  : function()
          {
            customLoading('show');
          },
        dataType: "json",
        success : function(result){
          console.log(result);
          if(result.status == true){
            table.ajax.reload(null, false);
            table1.draw();
            customLoading('hide');
            customNotif('Success', "Save Succes", 'success');
          } else{
            customLoading('hide');
            customNotif('Error','Please fill out all field or check the period has closed or not !!!','error' );
          }
        }
      });
    });

    //newcode seakrang manggil procedure saja
    $("#create_data").on('click', function () {
      $('#tblDataJournalAfterTax').slideDown();
      let id = $("#id").val();
      let validated  ='';
      let total_detail = table.data().count();
      let detail_data  = [];
      let data_line    = [];
      detail_data_all.push(detail_data);
      let today = new Date();
      let validated_date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

      data = {
        data_line : data_line
      }
      $.ajax({
        url   : baseURL + 'user_tax/create_user',
        type  : "POST",
        data  : data,
        beforeSend  : function()
          {
            customLoading('show');
          },
        dataType: "json",
        success : function(result){
          console.log(result);
          if(result.status == true){
            table.ajax.reload(null, false);
            table1.draw();
            customLoading('hide');
            customNotif('Success', "Created Succes", 'success');
          } else{
            customLoading('hide');
            customNotif('Error','Created Failed, Please Fill Out All Field!','error' );
          }
        }
      });
    });

    let url1  = baseURL + 'user_tax/load_data_after';

    Pace.track(function(){
      $('#table_data1').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
          "url"  : url1,
          "type" : "POST",
          "dataType": "json",
          "data"    : function ( d ) {
            d.invoice_date_from = invoice_date_from;
            d.invoice_date_to   = invoice_date_to;
            d.vendor_name   = vendor_name;
            d.validate_status   = validate_status;
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
        { "data": "tanggal_invoice", "width": "150px", "class": "text-left" },
        { "data": "invoice_date", "width": "150px", "class": "text-left" },
        { "data": "due_date", "width": "100px", "class": "text-left" },
        { "data": "batch_name", "width": "150px", "class": "text-left" },
        { "data": "batch_description", "width": "150px", "class": "text-left" },
        { "data": "nama_vendor", "width": "150px", "class": "text-left" },
        { "data": "journal_name", "width": "100px", "class": "text-left" },
        { "data": "no_invoice", "width": "100px", "class": "text-left" },
        { "data": "no_kontrak", "width": "100px", "class": "text-left" },
        { "data": "account_description", "width": "250px", "class": "text-left" },
        { "data": "nature", "class":"text-letf", "width": "100px" },
        { "data": "currency", "width": "100px", "class": "text-left" },
        { "data": "debet", "width": "100px", "class": "text-left" },
        { "data": "credit", "width": "100px", "class": "text-left" },
        { "data": "journal_description", "width": "200px", "class": "text-left" },
        ],
        "pageLength"      : 100,
        "ordering"        : false,
        "scrollY"         : 480,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "bAutoWidth"      : true,
        drawCallback: function (settings) {
          var api = this.api();
          var rows = api.rows({ page: 'current' }).nodes();
          var last = null;
          api.column(7, { page: 'current' }).data().each(function (group, i) {
            console.log(i);
            if (last != group && i > 0) {

              $(rows).eq(i).before(
                '<tr class="group"><td align="center" colspan="18" style="color:#fff;background-color:#999;line-height: 0px;height: 5px;padding: 0px;">&nbsp;</td></tr>'
                );
            }
            last = group;
          });
        }
      });
    });

    table1 = $('#table_data1').DataTable();

    $('#table_data tbody').on('focus', 'tr td input.tglfakturpajak', function () {
      $(this).datepicker({
      format: 'dd-mm-yyyy',
      todayHighlight:'TRUE',
      autoclose: true,
      changeMonth: true,
      changeYear: true,
      minDate:0,})
    // }).datepicker("setDate",'now');
    });
    <?php if(in_array("Invoice Tax Inquiry", $group_name)) : ?>
      $("#tblDataJournalBeforeTax").hide();
    <?php endif ?>

  });

</script>