<?php
session_start();

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

switch($_GET['md']){
	default:
		echo '[E04]遷移エラーです。';
		break;
	case 1:
		$query = "INSERT INTO archiveData (Owner, CreatedAt, Text) VALUES (:ownerID, :createTime, :textData)";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':createTime', date("Y-m-d H:i:s")  , PDO::PARAM_STR);
		$stmt->bindParam(':textData', $_POST['content'], PDO::PARAM_STR);
		$stmt->execute();

		$query = "SELECT * FROM archiveData WHERE Owner = :ownerID AND CreatedAt = :createTime";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':createTime', date("Y-m-d H:i:s")  , PDO::PARAM_STR);
		$stmt->execute();
		$dataID = $stmt->fetch();
		$archiveID = $dataID['ID'];

		$query = "INSERT INTO archiveList (Owner, Name, dataID, CreatedAt, Draft) VALUES (:ownerID, :postTitle, :dataID, :createTime, 1)";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':postTitle',  $_POST['title']  , PDO::PARAM_STR);
		$stmt->bindParam(':dataID', $archiveID, PDO::PARAM_INT);
		$stmt->bindParam(':createTime', date("Y-m-d H:i:s")  , PDO::PARAM_STR);
		$stmt->execute();

		$_SESSION['ToastMes'] = '記事を下書きに保存しました。';

		break;
	case 2:
		$query = "INSERT INTO archiveData (Owner, CreatedAt, Text) VALUES (:ownerID, :createTime, :textData)";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':createTime', date("Y-m-d H:i:s")  , PDO::PARAM_STR);
		$stmt->bindParam(':textData', $_POST['content'], PDO::PARAM_STR);
		$stmt->execute();

		$query = "SELECT * FROM archiveData WHERE Owner = :ownerID AND CreatedAt = :createTime";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':createTime', date("Y-m-d H:i:s")  , PDO::PARAM_STR);
		$stmt->execute();
		$dataID = $stmt->fetch();
		$archiveID = $dataID['ID'];

		$query = "INSERT INTO archiveList (Owner, Name, dataID, CreatedAt, Draft) VALUES (:ownerID, :postTitle, :dataID, :createTime, 0)";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':postTitle',  $_POST['title']  , PDO::PARAM_STR);
		$stmt->bindParam(':dataID', $archiveID, PDO::PARAM_INT);
		$stmt->bindParam(':createTime', date("Y-m-d H:i:s")  , PDO::PARAM_STR);
		$stmt->execute();

		$_SESSION['ToastMes'] = '記事を投稿しました。';

		break;
	case 3:
		$query = "SELECT * FROM archiveList WHERE Owner = :ownerID AND ID = :postID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':postID', $_GET['postid']  , PDO::PARAM_INT);
		$stmt->execute();
		$dataID = $stmt->fetch();
		if(empty($dataID)){
			echo '[E03]権限エラーです。';
			exit(3);
		}
		$query = "UPDATE archiveList SET Name = :titleName, Draft = 1 WHERE Owner = :ownerID AND ID = :postID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':titleName', $_POST['title'], PDO::PARAM_STR);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':postID', $_GET['postid']  , PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "UPDATE archiveData SET Text = :content WHERE Owner = :ownerID AND ID = :dataID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':postID', $dataID['dataID']  , PDO::PARAM_INT);
		$stmt->execute();

		$_SESSION['ToastMes'] = '記事を下書きに保存しました。';

		break;
	case 4:
		$query = "SELECT * FROM archiveList WHERE Owner = :ownerID AND ID = :postID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':postID', $_GET['postid']  , PDO::PARAM_INT);
		$stmt->execute();
		$dataID = $stmt->fetch();
		if(empty($dataID)){
			echo '[E03]権限エラーです。';
			exit(3);
		}
		$query = "UPDATE archiveList SET Name = :titleName, Draft = 0 WHERE Owner = :ownerID AND ID = :postID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':titleName', $_POST['title'], PDO::PARAM_STR);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':postID', $_GET['postid']  , PDO::PARAM_INT);
		$stmt->execute();
		
		$query = "UPDATE archiveData SET Text = :content WHERE Owner = :ownerID AND ID = :dataID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
		$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':postID', $dataID['dataID']  , PDO::PARAM_INT);
		$stmt->execute();

		$_SESSION['ToastMes'] = '記事を更新しました。';

		break;
}
header("Location: settings.php?page=posts");
?>
