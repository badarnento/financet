<div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-md-3 col-sm-6">
        <div class="form-group">
          <label>Payment Batch Date From</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="payment_date_from" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="form-group">
          <label>Payment Batch Date To</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="payment_date_to" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
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
      <div class="col-sm-6 col-sm-offset-6 col-md-3 col-md-offset-9">
        <div class="form-group pull-right">
          <label>&nbsp;</label>
          <button id="btn-create" class="btn btn-success btn-rounded w-200p" type="button" ><i class="fa  fa-plus"></i> <span> CREATE NEW</span></button>
        </div>
      </div>
    </div>
    <div class="row">
    	<div class="col-md-12">
    		<table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe small">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th class="text-center">Batch Name</th>
              <th class="text-center">Batch Date</th>
              <th class="text-center">Amount</th>
              <th class="text-center">Action</th>
              <th class="text-center">Approve</th>
            </tr>
          </thead>
        </table>
      </div>

      <?php if($approval): ?>
        <div class="col-md-12">
          <button type="button" id="save_data" class="btn btn-info m-10 w-100p"><i class="fa fa-save"></i> Save </button>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- added by adi baskoro -->
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

<div class="row" id="tblData">

  <div class="col-md-12">

    <div class="white-box">

      <table id="table_data2" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">

        <thead>

          <tr>

            <th class="text-center">No.</th>
            <th class="text-center">Batch Name</th>
            <th class="text-center">No Journal</th>
            <th class="text-center">Account Description</th>
            <th class="text-center">Nature</th>
            <th class="text-center">Debit</th>
            <th class="text-center">Credit</th>
            <th class="text-center">Journal Description</th>
            <th class="text-center">Accounting Date</th>

          </tr>

        </thead>

      </table>

    </div>

  </div>

</div>

<div class="row" id="tblDatadownload2">
  <div class="col-md-12">
    <div class="white-box boxshadow">     
      <div class="row">
        <div class="form-group">
          <div class="col-md-offset-5 col-md-2 m-b-10">
            <button id="btnDownload" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){

    let date_from   = $("#payment_date_from").val();
    let date_to     = $("#payment_date_to").val();

    const CATEGORY  = '<?= $category ?>';

    const hide_action = '<?= (in_array("Payment Batch Inquiry", $group_name)) ? " d-none" : "" ?>';

    let url        = baseURL + 'payment-batch/api/load_batch_payment_inquiry';
    let ajaxData = {
      "url"  : url,
      "type" : "POST",
      "dataType": "json",
      "data"    : function ( d ) {
       d.date_from  = date_from;
       d.date_to    = date_to;
     }
   }

   let jsonData = [
            { "data": "no", "width": "10px", "class": "text-center" },
            { "data": "batch_name", "width": "100px" },
            { "data": "batch_date", "width": "100px", "class": "text-center" },
            { "data": "total_amount", "width": "100px", "class": "text-right" },
            { "data": "action", "width": "100px", "class": "text-center" },
            { "data": "approval", "width": "100px", "class": "text-center" }
         ];

 data_table(ajaxData,jsonData);

 table = $('#table_data').DataTable();

 let url2  = baseURL + 'payment-batch/api/load_data_journal';

 let ajaxData2 = {

  "url"  : url2,

  "type" : "POST",

  "dataType": "json",

  "data"    : function ( d ) {

    d.date_from = date_from;
    d.date_to   = date_to;

  }

}

let jsonData2 = [
{ "data": "no", "width": "10px", "class": "text-center" },
{ "data": "batch_name", "width": "100px", "class": "text-left" },
{ "data": "no_journal", "width": "100px", "class": "text-left" },
{ "data": "account_description", "width": "150px", "class": "text-left" },
{ "data": "nature", "width": "100px", "class": "text-left" },
{ "data": "debit", "width": "75px", "class": "text-left" },
{ "data": "credit", "width": "75px", "class": "text-left" },
{ "data": "journal_description", "width": "500px", "class": "text-left" },
{ "data": "gl_date", "width": "75px", "class": "text-left" },

];

data_table(ajaxData2,jsonData2,"table_data2");

table2 = $('#table_data2').DataTable();

$('#btnView').on( 'click', function () {
  directorate = $("#directorate").val();
  fs_status   = $("#status").val();
  date_from   = $("#payment_date_from").val();
  date_to     = $("#payment_date_to").val();
  table.draw();
  table2.draw();
});

$("#btnPrint").on("click", function(){

  let vpayment_date_from = '';
  let vpayment_date_to = '';

  let url   ="<?php echo site_url(); ?>payment-batch/api/download_batch_payment_inquiry";

  vpayment_date_from = $("#payment_date_from").val();
  vpayment_date_to = $("#payment_date_to").val();

  window.open(url+'?date_from='+vpayment_date_from +"&date_to="+ vpayment_date_to, '_blank');

  window.focus();

});


$('#table_data tbody').on('change', 'tr td select.approval1', function () {

    let data       = table.row( $(this).parents('tr') ).data();
    let batch_name = data.batch_name;
    let approval   = $(this).val();

    // console.log(approval);

    $.ajax({
      url   : baseURL + 'payment-batch/api/update_approval',
      type  : "POST",
      data  : {batch_name :  batch_name, approval : approval},
      dataType: "json",
      success : function(result){
        console.log('success');
      }
    });
});


$("#save_data").on("click", function(){

     customLoading('show');
     
     setTimeout(function(){
       customLoading('hide');
       customNotif('Success', 'Data successfully saved', 'success');
     }, 1000);

      /*let data_lines  = [];

      table.rows().eq(0).each( function ( index ) {
          j = index+1;
          data = table.row( index ).data();
          batch_name = data.batch_name;

          approval1      = $("#approval1-"+j).val();
          approval2      = $("#approval2-"+j).val();
          data_lines.push({'batch_name' : batch_name, 'approval1' : approval1, 'approval2' : approval2});
      });

        $.ajax({
            url   : baseURL + 'payment-batch/api/save_approval',
            type  : "POST",
            data  : {  category : CATEGORY, data_lines : data_lines },
            dataType: "json",
            beforeSend  : function(){
                          customLoading('show');
                        },
            success : function(result){
              console.log(result);
              if(result.status == true){
                customLoading('hide');
                customNotif('Success', result.messages, 'success');
              }
              else{
                customLoading('hide');
                customNotif('Error', result.messages, 'error');
              }
            }
        });*/


});

$("#btnDownload").on("click", function(){

  let vpayment_date_from = '';
  let vpayment_date_to = '';

  let url   ="<?php echo site_url(); ?>payment-batch/api/download_data_journal";

  vpayment_date_from = $("#payment_date_from").val();
  vpayment_date_to = $("#payment_date_to").val();

  window.open(url+'?date_from='+vpayment_date_from +"&date_to="+ vpayment_date_to, '_blank');

  window.focus();

});

$('#table_data').on('click', 'a.action-view', function () {
  batch_name = $(this).data('id');
  $(location).attr('href', baseURL + 'payment-batch/' + batch_name);
});

$('#table_data').on('click', 'a.action-edit', function () {
  id_fs = $(this).data('id');
  data  = table.row( $(this).parents('tr') ).data();

});

$('#table_data').on('click', 'a.action-edit', function () {
  id_fs = $(this).data('id');
  $(location).attr('href', baseURL + 'feasibility-study/edit/' + id_fs);
  status = data.status
  if(status.toLowerCase() == "fs used"){
    customNotif('Warning', 'Tidak bisa di edit karena status '+status, 'warning');
  }else{
    $(location).attr('href', baseURL + 'feasibility-study/edit/' + pr_header_id);
  }
});


    $('#table_data').on('click', 'input.paid_status', function () {

      let data  = table.row( $(this).parents('tr') ).data();
      let batch_name = data.batch_name;

      if (this.checked) {
        check = 'Y';
      }else{
        check = 'N';
      }

      $.ajax({
        url   : baseURL + 'payment-batch/api/update_paid_status',
        type  : "POST",
        data  : {paid_status :  check, batch_name :  batch_name},
        dataType: "json",
        success : function(result){
          console.log(result);
          if (result.status == true) {
            customNotif('Success', result.messages, 'success');
          } else {
            customNotif('Failed', result.messages, 'error');
          }
        }
      });
    });


$('#btn-create').on( 'click', function () {
  customLoading('show');
  setTimeout(function(){
    $(location).attr('href', baseURL + 'payment-batch/create'/* + result.pr_number*/);
  }, 300);
});

$('.mydatepicker').datepicker({
  format: 'dd-mm-yyyy',
  todayHighlight:'TRUE',
  autoclose: true,
});

  $('#table_data').on('click', 'a.action-delete', function () {
      data           = table.row( $(this).parents('tr') ).data();
      batch_name_del = data.batch_encrypt;

      if(data.paid_status == 1){
        customNotif('Warning', 'Tidak bisa di hapus karena status Approved', 'warning');
      }else{
        $("#modal-delete-label").html('Delete Data : ' +  data.batch_name);
        $("#modal-delete").modal('show');
      }
    });

    $('#button-delete').on( 'click', function () {
      console.log(batch_name_del)
        $.ajax({
          url       : baseURL + 'payment-batch/api/delete_batch',
          type      : 'post',
          data      : { batch_name: batch_name_del},
          beforeSend  : function(){
                          customLoading('show');
                        },
          dataType : 'json',
          success : function(result){
            $("#modal-delete").modal('hide');
            customLoading('hide');
            if (result.status == true) {
              table.ajax.reload(null, false);
              customNotif('Success', result.messages, 'success');
            } else {
              customNotif('Failed', result.messages, 'error');
            }
          }
        });
    });

      $('#table_data').on('click', 'a.action-cetak', function () {
        batch_name = $(this).data('id');
        data       = table.row( $(this).parents('tr') ).data();
        bank_name  = data.bank_name;

        let url   = baseURL + 'payment-batch/api/export_csv?batch_name='+batch_name;

        if(bank_name == "BNI"){
          window.open(url+'&category=1', '_blank');
        }else if(bank_name == "BANK BNI Syariah"){
          window.open(url+'&category=1', '_blank');
        }
        else{
          window.open(url+'&category=2', '_blank');
          setTimeout(function () {
            window.open(url+'&category=3', '_blank');
          }, 1000)
        }
        window.focus();

      });

      <?php if($this->session->flashdata('messages') != ""): ?>
        customNotif('Error', '<?= $this->session->flashdata('messages') ?>', 'error');
      <?php endif; ?>

      <?php if(in_array("Payment Batch Inquiry", $group_name)) : ?>
        $("#btn-create").hide();
      <?php endif ?>

    });
  </script>