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
        <button id="btnCetak" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Cetak Expense</span></button>
      </div>
    </div>
  </div>
  </div>
</div>

<div class="row">
  <div class="white-box boxshadow m-b-0 border-top-only-5 panel-title-custom">
    <div class="col-md-6">
      <h5 class="font-weight-700 m-0 text-uppercase">Expense Dhasboard</h5>
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
                <th class="text-center">Nature</th>
                <th class="text-center">COA Description</th>
                <th class="text-center">Outstanding</th>
                <th class="text-center">Reverse</th>
                <th class="text-center">Tanggal 1</th>
                <th class="text-center">Tanggal 2</th>
                <th class="text-center">Tanggal 3</th>
                <th class="text-center">Tanggal 4</th>
                <th class="text-center">Tanggal 5</th>
                <th class="text-center">Tanggal 6</th>
                <th class="text-center">Tanggal 7</th>
                <th class="text-center">Tanggal 8</th>
                <th class="text-center">Tanggal 9</th>
                <th class="text-center">Tanggal 10</th>
                <th class="text-center">Tanggal 11</th>
                <th class="text-center">Tanggal 12</th>
                <th class="text-center">Tanggal 13</th>
                <th class="text-center">Tanggal 14</th>
                <th class="text-center">Tanggal 15</th>
                <th class="text-center">Tanggal 16</th>
                <th class="text-center">Tanggal 17</th>
                <th class="text-center">Tanggal 18</th>
                <th class="text-center">Tanggal 19</th>
                <th class="text-center">Tanggal 20</th>
                <th class="text-center">Tanggal 21</th>
                <th class="text-center">Tanggal 22</th>
                <th class="text-center">Tanggal 23</th>
                <th class="text-center">Tanggal 24</th>
                <th class="text-center">Tanggal 25</th>
                <th class="text-center">Tanggal 26</th>
                <th class="text-center">Tanggal 27</th>
                <th class="text-center">Tanggal 28</th>
                <th class="text-center">Tanggal 29</th>
                <th class="text-center">Tanggal 30</th>
                <th class="text-center">Tanggal 31</th>
                <th class="text-center">Grand Total</th>
              </tr>
            </thead>
            <tfoot align="right">
              <tr><th colspan="2"></th><th></th><th></th><th></th><th></th>
                  <th></th><th></th><th></th><th></th><th></th><th></th>
                  <th></th><th></th><th></th><th></th><th></th><th></th>
                  <th></th><th></th><th></th><th></th><th></th><th></th>
                  <th></th><th></th><th></th><th></th><th></th><th></th>
                  <th></th><th></th><th></th><th></th><th></th><th></th>
                  <th></th>
              </tr>
            </tfoot>
          </table>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){

    let year  = $('#year').val();
    let month = $('#month').val();

  let url = baseURL + 'report_xls/Expense/load_data';

  Pace.track(function(){
    $('#table_data').DataTable({
      "serverSide"      : true,
      "processing"      : true,
      "ajax"            : {
                "url"  : url,
                "type" : "POST",
                "dataType": "json",
                "data"    : function ( d ) {
                              d.year = year;
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
                  { "data": "nature", "width":"50px", "class":"text-left"},
                  { "data": "coa_description", "width":"300px", "class":"text-left"},
                  { "data": "outstanding", "width":"100px", "class":"right-right"},
                  { "data": "reverse", "width":"100", "class":"text-left"},
                  { "data": "tanggal_1", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_2", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_3", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_4", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_5", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_6", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_7", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_8", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_9", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_10", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_11", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_12", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_13", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_14", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_15", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_16", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_17", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_18", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_19", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_20", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_21", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_22", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_23", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_24", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_25", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_26", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_27", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_28", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_29", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_30", "width":"100px", "class":"text-right"},
                  { "data": "tanggal_31", "width":"100px", "class":"text-right"},
                  { "data": "grand_total", "width":"100px", "class":"text-right"}
              ],
              "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/\./g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
      var outstanding = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
      var reverse = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
       var tgl_1 = api
                .column( 5 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
       var tgl_2 = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_3 = api
                .column( 7 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_4 = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_5 = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_6 = api
                .column( 10 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_7 = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_8 = api
                .column( 12 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_9 = api
                .column( 13 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_10 = api
                .column( 14 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_11 = api
                .column( 15 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_12 = api
                .column( 16 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_13 = api
                .column( 17 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_14 = api
                .column( 18 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_15 = api
                .column( 19 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_16 = api
                .column( 20 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_17 = api
                .column( 21 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_18 = api
                .column( 22 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_19 = api
                .column( 23 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_20 = api
                .column( 24 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_21 = api
                .column( 25 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_22 = api
                .column( 26 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_23 = api
                .column( 27 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_24 = api
                .column( 28 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_25 = api
                .column( 29 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_26 = api
                .column( 30 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_27 = api
                .column( 31 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_28 = api
                .column( 32 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_29 = api
                .column( 33 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_30 = api
                .column( 34 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var tgl_31 = api
                .column( 35 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        var total = api
                .column( 36 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
      
        
            // Update footer by showing the total with the reference of the column index 
      $( api.column( 0 ).footer() ).html('Total');
      $( api.column( 3 ).footer() ).html(formatNumber(outstanding));
      $( api.column( 4 ).footer() ).html(formatNumber(reverse));
      $( api.column( 5 ).footer() ).html(formatNumber(tgl_1));
      $( api.column( 6 ).footer() ).html(formatNumber(tgl_2));
      $( api.column( 7 ).footer() ).html(formatNumber(tgl_3));
      $( api.column( 8 ).footer() ).html(formatNumber(tgl_4));
      $( api.column( 9 ).footer() ).html(formatNumber(tgl_5));
      $( api.column( 10 ).footer() ).html(formatNumber(tgl_6));
      $( api.column( 11 ).footer() ).html(formatNumber(tgl_7));
      $( api.column( 12 ).footer() ).html(formatNumber(tgl_8));
      $( api.column( 13 ).footer() ).html(formatNumber(tgl_9));
      $( api.column( 14 ).footer() ).html(formatNumber(tgl_10));
      $( api.column( 15 ).footer() ).html(formatNumber(tgl_11));
      $( api.column( 16 ).footer() ).html(formatNumber(tgl_12));
      $( api.column( 17 ).footer() ).html(formatNumber(tgl_13));
      $( api.column( 18 ).footer() ).html(formatNumber(tgl_14));
      $( api.column( 19 ).footer() ).html(formatNumber(tgl_15));
      $( api.column( 20 ).footer() ).html(formatNumber(tgl_16));
      $( api.column( 21 ).footer() ).html(formatNumber(tgl_17));
      $( api.column( 22 ).footer() ).html(formatNumber(tgl_18));
      $( api.column( 23 ).footer() ).html(formatNumber(tgl_19));
      $( api.column( 24 ).footer() ).html(formatNumber(tgl_20));
      $( api.column( 25 ).footer() ).html(formatNumber(tgl_21));
      $( api.column( 26 ).footer() ).html(formatNumber(tgl_22));
      $( api.column( 27 ).footer() ).html(formatNumber(tgl_23));
      $( api.column( 28 ).footer() ).html(formatNumber(tgl_24));
      $( api.column( 29 ).footer() ).html(formatNumber(tgl_25));
      $( api.column( 30 ).footer() ).html(formatNumber(tgl_26));
      $( api.column( 31 ).footer() ).html(formatNumber(tgl_27));
      $( api.column( 32 ).footer() ).html(formatNumber(tgl_28));
      $( api.column( 33 ).footer() ).html(formatNumber(tgl_29));
      $( api.column( 34 ).footer() ).html(formatNumber(tgl_30));
      $( api.column( 35 ).footer() ).html(formatNumber(tgl_31));
      $( api.column( 36 ).footer() ).html(formatNumber(total));
        },
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

  $('#btnView').on( 'click', function () {
      year  = $('#year').val();
      month  = $('#month').val();
    table.draw();
  });

    $("#btnCetak").on("click", function(){
      year   = $("#year").val();
      month  = $("#month").val();
      url   ="<?php echo site_url(); ?>report_xls/Expense/cetak_expense";
      window.open(url+'?year='+year+'&month='+month, '_blank');
      window.focus();
    });

  });
</script>