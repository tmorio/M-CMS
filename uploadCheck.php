<?php

require_once('./myid.php');
session_start();
if(empty($_SESSION['userNo'])){
        header("Location: login.php");
}
		try {

			if (!isset($_FILES['iconImg']['error']) || !is_int($_FILES['iconImg']['error'])) {
				throw new RuntimeException('パラメータが不正です。\nファイルをご確認下さい。');
			}

			switch ($_FILES['iconImg']['error']) {
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					throw new RuntimeException('ファイルが選択されていません');
				default:
					throw new RuntimeException('予期しないエラーが発生しました。\n再度お試し下さい。');
			}

			if ($_FILES['iconImg']['size'] > 5242880) {
				throw new RuntimeException('アップロードできるサイズを超えています。\nサイズが5MB以下になるように圧縮などを行って下さい。');
			}

			if (!$ext = array_search(
				mime_content_type($_FILES['iconImg']['tmp_name']),
				array(
					'jpg' => 'image/jpeg',
					'png' => 'image/png',
				),
				true
			)) {
				throw new RuntimeException('ファイル形式が不正です。\n正しいファイルを選択してください。');
			}

			$iconFileName = sha1_file($_FILES['iconImg']['tmp_name']) . '.' . $ext;

			if (!move_uploaded_file(
				$_FILES['iconImg']['tmp_name'],
				$path = sprintf('./storage/%s',
					$iconFileName
				)
			)) {
				throw new RuntimeException('ファイルの保存に失敗しました。\nフォルダに読み書き権限があるかご確認ください。');
			}

			chmod($path, 0644);

	                try {
	                        $strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");
	                        $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
	                        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	                        $query = "INSERT INTO Media (Owner, linkPath) VALUES (:ownerID, :mediaPath)";
	                        $stmt = $dbh->prepare($query);
				$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
	                        $stmt->bindParam(':mediaPath', $iconFileName, PDO::PARAM_STR);
	                        $stmt->execute();
				$_SESSION['ToastMes'] = 'メディアのアップロードに成功しました。';
	                        header("Location: settings.php?page=media");
	                } catch (PDOException $e) {
	                        echo 'データベースへの接続に失敗しました．';
	                }


		} catch (RuntimeException $e) {

			echo '<script>alert("' . $e->getMessage() .'");location.href="settings.php?page=upload";</script>';

		}


?>
