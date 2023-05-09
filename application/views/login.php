<?php
  header("X-Frame-Options: SAMEORIGIN");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?= $this->config->item('site_description') ?>">
<meta name="author" content="">
<link rel="shortcut icon" href="<?= base_url('assets/custom/img/') ?>favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" href="<?= base_url('assets/custom/img/') ?>apple-touch-icon.png">
<title><?= $title ?> | <?= $this->config->item('site_title') ?></title>
<!-- Bootstrap Core CSS -->
<link href="<?= base_url('assets/') ?>bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- animation CSS -->
<link href="<?= base_url('assets/') ?>css/animate.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="<?= base_url('assets/') ?>css/style.css" rel="stylesheet">
<link href="<?= base_url('assets/custom/') ?>css/custom.min.css" rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>
  <style>
    .login-register{
      background:  url('<?= base_url("assets/custom/img/login.jpg")?>') !important;
      background-size: cover !important;
    }
  </style>
<!-- Preloader -->
<div class="preloader">
  <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="login-register">
  <div class="login-box p-30 border-radius-10">
      <form role="form" id="loginform" class="form-horizontal" autocomplete="off" data-toggle="validator">
        <div class="text-center">
          <!-- <img src="<?= base_url("assets/custom/img/") ?>logo-square-white.png" alt=""> -->
          <h3 class="box-title m-b-10 text-center font-weight-bold"><?= $this->config->item('site_title') ?></h3>
        </div>
        <h4 class="box-title m-b-10 text-center font-weight-400 hide text-red" id="errorMsg">Failed to login</h4>
        <div class="form-group ">
          <div class="col-xs-12">
            <input class="form-control" type="text" id="inputIdentity" name="inputIdentity" required="" autocomplete="off" placeholder="Masukkan Username">
          </div>
        </div>
        <div class="form-group">
          <div class="col-xs-12">
            <input class="form-control" type="password" name=" inputPassword" required="" autocomplete="off" placeholder="Masukkan Password">
          </div>
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button class="btn btn-danger btn-lg btn-block text-uppercase waves-effect waves-light border-radius-5" type="submit">Log In</button>
          </div>
        </div>
      </form>
      <div class="text-center">
        <p>&copy; <?= date('Y') ?> <?= $this->config->item('site_title') ?> by FinOps </p>
      </div>
  </div>
</section>
<!-- jQuery -->
<script src="<?= base_url('assets/') ?>plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="<?= base_url('assets/') ?>bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Menu Plugin JavaScript -->
<script src="<?= base_url('assets/') ?>plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
<!--slimscroll JavaScript -->
<script src="<?= base_url('assets/') ?>js/jquery.slimscroll.js"></script>
<!-- Custom Theme JavaScript -->
<script src="<?= base_url('assets/') ?>js/custom.min.js"></script>
<script src="<?= base_url('assets/') ?>custom/js/custom.min.js"></script>
<script>
  const BASE_URL     = "<?= base_url() ?>";
  const imageLoading = '<?= base_url('assets/img/loading.gif') ?>';
  let redirect       = "<?= $redirect ?>";
  const BASH_CODE    = "<?= $this->config->item('login_hash_url') ?>";   
</script>

<script src="<?= base_url('assets/custom/js/') ?>login.min.js"></script>
</body>
</html>
