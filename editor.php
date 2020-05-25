<?php

ini_set("session.cookie_secure", 1);

session_start();
session_regenerate_id(true);
if(empty($_SESSION['userNo'])){
	header("Location: login.php");
}

if(!empty($_GET['postid'])){
	require_once('./myid.php');

	$strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8mb4'");
	try {
		$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
		$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	} catch (PDOException $e) {
		echo $e->getMessage();
		exit;
	}

	$query = "SELECT * FROM archiveList WHERE ID = :postID AND Owner = :nowOwner";
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':postID', $_GET['postid'], PDO::PARAM_INT);
	$stmt->bindParam(':nowOwner', $_SESSION['userNo'], PDO::PARAM_INT);
	$stmt->execute();
	$listID = $stmt->fetch();

	$query = "SELECT * FROM archiveData WHERE ID = :dataID AND Owner = :nowOwner";
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':dataID', $listID['dataID'], PDO::PARAM_INT);
	$stmt->bindParam(':nowOwner', $_SESSION['userNo'], PDO::PARAM_INT);
	$stmt->execute();
	$dataInfo = $stmt->fetch();

}
?>
<!doctype html>
<html style="background:#fff;">
	<head>
		<meta charset="UTF-8">
		<title>Morikapu CMS - Admin</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css?">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>

	</head>
	<body style="background:#fff;">

	<div class="serviceHeader navbar-fixed">
		<nav>
			<div class="nav-wrapper black-text">
				<img class="logo-image" src="img/logo.png">
				<ul class="right">
					<a class="btn waves-effect waves-light" href="logout.php"><i class="material-icons left">exit_to_app</i>ログアウト</a>
					&thinsp;
				</ul>
			</div>
		</nav>
	</div>

	<div class="editorBoard">
			<div class="settingInfo" style="border: solid 1px #d3d3d3;border-style:none solid none none;overflow:auto;">
				<form method="post" name="postData" id="postData" action="publish.php">
					<a class="waves-effect waves-light btn" href="settings.php?page=posts"><i class="material-icons left">arrow_back</i>戻る</a><br>
					<h3>新規投稿・編集</h3>
					
					<br>
					
					<div class="input-field col s6">
						<input id="postTitle" name="title" type="text" class="validate" value="<?php echo htmlspecialchars($listID['Name'], ENT_QUOTES, 'UTF-8'); ?>">
						<label for="postTitle">タイトル</label>
					</div>
					<br>
					<div class="input-field col s12" >
						<select name="category">
							<option value="" disabled selected>カテゴリーを選択してください。</option>
							<option value="1">プログラミング</option>
							<option value="2">日常</option>
							<option value="3">研修</option>
						</select>
						<label>カテゴリー</label>
					</div>
					<br>

					<div class="s12" style="border:solid 1px #d3d3d3;padding:5px;">
						<div class="row" style="margin:0;">
							<div class="input-field col s12">
								<textarea id="postText" name="content" class="materialize-textarea"  style="height:50vh;"><?php echo htmlspecialchars($dataInfo['Text'], ENT_QUOTES, 'UTF-8'); ?></textarea>
								<label for="postText">本文（Markdown）</label>
							</div>
						</div>
					</div>

				</form>
			</div>

			<div style="padding:15px 10px 0;overflow:auto;border: solid 1px #d3d3d3;border-style:none none none solid;">
				<div class="postMenu">
					<h3>投稿</h3>

					<?php
						if(empty($_GET['postid'])){
							echo '<a class="waves-effect waves-light btn" onclick="post_click(0);"><i class="material-icons left">save</i>下書きに保存</a>';
							echo '<a class="waves-effect waves-light btn right" onclick="post_click(1);"><i class="material-icons right">send</i>投稿</a>';
						}else{
							echo '<a class="waves-effect waves-light btn" onclick="update_click(0);"><i class="material-icons left">save</i>下書きにして保存</a>';
							echo '<a class="waves-effect waves-light btn right" onclick="update_click(1);"><i class="material-icons right">send</i>更新</a>';
						}
					?>
				</div>
				<div class="postMenu">
					<h3>画像の挿入</h3>
					画像クリックで挿入します。<br>
				</div>
			</div>
			
	</div>
		<footer>
			<script>
				function post_click(value) {
					var target = document.getElementById("postData");
					if (value == 0) {
						target.action = "publish.php?md=1";
					}
					else if (value == 1) {
						target.action = "publish.php?md=2";
					}
					postData.submit();
				}
				
				function update_click(value) {
					var target = document.getElementById("postData");
					if (value == 0) {
						target.action = "publish.php?md=3&postid=<?php echo htmlspecialchars($_GET['postid'], ENT_QUOTES, 'UTF-8'); ?>";
					}
					else if (value == 1) {
						target.action = "publish.php?md=4&postid=<?php echo htmlspecialchars($_GET['postid'], ENT_QUOTES, 'UTF-8'); ?>";
					}
					postData.submit();
				}
				$(document).ready(function(){
					$('.modal').modal();
				});
				$(document).ready(function(){
					$('select').formSelect();
				});
			</script>
		</footer>
</body>

