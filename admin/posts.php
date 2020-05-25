<?php
session_start();

if(empty($_SESSION['userNo'])){
        header("Location: login.php");
}

                echo '<a class="waves-effect waves-light btn z-depth-0" href="settings.php"><i class="material-icons left">arrow_back</i>戻る</a><br>';
                echo '<h3>記事管理</h3>';
                echo '<a class="waves-effect waves-light btn-large z-depth-0" href="editor.php" onclick=""><i class="material-icons left">add</i>新規投稿</a><br><br>';
		$query = "SELECT * FROM archiveList WHERE Owner = :UserID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':UserID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->execute();
		$posts = $stmt->fetchAll();

                if(count($posts) != 0){
			foreach($posts as $data){
				if(empty($data['ID'])){
					break;
				}
				if($counter == 0){
					echo '<ul class="collapsible">';
				}
				echo '<li><div class="collapsible-header">';
				echo htmlspecialchars($data['Name'], ENT_QUOTES, 'UTF-8') . "<br>";
                                if($data['Draft'] == 1){
                                        echo '下書き - ';
                                }
				echo "作成日：" . htmlspecialchars($data['CreatedAt'], ENT_QUOTES, 'UTF-8');
				echo '<div style="margin: 0 0 0 auto;">';
				if($data['Draft'] != 1){ echo '<a class="waves-effect waves-light btn blue z-depth-0" target="_blank" href="read.php?id=' . $data['dataID'] . '"><i class="material-icons left">open_in_new</i>開く</a>&nbsp;&nbsp;';}
				echo '<a class="waves-effect waves-light btn z-depth-0" href="editor.php?postid=' . $data['dataID'] . '"><i class="material-icons left">edit</i>編集</a>&nbsp;&nbsp;';
				echo '<a class="waves-effect waves-light btn red z-depth-0" href="delete.php?postid=' . $data['dataID']  . '"><i class="material-icons left">delete</i>削除</a>';
				echo '</div>';
				echo '</div></li>';
				$counter++;
			}
			echo '</ul>';
                }else{
                        echo '<div class="row"><div class="col s12 m12 pink lighten-5"><h5 class="valign-wrapper"><i style="font-size: 2.5rem;" class="material-icons orange-text text-darken-5">warning</i><span style="color:#000;font-size:1.2rem;">';
                        echo '&nbsp;&nbspまだ記事が投稿されていません。<br>&nbsp;&nbsp;上の新規投稿ボタンから記事を投稿しましょう！</span></h5></div></div>';
                }

?>



