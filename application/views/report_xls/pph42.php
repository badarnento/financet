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
 <div class="col-md-2">
      <div class="form-group">
        <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
      </div>
  </div>
  <div class="row">
    <div class="form-group">
      <div class="col-md-2 col-md-2 col-sm-12">
        <button id="btnCetak" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download Pph 4(2) Sewa</span></button>
      </div>
    </div>
  </div>
</div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Pph 4(2) Sewa</h5>
    </div>
  </div>
  <div class="white-box boxshadow border-bottom-only-5">
  <div class="row">
    <div id="tbl_search" class="col-md-12 positon-relative">
      <i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
      <input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
    </div>
    <div class="col-md-12" style="overflow: auto;">
        <table id="table_data" class="table dataTable table-responsive table-striped table-bordered cell-border stripe small hover w-full">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center">NPWP WP Dipotong</th>
                <th class="text-center">Nama WP Dipotong</th>
                <th class="text-center">Alamat WP Dipotong</th>
                <th class="text-center">NOP</th>
                <th class="text-center">Lokasi Sewa</th>
                <th class="text-center">Nomor Bukti Potong</th>
                <th class="text-center">Tanggal Bukti Potong</th>
                <th class="text-center">Bruto</th>
                <th class="text-center">PPh</th>
                <th class="text-center">NPWP Pemotong</th>
                <th class="text-center">Nama Pemotong</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>
  </div>
</div>

<script>
  
  $(document).ready(function(){

    let year  = $('#year').val();
    let month = $('#month').val();

  let url = baseURL + 'report_xls/pph/load_pph42';

  Pace.track(function(){
    $('#table_data').DataTable({
      "serverSide"      : true,
      "processing"      : true,
      "ajax"            : {
                "url"  : url,
                "type" : "POST",
                "dataType": "json",
                "data"    : function ( d ) {
                            d.year  = year;
                            d.month = month;
                                    }
                        },
      "language"        : {
                            "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
                            "infoEmpty"   : "Empty Data",
                            "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
                            "search"      : "_INPUT_"
                          },
      "columns"         : [
                  { "data": "no", "width":"10px", "class":"text-center"},
                  { "data": "npwp_dipotong", "width":"200px", "class":"text-left"},
                  { "data": "wp_dipotong", "width":"300", "class":"text-left"},
                  { "data": "alamat", "width":"500px", "class":"text-left"},
                  { "data": "nop", "width":"100px", "class":"text-center"},
                  { "data": "lokasi", "width":"300px", "class":"text-left"},
                  { "data": "no_bupot", "width":"200px", "class":"text-center"},
                  { "data": "tgl_bupot", "width":"100px", "class":"text-left"},
                  { "data": "dpp", "width":"100px", "class":"text-right"},
                  { "data": "pph", "width":"100px", "class":"text-right"},
                  { "data": "npwp_pemotong", "width":"200px", "class":"text-left"},
                  { "data": "nama_pemotong", "width":"200px", "class":"text-left"}
              ],
      "pageLength"      : 100,
      "ordering"        : false,
      "scrollY"         : 480,
      "scrollCollapse"  : true,
      "scrollX"         : true,
      "autoWidth"       : true,
      "bAutoWidth"      : false
    });
});

  let table = $('#table_data').DataTable();
  $('#table_data_filter').remove();
  $('#table_data_length').remove();
  $("#tbl_search").on('keyup', "input[type='search']", function(){
      table.search( $(this).val() ).draw();
  });

  $('#btnView').on( 'click', function () {
      year  = $('#year').val();
      month  = $('#month').val();
    table.draw();
  });

    $("#btnCetak").on("click", function(){
      year  = $('#year').val();
      month  = $('#month').val();
      url         ="<?php echo site_url(); ?>report_xls/Pph/cetak_pph42";
      window.open(url+'?year='+year+'&month='+month, '_blank');
      window.focus();
    });

  });
</script>