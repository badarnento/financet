<div class="white-box boxshadow">
  <div class="row">
    <div class="col-md-2">
        <div class="form-group">
          <label>Bulan</label>
          <select class="form-control" id="bulan" name="bulan">
            <option value="0">--Pilih--</option>
          <?php
             $namaBulan = list_month();
             $bln = date('m');
             for ($i=1;$i< count($namaBulan);$i++){
               $selected  = ($i==$bln)?"selected":"";
               echo "<option value='".$i."' data-name='".$namaBulan[$i]."' >".$namaBulan[$i]."</option>";
             }
          ?>            
          </select>
        </div>
    </div>
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
      <div class="col-md-2 col-md-2 col-sm-12">
        <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button" ><i class="fa  fa-search"></i> <span>View</span></button>
      </div>
      <div class="col-md-2 col-md-2 col-sm-12">
        <button id="btnCetak" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Cetak TB</span></button>
      </div>
    </div>
  </div>
  </div>
</div>

<div class="row">   
  <div id="accordion" class="panel panel-info boxshadow animated slideInDown">
    <div id="collapse-data" class="panel-collapse collapse in">
      <div class="panel-body">
          <table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center">NATURE</th>
                <th class="text-center">ACCOUNT DESCRIPTION</th>
                <th class="text-center">SALDO AWAL</th>
                <th class="text-center">DEBIT</th>
                <th class="text-center">CREDIT</th>
                <th class="text-center">BALANCE</th>
                <th class="text-center">SALDO AKHIR</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>

<script>
  
  $(document).ready(function(){

    $("#btnCetak").attr('disabled', true);

    $("#btnCetak").on("click", function(){
    year  = $("#year").val();
    month = $("#bulan").val();
    category = "tb";

    url         ="<?php echo site_url(); ?>tb_report/tbReport_ctl/load_tb";

    window.open(url+'?year='+year+'&month='+month+'&category='+category, '_blank');
    window.focus();
  });

    let tahun = $("#year").val();
    let bulan = $("#bulan").val();
    let url = baseURL + 'tb_report/tbReport_ctl/load_data_tbdtl';
    let ajaxData = {
            "url"  : url,
            "type" : "POST",
            "data"    : function ( d ) {
                                d.tahun = tahun;
                                d.bulan = bulan;
                              }
          }
    let jsonData = [
                    { "data": "no", "width":"10px", "class":"text-center"},
                    { "data": "nature", "width":"100px", "class":"text-left"},
                    { "data": "account_description", "width":"200px", "class":"text-left"},
                    { "data": "saldo_awal", "width":"200px", "class":"text-right"},
                    { "data": "debit", "width":"200px", "class":"text-right"},
                    { "data": "credit", "width":"200px", "class":"text-right"},
                    { "data": "balance", "width":"200px", "class":"text-right"},
                    { "data": "saldo_akhir", "width":"200px", "class":"text-right"}
                   ];
    data_table(ajaxData,jsonData);

    let table = $('#table_data').DataTable();

    $('#btnView').on( 'click', function () {
      tahun = $("#year").val();
      bulan = $("#bulan").val();
      table.draw();
      $("#btnCetak").removeAttr('disabled');
    });

  });
</script>