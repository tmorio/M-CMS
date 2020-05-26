<?php
session_start();

if(empty($_SESSION['userNo'])){
        header("Location: login.php");
}

                $query = "SELECT * FROM Media WHERE Owner = :UserID AND ID = :mediaid";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':UserID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->bindParam(':mediaid', $_GET['mediaid'], PDO::PARAM_INT);
                $stmt->execute();
                $mediaInfo = $stmt->fetch();

                echo '<a class="waves-effect waves-light btn-large z-depth-0" href="settings.php?page=media"><i class="material-icons left">arrow_back</i>戻る</a>&nbsp;&nbsp;';
		echo '<a class="waves-effect waves-light btn-large red  z-depth-0" href="mediaDelete.php?mediaid=' . htmlspecialchars($mediaInfo['ID'], ENT_QUOTES, 'UTF-8')  . '"><i class="material-icons left">delete</i>画像を削除する</a><br><br>';
                echo  '<h3>' . htmlspecialchars($mediaInfo['linkPath'], ENT_QUOTES, 'UTF-8') . '</h3><br><h5>プレビュー</h5><br><br>';

		echo '<img class="photoCheck" src="./storage/' . htmlspecialchars($mediaInfo['linkPath'], ENT_QUOTES, 'UTF-8') . '">';

?>




