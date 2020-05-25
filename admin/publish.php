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

$query = "INSERT INTO archiveList (Owner, Name, dataID, CreatedAt) VALUES (:ownerID, :postTitle, :dataID, :createTime)";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
$stmt->bindParam(':postTitle',  $_POST['title']  , PDO::PARAM_STR);
$stmt->bindParam(':dataID', $archiveID, PDO::PARAM_INT);
$stmt->bindParam(':createTime', date("Y-m-d H:i:s")  , PDO::PARAM_STR);
$stmt->execute();

header("Location: posts.php");
?>
