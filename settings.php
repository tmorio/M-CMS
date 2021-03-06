<?php

ini_set("session.cookie_secure", 1);

session_start();
session_regenerate_id(true);
if(empty($_SESSION['userNo'])){
	header("Location: login.php");
}


require_once('./myid.php');

$strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8mb4'");
try {
	$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
	echo $e->getMessage();
	exit;
}

$query = "SELECT * FROM Settings WHERE ID = 1";
$stmt = $dbh->prepare($query);
$stmt->execute();
$result = $stmt->fetch();

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
					<a class="btn waves-effect waves-light z-depth-0" href="index.php" target="_blank" style="margin-left:0;margin-right:0;"><i class="material-icons left">open_in_new</i>サイトを開く</a>
					<a class="btn waves-effect waves-light z-depth-0" href="logout.php" style="margin-left:0;margin-right:0;"><i class="material-icons left">exit_to_app</i>ログアウト</a>
					&thinsp;
				</ul>
			</div>
		</nav>
	</div>

	<div class="settingBoard">
		<!-- 設定分類一覧表示 -->
		<div class="collection with-header settingList">
			<div class="collection-header"><h5>管理メニュー</h5></div>
			<a href="settings.php" class="collection-item blue-grey-text text-darken-4"><i class="material-icons left">home</i>ダッシュボード</a>
			<a href="?page=posts" class="collection-item blue-grey-text text-darken-4"><i class="material-icons left">edit</i>記事管理</a>
			<a href="?page=media" class="collection-item blue-grey-text text-darken-4"><i class="material-icons left">camera_alt</i>メディア管理</a>
			<a href="?page=members" class="collection-item blue-grey-text text-darken-4"><i class="material-icons left">group</i>ユーザー管理</a>
			<a href="?page=setting" class="collection-item blue-grey-text text-darken-4"><i class="material-icons left">settings</i>ブログ設定</a>
			<a href="?page=version" class="collection-item blue-grey-text text-darken-4"><i class="material-icons left">copyright</i>ライセンス</a>
		</div>
		<!-- 設定表示 -->
		<div class="settingInfo" style="border: solid 1px #d3d3d3;border-style:none solid none none;overflow:auto;">
<?php
switch ($_GET['page']) {
	default:
		require_once('./admin/home.php');
		break;
	case members:
		require_once('./admin/member.php');
		break;
        case posts:
                require_once('./admin/posts.php');
                break;
	case media:
		require_once('./admin/media.php');
		break;
	case version:
		require_once('./admin/settingVer.php');
		break;
	case upload:
		require_once('./admin/uploader.php');
		break;
	case userinfo:
		require_once('./admin/userInfo.php');
		break;
	case mediaEdit:
		require_once('./admin/mediaEdit.php');
		break;
	case setting:
		require_once('./admin/blogSetting.php');
		break;
}

?>

	</div>
</div>
	<div id="changePassword" class="modal">
		<form action="doSetting.php?Setup=chPass" method="POST">
			<div class="modal-content textBlack">
				<h3>パスワード変更</h3>
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">vpn_key</i>
						<input name="nowPassword" id="nowPassword" type="password" class="validate" required>
						<label for="nowPassword">現在のパスワード</label>
					</div>
					<div class="input-field col s12">
						<i class="material-icons prefix">vpn_key</i>
						<input name="newPassword" id="newPassword" type="password" class="validate" required>
						<label for="newPassword">新しいパスワード</label>
					</div>
					<div class="input-field col s12">
						<i class="material-icons prefix">vpn_key</i>
						<input name="newPasswordVerify" id="newPasswordVerify" type="password" class="validate" required>
						<label for="newPasswordVerify">新しいパスワード（確認）</label>
					</div>
				</div>
				<a class="waves-effect waves-light modal-close btn red left"><i class="material-icons left">close</i>キャンセル</a>
				<button class="btn waves-effect waves-light right" type="submit" id="login" name="login"><i class="material-icons left">check</i>変更する</button><br>
			</div>
		</form>
	</div>
		<footer>
			<script>
				$(document).ready(function(){
					$('.modal').modal();
				});
				function selectUser(userID){
					document.getElementById( "userid" ).value = userID;
				}
				function editUser(userID, Name, studentID){
					document.getElementById( "updateUserID" ).value = userID;
					document.getElementById( "newStudentName" ).value = Name;
					document.getElementById( "newStudentID" ).value = studentID;
					$(document).ready(function() {
						M.updateTextFields();
					});
				}
				function delUser(userID){
					document.getElementById( "deluserid" ).value = userID;
	                        }
				$(document).ready(function(){
					$('select').formSelect();
				});

                                <?php

                                        if(!empty($_SESSION['ToastMes'])){
                                                echo 'window.onload = function(){ M.toast({html:\'' . $_SESSION['ToastMes'] . '\'})}';
                                                unset($_SESSION['ToastMes']);
                                        }
                                ?>

			</script>
		</footer>
</body>

