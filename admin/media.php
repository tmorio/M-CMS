<?php
session_start();

if(empty($_SESSION['userNo'])){
        header("Location: login.php");
}

                echo '<a class="waves-effect waves-light btn z-depth-0" href="settings.php"><i class="material-icons left">arrow_back</i>戻る</a><br>';
                echo '<h3>メディア管理</h3>';
                echo '<a class="waves-effect waves-light btn-large z-depth-0 modal-trigger" href="#addUser" onclick=""><i class="material-icons left">cloud_upload</i>アップロード</a><br><br>';

		$query = "SELECT * FROM archiveList WHERE Owner = :UserID";
		$stmt = $dbh->prepare($query);
		$stmt->bindParam(':UserID', $_SESSION['userNo'], PDO::PARAM_INT);
		$stmt->execute();

                if(count($researchUsers) != 0){
                        echo '<ul class="collection">';

                        foreach($researchUsers as $data){
                                echo '<li class="collection-item avatar">';

                                if(!empty($data['PhotoName'])){
                                        echo '<img src="img/users/' . htmlspecialchars($data['PhotoName'], ENT_QUOTES, 'UTF-8') . '.jpg" alt="" class="circle">';
                                }else{
                                        echo '<img src="img/default.jpg" alt="" class="circle">';
                                }

                                echo '<span class="title">' . htmlspecialchars($data['Name'], ENT_QUOTES, 'UTF-8') . " <br>" . htmlspecialchars($data['UserID'], ENT_QUOTES, 'UTF-8') . " - " . htmlspecialchars($data['Email'], ENT_QUOTES, 'UTF-8')  . '</span>';

                                echo '<span class="right">';
                                echo '<a class="waves-effect waves-light btn" href="?page=info&user=' . $data['ID'] . '"><i class="material-icons left">edit</i>詳細・編集</a>';
                                echo '</span>';
                                echo '<br><br>';
                                echo '</li>';

                        }

                        echo '</ul>';
                }else{
                        echo '<div class="row"><div class="col s12 m12 pink lighten-5"><h5 class="valign-wrapper"><i style="font-size: 2.5rem;" class="material-icons orange-text text-darken-5">warning</i><span style="color:#000;font-size:1.2rem;">';
                        echo '&nbsp;&nbspメディアが見つかりません。<br>&nbsp;&nbsp;上のアップロードボタンからメディアをアップロードできます。</span></h5></div></div>';
                }

?>




