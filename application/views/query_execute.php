<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Query Executor</title>
	<link rel="stylesheet" href="">
</head>
<body>

	<form action="<?= base_url('/query-executor')?>" method="post">
		<textarea name="q_string" id="q_string" cols="30" rows="10"></textarea>
		<input type="submit">
	</form>
	
</body>
</html>