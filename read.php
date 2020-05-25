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
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" type="text/css" href="css/materialize.min.css">
                <link rel="stylesheet" type="text/css" href="css/style.css?">
                <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
                <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
                <script type="text/javascript" src="js/materialize.min.js"></script>

        	<title><?php echo htmlspecialchars($listInfo['Name'], ENT_QUOTES, 'UTF-8'); ?> - もりかぷの日記</title>
		<script src="js/marked.min.js"></script>

    </head>
    <body>
        <div class="container">
	    <span style="font-size:2.0rem;"><?php echo htmlspecialchars($listInfo['Name'], ENT_QUOTES, 'UTF-8'); ?></span>
            <div class="objectMargin">
		<div id="markdownOutput"></div>
            </div>
        </div>
        <div style="display: none;">
            <textarea id="planeTextArea"><?php echo $dataInfo['Text']; ?></textarea>
        </div>

    </body>
    <footer>
	<script>
	    document.getElementById("markdownOutput").innerHTML = marked(document.getElementById("planeTextArea").innerHTML);
	</script>
    </footer>
</html>
