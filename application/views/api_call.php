<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Test API Call</title>
	<link rel="stylesheet" href="">
</head>
<body>

	<div id="content"> Halo test</div>

  <script src="<?= base_url('assets/') ?>plugins/bower_components/jquery/dist/jquery.min.js"></script>

	<script>
		const BASE_URL = 'https://gfourtech.com/';
		let token  = '';
		let authData = { "email":"finance.tools@mail.com", "password":"F1n@nc3T00L5" };
		api_fpjp('paid', '1234');

		function getToken() {

			let accesToken;
        	return new Promise(resolve => {
				$.ajax({
			        url   : BASE_URL + 'finance-endpoint/digipos-apis-action/get-token',
			        type  : "POST",
				    data: JSON.stringify(authData),
				    contentType: "application/json; charset=utf-8",
				    dataType: "json",
			        success : function(result){
			        	if(result.status == true){
			        		accesToken = result.token;
			        	}
		        		resolve(accesToken);
			        }
			    });
        	});
		}		

	    async function api_fpjp(status_paid='paid', no_fpjp='1') {

			let endURL    = (status_paid == 'paid') ? 'paid-fpjp' : 'unpaid-fpjp';
			let data_fpjp = {"no_fpjp": no_fpjp};
			let token = await getToken();

	    	$.ajax({
		        url   : BASE_URL + 'finance-endpoint/digipos-apis-action/' + endURL,
		        type  : "POST",
			    data: JSON.stringify(data_fpjp),
			    contentType: "application/json; charset=utf-8",
			    dataType: "json",
			    // beforeSend: function(xhr, settings) { xhr.setRequestHeader('Authorization','Bearer ' + token); },
			    headers: {
				    Authorization: 'Bearer '+token
				},
		        success : function(result){
		        	console.log('success');
		        	$("#content").html(result.message);
		        },
		        error : function(xhr, textStatus, errorThrown ) {
			        if (textStatus == 'timeout') {
			            this.tryCount++;
			            if (this.tryCount <= this.retryLimit) {
			                $.ajax(this);
			                return;
			            }            
			            return;
			        }
			        if (xhr.status == 401) {
						$.ajax(this);
		                return;
			        } else {
			        	console.log('erorr');
			        }
		        }
		    });
		}
		

	</script>
	
</body>
</html>