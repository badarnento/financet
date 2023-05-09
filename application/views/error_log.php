<?php
  header("X-Frame-Options: SAMEORIGIN");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Error Log</title>
	<link rel="stylesheet" href="">
</head>
<body>
	<div id="content">
        <div class="container-fluid">
            <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-exclamation-triangle"></i></h3>
            </div>
            <div class="panel-body">
                <div><?= $file_size ?></div>
                <textarea wrap="off" rows="15" cols="100" readonly class="form-control"><?= $log; ?></textarea>
            </div>
            </div>
        </div>
    </div>
</body>
</html>