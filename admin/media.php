<?php
session_start();

if(empty($_SESSION['userNo'])){
        header("Location: login.php");
}

                echo '<a class="waves-effect waves-light btn z-depth-0" href="settings.php"><i class="material-icons left">arrow_back</i>戻る</a><br>';
                echo '<h3>メディア管理</h3>';
                echo '<a class="waves-effect waves-light btn-large z-depth-0 modal-trigger" href="?page=upload" onclick=""><i class="material-icons left">cloud_upload</i>アップロード</a><br>';

		$query = "SELECT * FROM Media WHERE Owner = :UserID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':UserID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->execute();
		$mediaInfo = $stmt->fetchAll();

                if(count($mediaInfo) != 0){
			echo '<p>画像をクリックすると全体のプレビューや削除を行えます。</p>';
			echo '<div class="mediaGrid">';
                        foreach($mediaInfo as $data){
				echo '<a href="settings.php?page=mediaEdit&mediaid=' . htmlspecialchars($data['ID'], ENT_QUOTES, 'UTF-8')  . '"><div class="mediaBox">';
				echo '<img class="photoBox" src="./storage/' . htmlspecialchars($data['linkPath'], ENT_QUOTES, 'UTF-8') . '">';
				echo '</div></a>';
                        }
			echo '</div>';

                }else{
                        echo '<br><div class="row"><div class="col s12 m12 pink lighten-5"><h5 class="valign-wrapper"><i style="font-size: 2.5rem;" class="material-icons orange-text text-darken-5">warning</i><span style="color:#000;font-size:1.2rem;">';
                        echo '&nbsp;&nbspメディアが見つかりません。<br>&nbsp;&nbsp;上のアップロードボタンからメディアをアップロードできます。</span></h5></div></div>';
                }

?>




