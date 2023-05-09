<div class="row">
	<form class="form-horizontal">
		<div class="form-group m-b-10">
            <div class="col-xs-4 col-sm-2">
            	<select class="form-control m-b-10" id="year">
        		<?php foreach($get_exist_year as $value): ?>
        			<option value="<?= $value['TAHUN'] ?>"<?= ($value['TAHUN'] == date('Y')) ? ' selected' : '' ?>><?= $value['TAHUN'] ?></option>
        		<?php endforeach; ?>
            	</select>
            </div>
            <div class="col-xs-4 col-sm-2 w-auto">
            	<button id="btnView" class="btn btn-info btn-outline border-radius-5 w-100p m-b-10" type="button"><i class="fa fa-search"></i> View</button>
            </div>
            <div id="btnPrint" class="col-xs-4 col-sm-2">
            	<button class="btn btn-info border-radius-5 w-150p m-b-10" type="button"><i class="fa fa-download"></i> Download</button>
            </div>
        </div>
	</form>
</div>

<?php $this->load->view('ilustration') ?>

<div class="row">
	<div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 z-index-2 panel-title-custom">
		<div class="col-md-6">
			<h5 class="font-weight-700 m-0 text-uppercase">Informasi Budget</h5>
		</div>
	</div>
	<div class="white-box boxshadow z-index-2 py-10 border-bottom-only-5 small">

  <div class="row">
    <div class="col-lg-2">
    <div class="form-group">
      <label>Program ID</label>
      <select class="form-control input-sm" id="entry" name="entry">
        <option value="" data-name="" ></option>
      </select>
    </div>
   </div>
    <div class="col-lg-2">
    <div class="form-group">
      <label>Nominal RKAP</label>
      <input type="text" class="form-control input-sm" id="nominalEntry" name="nominalEntry" readonly>
    </div>
   </div>
   <div class="col-lg-2">
    <div class="form-group">
      <label>Justification</label>
      <input type="text" class="form-control input-sm" id="fsEntry" name="fsEntry" readonly>
    </div>
   </div>
   <div class="col-lg-2">
    <div class="form-group">
      <label>Fund Avl RKAP</label>
      <input type="text" class="form-control input-sm" id="faRkapEntry" name="faRkapEntry" readonly>
    </div>
   </div>
   <div class="col-lg-2">
    <div class="form-group">
      <label>Fund Avl Justif</label>
      <input class="form-control input-sm" id="avdEntry" name="avdEntry" type="text" readonly>          
    </div>
   </div>
   <?php if(in_array("Budget Controller", $group_name)): ?>

   <div class="col-lg-2">
    <div class="form-group" >
      <label>FA BUFFER (PR - PO)</label>
      <input class="form-control input-sm" id="avdEntrybck" name="avdEntrybck" type="text" readonly>          
    </div>
   </div>
   <?php endif ?>
  </div>

  <div class="row">
    <div class="col-lg-2">
      <div class="form-group">
        <label>Directorate</label>
        <select class="form-control input-sm" id="directorate" name="directorate">
			<?php
				$id_directorat   = $this->session->userdata('id_dir_code');
				$directorat_name = get_directorat($id_directorat);

				if(!$su_budget && $id_directorat > 0 && $binding == true):
					echo '<option value="'.$id_directorat.'" data-direktorat="'.$directorat_name.'" data-name="'.$directorat_name.'">'.$directorat_name.'</option>';
				else:

          $opt_dir  = (count($directorat) > 1) ? '<option value="0">-- Choose --</option>' : '';
          $last_dir = "";
					foreach($directorat as $value):

            $id_dir_code = $value['ID_DIR_CODE'];
            $dir_name    = $value['DIRECTORAT_NAME'];

						if($dir_name != $last_dir):
              $opt_dir .= '<option value="'.$id_dir_code.'" data-direktorat="'.$dir_name.'" data-name="'.$dir_name.'">'.$dir_name.'</option>';
						endif;
						$last_dir = $dir_name;

					endforeach;

					echo $opt_dir;

				endif;

			?>
        </select>
      </div>
     </div>
    <div class="col-lg-2">
    <div id="derror7" class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control input-sm" id="nominalDirektorat" name="nominalDirektorat" readonly>
    </div>
   </div>
   <div class="col-lg-2">
   <div id="derror7" class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control input-sm" id="fsDirektorat" name="fsDirektorat" readonly>
    </div>
   </div>
   <div class="col-lg-2">
   <div id="derror7" class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control input-sm" id="faRkapDirektorat" name="faRkapDirektorat" readonly>
    </div>
   </div>
   <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input class="form-control input-sm" id="avdDirektorat" name="avdDirektorat" type="text" readonly>          
    </div>
   </div>
   <?php if(in_array("Budget Controller", $group_name)): ?>
   <div class="col-lg-2" >
    <div class="form-group">
      <label>&nbsp;</label>
      <input class="form-control input-sm" id="avdDirektoratbck" name="avdDirektoratbck" type="text" readonly>
    </div>
   </div>
   <?php endif ?>
  </div>
  <div class="row">
    <div class="col-lg-2">
    <div class="form-group">
      <label>Division</label>
      <select class="form-control input-sm" id="division" name="division">
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
      <input type="text" class="form-control input-sm" id="nominalDivision" name="nominalDivision" readonly>
    </div>
   </div>
   <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control input-sm" id="fsDivision" name="fsDivision" readonly>
    </div>
   </div>
   <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control input-sm" id="faRkapDivision" name="faRkapDivision" readonly>
    </div>
   </div>
   <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input class="form-control input-sm" id="avdDivision" name="avdDivision" type="text" readonly>          
    </div>
   </div>
   <?php if(in_array("Budget Controller", $group_name)): ?>
   <div class="col-lg-2" >
    <div class="form-group">
      <label>&nbsp;</label>
      <input class="form-control input-sm" id="avdDivisionbck" name="avdDivisionbck" type="text" readonly>          
    </div>
   </div>
   <?php endif ?>
  </div>
  <div class="row">
    <div class="col-lg-2">
    <div class="form-group">
      <label>Unit</label>
      <select class="form-control input-sm" id="unit" name="unit">
          <?php
            if($binding == false || $data_binding['unit'] == false):
              echo '<option value="0">-- Choose --</option>';
            else:

              $unit = $data_binding['unit'];
              $opt_unit = (count($unit) > 1) ? '<option value="0">-- Choose --</option>' : '';
            
              foreach($unit as $value):
                  $replace = str_replace("&","|AND|", $value['UNIT_NAME']);
                  $opt_unit .= '<option value="'.$value['ID_UNIT'].'" data-unt="'.$replace.'" data-name="'.$value['UNIT_NAME'].'">'.$value['UNIT_NAME'].'</option>';
              endforeach;

              echo $opt_unit;
            endif;
          ?>
      </select>
    </div>
   </div>
    <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control input-sm" id="nominalUnit" name="nominalUnit" readonly>
    </div>
   </div>
   <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control input-sm" id="fsUnit" name="fsUnit" readonly>
    </div>
   </div>
   <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input type="text" class="form-control input-sm" id="faRkapUnit" name="faRkapUnit" readonly>
    </div>
   </div>
   <div class="col-lg-2">
    <div class="form-group">
      <label>&nbsp;</label>
      <input class="form-control input-sm" id="avdUnit" name="avdUnit" type="text" readonly>          
    </div>
   </div>
   <?php if(in_array("Budget Controller", $group_name)): ?>
   <div class="col-lg-2">
    <div class="form-group" >
      <label>&nbsp;</label>
      <input class="form-control input-sm" id="avdUnitbck" name="avdUnitbck" type="text" readonly>          
    </div>
   </div>
   <?php endif ?>
  </div>
 </div>
</div>


<div class="row" id="tblData">
	<div class="white-box boxshadow m-b-0 mt-10 border-top-only-5 small">
		<div class="row">

	      <div id="tbl_search" class="col-md-12 positon-relative">
	          <i class="fa fa-search positon-absolute p-5 mt-2 ml-5 pos"></i>
	          <input class="input-field px-30 form-control bg-lightgrey input-sm  border-radius-5 mb-10" type="search" placeholder="Search here...">
	      </div>
			<div class="col-md-12">
	    		<table id="table_data" class="table table-hover small display table-striped table-responsive dataTable w-full">

<!-- <div class="row" id="tblData"> -->
  <!-- <div class="col-md-12"> -->
      <!-- <div class="white-box"> -->
        <!-- <h3 class="box-title m-b-0">Data Table</h3> -->
        <!-- <div class="table-responsive"> -->
          <!-- <table id="table_data" class="table table-striped table-hover table-bordered display cell-border stripe hover"> -->
            <thead>
              <tr>
                <th>Direktorat</th>
                <th>PIC</th>
                <th>No</th>
                <th>Division</th>
                <th>Unit</th>
                <th>Tribe/Usecase</th>
                <th>Capex/Opex</th>
                <th>B2B Arragement with Tsel (Yes/No)</th>
                <th>Sales chanel</th>
                <th>Parent Account</th>
                <th>Sub Parent</th>
                <th>Proc Type</th>
                <th>Month</th>
                <th>Periode</th>
                <th>RKAP Name (Description)</th>
                <th>Nominal</th>
                <th>Target Group</th>
                <th>Target Quantity</th>
                <th>Program Type</th>
                <th>Detail Activity</th>
                <th>Vendor (if possible)</th>
                <th>Entry Optimize Monitize</th>
                <th>ABS FPJP</th>
                <th>ABS PR</th>
                <th>ABS PO</th>
                <th>ABS INV</th>
                <th>ABSPAY</th>
                <th>RELOC OUT</th>
                <th>RELOC IN</th>
                <th>Feasibility Study</th>
                <th>Fund Avl RKAP</th>
                <th>Fund Avl Justif</th>
                <?php if(in_array("Budget Controller", $group_name)): ?>
                <th>FUND_BUFFER</th>
                <?php endif ?>
              </tr>
            </thead>
          </table>
      </div>
      </div>
    </div>
</div>

<script>
  
  $(document).ready(function(){

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

  let year = $("#year").val();
  let direktorat = $("#directorate").find(':selected').attr('data-direktorat');
  let divisi = $("#division").attr('data-div');
  let unit = $("#unit").attr('data-unt');
  let entry = $("#entry").val();
  
  let url  = baseURL + 'capex/load_data_header';
    /*let ajaxData = {
                    "url"  : url,
                    "type" : "POST",
                    "dataType": "json",
                    "data"    : function ( d ) {
                      console.log(divisi);
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
                    { "data": "no", "width": "50px" },
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
                    { "data": "rkap_name", "width": "100px" },
                    { "data": "nominal", "width": "100px" },
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
  table = $('#table_data').DataTable();*/

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
                                d.direktorat  = direktorat;
                                d.divisi  = divisi;
                                d.unit  = unit;
                                d.entry  = (entry) ? entry : 0 ;
                                  }
                          },
        "language"        : {
                              "emptyTable"  : "<span class='label label-danger'>Data Not Found!</span>",
                              "infoEmpty"   : "Data Kosong",
                              "processing"  : '<img src="' + imageLoadingSmall + '" alt="" />',
                              "search"      : "_INPUT_",
                              "paginate": {
                                          'previous': '<i class="fa fa-angle-left"></i>',
                                          'next': '<i class="fa fa-angle-right"></i>',
                                        },
                              "lengthMenu": " &nbsp; _MENU_ &nbsp; rows per page"
                            },
        "columns"         : [
                            { "data": "direktorat", "width": "100px" },
		                    { "data": "pic", "width": "150px" },
		                    { "data": "no", "width": "50px" },
		                    { "data": "division", "width": "150px" },
		                    { "data": "unit", "width": "150px" },
		                    { "data": "tribe_usecase", "width": "100px" },
		                    { "data": "capex_opex", "width": "100px" },
		                    { "data": "b2b_arragement", "width": "250px" },
		                    { "data": "sales_chanel", "width": "100px" },
		                    { "data": "parent_account", "width": "100px" },
		                    { "data": "sub_parent", "width": "100px" },
		                    { "data": "proc_type", "width": "100px" },
		                    { "data": "month", "width": "100px" },
		                    { "data": "periode", "width": "100px" },
		                    { "data": "rkap_name", "width": "100px" },
		                    { "data": "nominal", "width": "100px" },
		                    { "data": "target_group", "width": "200px" },
		                    { "data": "target_quantity", "width": "100px" },
		                    { "data": "program_type", "width": "150px" },
		                    { "data": "detail_activity", "width": "200px" },
		                    { "data": "vendor", "width": "200px" },
		                    { "data": "entry_optimize", "width": "200px" },
		                    { "data": "abs_fpjp", "width": "200px"},
		                    { "data": "abs_pr", "width": "200px"},
		                    { "data": "abs_po", "width": "200px"},
		                    { "data": "abs_inv", "width": "200px"},
		                    { "data": "abs_pay", "width": "200px"},
		                    { "data": "reloc_out", "width": "200px"},
		                    { "data": "reloc_in", "width": "200px"},
		                    { "data": "fs", "width": "200px"},
		                    { "data": "fa_rkap", "width": "200px"},
		                    { "data": "fa_fs", "width": "200px"},
		                    <?php if(in_array("Budget Controller", $group_name)): ?>
		                    { "data": "fund_buffer", "width": "200px"}
		                    <?php endif ?>
                ],
          "fnDrawCallback": function () {
            $('#table_data_length').prepend($('#table_data_info'));
          },
           "dom": '<"top"i>rt<"bottom"flp><"clear">',
        "ordering"        : false,
        "scrollY"         : 520,
        "scrollX"         : true,
        "scrollCollapse"  : true,
        "autoWidth"       : true,
        "bAutoWidth"      : true,
      });
  });

  let table = $('#table_data').DataTable();
  // $('input[type="search"]').attr('placeholder','Search here...').addClass('form-control input-sm m-l-0');
  $("#table_data_filter").remove();

  $("#tbl_search").on('keyup', "input[type='search']", function(){
      table.search( $(this).val() ).draw();
  });

  $('#table_data tbody').on( 'click', 'tr', function () {
    if (! $(this).hasClass('selected') ) {
      table.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });
 
    $('#btnView').on( 'click', function () {
      console.log('clicked');
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

    url         ="<?php echo site_url(); ?>capex/cetak_data_header";
    vyear       = $("#year").val();
    vdirektorat = $("#directorate").find(":selected").attr('data-direktorat');
    vdivisi     = $("#division").find(":selected").attr('data-div');
    vunit       = $("#unit").find(":selected").attr('data-unt');
    ventry      = $("#entry").find(":selected").attr('data-entry');

    window.open(url+'?year='+vyear+'&direktorat='+vdirektorat+'&divisi='+vdivisi+'&unit='+vunit+'&entry='+ventry, '_blank');
    window.focus();
  });

  function getDivision()
  {
    let id_dir = $("#directorate").val();

    $("#division").attr('disabled', true);
	$("#division").css('cursor', 'wait');

    $.ajax({
        url   : "<?php echo site_url('capex/load_division') ?>",
        type  : "POST",
        data  : {id_dir :  id_dir},
        dataType: "html",
        success : function(result){
			var vselect ='<option value="0" data-name="" selected >-- Choose --</option>';
			$("#division").html("");          
			$("#division").html(vselect+result);
			$("#division").attr('disabled', false);
			$("#division").css('cursor', 'default');
        }
    });
  }

/*function getDivision(){

      let directorat    = $("#directorate").find(':selected').attr('data-name');

      $.ajax({
          url   : baseURL + 'api-budget/load_data_rkap_view',
          type  : "POST",
          data  : {category : "division", directorat:directorat},
          dataType: "json",
          success : function(result){
            let division_opt = '<option value="0">--All Division--</option>';
            if(result.status == true){
              let data     = result.data;
              for(var i = 0; i < data.length; i++) {
                  obj = data[i];
                  division_opt += '<option value="'+ obj.id_division +'" data-name="'+ obj.division +'">'+ obj.division +'</option>';
              }
            }
            $("#division").html(division_opt);        
          }
      });
    }*/

  function numberWithCommas(x) {
      var parts = x.toString().split(".");
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return parts.join(".");
  }

  function getUnit()
  {
    let id_dir = $("#directorate").val();
    let id_div = $("#division").val();

    $("#unit").attr('disabled', true);
	$("#unit").css('cursor', 'wait');

    $.ajax({
        url   : "<?php echo site_url('capex/load_unit') ?>",
        type  : "POST",
        data  : {id_dir :  id_dir, id_div :  id_div},
        dataType: "html",
        success : function(result){
    			var vselect ='<option value="0" data-name="" selected >-- Choose --</option>';
    			$("#unit").html("");          
    			$("#unit").html(vselect+result);
    			$("#unit").attr('disabled', false);
    			$("#unit").css('cursor', 'default');
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
      url   : baseURL + 'capex/load_entry',
      type  : "POST",
      data  : {directorat : directorat},
      dataType: "html",
      success : function(result){
        var vselect ='<option value="0" data-name="" selected >-- Choose --</option>';
        $("#entry").html("");          
        $("#entry").html(vselect+result);          
      }
    });
  }


  $("#directorate").on("change", function(){
      nmlDirektorat = $(this).find(':selected').attr('data-nominal');
      direktorat = $("#directorate").find(':selected').attr('data-direktorat');

      year = $("#year").val();
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
      divisi = $("#division").find(':selected').attr('data-div');
      year = $("#year").val();
      direktorat = $("#directorate").find(':selected').attr('data-direktorat');
      divisi = $("#division").find(':selected').attr('data-div');
      entry = $("#entry").val();
      getUnit();
      getNominal("division");
      unit = $("#unit").find(':selected').attr('data-unt');
      console.log(unit);
      table.draw();
      $('#tblData').slideDown(700);
      $('#tblData').css( 'display', 'block' );
      table.columns.adjust().draw();
    });

  $("#unit").on("change", function(){
      unit = $("#unit").find(':selected').attr('data-unt');

      year = $("#year").val();
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
      year = $("#year").val();
      entry = $("#entry").val();
      getNominal("entry");
      table.draw();
      $('#tblData').slideDown(700);
      $('#tblData').css( 'display', 'block' );
      table.columns.adjust().draw();
    });


  function getNominal(category=""){

    let year     = $("#year").val();
    let id_dir   = $("#directorate").val();
    let id_div   = $("#division").val();
    let id_unit  = $("#unit").val();
    let id_entry = $("#entry").val();
    
    let nominal_url = baseURL + 'capex/load_nominal';

    if(is_bod == true){
      if(category == "entry" || category == "directorate"){
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