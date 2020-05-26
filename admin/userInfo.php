<?php
                $query = "SELECT * FROM Users WHERE ID = :userID";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':userID', $_GET['userid'], PDO::PARAM_INT);
                $stmt->execute();
                $userInfo = $stmt->fetch();


                if(empty($userInfo['ID'])){
                        echo '値の受け渡しに失敗しました。';
                        exit(1);
                }
                echo '<a class="waves-effect waves-light btn" href="?page=members"><i class="material-icons left">arrow_back</i>戻る</a><br><br>';
                echo '<h3>ユーザー情報</h3>';
                echo '<div style="display: flex;">';
                if(empty($userInfo['PhotoName'])){
                        echo '<img src="img/default.jpg" class="iconBox">';
                }else{
                        echo '<img src="img/users/' . htmlspecialchars($userInfo['PhotoName'], ENT_QUOTES, 'UTF-8') . '.jpg" class="iconBox">';
                }
                echo '<div class="infoName"><span style="font-size:2.5rem;">' . htmlspecialchars($userInfo['Name'], ENT_QUOTES, 'UTF-8') . '</span><br><span style="font-size:1.5rem;">ログインID: ' . htmlspecialchars($userInfo['UserID'], ENT_QUOTES, 'UTF-8') . '<br>';
		echo '</span><span style="font-size:1.5rem;">E-mail: ' . htmlspecialchars($userInfo['Email'], ENT_QUOTES, 'UTF-8') . '<br>';
                echo '</span></div>';
                echo '</div><br>';
                echo '<a class="waves-effect waves-light btn modal-trigger blue" href="#nowAccount" onclick="selectUser(' . $userInfo['ID'] . ')"><i class="material-icons left">lock</i>パスワード変更</a>';
                echo '&nbsp;';
                echo '<a class="waves-effect waves-light btn modal-trigger blue" href="#editUser" onclick="editUser(' . $userInfo['ID'] . ',\'' . $userInfo['Name'] . '\',\'' . $userInfo['studentID'] . '\')"><i class="material-icons left">mail</i>メールアドレスを変更</a>';
                echo '&nbsp;';
		if($userInfo['ID'] == 1){
                echo '<a class="waves-effect waves-light btn modal-trigger red right disabled"><i class="material-icons left">close</i>マスターは削除できません</a><br><br>';
		}else{
		echo '<a class="waves-effect waves-light btn modal-trigger red right" href="#delUser" onclick="delUser(' . $userInfo['ID'] . ')"><i class="material-icons left">close</i>ユーザーを削除</a><br><br>';
		}

		echo '<h5>ユーザーの記事</h5>';
		$query = "SELECT * FROM archiveList WHERE Owner = :UserID";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':UserID', $_GET['userid'], PDO::PARAM_INT);
                $stmt->execute();
                $posts = $stmt->fetchAll();

                if(count($posts) != 0){
                        foreach($posts as $data){
                                if(empty($data['ID'])){
                                        break;
                                }
                                if($counter == 0){
                                        echo '<ul class="collapsible z-depth-0">';
                                }
                                if($counter % 2 == 0){
                                        echo '<li><div class="collapsible-header">';
                                }else{
                                        echo '<li><div class="collapsible-header" style="background-color:#eee;">';
                                }
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
                        echo '&nbsp;&nbspまだ記事が投稿されていません。</span></h5></div></div>';
                }


?>
