<div>

  <div class="white-box boxshadow">   


   <div class="row">

    <div class="col-sm-3">
      <div class="form-group">
        <label class="form-control-label" for="ddlfilterdateby">Filter Date By</label>
        <select class="form-control" id="ddlfilterdateby" name="ddlfilterdateby">
          <?php ?>
            <?php echo $opt_val_filterdate ?> 
          <?php ?>
        </select> 
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label>Date From</label>
        <div class="input-group">
          <input type="text" class="form-control mydatepicker" id="ddlVerificatedDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
          <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label>Date To</label>
        <div class="input-group">
          <input type="text" class="form-control mydatepicker" id="ddlVerificatedDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
          <span class="input-group-addon"><i class="icon-calender"></i></span>
        </div>
      </div>
    </div>

     

  </div>


  <div class="row">

   <!--  <div class="col-sm-3">
      <div class="form-group">
        <label class="form-control-label" for="ddlVendorName">Vendor Name</label>
        <select class="form-control" id="ddlVendorName" name="ddlVendorName">
          <option value="">-- Choose Vendor -- </option> 
        </select> 
      </div>
    </div> -->

    <div class="col-sm-3">
      <div class="form-group">
        <label class="form-control-label" for="ddlVendorName">Vendor Name</label>
        <select class="form-control select2" id="ddlVendorName" name="ddlVendorName">
          <option value="">-- Choose Vendor -- </option> 
          <?php foreach ($all_vendor as $key => $value): ?>
            <option value="<?= $value['NAMA_VENDOR'] ?>"><?= $value['NAMA_VENDOR'] ?></option> 
          <?php endforeach; ?>
        </select> 
      </div>
    </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label class="form-control-label" for="ddlverificatedstatus">Status</label>
        <select class="form-control" id="ddlverificatedstatus" name="ddlverificatedstatus" style="max-width: 75%">
          <?php ?>
            <?php echo $opt_val_status ?> 
          <?php ?>
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


<div class="row" id="tblDataInquiry">

  <div class="col-md-12">

    <div class="white-box">

      <label> Inquiry </label>
      <br>

      <table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">

        <thead>

          <tr>

            <th class="text-center">No.</th>
            <th class="text-center">Transaction Date</th>
            <th class="text-center">Invoice Date</th>
            <th class="text-center">Due Date</th>
            <th class="text-center">Batch Name</th>
            <th class="text-center">Batch Description</th>
            <th class="text-center">Nama Vendor</th>
            <th class="text-center">Nama Journal</th>
            <th class="text-center">No Invoice</th>
            <th class="text-center">No Kontrak</th>
            <th class="text-center">Nature</th>
            <th class="text-center">Account Description</th>
            <th class="text-center">Currency</th>
            <th class="text-center">Debit</th>
            <th class="text-center">Credit</th>
            <th class="text-center">Journal Description</th> 
            <th class="text-center">Remark Approved</th>
            <th class="text-center">Verified</th> 
              <!-- <th> 
                <div class="checkbox checkbox-inverse" class="text-center">
                  <input id="checkboxAll" type="checkbox">
                  <label for="checkboxAll">  Verified </label>
                </div>
              </th> -->         

              <th class="text-center">Remark Verificated</th>
              <th class="text-center"> AR > AP </th> 
              <th class="text-center">Action</th> 

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
           <!-- <button id="btn-cetak" class="btn btn-danger btn-rounded w-150p m-b-10" type="button" ><i class="fa fa-file-pdf-o"></i> <span>Cetak</span></button> -->
         </div>
       </div>
     </div>
   </div>
 </div>



 <div class="white-box boxshadow">   

  <div class="row">

    <div class="col-sm-3">
      <div class="form-group">
        <label class="form-control-label" for="ddlstatus">Status</label>
        <select class="form-control" id="ddlstatus" name="ddlstatus">
          <?php ?>
            <?php echo $opt_val_verified ?> 
          <?php ?>
        </select> 
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
        <label class="form-control-label" for="ddlbatchapproval">Batch Approval</label>
        <select class="form-control select2" id="ddlbatchapproval" name="ddlbatchapproval">
          <?php ?>
            <?php echo $opt_val_batch ?> 
          <?php ?>
        </select> 
      </div>
    </div>

  </div>

</div>


<div class="row" id="tblDataJournalAfterTax">

  <div class="col-md-12">

    <div class="white-box">

      <table id="table_data2" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">

        <thead>

          <tr>

            <th class="text-center">No.</th>
            <th class="text-center">Transaction Date</th>
            <th class="text-center">Invoice Date</th>
            <th class="text-center">Due Date</th>
            <th class="text-center">Batch Name</th>
            <th class="text-center">Batch Description</th>
            <th class="text-center">Nama Vendor</th>
            <th class="text-center">Nama Journal</th>
            <th class="text-center">No Invoice</th>
            <th class="text-center">No Kontrak</th>
            <th class="text-center">Nature</th>
            <th class="text-center">Account Description</th>
            <th class="text-center">Currency</th>
            <th class="text-center">Debit</th>
            <th class="text-center">Credit</th>
            <th class="text-center">Journal Description</th>
            <th class="text-center">Remark Approved</th>
            <th class="text-center">Verified</th>   
            <!-- <th> 
              <div class="checkbox checkbox-inverse" class="text-center">
                <input id="checkboxAll" type="checkbox">
                <label for="checkboxAll">  Verified </label>
              </div>
            </th> -->         

            <th class="text-center">Remark Verificated</th>
            <th class="text-center"> AR > AP </th>
            <th class="text-center">Document</th>
            <th class="text-center">Action</th>
            <th class="text-center">GL Period</th>

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


</div>



<script>

 $(document).ready(function(){

  // getVendorName();
  // getFilterDate();
  // getAllStatus();
  // getStatusverified();
  // getBatchApproval();

  let filter_date = $("#ddlfilterdateby").val();
  let verificated_date_from = $("#ddlVerificatedDateFrom").val();
  let verificated_date_to = $("#ddlVerificatedDateTo").val();
  let vendor_name = $("#ddlVendorName").val();
  let verificatedstatus = $("#ddlverificatedstatus").val();
  let verified_status = $("#ddlstatus").val();
  let batch_appr = $("#ddlbatchapproval").val();

  $("#btn-cetak").attr('disabled', true);


  $(".select2").select2();
 
  $('.mydatepicker').datepicker({
    format: 'dd/mm/yyyy',
    todayHighlight:'TRUE',
    autoclose: true,
  });

  $("#ddlVendorName").on("change", function(){
    vendor_name = $("#ddlVendorName").val();
  });

  $("#ddlfilterdateby").on("change", function(){
    filter_date = $("#ddlfilterdateby").val();
  });

  $("#ddlverificatedstatus").on("change", function(){
    verificatedstatus = $("#ddlverificatedstatus").val();
  });

  $("#ddlstatus").on("change", function(){
    vendor_name = $("#ddlVendorName").val();
    verified_status = $("#ddlstatus").val();
    getBatchApproval();
    batch_appr = $("#ddlbatchapproval").val();

    // table2.draw();
    
    table2.columns.adjust().draw();
  });

  $("#ddlbatchapproval").on("change", function(){
    vendor_name = $("#ddlVendorName").val();
    verified_status = $("#ddlstatus").val();
    batch_appr = $("#ddlbatchapproval").val();

    // table2.draw();
    
    table2.columns.adjust().draw();
  });

  let url  = baseURL + 'uservalidate/load_data_inquiry';

  Pace.track(function(){
    $('#table_data').DataTable({
      "serverSide"      : true,
      "processing"      : true,
      "ajax"            : {
        "url"  : url,
        "type" : "POST",
        "dataType": "json",
        "data"    : function ( d ) {
          d.filter_date = filter_date;
          d.verificated_date_from = verificated_date_from;
          d.verificated_date_to   = verificated_date_to;
          d.vendor_name   = vendor_name;
          d.verificatedstatus   = verificatedstatus;
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
      { "data": "journal_name", "width": "150px", "class": "text-left" },
      { "data": "no_invoice", "width": "150px", "class": "text-left" },
      { "data": "no_kontrak", "width": "100px", "class": "text-left" },
      { "data": "nature", "class":"text-letf", "width": "100px" },
      { "data": "account_description", "width": "250px", "class": "text-left" },
      { "data": "currency", "width": "100px", "class": "text-left" },
      { "data": "debet", "width": "100px", "class": "text-left" },
      { "data": "credit", "width": "100px", "class": "text-left" },
      { "data": "journal_description", "width": "200px", "class": "text-left" },
      { "data": "remark_approved", "width": "300px", "class": "text-center" },
      { "data": "validated", "width": "100px", "class": "text-center" },
      { "data": "remark", "width": "300px", "class": "text-center" },
      { "data": "is_more_than_ap", "width": "100px", "class": "text-center" },
      { "data": "action", "width": "100px", "class": "text-center" }
      ],
      "pageLength"      : 100,
      "ordering"        : false,
      "scrollY"         : 480,
      "scrollX"         : true,
      "scrollCollapse"  : true,
      "autoWidth"       : true,
      "bAutoWidth"      : true,
      "rowsGroup": [16,17,18,19,20],
      drawCallback: function (settings) {
        var api = this.api();
        var rows = api.rows({ page: 'current' }).nodes();
        var last = null;
        api.column(7, { page: 'current' }).data().each(function (group, i) {
          if (last != group && i > 0) {

            $(rows).eq(i).before(
              '<tr class="group"><td align="center" colspan="21" style="color:#fff;background-color:#999;line-height: 0px;height: 5px;padding: 0px;">&nbsp;</td></tr>'
              );
          }
          last = group;
        });
      }
    });
  });

  table = $('#table_data').DataTable();


  let url2  = baseURL + 'uservalidate/load_data_journal_after_tax';

  Pace.track(function(){
    $('#table_data2').DataTable({
      "serverSide"      : true,
      "processing"      : true,
      "ajax"            : {
        "url"  : url2,
        "type" : "POST",
        "dataType": "json",
        "data"    : function ( d ) {
          d.vendor_name   = vendor_name;
          d.verified_status   = verified_status;
          d.batch_appr = batch_appr;
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
      { "data": "journal_name", "width": "150px", "class": "text-left" },
      { "data": "no_invoice", "width": "150px", "class": "text-left" },
      { "data": "no_kontrak", "width": "200px", "class": "text-left" },
      { "data": "nature", "class":"text-letf", "width": "100px" },
      { "data": "account_description", "width": "250px", "class": "text-left" },
      { "data": "currency", "width": "100px", "class": "text-left" },
      { "data": "debet", "width": "100px", "class": "text-left" },
      { "data": "credit", "width": "100px", "class": "text-left" },
      { "data": "journal_description", "width": "200px", "class": "text-left" },
      { "data": "remark_approved", "width": "300px", "class": "text-center" },
      { "data": "validated", "width": "100px", "class": "text-center" },
      { "data": "remark", "width": "300px", "class": "text-center" },
      { "data": "is_more_than_ap", "width": "100px", "class": "text-center" },
      { "data": "fpjp_source", "width": "100px", "class": "text-center" },
      { "data": "action", "width": "100px", "class": "text-center" },
      { "data": "period_status", "width": "100px", "class": "text-center" }
      ],
      "pageLength"      : 100,
      "ordering"        : false,
      "scrollY"         : 480,
      "scrollX"         : true,
      "scrollCollapse"  : true,
      "autoWidth"       : true,
      "bAutoWidth"      : true,
      "rowsGroup": [16,17,18,19,20,21,22],
      drawCallback: function (settings) {
        var api = this.api();
        var rows = api.rows({ page: 'current' }).nodes();
        var last = null;
        api.column(7, { page: 'current' }).data().each(function (group, i) {
          if (last != group && i > 0) {

            $(rows).eq(i).before(
              '<tr class="group"><td align="center" colspan="23" style="color:#fff;background-color:#999;line-height: 0px;height: 5px;padding: 0px;">&nbsp;</td></tr>'
              );
          }
          last = group;
        });
      }
    });
  });


  table2 = $('#table_data2').DataTable();

  $("#btnPrint").on("click", function(){

   let vfilter_date = '';
   let vverificated_date_from = '';
   let vverificated_date_to = '';
   let vvendor_name = '';

   let url   ="<?php echo site_url(); ?>uservalidate/download_data_after_tax";

   vfilter_date = $("#ddlfilterdateby").val();
   vverificated_date_from = $("#ddlVerificatedDateFrom").val();
   vverificated_date_to = $("#ddlVerificatedDateTo").val();
   vvendor_name = $("#ddlVendorName").val();
   vverificatedstatus = $("#ddlverificatedstatus").val();

   window.open(url+'?verificated_date_from='+ vverificated_date_from+"&verificated_date_to="+ vverificated_date_to+"&vendor_name="+ vvendor_name+"&filter_date="+ vfilter_date+"&verificated_status="+ vverificatedstatus, '_blank');

   window.focus();

 });


  $('#btnView').on( 'click', function () {

    filter_date = $("#ddlfilterdateby").val();
    verificated_date_from = $("#ddlVerificatedDateFrom").val();
    verificated_date_to = $("#ddlVerificatedDateTo").val();
    vendor_name = $("#ddlVendorName").val();
    verificatedstatus = $("#ddlverificatedstatus").val();
    verified_status = $("#ddlstatus").val();
    $("#btn-cetak").removeAttr('disabled');

    console.log(verificated_date_from);
    console.log(verificated_date_to);
    console.log(verified_status);


    // table.draw();
    // table2.draw();

    // $('#tblDataInquiry').css( 'display', 'block' );
    // $('#tblDataJournalAfterTax').css( 'display', 'block' );

    // <?php if(in_array("Verification Inquiry", $group_name)) : ?>
    //   $("#tblDataJournalAfterTax").hide();
    // <?php endif ?>

    table.columns.adjust().draw();
    table2.columns.adjust().draw();
  });

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

  function getFilterDate()

  {

    $.ajax({

      url   : baseURL + 'uservalidate/load_ddl_filter_date_by',

      type  : "POST",

      dataType: "html",

      success : function(result){

        $("#ddlfilterdateby").html("");       
        $("#ddlfilterdateby").html(result);          

      }

    });     

  }

  function getAllStatus()

  {

    $.ajax({

      url   : baseURL + 'uservalidate/load_ddl_all_status',

      type  : "POST",

      dataType: "html",

      success : function(result){

        $("#ddlverificatedstatus").html("");       
        $("#ddlverificatedstatus").html(result);         
      }

    });     

  }

  function getStatusverified()

  {

    $.ajax({

      url   : baseURL + 'uservalidate/load_ddl_verified',

      type  : "POST",

      dataType: "html",

      success : function(result){

        $("#ddlstatus").html("");       
        $("#ddlstatus").html(result);         
      }

    });     

  }


  function getBatchApproval()

  {

    let batch_approval = '<?= $batch_approval ?>';
    let param_verified_status = $("#ddlstatus").val();

    $.ajax({

      url   : baseURL + 'uservalidate/load_ddl_Approve_batch',
      type  : "POST",
      data  : {param_verified_status :  param_verified_status},
      dataType: "html",

      success : function(result){

        $("#ddlbatchapproval").html("");       
        $("#ddlbatchapproval").html(result);      

        // if(batch_approval)
        // {
        //   $("#ddlbatchapproval").val(batch_approval);
        //   batch_appr = $("#ddlbatchapproval").val();
        // }   
        setTimeout(function(){
            $(".select2").select2();
        }, 500);
      }

    });     

  }


//region alt + 0 to minimize
 //  $("#checkboxAll").on("click", function(){
 //    if($(this).prop('checked') == false){      
 //      $(".checklist").prop('checked',false);
 //    } else {
 //     $(".checklist").prop('checked',true);
 //   } 
 // });

//kodingan lama
 $("#btn_save").on('click', function () {
  let detail_data_all   = [];
  let detail_data  = [];
  let total_data = table2.data().count();
  let data_line  = [];
  let validateval ='';
  let remarks ='';
  let jurnal ='';
  let replacejurnal ='';
  detail_data_all.push(detail_data);
  let today = new Date();
  let verificated_date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
    // verificated_date = date("Y-m-d");
  let ddl_verified_status = $("#ddlstatus").val();

    for (let i = 0; i < total_data; i++) 
    {
      j = i+1; 
      get_data = table2.row(i).data();
      jurnal = get_data.journal_name;
      header_id = get_data.gl_header_id;

      replacejurnal = jurnal.replace(/[^a-zA-Z0-9]/g, '');
      get_checkbox = document.getElementById("checkbox-"+ replacejurnal);
      get_remark   = document.getElementById("remark-"+ replacejurnal);

      if (get_checkbox.checked == true) 
      {
        validateval = 'Y';
      }
      else
      {
        validateval = 'N';
      }


      remarks = ((get_remark.value == null ) ? '' : get_remark.value);

      batch_approval = get_data.batch_approval;

      // console.log(validateval);
      // console.log(remarks);
      // console.log(batch_approval + 'test');


      if(ddl_verified_status == 'N' && validateval == 'Y')
      {

      data_line.push({'nojournal' : jurnal, 'validated' : validateval, 'remark' : remarks, 'verificated_date' : verificated_date, 'batch_approval' : batch_approval, 'detail_data' : detail_data_all[i]});
      }
      else if(ddl_verified_status == 'Y' && validateval == 'N')
      {
        data_line.push({'nojournal' : jurnal, 'validated' : validateval, 'remark' : remarks, 'verificated_date' : verificated_date, 'batch_approval' : batch_approval, 'detail_data' : detail_data_all[i]});
      } 


    }

    if(data_line.length > 0)
    {

      data = {
        data_line : data_line
      }

      $.ajax({
        url   : baseURL + 'uservalidate/update_user_validate',
        type  : "POST",
        data  : data,
        beforeSend  : function()
          {
            customLoading('show');
          },
        dataType: "json",
        success : function(result){
          console.log(result);
          if(result == 1)
          {
            table.ajax.reload(null, false);
            table2.ajax.reload(null, false);
            
            customLoading('hide');
            customNotif('Success', "Data verification", 'success');
          // setTimeout(function(){
          //   $(location).attr('href', baseURL + 'uservalidate/user-validate');
          // }, 1000);
        }
        else {
          customLoading('hide');
          customNotif('Failed', "No data Verified", 'error');
        }
      }
    });

    }
    else
    {
      customLoading('hide');
      customNotif('Warning', "No data Verified", 'warning');
    }

  });
 //


          

/*$("#btn-cetak").on("click", function(){
  let url   ="<?php echo site_url(); ?>uservalidate/printPDF";
  let vvendor_name = '';
  vvendor_name = $("#ddlVendorName").val();

  if (!table.data().any()){
   customNotif('Info','Data Kosong!','warning' );
   exit();
 }else{
  window.open(url+'?vendor_name='+vvendor_name,'_blank');
  window.focus();
}


});*/

 //endregion


//kodingan di update pake tombol save 18 jan 2021
//  $('#table_data2').on('click', 'input.checklist', function () {

//   let data  = table2.row( $(this).parents('tr') ).data();
//   let nojournal = data.journal_name;
//   let today = new Date();
//   let verificated_date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

//   if (this.checked) {
//     check = 'Y';
//   }else{
//     check = 'N';
//   }

//   console.log(check);
//   console.log(nojournal);
//   console.log(verificated_date);

//   $.ajax({
//     url   : baseURL + 'uservalidate/update_user_validate',
//     type  : "POST",
//     data  : {verified_status : check, nojournal : nojournal, verificated_date : verificated_date},
//     beforeSend  : function()
//           {
//             customLoading('show');
//           },
//     dataType: "json",
//     success : function(result){
//       console.log(result);
//       if (result.status == true) {
//         table.ajax.reload(null, false);
//         table2.ajax.reload(null, false);
//         customLoading('hide');
//         customNotif('Success', result.messages, 'success');
//       } else {
//         customLoading('hide');
//         customNotif('Failed', result.messages, 'error');
//       }
//     }
//   });
// });

 $('#table_data2').on('click', 'input.ismorethanap', function () {

  let data  = table2.row( $(this).parents('tr') ).data();
  let nojournal = data.journal_name;

  if (this.checked) {
    check = 'Y';
  }else{
    check = 'N';
  }

  console.log(check);

  $.ajax({
    url   : baseURL + 'uservalidate/update_ismorethanap',
    type  : "POST",
    data  : {ismorethanap_status : check, nojournal : nojournal},
    beforeSend  : function()
          {
            customLoading('show');
          },
    dataType: "json",
    success : function(result){
      console.log(result);
      if (result.status == true) {
        table.ajax.reload(null, false);
        table2.ajax.reload(null, false);
        customLoading('hide');
        customNotif('Success', result.messages, 'success');
      } else {
        customLoading('hide');
        customNotif('Failed', result.messages, 'error');
      }
    }
  });
});


//kodingan di update pake tombol save 18 jan 2021
//  $('#table_data2').on('change', 'textarea.remarks', function () {

//   let data  = table2.row( $(this).parents('tr') ).data();
//   let nojournal = data.journal_name;
//   let validatedval = data.validated_value;
//   let remark_verificated = this.value;

//   console.log(remark_verificated);

//   $.ajax({
//     url   : baseURL + 'uservalidate/update_remark',
//     type  : "POST",
//     data  : {remark_verificated : remark_verificated, nojournal : nojournal, validatedval : validatedval},
//     dataType: "json",
//     success : function(result){
//       console.log(result);
//       if (result.status == true) {
//         table.ajax.reload(null, false);
//         table2.ajax.reload(null, false);
//         customNotif('Success', result.messages, 'success');
//       } else {
//         customNotif('Failed', result.messages, 'error');
//       }
//     }
//   });
// });


 $("#btn-cetak").on("click", function(){
  let url   ="<?php echo site_url(); ?>uservalidate/printPDF";
  let vvendor_name = '';
  vvendor_name = $("#ddlVendorName").val();
  let vinvoice_date_from = $("#ddlVerificatedDateFrom").val();
  let vinvoice_date_to   = $("#ddlVerificatedDateTo").val();

  if (!table.data().any()){
   customNotif('Info','Data Kosong!','warning' );
   exit();
 }else{
  window.open(url+'?vendor_name='+vvendor_name+'&invoice_date_from='+vinvoice_date_from+'&invoice_date_to='+vinvoice_date_to,'_blank');
  window.focus();
}


});

 $('#table_data').on('click', 'a.action-cetak', function () {
      data      = table.row( $(this).parents('tr') ).data();
      let jurnal_name = data.journal_name;

      let url   ="<?php echo site_url(); ?>uservalidate/printPDFLine";

      if (!table.data().any()){
       customNotif('Info','Data Kosong!','warning' );
       exit();
     }else{
      window.open(url+'?jurnal_name='+jurnal_name,'_blank');
      window.focus();
    }

    });

 $('#table_data2').on('click', 'a.action-cetak', function () {
      data      = table2.row( $(this).parents('tr') ).data();
      let jurnal_name = data.journal_name;

      let url   ="<?php echo site_url(); ?>uservalidate/printPDFLine";

      if (!table2.data().any()){
       customNotif('Info','Data Kosong!','warning' );
       exit();
     }else{
      window.open(url+'?jurnal_name='+jurnal_name,'_blank');
      window.focus();
    }

    });

 <?php if(in_array("Verification Inquiry", $group_name)) : ?>
  $("#tblDataJournalAfterTax").hide();
  $("#btn-cetak").hide();
<?php endif ?>


});

</script>