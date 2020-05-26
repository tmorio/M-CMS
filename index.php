<?php 
session_start();

require_once('./myid.php');

$strcode = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8mb4'");
try {
    $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_ID, DB_PASS, $strcode);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

$query = "SELECT * FROM archiveList";
$stmt = $dbh->prepare($query);
$stmt->execute();
?>

<!doctype html>
<html>
    <head>
        <title><?php echo BLOG_NAME; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="css/style2.css">
        <link rel="stylesheet" type="text/css" href="css/materialize.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>

    </head>
    <header>
        <div class="navbar-fixed">
            <nav class="blue">
                <div class="nav-wrapper">
                    <!-- <a href="./index.php" class="brand-logo"><img src="img/portallogo.png" class="logo-img"></a> -->
		    &nbsp;<?php echo BLOG_NAME; ?>
                    <a href="#" data-target="sideNavMobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                    <ul class="right hide-on-med-and-down" style="padding: 0 1vw 0 0;">
                        <li><a href="index.php">Home</a></li>
                        &nbsp;&nbsp;
                    </ul>
                </div>
            </nav>
        </div>
        <ul class="sidenav" id="sideNavMobile">
            <li><a href="index.php" class="waves-effect"><i class="material-icons">home</i>ホーム</a></li>
        </ul>

    </header>
    <body>
        <div class="titleBack">
            <div class="whiteColor">
                <div class="topContent">
                    <div class="headObject">
                        <span class="subContent" style="vertical-align:middle;">記事一覧</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
                <div class="objectMargin">
            <?php
                $counter = 0;
                foreach($stmt as $data){
                    if(empty($data['ID'])){
                        break;
                    }
                    if($counter == 0){
                        echo '<ul class="collapsible">';
                    }
                    echo '<li><a href="read.php?id=' . $data['ID']  . '"><div class="collapsible-header">';
                    echo htmlspecialchars($data['Name'], ENT_QUOTES, 'UTF-8');
                    echo '</div></a></li>';
                    $counter++;
                }
                echo '</ul>';
            ?>
            </div>
        </div>
    </body>
    <footer>
    </footer>


