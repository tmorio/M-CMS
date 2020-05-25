<?php
require_once('./myid.php');

session_start();

try {
		$strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");
                $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
                $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
		$errorMessage = 'データベースへの接続に失敗しました．';
}

if(empty($_SESSION['token1']) || empty($_SESSION['token2'])){

	if(empty($_GET['token1']) || empty($_GET['token2'])){
	        echo "認証できませんでした。再度手続きを行って下さい。";
	        exit(1);
	}

	$query = "SELECT * FROM ResetToken WHERE AuthKey = :authToken AND AuthPri = :privateToken";
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':authToken', $_GET['token1'], PDO::PARAM_STR);
	$stmt->bindParam(':privateToken', $_GET['token2'], PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetch();

	if(empty($result['ID'])){
		echo "認証できませんでした。再度手続きを行って下さい。";
		exit(2);
	}else{
		if(strtotime($result['expired']) < time()){
			echo "有効期限切れです。再度手続きを行って下さい。";
			exit(2);
		}
	}
}


if(empty($_SESSION['token1']) || empty($_SESSION['token2'])){
	$_SESSION['token1'] = $_GET['token1'];
	$_SESSION['token2'] = $_GET['token2'];
}

$errorMessage = '';
if (isset($_POST["changePassword"])) {
        if (empty($_POST["newPassword"])) {
                $errorMessage = 'パスワードが入力されていません．';
        } else if (empty($_POST["verifyPassword"])) {
                $errorMessage = 'パスワード（確認）が入力されていません．';
        }
        if ($_POST["newPassword"] == $_POST["verifyPassword"]) {

	        $query = "SELECT * FROM ResetToken WHERE AuthKey = :authToken AND AuthPri = :privateToken";
	        $stmt = $dbh->prepare($query);
	        $stmt->bindParam(':authToken', $_SESSION['token1'], PDO::PARAM_STR);
	        $stmt->bindParam(':privateToken', $_SESSION['token2'], PDO::PARAM_STR);
	        $stmt->execute();
	        $result = $stmt->fetch();
	        if((strtotime($result['expired']) < time()) || empty($result['ID'])){
                        echo "有効期限切れです。再度手続きを行って下さい。";
                        exit(2);
                }
		$newPassword = password_hash($_POST["newPassword"], PASSWORD_DEFAULT);
		$userSelector = $result['UserID'];
		$query = "UPDATE AdminUsers SET Password = :newPassword WHERE ID = :UserID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':newPassword', $newPassword, PDO::PARAM_STR);
		$stmt->bindParam(':UserID', $userSelector, PDO::PARAM_INT);
		$stmt->execute();
		$query = "DELETE FROM ResetToken WHERE UserID = :UserID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':UserID', $userSelector, PDO::PARAM_INT);
		$stmt->execute();
        	header("Location: ./complete.php");
        	exit(0);
        }
}
?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>パスワードリセット</title>
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

<?php
	if($errorMessage!=null){
		echo '
		<div class="row">
			<div class="col s12 m12 pink lighten-5">
				<h5 class="valign-wrapper">
					<i style="font-size: 2.5rem;" class="material-icons orange-text text-darken-5">warning</i>
					<font class="red-text">';
					echo "&nbsp;" . htmlspecialchars($errorMessage, ENT_QUOTES);
			  echo '</font>
				</h5>
			</div>
		</div>
		';
	}
	?>

	<div class="loginForm">
		<div class="centering">
			<p class="image"><img src="img/logo.png" style="height:50px;weight:auto;"/></p>
		</div>
		<form class="col s12 m12 card blue-grey lighten-5" id="loginForm" name="loginForm" action="" method="POST" style="padding:10px;">
			<div class="card-content grey-text text-darken-4">
			<span class="card-title">パスワードリセット</span>
				<div class="row">
					<div class="input-field col">
						<i class="material-icons prefix">vpn_key</i>
						<input type="password" id="newPassword" name="newPassword" class="validate" value="<?php
								if (!empty($_POST["newPassword"])) {echo	 htmlspecialchars($_POST["newPassword"], ENT_QUOTES);} ?>" required>
						<label for="newPassword" class="active">新パスワード</label>
					</div>
					<div class="input-field col">
						<i class="material-icons prefix">vpn_key</i>
						<input type="password" id="verifyPassword" name="verifyPassword" value="" required>
						<label for="verifyPassword" class="active">新パスワード（確認）</label>
					</div>
					<button class="btn waves-effect waves-ligh right" type="submit" id="changePassword" name="changePassword">パスワードを変更</button>
				</div>
			</div>
		</form>
	</div>

	</body>
</html>
