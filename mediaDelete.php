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

$query = "SELECT * FROM Media WHERE Owner = :ownerID AND ID = :mediaID";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
$stmt->bindParam(':mediaID', $_GET['mediaid'], PDO::PARAM_INT);
$stmt->execute();
$mediaFile = $stmt->fetch();
$mediaPath = "./storage/" . $mediaFile['linkPath'];

$query = "DELETE FROM Media WHERE Owner = :ownerID AND ID = :mediaID";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
$stmt->bindParam(':mediaID', $_GET['mediaid'], PDO::PARAM_INT);
$stmt->execute();

unlink($mediaPath);

$_SESSION['ToastMes'] = 'サーバー上から削除しました。';

header("Location: settings.php?page=media");
?>
