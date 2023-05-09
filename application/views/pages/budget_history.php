<div class="row">
  <div class="white-box boxshadow">     
    <div class="row">
      <div class="col-md-2">
        <div class="form-group">
          <label>Period Date</label>
          <div class="input-group">
            <input type="text" class="form-control mydatepicker" id="year" data-toggle="validator" data-error="Date Cannot less than Today" placeholder="dd-mm-yyyy" value="<?= dateFormat(time(), 5)?>">
            <span class="input-group-addon"><i class="icon-calender"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>&nbsp;</label>
          <button id="btnView" class="btn btn-default btn-rounded custom-input-width btn-block" type="button"><i class="fa fa-search"></i> <span>VIEW</span></button>
        </div>
      </div>
      <label>&nbsp;</label>
      <div class="form-group">
        <div class="col-md-2 col-md-2 col-sm-12">
          <button id="btnPrint" class="btn btn-success btn-rounded btn-block" type="button" ><i class="fa  fa-file-excel-o"></i> <span>Download</span></button>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-2">
        <div class="form-group">
          <label>Program ID</label>
          <select class="form-control" id="entry" name="entry">
            <option value="" data-name="" ></option>
          </select>
        </div>
      </div>
      <div class="col-lg-2">
        <div class="form-group">
          <label>Nominal RKAP</label>
          <input type="text" class="form-control" id="nominalEntry" name="nominalEntry" readonly>
        </div>
      </div>
      <div class="col-lg-2">
        <div class="form-group">
          <label>Feasibility Study</label>
          <input type="text" class="form-control" id="fsEntry" name="fsEntry" readonly>
        </div>
      </div>
      <div class="col-lg-2">
        <div class="form-group">
          <label>Fund Available RKAP</label>
          <input type="text" class="form-control" id="faRkapEntry" name="faRkapEntry" readonly>
        </div>
      </div>
      <div class="col-lg-2">
        <div class="form-group">
          <label>Fund Available FS</label>
          <input class="form-control" id="avdEntry" name="avdEntry" type="text" readonly>          
        </div>
      </div>
      <?php if(in_array("Budget Controller", $group_name)): ?>
       <div class="col-lg-2">
        <div class="form-group" >
          <label>FA BUFFER (PR - PO)</label>
          <input class="form-control" id="avdEntrybck" name="avdEntrybck" type="text" readonly>          
        </div>
      </div>
    <?php endif ?>
  </div>

  <div class="row">
    <div class="col-lg-2">
      <div class="form-group">
        <label>Directorate</label>
        <select class="form-control" id="directorate" name="directorate">
          <?php
            if(count($directorat) > 1):
              echo '<option value="0">--All Directorate--</option>';
            endif;
            foreach($directorat as $value): ?>
            <option value="<?= $value['ID_DIR_CODE'] ?>" data-direktorat="<?= $value['DIRECTORAT_NAME'] ?>" data-name="<?= $value['DIRECTORAT_NAME'] ?>"><?= $value['DIRECTORAT_NAME'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
     </div>
    <div class="col-lg-2">
      <div id="derror7" class="form-group">
        <label>&nbsp;</label>
        <input type="text" class="form-control" id="nominalDirektorat" name="nominalDirektorat" readonly>
      </div>
    </div>
    <div class="col-lg-2">
     <div id="derror7" class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control" id="fsDirektorat" name="fsDirektorat" readonly>
    </div>
  </div>
  <div class="col-lg-2">
   <div id="derror7" class="form-group">
    <label>&nbsp;</label>
    <input type="text" class="form-control" id="faRkapDirektorat" name="faRkapDirektorat" readonly>
  </div>
</div>
<div class="col-lg-2">
  <div class="form-group">
    <label>&nbsp;</label>
    <input class="form-control" id="avdDirektorat" name="avdDirektorat" type="text" readonly>
  </div>
</div>
<?php if(in_array("Budget Controller", $group_name)): ?>
 <div class="col-lg-2" >
  <div class="form-group">
    <label>&nbsp;</label>
    <input class="form-control" id="avdDirektoratbck" name="avdDirektoratbck" type="text" readonly>
  </div>
</div>
<?php endif ?>
</div>
<div class="row">
  <div class="col-lg-2">
    <div class="form-group">
      <label>Division</label>
      <select class="form-control" id="division" name="division">
          <?php
            if($binding == false || $data_binding['division'] == false):
              echo '<option value="0">-- Choose --</option>';
            else:
            
              foreach($data_binding['division'] as $value):
                $replace = str_replace("&","|AND|", $value['DIVISION_NAME']);
          ?>
              <option value="<?= $value['ID_DIVISION'] ?>" data-div="<?= $replace ?>" data-name="<?= $value['DIVISION_NAME'] ?>"><?= $value['DIVISION_NAME'] ?></option>
          <?php
              endforeach; 
            endif;
          ?>
      </select>
    </div>
  </div>
  <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control" id="nominalDivision" name="nominalDivision" readonly>
    </div>
  </div>
  <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control" id="fsDivision" name="fsDivision" readonly>
    </div>
  </div>
  <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control" id="faRkapDivision" name="faRkapDivision" readonly>
    </div>
  </div>
  <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input class="form-control" id="avdDivision" name="avdDivision" type="text" readonly>          
    </div>
  </div>
  <?php if(in_array("Budget Controller", $group_name)): ?>
   <div class="col-lg-2" >
    <div class="form-group">
      <label>&nbsp;</label>
      <input class="form-control" id="avdDivisionbck" name="avdDivisionbck" type="text" readonly>          
    </div>
  </div>
<?php endif ?>
</div>
<div class="row">
  <div class="col-lg-2">
    <div class="form-group">
      <label>Unit</label>
      <select class="form-control" id="unit" name="unit">
          <?php
            if($binding == false || $data_binding['unit'] == false):
              echo '<option value="0">-- Choose --</option>';
            else:
            
              foreach($data_binding['unit'] as $value):
                $replace = str_replace("&","|AND|", $value['UNIT_NAME']);
          ?>
              <option value="<?= $value['ID_UNIT'] ?>" data-unt="<?= $replace ?>" data-name="<?= $value['UNIT_NAME'] ?>"><?= $value['UNIT_NAME'] ?></option>
          <?php
              endforeach; 
            endif;
          ?>
      </select>
    </div>
  </div>
  <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control" id="nominalUnit" name="nominalUnit" readonly>
    </div>
  </div>
  <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control" id="fsUnit" name="fsUnit" readonly>
    </div>
  </div>
  <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control" id="faRkapUnit" name="faRkapUnit" readonly>
    </div>
  </div>
  <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input class="form-control" id="avdUnit" name="avdUnit" type="text" readonly>          
    </div>
  </div>
  <?php if(in_array("Budget Controller", $group_name)): ?>
   <div class="col-lg-2">
    <div class="form-group" >
      <label>&nbsp;</label>
      <input class="form-control" id="avdUnitbck" name="avdUnitbck" type="text" readonly>          
    </div>
  </div>
<?php endif ?>
</div>
</div>
</div>

<div class="row" id="tblData">
  <div class="col-md-12">
    <div class="white-box">
      <!-- <h3 class="box-title m-b-0">Data Table</h3> -->
      <!-- <div class="table-responsive"> -->
        <table id="table_data" class="table table-responsive w-full table-striped table-hover table-bordered display cell-border stripe hover dataTable small">
          <thead>
            <tr>
              <th class="text-center">Direktorat</th>
              <th class="text-center">PIC</th>
              <th class="text-center">No</th>
              <th class="text-center">Division</th>
              <th class="text-center">Unit</th>
              <th class="text-center">Tribe/Usecase</th>
              <th class="text-center">Capex/Opex</th>
              <th class="text-center">B2B Arragement with Tsel (Yes/No)</th>
              <th class="text-center">Sales chanel</th>
              <th class="text-center">Parent Account</th>
              <th class="text-center">Sub Parent</th>
              <th class="text-center">Proc Type</th>
              <th class="text-center">Month</th>
              <th class="text-center">Periode</th>
              <th class="text-center">RKAP Name (Description)</th>
              <th class="text-center">Nominal</th>
              <th class="text-center">Target Group</th>
              <th class="text-center">Target Quantity</th>
              <th class="text-center">Program Type</th>
              <th class="text-center">Detail Activity</th>
              <th class="text-center">Vendor (if possible)</th>
              <th class="text-center">Entry Optimize Monitize</th>
              <th class="text-center">ABS FPJP</th>
              <th class="text-center">ABS PR</th>
              <th class="text-center">ABS PO</th>
              <th class="text-center">ABS INV</th>
              <th class="text-center">ABSPAY</th>
              <th class="text-center">RELOC OUT</th>
              <th class="text-center">RELOC IN</th>
              <th class="text-center">Feasibility Study</th>
              <th class="text-center">Fund Available RKAP</th>
              <th class="text-center">Fund Available FS</th>
              <?php if(in_array("Budget Controller", $group_name)): ?>
                <th class="text-center">FUND_BUFFER</th>
              <?php endif ?>
            </tr>
          </thead>
        </table>
        <!-- </div> -->
      </div>
    </div>
  </div>

  <script>

    $(document).ready(function(){

      /*const is_bod = <?= ($this->session->userdata('is_bod')) ? 'true' : 'false' ?>;

      getEntry();
      if(is_bod == true){
        getDivision();
        getNominal("directorat");
      }*/

       const is_bod = <?= ($this->session->userdata('is_bod')) ? 'true' : 'false' ?>;
      const binding = <?= ($binding) ? "'".$binding."'" : 'false' ?>;

      let clickView = false;

      getEntry();

      if(binding != false){
        getNominal("directorat");
        if(binding == 'directorat'){
          getDivision();
          setTimeout(function(){
            getNominal("division");
          }, 2000);
        }
        if(binding == 'division'){
          setTimeout(function(){
            getNominal("division");
          }, 2000);
          getUnit();
        }
        if(binding == 'unit'){
          $("#division").trigger("change");
          $("#unit").trigger("change");
          setTimeout(function(){
            getNominal("division");
            getNominal("unit");
          }, 2000);
        }

        clickView  = true;

      }else if(is_bod == true){
        getDivision();
        getNominal("directorat");
      }

      $('#tblData').hide();

      $('.mydatepicker').datepicker({
        format: 'dd-mm-yyyy',
        todayHighlight:'TRUE',
        autoclose: true,
      });

     /* let periode_history_bruto = document.getElementById("year").value;
      let periode_history = periode_history_bruto.split("/");
      let conv_periode_history = periode_history[2] + "-" + periode_history[1] + "-" + periode_history[0];

      let year = conv_periode_history;*/

      let year = $("#year").val();
      let direktorat = $("#directorate").find(':selected').attr('data-direktorat');
      let divisi = $("#division").attr('data-div');
      let unit = $("#unit").attr('data-unt');
      let entry = $("#entry").val();

      let url  = baseURL + 'budgethistory_ctl/load_data_history_header';
      let ajaxData = {
        "url"  : url,
        "type" : "POST",
        "dataType": "json",
        "data"    : function ( d ) {
          d.year = year;
          d.direktorat  = direktorat;
          d.divisi  = divisi;
          d.unit  = unit;
          d.entry  = (entry) ? entry : 0 ;
        }
      }
      let jsonData = [
      { "data": "direktorat", "width": "100px" },
      { "data": "pic", "width": "150px" },
      { "data": "no", "width": "50px", "class": "text-center" },
      { "data": "division", "width": "150px" },
      { "data": "unit", "width": "150px" },
      { "data": "tribe_usecase", "width": "100px" },
      { "data": "capex_opex", "width": "100px", "class": "text-center" },
      { "data": "b2b_arragement", "width": "250px" },
      { "data": "sales_chanel", "width": "100px" },
      { "data": "parent_account", "width": "100px", "class": "text-center" },
      { "data": "sub_parent", "width": "100px" },
      { "data": "proc_type", "width": "100px", "class": "text-center" },
      { "data": "month", "width": "100px", "class": "text-center" },
      { "data": "periode", "width": "100px", "class": "text-center" },
      { "data": "rkap_name", "width": "200px" },
      { "data": "nominal", "class":"text-right", "width": "100px" },
      { "data": "target_group", "width": "200px" },
      { "data": "target_quantity", "width": "100px", "class": "text-center" },
      { "data": "program_type", "width": "150px", "class": "text-center" },
      { "data": "detail_activity", "width": "200px" },
      { "data": "vendor", "width": "200px" },
      { "data": "entry_optimize", "width": "200px" },
      { "data": "abs_fpjp", "width": "200px", "class": "text-right" },
      { "data": "abs_pr", "width": "200px", "class": "text-right" },
      { "data": "abs_po", "width": "200px", "class": "text-right" },
      { "data": "abs_inv", "width": "200px", "class": "text-right" },
      { "data": "abs_pay", "width": "200px", "class": "text-right" },
      { "data": "reloc_out", "width": "200px", "class": "text-right" },
      { "data": "reloc_in", "width": "200px", "class": "text-right" },
      { "data": "fs", "width": "200px", "class": "text-right" },
      { "data": "fa_rkap", "width": "200px", "class": "text-right" },
      { "data": "fa_fs", "width": "200px", "class": "text-right" },
      <?php if(in_array("Budget Controller", $group_name)): ?>
        { "data": "fund_buffer", "width": "200px", "class": "text-right" }
      <?php endif ?>
      ];
      data_table(ajaxData,jsonData);
      table = $('#table_data').DataTable();

      $('#btnView').on( 'click', function () {

        year = $("#year").val();

        direktorat = $("#directorate").find(':selected').attr('data-direktorat');
        divisi = $("#division").find(':selected').attr('data-div');
        unit = $("#unit").find(':selected').attr('data-unt');
        entry = $("#entry").val();
        table.draw();
        $('#tblData').slideDown(700);
        $('#tblData').css( 'display', 'block' );
        table.columns.adjust().draw();
      });

      $("#btnPrint").on("click", function(){
        let vyear       = "";
        let vdirektorat = "";
        let vdivisi     = "";
        let vunit       = "";
        let ventry      = "";

        /*let periode_history_bruto = document.getElementById("year").value;
        let periode_history = periode_history_bruto.split("/");
        let conv_periode_history = periode_history[2] + "-" + periode_history[1] + "-" + periode_history[0];*/

        url         = baseURL + "budgethistory_ctl/cetak_data_history_header";
        //vyear       = conv_periode_history;
        vyear       = $("#year").val();
        vdirektorat = $("#directorate").find(":selected").attr('data-direktorat');
        vdivisi     = $("#division").find(":selected").attr('data-div');
        vunit       = $("#unit").find(":selected").attr('data-unt');
        ventry      = $("#entry").find(":selected").attr('data-entry');

        window.open(url+'?year='+vyear+'&direktorat='+vdirektorat+'&divisi='+vdivisi+'&unit='+vunit+'&entry='+ventry, '_blank');
        window.focus();
      });

      function getDirectorate()
      {
        let periode_history_bruto = document.getElementById("year").value;
        let periode_history = periode_history_bruto.split("/");
        let conv_periode_history = periode_history[2] + "-" + periode_history[1] + "-" + periode_history[0];

        let year = conv_periode_history;

        $.ajax({
          url   : "<?php echo site_url('budgethistory_ctl/load_directorate') ?>",
          type  : "POST",
          data  : {year:year},
          dataType: "html",
          success : function(result){
            var vselect ='<option value="0" data-name="" selected >-- Choose -- </option>';
            $("#directorate").html("");          
            $("#directorate").html(vselect+result);        
          }
        });     
      }

      function getDivision()
      {
        let id_dir = $("#directorate").val();

        $.ajax({
          url   : "<?php echo site_url('budgethistory_ctl/load_division') ?>",
          type  : "POST",
          data  : {id_dir :  id_dir},
          dataType: "html",
          success : function(result){
            var vselect ='<option value="0" data-name="" selected >-- Choose -- </option>';
            $("#division").html("");          
            $("#division").html(vselect+result);          
          }
        });
      }


      function numberWithCommas(x) {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
      }

      function getUnit()
      {
        let id_dir = $("#directorate").val();
        let id_div = $("#division").val();

        $.ajax({
          url   : "<?php echo site_url('budgethistory_ctl/load_unit') ?>",
          type  : "POST",
          data  : {id_dir :  id_dir, id_div :  id_div},
          dataType: "html",
          success : function(result){
            var vselect ='<option value="0" data-name="" selected >-- Choose -- </option>';
            $("#unit").html("");          
            $("#unit").html(vselect+result);          
          }
        });     
      }

      function getEntry()
      {

        let directorat = 0;
        if(is_bod == true){
          directorat = $("#directorate").find(':selected').attr('data-direktorat');
        }

        console.log(directorat);

        $.ajax({
          url   : baseURL + 'budgethistory_ctl/load_entry',
          type  : "POST",
          data  : {directorat : directorat},
          dataType: "html",
          success : function(result){
            var vselect ='<option value="0" data-name="" selected >-- Choose -- </option>';
            $("#entry").html("");          
            $("#entry").html(vselect+result);          
          }
        });
      }


      $("#directorate").on("change", function(){
        nmlDirektorat = $(this).find(':selected').attr('data-nominal');
        direktorat = $("#directorate").find(':selected').attr('data-direktorat');

        let periode_history_bruto = document.getElementById("year").value;
        let periode_history = periode_history_bruto.split("/");
        let conv_periode_history = periode_history[2] + "-" + periode_history[1] + "-" + periode_history[0];

        let year = conv_periode_history;

        direktorat = $("#directorate").find(':selected').attr('data-direktorat');
        divisi = $("#division").find(':selected').attr('data-div');
        unit = $("#unit").find(':selected').attr('data-unt');
        entry = $("#entry").val();
        getDivision();
        getNominal("directorat");
        table.draw();
        $('#tblData').slideDown(700);
        $('#tblData').css( 'display', 'block' );
        table.columns.adjust().draw();

      });

      $("#division").on("change", function(){
        nmlDivisi = $(this).find(':selected').attr('data-divisi');
        divisi = $("#division").find(':selected').attr('data-div');

        let periode_history_bruto = document.getElementById("year").value;
        let periode_history = periode_history_bruto.split("/");
        let conv_periode_history = periode_history[2] + "-" + periode_history[1] + "-" + periode_history[0];

        let year = conv_periode_history;

        direktorat = $("#directorate").find(':selected').attr('data-direktorat');
        divisi = $("#division").find(':selected').attr('data-div');
        unit = $("#unit").find(':selected').attr('data-unt');
        entry = $("#entry").val();
        getUnit();
        getNominal("division");
        table.draw();
        $('#tblData').slideDown(700);
        $('#tblData').css( 'display', 'block' );
        table.columns.adjust().draw();
      });

      $("#unit").on("change", function(){
        nmlUnit = $(this).find(':selected').attr('data-unit');
        unit = $("#unit").find(':selected').attr('data-unt');

        let periode_history_bruto = document.getElementById("year").value;
        let periode_history = periode_history_bruto.split("/");
        let conv_periode_history = periode_history[2] + "-" + periode_history[1] + "-" + periode_history[0];

        let year = conv_periode_history;


        direktorat = $("#directorate").find(':selected').attr('data-direktorat');
        divisi = $("#division").find(':selected').attr('data-div');
        unit = $("#unit").find(':selected').attr('data-unt');
        entry = $("#entry").val();
        getNominal("unit");
        table.draw();
        $('#tblData').slideDown(700);
        $('#tblData').css( 'display', 'block' );
        table.columns.adjust().draw();
      });

      $("#entry").on("change", function(){
        nmlEntry = $(this).find(':selected').attr('data-entry');

        let periode_history_bruto = document.getElementById("year").value;
        let periode_history = periode_history_bruto.split("/");
        let conv_periode_history = periode_history[2] + "-" + periode_history[1] + "-" + periode_history[0];

        let year = conv_periode_history;

        direktorat = $("#directorate").find(':selected').attr('data-direktorat');
        divisi = $("#division").find(':selected').attr('data-div');
        unit = $("#unit").find(':selected').attr('data-unt');
        entry = $("#entry").val();
        getNominal("entry");
        // getDirectorate();
        // getDivision();
        // getUnit();
        table.draw();
        $('#tblData').slideDown(700);
        $('#tblData').css( 'display', 'block' );
        table.columns.adjust().draw();
      });


      function getNominal(category=""){

        let periode_history_bruto = document.getElementById("year").value;
        let periode_history = periode_history_bruto.split("-");
        let conv_periode_history = periode_history[2] + "-" + periode_history[1] + "-" + periode_history[0];

        let year = conv_periode_history;
        
        let id_dir   = $("#directorate").val();
        let id_div   = $("#division").val();
        let id_unit  = $("#unit").val();
        let id_entry = $("#entry").val();

        let nominal_url = baseURL + 'budgethistory_ctl/load_nominal';

        if(is_bod == true){
          if(category == "entry" || category == "directorat"){
            nominal_url = baseURL + 'capex/load_nominal_bod';
          }
        }

        $.ajax({
          url   : nominal_url,
          type  : "POST",
          data  : {category: category, year: year, id_dir: id_dir, id_div: id_div, id_unit:id_unit, id_entry:id_entry},
          dataType: "json",
          success : function(result){

            if(result.NOMINAL){

              val_nominal   = numberWithCommas(result.NOMINAL);
              val_fa        = numberWithCommas(result.FS);
              val_fs_rkap   = numberWithCommas(result.FA_RKAP);
              val_fa_fs     = numberWithCommas(result.FA_FS);
              val_fa_buffer = numberWithCommas(result.FA_BUFFER);

            }else{

              val_nominal   = 0;
              val_fa        = 0;
              val_fs_rkap   = 0;
              val_fa_fs     = 0;
              val_fa_buffer = 0;

            }

            if (category == "entry"){
              $("#nominalEntry").val(val_nominal);
              $("#fsEntry").val(val_fa);
              $("#faRkapEntry").val(val_fs_rkap);
              $("#avdEntry").val(val_fa_fs);
              $("#avdEntrybck").val(val_fa_buffer);
              if(is_bod == false){
                $("#directorate").val(0);
                $("#nominalDirektorat").val(0);
                $("#fsDirektorat").val(0);
                $("#faRkapDirektorat").val(0);
                $("#avdDirektorat").val(0);
                $("#avdDirektoratbck").val(0);
              }
              $("#division").val(0);
              $("#unit").val(0);
              $("#fsDivision").val(0);
              $("#faRkapDivision").val(0);
              $("#fsUnit").val(0);
              $("#faRkapUnit").val(0);
              $("#nominalDivision").val(0);
              $("#avdDivision").val(0);
              $("#nominalUnit").val(0);
              $("#avdUnit").val(0);
              $("#avdDivisionbck").val(0);
              $("#avdUnitbck").val(0);
            }
            else if(category == "directorat"){
              $("#nominalDirektorat").val(val_nominal);
              $("#fsDirektorat").val(val_fa);
              $("#faRkapDirektorat").val(val_fs_rkap);
              $("#avdDirektorat").val(val_fa_fs);
              $("#avdDirektoratbck").val(val_fa_buffer);
              $("#nominalDivision").val(0);
              $("#avdDivision").val(0);
              $("#nominalUnit").val(0);
              $("#avdUnit").val(0);
              $("#avdDivisionbck").val(0);
              $("#avdUnitbck").val(0);
              $("#fsDivision").val(0);
              $("#fsUnit").val(0);
              $("#faRkapDivision").val(0);
              $("#faRkapUnit").val(0);
            }
            else if(category == "division"){
              $("#nominalDivision").val(val_nominal);
              $("#fsDivision").val(val_fa);
              $("#faRkapDivision").val(val_fs_rkap);
              $("#avdDivision").val(val_fa_fs);
              $("#avdDivisionbck").val(val_fa_buffer);
              $("#nominalUnit").val(0);
              $("#avdUnit").val(0);
              $("#avdUnitbck").val(0);
              $("#fsUnit").val(0);
              $("#faRkapUnit").val(0);
            }
            else if(category == "unit"){
              $("#nominalUnit").val(val_nominal);
              $("#fsUnit").val(val_fa);
              $("#faRkapUnit").val(val_fs_rkap);
              $("#avdUnit").val(val_fa_fs);
              $("#avdUnitbck").val(val_fa_buffer);
            }
            else{
              $("#nominalDirektorat").val(0);
              $("#avdDirektorat").val(0);
              $("#nominalDivision").val(0);
              $("#avdDivision").val(0);
              $("#nominalUnit").val(0);
              $("#avdUnit").val(0);
            }
          }
        });

      }



    if(clickView){
      setTimeout(function(){
        $("#btnView").trigger("click");
      }, 1000);
    }

    });
  </script>