<?php 
session_start();

if(empty($_GET['id'])){
    header("Location: ./list.php");
    exit(0);
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

$query = "SELECT * FROM archiveList WHERE ID = :postID";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':postID', $_GET['id'], PDO::PARAM_STR);
$stmt->execute();
$listInfo = $stmt->fetch();

$query = "SELECT * FROM archiveData WHERE ID = :dataID";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':dataID', $listInfo['dataID'], PDO::PARAM_INT);
$stmt->execute();
$dataInfo = $stmt->fetch();
?>

<!doctype html>
<html>
    <head>
        <?php require_once('./meta.php'); ?>
        <title>Java学習ポータル</title>
	<script src="https://cdn.rawgit.com/chjj/marked/master/marked.min.js"></script>
    </head>
    <header>
        <?php require_once('./header.php'); ?>
    </header>
    <body>
        <div class="titleBack">
            <div class="whiteColor">
                <div class="topContent">
                    <div class="headObject">
                        <span class="subContent" style="vertical-align:middle;"><?php echo htmlspecialchars($listInfo['Name'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="objectMargin">
		<div id="markdownOutput"></div>
            </div>
        </div>
	<div style="display: none;">
	    <textarea id="planeTextArea"><?php echo $dataInfo['Text']; ?></textarea>
	</div>
    </body>
    <footer>
        <?php require_once('./footer.php'); ?>
	<script>
	    document.getElementById("markdownOutput").innerHTML = marked(document.getElementById("planeTextArea").innerHTML);
	</script>
    </footer>
</html>
