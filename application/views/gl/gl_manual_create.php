<div class="row">
  <div class="white-box boxshadow">
	<form class="form-horizontal">
    <div class="row">
		<div class="col-sm-6">
			<div class="form-group m-b-10">
	            <label for="batch_name" class="col-sm-3 control-label text-left">Batch Name <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
	              <input type="text" class="form-control" id="batch_name" placeholder="Batch Name" value="<?= $batch_name ?>" autocomplete="off">
	            </div>
	        </div>
	    	<div class="form-group m-b-10">
	          	<label for="gl_date" class="col-sm-3 control-label text-left">GL Date <span class="pull-right">:</span></label>
	            <div class="col-sm-9 col-md-6">
					<div class="input-group">
						<input type="text" class="form-control mydatepicker" id="gl_date" placeholder="dd-mm-yyyy" value="<?= date("d-m-Y")?>">
						<span class="input-group-addon"><i class="icon-calender"></i></span>
					</div>
	            </div>
	        </div>
	    </div>
    </div>
    </form>
  </div>
</div>

<div class="row">
	<div class="white-box">
		<div class="row">
			<div class="col-md-12">
				<h4>GL Manual</h4>
			</div>
			<div class="col-md-12">
		      <table id="table_glmanual" class="table dataTable table-responsive table-striped table-bordered cell-border stripe w-full">
		        <thead>
		        	<tr>
			          	<th class="text-center">No</th>
			          	<!-- <th class="text-center">GL Date</th> -->
			          	<th class="text-center">Journal Name</th>
			          	<th class="text-center">Journal Description</th>
			          	<th class="text-center">Debit</th>
			          	<th class="text-center">Credit</th>
			          	<th class="text-center">Nature</th>
			          	<th class="text-center">Account Description</th>
			        </tr>
		        </thead>
		      </table>
		    </div>
		</div>
		<div class="row">
	    	<div class="col-md-12">
	    		<div class="pull-right"><button type="button" id="save_data" class="btn btn-info m-10 w-100p"><i class="fa fa-save"></i> Save </button></div>
	    	</div>
		</div>
  </div>
</div>

	<script>

		$(document).ready(function(){

			let counter      = 1;
			let batch_name   = $("#batch_name").val();;
			const date_today = '<?= date("d-m-Y") ?>';
			const opt_default = '<option value="0" data-name="">-- Choose --</option>';

			// let urut_journal = String(counter).padStart(2, '0')+'/'+batch_name;
			let urut_journal = '';

			let buttonAdd = '<button type="button" id="addRow" class="btn btn-success btn-sm m-l-5 mr-5"><i class="fa fa-plus"></i> Add New </button>';
				buttonAdd += '<button type="button" id="deleteRow" class="btn btn-danger btn-sm m-l-5 mr-5" disabled><i class="fa fa-trash"></i> Delete </button>';
			// let acc_date = '<div class="form-group m-b-0"><input id="acc_date-'+counter+'" type="text" class="form-control input-sm acc_date date-picker" placeholder="dd/mm/yyyy" value="'+date_today+'" autocomplete="off"></div>';
			let journal_name = '<div class="form-group m-b-0"><input id="journal_name-'+counter+'" data-id="' + counter + '" class="form-control input-sm journal_name" autocomplete="off" value="'+urut_journal+'"></div>';
			let journal_desc = '<div class="form-group m-b-0"><input id="journal_desc-'+counter+'" data-id="' + counter + '" class="form-control input-sm journal_desc" autocomplete="off"></div>';
			let debit = '<div class="form-group m-b-0"><input id="debit-'+counter+'" data-id="' + counter + '" class="form-control input-sm debit text-right money-format debit-credit" value="0"></div>';
			let credit = '<div class="form-group m-b-0"><input id="credit-'+counter+'" data-id="' + counter + '" class="form-control input-sm credit text-right money-format debit-credit" value="0"></div>';
			let nature     = '<div class="form-group m-b-0"><select id="nature-'+counter+'" data-id="' + counter + '" class="form-control input-sm nature select-center">';
					nature += opt_default;
					nature += '</select></div>';
			let acc_desc = '<div class="form-group m-b-0"><input id="acc_desc-'+counter+'" data-id="' + counter + '" class="form-control input-sm acc_desc" value="" readonly></div>';

			getNature(counter);

		    let table_glmanual = $('#table_glmanual').DataTable({

			    "data":[{
			    		 	"no": counter,
			    			// "acc_date": acc_date,
			    			"journal_name": journal_name,
			    			"journal_desc": journal_desc,
			    			"debit": debit,
			    			"credit": credit,
			    			"nature": nature,
			    			"acc_desc": acc_desc
			    		}],
			    "columns":[
			    			{"data": "no", "width": "40px", "class": "text-center p-10" },
			    			// {"data": "acc_date", "width": "100px", "class": "text-center" },
			    			{"data": "journal_name", "width": "150px", "class": "p-2" },
			    			{"data": "journal_desc", "width": "200px", "class": "p-2" },
			    			{"data": "debit", "width": "150px", "class": "p-2" },
			    			{"data": "credit", "width": "150px", "class": "p-2" },
			    			{"data": "nature", "width": "250px", "class": "p-2" },
			    			{"data": "acc_desc", "width": "300px", "class": "p-2" }
			    		],
				"ordering"        : false,
				"scrollY"         : 480,
				"scrollX"         : true,
				"scrollCollapse"  : true,
				"paging" 		  : false
			});

			$('#table_glmanual tbody tr').eq(counter-1).addClass('selected');
			$('#table_glmanual_filter').html(buttonAdd);
			$('#table_glmanual_info').html('');

			$('#table_glmanual tbody').on( 'click', 'tr', function () {
				if (! $(this).hasClass('selected') ) {
					table_glmanual.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					let get_table = table_glmanual.row( this );
					let index     = get_table.index()
					let index_Num = index+1;
					let data      = get_table.data();

					total_data = table_glmanual.data().count();

					if(total_data > 1){
						$("#deleteRow").attr('disabled', false);
					}
					
					let pr_line_key_val = data.pr_line_key;
				}
			});

			$('#addRow').on( 'click', function () {
				indexNow = table_glmanual.data().count();
				$("#deleteRow").attr('disabled', false);

				if($("#journal_name-"+indexNow).val() == "" || $("#nature-"+indexNow).val() == "0"){
					customNotif('Warning', 'Please fill out all line field!', 'warning');
				}
				else{

					counter++;

					// let urut_journal = String(counter).padStart(2, '0')+'/'+batch_name;

					// let acc_date = '<div class="form-group m-b-0"><input id="acc_date-'+counter+'" type="text" class="form-control input-sm acc_date date-picker" placeholder="dd/mm/yyyy" value="'+date_today+'" autocomplete="off"></div>';
					let journal_name = '<div class="form-group m-b-0"><input id="journal_name-'+counter+'" data-id="' + counter + '" class="form-control input-sm journal_name" autocomplete="off" value="'+urut_journal+'"></div>';
					let journal_desc = '<div class="form-group m-b-0"><input id="journal_desc-'+counter+'" data-id="' + counter + '" class="form-control input-sm journal_desc" autocomplete="off"></div>';
					let debit = '<div class="form-group m-b-0"><input id="debit-'+counter+'" data-id="' + counter + '" class="form-control input-sm debit text-right money-format debit-credit" value="0"></div>';
					let credit = '<div class="form-group m-b-0"><input id="credit-'+counter+'" data-id="' + counter + '" class="form-control input-sm credit text-right money-format debit-credit" value="0"></div>';
					let nature     = '<div class="form-group m-b-0"><select id="nature-'+counter+'" data-id="' + counter + '" class="form-control input-sm nature select-center">';
							nature += opt_default;
							nature += '</select></div>';
					let acc_desc = '<div class="form-group m-b-0"><input id="acc_desc-'+counter+'" data-id="' + counter + '" class="form-control input-sm acc_desc" value="" readonly></div>';

					getNature(counter);

					table_glmanual.$('tr.selected').removeClass('selected');

				    table_glmanual.rows.add(
				       [{ 
							"no": counter,
			    			// "acc_date": acc_date,
			    			"journal_name": journal_name,
			    			"journal_desc": journal_desc,
			    			"debit": debit,
			    			"credit": credit,
			    			"nature": nature,
			    			"acc_desc": acc_desc
			    		}]
				    ).draw();

				    $('#table_glmanual tbody tr').eq(counter-1).addClass('selected');
				}
			});


			$('#deleteRow').on( 'click', function () {

				row_now    = $('#table_glmanual tbody tr.selected')
				get_table  = table_glmanual.row( row_now );
				index      = get_table.index()

				total_data  = table_glmanual.data().count();

				if(total_data > 0){

					table_glmanual.row(index).remove().draw();

					table_glmanual.column( 0 ).data().each( function ( value, i ) {
						num = i+1;
						// urut_journal = String(num).padStart(2, '0')+'/'+batch_name;

						get_id = $('#table_glmanual tbody tr:eq(' + i + ') td:eq(0)').html();

						// $('#acc_date-'+get_id).attr({"id": 'acc_date-'+num, "data-id": num});
						$('#journal_name-'+get_id).attr({"id": 'journal_name-'+num, "data-id": num/*, "value": urut_journal*/});
						$('#journal_desc-'+get_id).attr({"id": 'journal_desc-'+num, "data-id": num});
						$('#debit-'+get_id).attr({"id": 'debit-'+num, "data-id": num});
						$('#credit-'+get_id).attr({"id": 'credit-'+num, "data-id": num});
						$('#nature-'+get_id).attr({"id": 'nature-'+num, "data-id": num});
						$('#acc_desc-'+get_id).attr({"id": 'acc_desc-'+num, "data-id": num});

						$('#table_glmanual tbody tr:eq(' + i + ') td:eq(0)').html(num);
					});

					counter = num;

					$(this).attr('disabled', true);
				}

		    });

		   /* $('#table_glmanual tbody').on('focus', 'tr td input.date-picker', function () {
		    	$(this).datepicker({
					format: 'dd-mm-yyyy',
					todayHighlight:'TRUE',
					autoclose: true,
					minDate:0
			    });
		    });*/

		    $('.mydatepicker').datepicker({
				format: 'dd-mm-yyyy',
				todayHighlight:'TRUE',
				autoclose: true,
		    });

		    $('#table_glmanual tbody').on('change', 'tr td select.nature', function () {
				id_row = $(this).data('id');
				acc_desc_val = $(this).find(':selected').data('desc');
				$("#acc_desc-"+id_row).val(acc_desc_val);
		    });

		    $('#table_glmanual tbody').on('input', 'tr td input.debit-credit', function () {
				id_row = $(this).data('id');
				dc_val = $(this).val().toString().replace(/\./g, '');

				if($(this).hasClass('debit')){
					credit_val = $("#credit-"+id_row).val().toString().replace(/\./g, '');
					if( parseInt(credit_val) > 0 ){
						$("#credit-"+id_row).val('0')
					}
				}
				else{
					debit_val = $("#debit-"+id_row).val().toString().replace(/\./g, '');
					if( parseInt(debit_val) > 0 ){
						$("#debit-"+id_row).val('0')
					}
				}
		    });

		    $("#save_data").on('click', function () {
				
				let batch_name = $("#batch_name").val();
				let gl_date    = $("#gl_date").val();
				let data_lines = [];
				let total_data = table_glmanual.data().count();

				if($("#journal_name-"+total_data).val() == "" || $("#nature-"+total_data).val() == 0 || $("#acc_desc-"+total_data).val() == ""){
					customNotif('Warning', 'Please fill out all line field!', 'warning');
				}else{

					table_glmanual.rows().eq(0).each( function ( index ) {
				    	j = index+1;
						// acc_date_val     = $("#acc_date-"+j).val();
						journal_name_val = $("#journal_name-"+j).val();
						journal_desc_val = $("#journal_desc-"+j).val();
						debit_val        = $("#debit-"+j).val();
						credit_val       = $("#credit-"+j).val();
						nature_val       = $("#nature-"+j).val();
						acc_desc_val     = $("#acc_desc-"+j).val();

						if(journal_name_val != ""  && nature_val != 0){
				    		data_lines.push({ 'journal_name' : journal_name_val,  'journal_desc' : journal_desc_val, 'debit' : parseInt(debit_val.replace(/\./g, '')), 'credit' : parseInt(credit_val.replace(/\./g, '')), 'nature' : nature_val, 'acc_desc' : acc_desc_val
				    			});
						}
					});

				    $.ajax({
				        url   : baseURL + 'journal-manua/api//save_gl',
				        type  : "POST",
				        data  : {batch_name: batch_name, gl_date: gl_date, data_lines: data_lines},
				        dataType: "json",
				        beforeSend  : function(){
		                          customLoading('show');
		                        },
				        success : function(result){
				        	if(result.status == true){
				        		customNotif('Success', "Journal Created", 'success');
				        		setTimeout(function(){
				        			customLoading('hide');
				        			$(location).attr('href', baseURL + 'uploadjournal/upload-journal');
				        		}, 500);
				        	}
				        	else{
				        		customLoading('hide');
				        		customNotif('Error', result.messages, 'error');
				        	}
				        }
				    });
				}
			});

		    function getNature(id_row) {

			    $.ajax({
			        url   : baseURL + 'api-budget/load_data_nature',
			        type  : "POST",
			        data: {get_all:true},
			        dataType: "json",
			        success : function(result){
						let nature_val = opt_default;
			        	if(result.status == true){
							data = result.data;
				        	for(var i = 0; i < data.length; i++) {
								obj = data[i];
							    nature_val += '<option value="'+ obj.nature +'" data-desc="'+ obj.description +'">'+ obj.nature +' - '+ obj.description +'</option>';
							}
			        	}
						$("#nature-"+id_row).html(nature_val);
			        }
			    });
			}
	});


</script>