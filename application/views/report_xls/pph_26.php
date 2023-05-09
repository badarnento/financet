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
        <button id="btnCetak" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download Pph 26</span></button>
      </div>
    </div>
  </div>
</div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">PPH 26</h5>
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
                <th class="text-center">Masa Pajak</th>
                <th class="text-center">Tahun Pajak</th>
                <th class="text-center">Tanggal Invoice</th>
                <th class="text-center">Tanggal Pemotong</th>
                <th class="text-center">TIN</th>
                <th class="text-center">Nama WP Pemotong</th>
                <th class="text-center">Tanggal Lahir WP Terpotong</th>
                <th class="text-center">Alamat WP Terpotong</th>
                <th class="text-center">No Paspor WP Terpotong</th>
                <th class="text-center">No Kitas WP Terpotong</th>
                <th class="text-center">Kode Negara</th>
                <th class="text-center">Kode Objek Pajak</th>
                <th class="text-center">Penanda Tangan BP Pengurus</th>
                <th class="text-center">Penghasilan Bruto</th>
                <th class="text-center">No Dok</th>
                <!-- <th class="text-center">No Invoice</th> -->
                <th class="text-center">Perkiraan Pengahsilan Neto</th>
                <th class="text-center">Mendapatkan Fasilitas</th>
                <th class="text-center">No Tanda Terima SKD</th>
                <th class="text-center">Tarif SKD</th>
                <th class="text-center">No Aturan DTP</th>
                <th class="text-center">NTPN DTP</th>
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

  let url = baseURL + 'report_xls/pph/load_pph26';

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
                  { "data": "masa_pajak", "width":"50px", "class":"text-center"},
                  { "data": "tahun_pajak", "width":"100", "class":"text-center"},
                  { "data": "tgl_invoice", "width":"100", "class":"text-center"},
                  { "data": "tgl_pemotong", "width":"100px", "class":"text-center"},
                  { "data": "tin", "width":"100px", "class":"text-center"},
                  { "data": "nama_wp", "width":"300px", "class":"text-left"},
                  { "data": "tgl_lahir", "width":"100px", "class":"text-center"},
                  { "data": "alamat_wp", "width":"300px", "class":"text-left"},
                  { "data": "no_paspor", "width":"100px", "class":"text-left"},
                  { "data": "no_kitas", "width":"100px", "class":"text-left"},
                  { "data": "kode_negara", "width":"100px", "class":"text-right"},
                  { "data": "kode_objek_pajak", "width":"100px", "class":"text-left"},
                  { "data": "penanda_tangan", "width":"100px", "class":"text-center"},
                  { "data": "dpp", "width":"100px", "class":"text-right"},
                  // { "data": "no_dok", "width":"300px", "class":"text-left"},
                  { "data": "no_invoice", "width":"300px", "class":"text-left"},
                  { "data": "perkiraan_penghasilan", "width":"100px", "class":"text-right"},
                  { "data": "fasilitas", "width":"100px", "class":"text-center"},
                  { "data": "no_tanda_terima", "width":"100px", "class":"text-left"},
                  { "data": "tarif_skd", "width":"100px", "class":"text-left"},
                  { "data": "aturan_dtp", "width":"100px", "class":"text-left"},
                  { "data": "ntpn_dtp", "width":"100px", "class":"text-left"}
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
      url         ="<?php echo site_url(); ?>report_xls/Pph/cetak_pph26";
      window.open(url+'?year='+year+'&month='+month, '_blank');
      window.focus();
    });

  });
</script>