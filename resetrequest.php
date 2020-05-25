<?php
require_once('./myid.php');

$errorMessage = '';
if (isset($_POST["login"])) {
        if (empty($_POST["usermail"])) {
                $errorMessage = 'メールアドレスが入力されていません．';
        }
        if (!empty($_POST["usermail"])) {
                try {
                        $strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");
                        $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
                        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$query = "SELECT * FROM AdminUsers WHERE Email = :usermail";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':usermail', $_POST['usermail'], PDO::PARAM_STR);
		$stmt->execute();
		$UserSet = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!empty($UserSet['Email'])){
			$token1 = substr(bin2hex(random_bytes(128)), 0, 128);
			$token2 = substr(bin2hex(random_bytes(128)), 0, 128);
			$exptoken = date("Y-m-d H:i:s",strtotime("+30 minute"));
			
			$query = "INSERT INTO ResetToken (AuthKey, AuthPri, expired, UserID) VALUES (:token1, :token2, :expd, :userid)";
			$stmt = $dbh->prepare($query);
			$stmt->bindParam(':token1', $token1, PDO::PARAM_STR);
			$stmt->bindParam(':token2', $token2, PDO::PARAM_STR);
			$stmt->bindParam(':expd', $exptoken, PDO::PARAM_STR);
			$stmt->bindParam(':userid', $UserSet['ID'], PDO::PARAM_INT);
			$stmt->execute();
			
			$toMail = $UserSet['Email'];
			$returnMail = 'no-reply@moritoworks.com';
			$name = "M-CMS";
			$mail = 'no-reply@moritoworks.com';
			$subject = "パスワードリセットリンクのお知らせ";
			$mydomain = "moritoworks.com/cms";
			$body = <<< EOM
{$name}をご利用頂きありがとうございます。
パスワードリセット依頼を承りましたのでご連絡致します。

パスワードリセットのお手続きを続けるには以下のURLにアクセスしてください。
※URLの有効期限はリセット依頼受付から30分間です。
https://{$mydomain}/resetauth.php?token1={$token1}&token2={$token2}

お手続きに身に覚えがない場合、お手数ですが運営事務局までお問い合わせ下さい。

なお、このメールは送信専用のメールアドレスで送信しているため、返信頂いても対応することができません。
何卒ご了承ください。
------------------------------
{$name}
Developed by Takenori Morio.

E-mail:admincenter@moritoworks.com
------------------------------
EOM;
			mb_language('ja');
			mb_internal_encoding('UTF-8');
			$header = 'From: ' . mb_encode_mimeheader($name) . ' <' . $mail . '>';
			mb_send_mail($toMail, $subject, $body, $header, '-f' . $returnMail);
		}

        header("Location: ./requestcomplete.php");
        exit(0);


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
			<span class="card-title">パスワード変更手続き</span>
				<div class="row">
					<div class="input-field col s8">
						<i class="material-icons prefix">mail</i>
						<input type="text" id="usermail" name="usermail" class="validate" value="<?php
								if (!empty($_POST["usermail"])) {echo	 htmlspecialchars($_POST["usermail"], ENT_QUOTES);} ?>" required>
						<label for="usermail" class="active">登録メールアドレス</label>
					</div>
					<button class="btn waves-effect waves-ligh right" type="submit" id="login" name="login">変更依頼送信</button>
				</div>
			</div>
		</form>
		<a href="login.php" class="btn waves-effect waves-ligh" ><i class="material-icons left">arrow_back</i>ログインへ</a><br><br>
	</div>

	</body>
</html>
