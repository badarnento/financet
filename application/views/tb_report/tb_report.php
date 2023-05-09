<div class="row">
<div class="white-box boxshadow">
  <div class="row">
    <div class="col-md-2">
      <div class="form-group">
        <label>Tahun</label>
        <select class="form-control" id="year">
          <option value="0">--Pilih--</option>
            <?php 
              foreach($get_exist_year as $value):
                echo "<option value='".$value['YEAR']."'>".$value['YEAR']."</option>";
              endforeach
            ?>
            </select>
          </div>
        </div>
        <label>&nbsp;</label>
  <div class="row">
    <div class="form-group">
      <div class="col-md-2 col-md-2 col-sm-12 d-none">
        <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button" ><i class="fa  fa-search"></i> <span>View</span></button>
      </div>
      <div class="col-md-2 col-md-2 col-sm-12">
        <button id="btnCetak" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Cetak TB</span></button>
      </div>
    </div>
  </div>
  </div>
</div>
</div>
<!-- <div class="row" id="tblData">   
  <div id="accordion" class="panel panel-info boxshadow animated slideInDown">
    <div id="collapse-data" class="panel-collapse collapse in">
      <div class="panel-body">
          <table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center">NATURE</th>
                <th class="text-center">DESCRIPTION</th>
                <th class="text-center">GROUP REPORT</th>
                <th class="text-center">SALDO AWAL</th>
                <th class="text-center">JANUARI</th>
                <th class="text-center">FEBRUARI</th>
                <th class="text-center">MARET</th>
                <th class="text-center">APRIL</th>
                <th class="text-center">MEI</th>
                <th class="text-center">JUNI</th>
                <th class="text-center">JULI</th>
                <th class="text-center">AGUSTUS</th>
                <th class="text-center">SEPTEMBER</th>
                <th class="text-center">OKTOBER</th>
                <th class="text-center">NOVEMBER</th>
                <th class="text-center">DESEMBER</th>
                <th class="text-center">YTD</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div> -->

<div class="row d-none" id="tblData">
  <div class="col-md-12">
      <div class="white-box">
        <!-- <h3 class="box-title m-b-0">Data Table</h3> -->
        <!-- <div class="table-responsive"> -->
          <table id="table_dataxx" class="table table-striped table-hover table-bordered display cell-border stripe hover">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center">NATURE</th>
                <th class="text-center">DESCRIPTION</th>
                <th class="text-center">GROUP REPORT</th>
                <th class="text-center">SALDO AWAL</th>
                <th class="text-center">JANUARI</th>
                <th class="text-center">FEBRUARI</th>
                <th class="text-center">MARET</th>
                <th class="text-center">APRIL</th>
                <th class="text-center">MEI</th>
                <th class="text-center">JUNI</th>
                <th class="text-center">JULI</th>
                <th class="text-center">AGUSTUS</th>
                <th class="text-center">SEPTEMBER</th>
                <th class="text-center">OKTOBER</th>
                <th class="text-center">NOVEMBER</th>
                <th class="text-center">DESEMBER</th>
                <th class="text-center">YTD</th>
              </tr>
            </thead>
          </table>
      <!-- </div> -->
      </div>
    </div>
</div>

<!-- <div class="row">
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
</div> -->

<script>
  
  $(document).ready(function(){

    $("#btnCetak").attr('disabled', true);
    //$("#tblData").hide();
    $("#btnView").attr('disabled', true);

    let param = 0;
    let tahun = $("#year").val();

let url = baseURL + 'tb_report/tbReport_ctl/load_data_tbytd';
    let ajaxData = {
            "url"  : url,
            "type" : "POST",
            "dataType": "json",
            "data"    : function ( d ) {
                                d.param = param;
                                d.tahun = tahun;
                              }
          }
    let jsonData = [
                    { "data": "no", "width":"10px", "class":"text-center"},
                    { "data": "nature", "width":"100px", "class":"text-left"},
                    { "data": "description", "width":"200px", "class":"text-left"},
                    { "data": "group_report", "width":"200px", "class":"text-left"},
                    { "data": "saldo_awal", "width":"150px", "class":"text-right"},
                    { "data": "jan", "width":"150px", "class":"text-right"},
                    { "data": "feb", "width":"150px", "class":"text-right"},
                    { "data": "mar", "width":"150px", "class":"text-right"},
                    { "data": "apr", "width":"150px", "class":"text-right"},
                    { "data": "may", "width":"150px", "class":"text-right"},
                    { "data": "jun", "width":"150px", "class":"text-right"},
                    { "data": "jul", "width":"150px", "class":"text-right"},
                    { "data": "aug", "width":"150px", "class":"text-right"},
                    { "data": "sep", "width":"150px", "class":"text-right"},
                    { "data": "oct", "width":"150px", "class":"text-right"},
                    { "data": "nov", "width":"150px", "class":"text-right"},
                    { "data": "des", "width":"150px", "class":"text-right"},
                    { "data": "ytd", "width":"150px", "class":"text-right"}
                   ];

    // data_table(ajaxData,jsonData);
    
    // let table = $('#table_data').DataTable();
    $("#table_data_filter").remove();

    $("#year").on("click", function () {
      $("#btnView").removeAttr('disabled');
      $("#btnCetak").removeAttr('disabled');
    })

    $("#btnView").on( "click", function () {
      tahun = $("#year").val();
      param = 1;
      table.draw();
      /*$("#tblData").slideDown(700);
      $('#tblData').css( 'display', 'block' );
      table.columns.adjust().draw();*/
    });

    $("#btnCetak").on("click", function(){
    year = $("#year").val();
    category = "tb";

    url         ="<?php echo site_url(); ?>tb_report/tbReport_ctl/load_tb_ytd";

    window.open(url+'?year='+year+'&category='+category, '_blank');
    window.focus();
  });

  });
</script>