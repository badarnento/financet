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

      <div class="col-sm-2">
        <div class="form-group">
          <label>Date From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="ddlApprovedDateFrom" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>

      <div class="col-sm-2">
        <div class="form-group">
          <label>Date To</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="ddlApprovedDateTo" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 4)?>">
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

      <div class="col-sm-2">

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
              <th class="text-center"> AR > AP </th>  
              <th class="text-center">Remark Verificated</th> 
              <th class="text-center">Approved</th>        
              <th class="text-center">Remark Approved</th>

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
            <div class="col-md-5"></div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
              <button id="btnPrint" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="white-box boxshadow"  id="divfilterstatus">   

    <div class="row">

      <div class="col-sm-3">
        <div class="form-group">
          <label class="form-control-label" for="ddlstatus">Status</label>
          <select class="form-control" id="ddlstatus" name="ddlstatus" style="max-width: 70%">
            <option value="">-- Choose Status -- </option> 
          </select> 
        </div>
      </div>

    <div class="col-sm-3">
      <div class="form-group">
        <label class="form-control-label" for="ddlbatchapproval">Batch Approval</label>
        <select class="form-control" id="ddlbatchapproval" name="ddlbatchapproval">
          <option value="">-- Choose Batch Approval -- </option> 
          <!-- <option value="<?= $batch_approval ?>"><?= $batch_approval ?></option>  -->
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
              <th class="text-center"> AR > AP </th>  
              <th class="text-center">Remark Verificated</th> 
              <th class="text-center">Approved</th> 
              <th class="text-center">Remark Approved</th>
              <th class="text-center">Document</th>
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



 <script>

   $(document).ready(function(){

    getVendorName();
    getFilterDate();
    getStatusapproved();
    getBatchApproval();

    let filter_date = $("#ddlfilterdateby").val();
    let approved_date_from = $("#ddlApprovedDateFrom").val();
    let approved_date_to = $("#ddlApprovedDateTo").val();
    let vendor_name = $("#ddlVendorName").val();
    let approved_status = $("#ddlstatus").val();
    let batch_appr = $("#ddlbatchapproval").val();
    // $('#tblDataInquiry').hide();
    // $('#tblDataJournalAfterTax').hide();
    // $('#tblDatadownload').hide();

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

    $("#ddlstatus").on("change", function(){
      vendor_name = $("#ddlVendorName").val();
      approved_status = $("#ddlstatus").val();
      getBatchApproval();
      batch_appr = $("#ddlbatchapproval").val();

      table2.draw();

      table2.columns.adjust().draw();
    });

    $("#ddlbatchapproval").on("change", function(){
    vendor_name = $("#ddlVendorName").val();
    approved_status = $("#ddlstatus").val();
    batch_appr = $("#ddlbatchapproval").val();

    table2.draw();
    
    table2.columns.adjust().draw();
  });

    let url  = baseURL + 'userapprovedjournal/load_data_inquiry';

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
            d.approved_date_from = approved_date_from;
            d.approved_date_to   = approved_date_to;
            d.vendor_name   = vendor_name;
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
        { "data": "no_invoice", "width": "100px", "class": "text-left" },
        { "data": "no_kontrak", "width": "100px", "class": "text-left" },
        { "data": "nature", "class":"text-letf", "width": "100px" },
        { "data": "account_description", "width": "250px", "class": "text-left" },
        { "data": "currency", "width": "100px", "class": "text-left" },
        { "data": "debet", "width": "100px", "class": "text-left" },
        { "data": "credit", "width": "100px", "class": "text-left" },
        { "data": "journal_description", "width": "200px", "class": "text-left" },
        { "data": "is_more_than_ap", "width": "100px", "class": "text-center" },
        { "data": "remark_verificated", "width": "300px", "class": "text-center" },
        { "data": "status", "width": "150px", "class": "text-center" },
        { "data": "remark_approved", "width": "300px", "class": "text-center" }
        ],
        "pageLength"      : 100,
        "ordering"        : false,
        "scrollY"         : 480,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "bAutoWidth"      : true,
        "rowsGroup": [16,17,18,19],
        drawCallback: function (settings) {
          var api = this.api();
          var rows = api.rows({ page: 'current' }).nodes();
          var last = null;
          api.column(6, { page: 'current' }).data().each(function (group, i) {
            if (last != group && i > 0) {

              $(rows).eq(i).before(
                '<tr class="group"><td align="center" colspan="20" style="color:#fff;background-color:#999;line-height: 0px;height: 5px;padding: 0px;">&nbsp;</td></tr>'
                );
            }
            last = group;
          });
        }
      });
    });


    table = $('#table_data').DataTable();

    let url2  = baseURL + 'userapprovedjournal/load_data_journal_after_tax';

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
            d.approved_status   = approved_status;
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
        { "data": "no_invoice", "width": "100px", "class": "text-left" },
        { "data": "no_kontrak", "width": "100px", "class": "text-left" },
        { "data": "nature", "class":"text-letf", "width": "100px" },
        { "data": "account_description", "width": "250px", "class": "text-left" },
        { "data": "currency", "width": "100px", "class": "text-left" },
        { "data": "debet", "width": "100px", "class": "text-left" },
        { "data": "credit", "width": "100px", "class": "text-left" },
        { "data": "journal_description", "width": "200px", "class": "text-left" },
        { "data": "is_more_than_ap", "width": "100px", "class": "text-center" },
        { "data": "remark_verificated", "width": "300px", "class": "text-center" },
        { "data": "status", "width": "150px", "class": "text-center" },
        { "data": "remark_approved", "width": "300px", "class": "text-center" },
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
        "rowsGroup": [16,17,18,19,20,21],
        drawCallback: function (settings) {
          var api = this.api();
          var rows = api.rows({ page: 'current' }).nodes();
          var last = null;
          api.column(6, { page: 'current' }).data().each(function (group, i) {
            if (last != group && i > 0) {

              $(rows).eq(i).before(
                '<tr class="group"><td align="center" colspan="22" style="color:#fff;background-color:#999;line-height: 0px;height: 5px;padding: 0px;">&nbsp;</td></tr>'
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
     let vapproved_date_from = '';
     let vapproved_date_to = '';
     let vvendor_name = '';

     let url   ="<?php echo site_url(); ?>userapprovedjournal/download_data_after_tax";

     vfilter_date = $("#ddlfilterdateby").val();
     vapproved_date_from = $("#ddlApprovedDateFrom").val();
     vapproved_date_to = $("#ddlApprovedDateTo").val();
     vvendor_name = $("#ddlVendorName").val();

     window.open(url+'?approved_date_from='+ vapproved_date_from+"&approved_date_to="+ vapproved_date_to+"&vendor_name="+ vvendor_name+"&filter_date="+ vfilter_date, '_blank');

     window.focus();

   });


    $('#btnView').on( 'click', function () {

      filter_date = $("#ddlfilterdateby").val()
      approved_date_from = $("#ddlApprovedDateFrom").val();
      approved_date_to = $("#ddlApprovedDateTo").val();
      vendor_name = $("#ddlVendorName").val();
      approved_status = $("#ddlstatus").val();

      table2.draw();

     //  $('#tblDataJournalAfterTax').slideDown(700);
     //  $('#tblDataJournalAfterTax').css( 'display', 'block' );

     //  <?php if(in_array("Payment Batch Inquiry", $group_name)) : ?>
     //   $("#tblDataJournalAfterTax").hide();
     // <?php endif ?>

     // table2.columns.adjust().draw();
     // $('#tblDatadownload').slideDown(700);


     table.draw();

     // $('#tblDataInquiry').slideDown(700);
     // $('#tblDataInquiry').css( 'display', 'block' );
     // table.columns.adjust().draw();
     // $('#tblDataInquiry').slideDown(700);
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

        url   : baseURL + 'userapprovedjournal/load_ddl_filter_date_by',

        type  : "POST",

        dataType: "html",

        success : function(result){

          $("#ddlfilterdateby").html("");       
          $("#ddlfilterdateby").html(result);          

        }

      });     

    }

    function getStatusapproved()

    {

      $.ajax({

        url   : baseURL + 'userapprovedjournal/load_ddl_approved',

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

      url   : baseURL + 'userapprovedjournal/load_ddl_Approve_batch',
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
      }

    });     

  }

 //  function getSelectAll()
 //  {
 //    $(".checklist").prop('checked',true);     
 //  }

 //  table2.on( 'draw', function () {         
 //   getSelectAll();
 // } );

//  $("#checkboxAll").on("click", function(){
//   if($(this).prop('checked') == false){      
//     $(".checklist").prop('checked',false);
//   } else {
//    $(".checklist").prop('checked',true);
//  } 
// });

//kodingan lama
$("#btn_save").on('click', function () {
  let detail_data_all   = [];
  let detail_data  = [];
  let total_data = table2.data().count();
  let data_line  = [];
  let approveval ='';
  let remarksvalue ='';
  let remarks ='';
  let jurnal ='';
  let replacejurnal ='';
  detail_data_all.push(detail_data);

    for (let i = 0; i < total_data; i++) 
    {
      j = i+1;

      get_data = table2.row(i).data();
      jurnal = get_data.journal_name;
      header_id = get_data.gl_header_id;
      user_logins = get_data.user_login;

      replacejurnal = jurnal.replace(/[^a-zA-Z0-9]/g, '');
      get_checkbox = document.getElementById("checkbox-"+ replacejurnal);
      get_remark   = document.getElementById("remark-"+ replacejurnal);

      var valuecheckbox = get_checkbox.options[get_checkbox.selectedIndex].value;
      var textcheckbox = get_checkbox.options[get_checkbox.selectedIndex].text;
      let today = new Date();
      let approved_date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

      let approved_status = $("#ddlstatus").val();

	     remarks = ((get_remark.value == null ) ? '' : get_remark.value);

        batch_approval = get_data.batch_approval;
      

    //kalau dari approved ke reject
    if(approved_status == "Y")
    {

      if (valuecheckbox == 0)
      {
        approveval = null;
        approved_date = null;
        remarks = '';


              // data_line.push({'nojournal' : jurnal, 'status' : approveval, 'remark_approved' : remarks, 'approved_date' : approved_date, 'batch_approval' : batch_approval, 'detail_data' : detail_data_all[i]});
      }

      if (valuecheckbox == 2)
      {
        approveval ='N';

        if(remarks == '' || remarks.includes('Approved by'))
        {
          remarks = ' Rejected by : ' + user_logins;
        }


              data_line.push({'nojournal' : jurnal, 'status' : approveval, 'remark_approved' : remarks, 'approved_date' : approved_date, 'batch_approval' : batch_approval, 'detail_data' : detail_data_all[i]});

      }

    }
    else if(approved_status == "N") // kalau dari reject ke approve
    {

      if (valuecheckbox == 0)
      {
        approveval = null;
        approved_date = null;
        remarks = '';


              // data_line.push({'nojournal' : jurnal, 'status' : approveval, 'remark_approved' : remarks, 'approved_date' : approved_date, 'batch_approval' : batch_approval, 'detail_data' : detail_data_all[i]});
      }

      if (valuecheckbox == 1) 
      {
        approveval ='Y';

        if(remarks == '' || remarks.includes('Rejected by'))
        {
          remarks = ' Approved by : ' + user_logins;
        }

              data_line.push({'nojournal' : jurnal, 'status' : approveval, 'remark_approved' : remarks, 'approved_date' : approved_date, 'batch_approval' : batch_approval, 'detail_data' : detail_data_all[i]});

      }

    }
    else // kalau dari choose
      {

       if (valuecheckbox == 1) 
      {
        approveval ='Y';

        if(remarks == '' || remarks.includes('Rejected by'))
        {
          remarks = ' Approved by : ' + user_logins;
        }

              data_line.push({'nojournal' : jurnal, 'status' : approveval, 'remark_approved' : remarks, 'approved_date' : approved_date, 'batch_approval' : batch_approval, 'detail_data' : detail_data_all[i]});

      }
      
      if (valuecheckbox == 2)
      {
        approveval ='N';

        if(remarks == '' || remarks.includes('Approved by'))
        {
          remarks = ' Rejected by : ' + user_logins;
        }

              data_line.push({'nojournal' : jurnal, 'status' : approveval, 'remark_approved' : remarks, 'approved_date' : approved_date, 'batch_approval' : batch_approval, 'detail_data' : detail_data_all[i]});

      }

    }

      // console.log(jurnal);
      // console.log(valuecheckbox);
      // console.log(textcheckbox);
      // console.log(approveval);
      // console.log(remarks);
    }

    // console.log(data_line)

    if(data_line.length > 0)
    {

      data = {
        data_line : data_line
      }

      $.ajax({
        url   : baseURL + 'userapprovedjournal/update_user_approved_journal',
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
            customLoading('hide');
            customNotif('Success', "Data has been approved", 'success');
            table.ajax.reload(null, false);
            table2.ajax.reload(null, false);
          }
          else {
            customLoading('hide');
            customNotif('Failed', "No data approved", 'error');
          }
        }
      });

    }
    else
    {
      customLoading('hide');
      customNotif('Warning', "No data approved", 'warning');
    }

  });

//region
//kodingan baru
// $('#table_data2').on('click', 'input.checklist', function () {

//   let data  = table2.row( $(this).parents('tr') ).data();
//   let nojournal = data.journal_name;
//   let today = new Date();
//   let approved_date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();

//   if (this.checked) {
//     check = 'Y';
//   }else{
//     check = 'N';
//   }

//   console.log(check);
//   console.log(nojournal);
//   console.log(approved_date);

//   $.ajax({
//     url   : baseURL + 'userapprovedjournal/update_user_approved_journal',
//     type  : "POST",
//     data  : {approved_status : check, nojournal : nojournal, approved_date : approved_date},
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

//kodingan baru
//  $('#table_data2').on('change', 'textarea.remarks', function () {

//   let data  = table2.row( $(this).parents('tr') ).data();
//   let nojournal = data.journal_name;
//   let remark_approved = this.value;

//   console.log(remark_approved);

//   $.ajax({
//     url   : baseURL + 'userapprovedjournal/update_remark',
//     type  : "POST",
//     data  : {remark_approved : remark_approved, nojournal : nojournal},
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
//endregion



<?php if(in_array("Approved Inquiry", $group_name)) : ?>
  $("#tblDataJournalAfterTax").hide();
  $("#divfilterstatus").hide();
<?php endif ?>


});

</script>