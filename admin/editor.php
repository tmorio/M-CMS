<?php
session_start();

if($_SESSION['userAdmin'] != 1){
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
if(!empty($_GET['postid'])){
    $query = "SELECT * FROM archiveList WHERE Owner = :ownerID AND ID = :postID";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
    $stmt->bindParam(':postID', $_GET['postid'], PDO::PARAM_INT);
    $stmt->execute();
    $dataInfo = $stmt->fetch();
    $dataID = $dataInfo['dataID'];
    $query = "SELECT * FROM archiveData WHERE Owner = :ownerID AND ID = :postID";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':ownerID', $_SESSION['userNo'], PDO::PARAM_INT);
    $stmt->bindParam(':postID', $dataID, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch();
}
?>

<!doctype html>
<html>
    <head>
        <?php require_once('./meta.php'); ?>
        <title>Java学習ポータル</title>
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    </head>
    <header>
            <?php require_once('./header.php'); ?>
    </header>
    <body>
        <div class="titleBack">
            <div class="whiteColor">
                <div class="topContent">
                    <div class="headObject">
                        <span class="subContent" style="vertical-align:middle;">記事の作成・編集</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="objectMargin">
                <form action="
		<?php
		    if(!empty($dataInfo['Name'])){
			echo 'update.php?postid=' . $_GET['postid'];
		    }else{
			echo 'publish.php';
		    }
		?>
		" method="post">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="title" name="title" type="text" class="validate" value="<?php
				if(!empty($dataInfo['Name'])){ echo $dataInfo['Name']; }
			    ?>">
                            <label for="title">タイトル</label>
                        </div>
                    </div>
		    <b>Markdown書式をご利用いただけます。</b><br>
                    <textarea name="details" id="markdownEditor" style="height: 40vh;"><?php if(!empty($data['Text'])){ echo $data['Text']; }?></textarea>
                    <br><br>
                    <button class="btn right" type="submit"><i class="material-icons left">send</i>投稿・更新</button>
                    <a class="btn" href="listadmin.php" rel="noopner noreferrer"><i class="material-icons left">arrow_back</i>記事管理画面へ戻る</a>
                </form>
            </div>
        </div>

    </body>
    <footer>
        <?php require_once('./footer.php'); ?>
    </footer>
</html>

