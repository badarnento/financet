<!-- <div class="row">
  <div class="white-box boxshadow">
    <div class="row">
      <div class="col-sm-2">
        <div class="form-group">
          <label>Tahun</label>
          <select class="form-control" id="year">
            <option value="0">--Pilih--</option>
              <?php 
                foreach($get_exist_year as $value):
                  $selected = ($value['YEAR']==date('Y'))?"selected":"";
                  echo "<option value='".$value['YEAR']."' ".$selected.">".$value['YEAR']."</option>";
                endforeach
              ?>
          </select>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="form-group">
          <label>Bulan</label>
          <select class="form-control" id="month" name="month">
              <?php
                 $namaBulan = list_month();
                 $bln = date('m');
                 for ($i=1;$i< count($namaBulan);$i++){
                  $selected = ($i==$bln)?"selected":"";
                   echo "<option value='".sprintf("%02d", $i)."' data-name='".$namaBulan[$i]."' ".$selected." >".$namaBulan[$i]."</option>";
                 }
              ?>
            </select>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
        </div>
      </div>
    </div>
  </div>
</div> -->

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
            <input type="text" class="form-control mydatepicker" id="payment_date_from" placeholder="dd/mm/yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="form-group">
          <label>Date To</label>
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
    	<div class="col-md-12">
    		<table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe small">
          <thead>
            <tr>
              <th class="text-center">No</th>
              <th class="text-center">Period</th>
              <th class="text-center">Tgl Terima AP</th>
              <th class="text-center">No Invoice</th>
              <th class="text-center">No Journal AP</th>
              <th class="text-center">No Journal TR</th>
              <th class="text-center">Rekening Sumber</th>
              <th class="text-center">No RK Sumber</th>
              <th class="text-center">Rekening Penerima</th>
              <th class="text-center">No RK Penerima</th>
              <th class="text-center">Nama Penerima</th>
              <th class="text-center">Total Invoice</th>
              <th class="text-center">Total Bayar</th>
              <th class="text-center">Keterangan</th>
              <th class="text-center">Paid Date</th>
              <th class="text-center">Due Date</th>
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

    let date_from     = $("#payment_date_from").val();
    let date_to       = $("#payment_date_to").val();
    let filterdateby  = $("#ddlfilterdateby").val();
    getFilterDate();
    $("#btnView").attr('disabled', true);

    let url        = baseURL + 'report/list-payment/api/load_data_outstanding';
    Pace.track(function(){
      $('#table_data').DataTable({
        "serverSide"      : true,
        "processing"      : true,
        "ajax"            : {
          "url"  : url,
          "type" : "POST",
          "dataType": "json",
          "data"    : function ( d ) {
               d.date_from        = date_from;
               d.date_to          = date_to;
               d.filterdateby     = filterdateby;
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
                            { "data": "period", "width": "70px", "class": "text-center" },
                            { "data": "tgl_terima_ap", "width": "100px", "class": "text-center" },
                            { "data": "no_invoice", "width": "180px", "class": "text-left" },
                            { "data": "no_journal_ap", "width": "100px", "class": "text-center" },
                            { "data": "no_journal_tr", "width": "100px", "class": "text-center" },
                            { "data": "rekening_sumber", "width": "100px", "class": "text-left" },
                            { "data": "no_rk_sumber", "width": "100px", "class": "text-center" },
                            { "data": "rekening_penerima", "width": "150px", "class": "text-left" },
                            { "data": "no_rk_penerima", "width": "150px", "class": "text-center" },
                            { "data": "nama_penerima", "width": "150px", "class": "text-left" },
                            { "data": "total_invoice", "width": "150px", "class": "text-right" },
                            { "data": "total_bayar", "width": "150px", "class": "text-right" },
                            { "data": "keterangan", "width": "250px", "class": "text-left" },
                            { "data": "paid_date", "width": "100px", "class": "text-center" },
                            { "data": "due_date", "width": "100px", "class": "text-center" }
        ],
        "pageLength"      : 100,
        "ordering"        : false,
        "scrollY"         : 480,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "bAutoWidth"      : true,
        "rowsGroup"       : [12],
        drawCallback: function (settings) {
          var api = this.api();
          var rows = api.rows({ page: 'current' }).nodes();
          var last = null;
          api.column(5, { page: 'current' }).data().each(function (group, i) {
            console.log(i);
            if (last != group && i > 0) {

              $(rows).eq(i).before(
                '<tr class="group"><td align="center" colspan="24" style="color:#fff;background-color:#999;line-height: 0px;height: 5px;padding: 0px;">&nbsp;</td></tr>'
                );
            }
            last = group;
          });
        }
      });
    });

   table = $('#table_data').DataTable();

  $('#btnView').on( 'click', function () {
      date_from     = $("#payment_date_from").val();
      date_to       = $("#payment_date_to").val();
      filterdateby  = $("#ddlfilterdateby").val();
    table.draw();
  });

  
  $("#btnPrint").on("click", function(){

      date_from     = $("#payment_date_from").val();
      date_to       = $("#payment_date_to").val();
      filterdateby  = $("#ddlfilterdateby").val();

    let url = baseURL + "report/list-payment/api/download_inquiry";
    window.open(url+'?from='+date_from +"&to="+ date_to+"&filterdateby="+ filterdateby, '_blank');
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
      url   : baseURL + 'report/list-payment/api/load_ddl_filter_date_by',
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