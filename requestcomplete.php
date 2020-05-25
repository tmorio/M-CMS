<?php
?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>リクエスト完了</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="css/whitestyle.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>
		<script>
			$(document).ready(function() {
				M.updateTextFields();
			});
		</script>
	</head>

	<div class="loginForm">
		<div class="centering">
			<p class="image"><img src="img/logo.png" style="height:50px;weight:auto;"/></p>
		</div>
		<form class="col s12 m12 card blue-grey lighten-5" id="loginForm" name="loginForm" action="" method="POST" style="padding:10px;">
			<div class="card-content grey-text text-darken-4">
			<span class="card-title">リクエスト完了</span>
					確認メールをお送りしましたので、メールボックスをご確認ください。<br><br>
					<a href="login.php" class="btn waves-effect waves-ligh right"  name="login">ログインへ</a><br><br>
			</div>
		</form>
		<span style="color:#000;">Powered by Morikapu Cloud.</span>
	</div>

	</body>
</html>
