<?php
require_once('./myid.php');

session_start();

if(!empty($_SESSION['userNo'])){
        header("Location: ./settings.php");
        exit(0);
}

$errorMessage = '';
if (isset($_POST["login"])) {
        if (empty($_POST["userid"])) {
                $errorMessage = 'IDが入力されていません．';
        } else if (empty($_POST["password"])) {
                $errorMessage = 'パスワードが入力されていません．';
        }
        if (!empty($_POST["userid"]) && !empty($_POST["password"])) {
                $userid = $_POST["userid"];
                try {
                        $strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");
                        $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
                        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                        $query = "SELECT * FROM Users WHERE UserID = :UserID";
                        $stmt = $dbh->prepare($query);
                        $stmt->bindParam(':UserID', $_POST['userid'], PDO::PARAM_STR);
                        $stmt->execute();
                        $result = $stmt->fetch();
                        if (password_verify($_POST['password'], $result['Password'])){
                                $_SESSION['userNo'] = $result['ID'];
                                session_regenerate_id(true);
				sleep(1);
                                header("Location: settings.php");
                        }else{
                                unset($result);
                                $errorMessage = 'IDまたはパスワードが違います。';
                        }
                } catch (PDOException $e) {
                        $errorMessage = 'データベースへの接続に失敗しました．';
                }
        }
}
?>

<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Login</title>
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
			<p class="image"><img src="img/logo.png" style="height:70px;weight:auto;"/></p>
		</div>
		<form class="col s12 m12 card blue-grey lighten-5" id="loginForm" name="loginForm" action="" method="POST" style="padding:10px;">
			<div class="card-content grey-text text-darken-4">
			<span class="card-title">ログイン</span>
				<div class="row">
					<div class="input-field col">
						<i class="material-icons prefix">person</i>
						<input type="text" id="userid" name="userid" class="validate" value="<?php
								if (!empty($_POST["userid"])) {echo	 htmlspecialchars($_POST["userid"], ENT_QUOTES);} ?>" required>
						<label for="userid" class="active">ID</label>
					</div>
					<div class="input-field col">
						<i class="material-icons prefix">vpn_key</i>
						<input type="password" id="password" name="password" value="" required>
						<label for="password" class="active">Password</label>
					</div>
					<button class="btn waves-effect waves-ligh right" type="submit" id="login" name="login">ログイン</button>
				</div>
			</div>
		</form>
		<a href="resetrequest.php" class="btn waves-effect waves-ligh" ><i class="material-icons left">help_outline</i>パスワードをお忘れですか?</a><br><br>
	</div>

	</body>
</html>
