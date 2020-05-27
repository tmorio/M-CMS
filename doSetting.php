<?php

session_start();
require_once('./myid.php');

$strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8mb4'");
try{
	$dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
	$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
	echo $e->getMessage();
	exit;
}

$query = "SELECT * FROM Users WHERE ID = :adminID";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':adminID', $_SESSION['userNo'], PDO::PARAM_INT);
$stmt->execute();
$permissionCheck = $stmt->fetch();

if(empty($permissionCheck['ID'])){
	echo '権限がありません。';
	exit(1);
}

unset($permissionCheck);

switch($_GET['Setup']){
	default:
		exit(0);
		break;
	case chPass:
		if ($_POST['newPassword'] != $_POST['newPasswordVerify']){
			header("Location: ./settings.php?mes=5");
			exit(2);
		}
		$query = "SELECT * FROM Users WHERE ID = :UserID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':UserID', $_SESSION['userNo'], PDO::PARAM_STR);
		$stmt->execute();
		$result = $stmt->fetch();

		if (password_verify($_POST['nowPassword'], $result['Password'])){
			$stmt = $dbh->prepare("UPDATE Users SET Password = ? WHERE ID = ?");
			$stmt->execute(array(password_hash($_POST['newPassword'], PASSWORD_DEFAULT), $_SESSION['userNo']));
			header("Location: ./settings.php?mes=2");
			exit(0);
		}else{
			header("Location: ./settings.php?mes=1");
			exit(1);
		}
		break;
	case addUser:
		$query = "INSERT INTO Users (Name, Status, studentID) VALUES (:studentName, 0, :studentID)";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':studentName', $_POST['studentName'], PDO::PARAM_STR);
		$stmt->bindParam(':studentID', $_POST['studentID'], PDO::PARAM_STR);
		$stmt->execute();
		header("Location: ./settings.php?page=members");
		exit(0);
		break;
	case delUser:
		if($_POST['deluserid'] == 1){
			echo 'マスターは削除できません。';
			exit(2);
			break;
		}

		$query = "DELETE FROM Users WHERE ID = :userID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':userID', $_POST['deluserid'], PDO::PARAM_STR);
		$stmt->execute();
		header("Location: ./settings.php?page=members&mes=4");
		exit(0);
		break;
	case blogSet:
		$query = "UPDATE Settings SET Name = :newName WHERE ID = 1";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':newName', $_POST['newBlogName'], PDO::PARAM_STR);
                $stmt->execute();
		$_SESSION['ToastMes'] = '設定を更新しました。';
		header("Location: ./settings.php?page=setting");
		exit(0);
		break;

}

