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

$query = "SELECT * FROM archiveList WHERE Owner = :ownerID AND ID = :postID";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
$stmt->bindParam(':postID', $_GET['postid'], PDO::PARAM_INT);
$stmt->execute();

$postInfo = $stmt->fetch();
$dataID = $postInfo['dataID'];

$query = "DELETE FROM archiveList WHERE Owner = :ownerID AND ID = :postID";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
$stmt->bindParam(':postID', $_GET['postid'], PDO::PARAM_INT);
$stmt->execute();

$query = "DELETE FROM archiveData WHERE Owner = :ownerID AND ID = :postID";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
$stmt->bindParam(':postID', $dataID, PDO::PARAM_INT);
$stmt->execute();

$_SESSION['ToastMes'] = '記事を削除しました。';

header("Location: settings.php?page=posts");
?>
