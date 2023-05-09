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
      <div class="col-md-2">
        <div class="form-group">
          <label>Bulan</label>
          <select class="form-control" id="month" name="month">
              <?php
                 $namaBulan = list_month();
                 $bln = date('m');
                 for ($i=1;$i< count($namaBulan);$i++){
                  $selected = ($i==$bln)?"selected":"";
                   echo "<option value='".$i."' data-name='".$namaBulan[$i]."' ".$selected." >".$namaBulan[$i]."</option>";
                 }
              ?>
            </select>
        </div>
      </div>
    </div>
  <div class="row">
    <div class="form-group">
      <div class="col-md-2 col-md-2 col-sm-12">
        <button id="btnCetak" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Cetak BS</span></button>
      </div>
    </div>
  </div>
  </div>
</div>

<script>
  $(document).ready(function(){

    $("#btnCetak").on("click", function(){
      year   = $("#year").val();
      month  = $("#month").val();
      bulan  = $("#month").find(':selected').attr('data-name');
      url   ="<?php echo site_url(); ?>report_xls/Bs/load_bs";
      window.open(url+'?year='+year+'&month='+month+'&bulan='+bulan, '_blank');
      window.focus();
    });

  });
</script>