<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?= $this->config->item('site_description') ?>">
<meta name="author" content="Badar Nento">
<link rel="shortcut icon" href="<?= base_url('assets/') ?>custom/img/favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" href="<?= base_url('assets/') ?>custom/img/apple-touch-icon.png">
<title><?= (isset($title)) ? $title . " |" : "" ?> <?= $this->config->item('site_title') ?></title>
<!-- Bootstrap Core CSS -->
<link href="<?= base_url('assets/') ?>bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Menu CSS -->
<link href="<?= base_url('assets/') ?>plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
<!-- animation CSS -->
<link href="<?= base_url('assets/') ?>css/animate.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="<?= base_url('assets/') ?>css/style.css" rel="stylesheet">
<!-- color CSS you can use different color css from css/colors folder -->
<link href="<?= base_url('assets/') ?>css/colors/blue.css" id="theme"  rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

</head>
<body>

<div id="wrapper">
  <!-- Page Content -->
  <div id="page-wrapper" class="m-0">
    <div class="container-fluid">
      <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
          <h4 class="page-title"><?= (isset($title)) ? $title : "API FPJP TEST" ?></h4>
        </div>
        <!-- /.col-lg-12 -->
      </div>
        <div class="row">
            <div class="col-md-6">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Update FPJP Status</h3>
                    <!-- <form class="form-horizontal"> -->
                    <form class="form-horizontal" role="form" id="form-check" data-toggle="validator">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">No FPJP *</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="no_fpjp" id="no_fpjp" placeholder="No FPJP" required="" data-error="Please input No FPJP" required>
                                 <div class="help-block with-errors"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-9">
                                <select class="form-control" name="status_fpjp" id="status_fpjp">
                                    <option value="paid">Paid</option>
                                    <option value="unpaid">Upaid</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Result</h3>
                    <h3 id="content_result">No result</h3>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    <!-- <footer class="footer text-center"> 2016 &copy; Elite Admin brought to you by themedesigner.in </footer> -->
  </div>
  <!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<!-- jQuery -->
<script src="<?= base_url('assets/') ?>plugins/bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?= base_url('assets/') ?>bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Menu Plugin JavaScript -->
<script src="<?= base_url('assets/') ?>plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
<!--slimscroll JavaScript -->
<script src="<?= base_url('assets/') ?>js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="<?= base_url('assets/') ?>js/waves.js"></script>
<!-- Custom Theme JavaScript -->
<script src="<?= base_url('assets/') ?>js/custom.min.js"></script>
<script src="<?= base_url('assets/') ?>js/validator.js"></script>

<!--Style Switcher -->
<script src="<?= base_url('assets/') ?>plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>


<script>

    const BASE_URL = 'https://digipos-api.linkaja.com/';
    let token      = '';
    let oldToken   = '';
    let authData   = { "email":"finance.tools@mail.com", "password":"F1n@nc3T00L5" };

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

        if(token == ''){
            token = await getToken();
            oldToken = token;
        }else{
            token = oldToken;
        }
        let content = '';

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
                console.log(result.success);
                if(result.success == true){
                    content = '<span class="text-success">' + result.message + '</span>';
                }else{
                    content = '<span class="text-danger">' + result.message + '</span>';
                }
                $("#content_result").html(content);
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

    $('#form-check').validator().on('submit', function(e) {
        if (!e.isDefaultPrevented()) {
            let no_fpjp = $("#no_fpjp").val();
            let status_paid = $("#status_fpjp").val();
            api_fpjp(status_paid, no_fpjp);
        }
        e.preventDefault();
    });

</script>
</body>
</html>
