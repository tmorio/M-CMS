<?php

ini_set("session.cookie_secure", 1);

session_start();
session_regenerate_id(true);
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


if(!empty($_GET['postid'])){
	$query = "SELECT * FROM archiveList WHERE ID = :postID AND Owner = :nowOwner";
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':postID', $_GET['postid'], PDO::PARAM_INT);
	$stmt->bindParam(':nowOwner', $_SESSION['userNo'], PDO::PARAM_INT);
	$stmt->execute();
	$listID = $stmt->fetch();

	$query = "SELECT * FROM archiveData WHERE ID = :dataID AND Owner = :nowOwner";
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':dataID', $listID['dataID'], PDO::PARAM_INT);
	$stmt->bindParam(':nowOwner', $_SESSION['userNo'], PDO::PARAM_INT);
	$stmt->execute();
	$dataInfo = $stmt->fetch();

}
?>
<!doctype html>
<html style="background:#fff;">
	<head>
		<meta charset="UTF-8">
		<title>Morikapu CMS - Admin</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="css/style.css?">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>

	</head>
	<body style="background:#fff;">

	<div class="serviceHeader navbar-fixed">
		<nav>
			<div class="nav-wrapper black-text">
				<img class="logo-image" src="img/logo.png">
				<ul class="right">
					<a class="btn waves-effect waves-light z-depth-0" href="logout.php"><i class="material-icons left">exit_to_app</i>ログアウト</a>
					&thinsp;
				</ul>
			</div>
		</nav>
	</div>

	<div class="editorBoard">
			<div class="settingInfo" style="border: solid 1px #d3d3d3;border-style:none solid none none;overflow:auto;">
				<form method="post" name="postData" id="postData" action="publish.php">
					<a class="waves-effect waves-light btn red z-depth-0" href="settings.php?page=posts"><i class="material-icons left">arrow_back</i>変更を破棄して戻る</a><br>
					<h3>新規投稿・編集</h3>
					
					<br>
					
					<div class="input-field col s6">
						<input id="postTitle" name="title" type="text" class="validate" value="<?php echo htmlspecialchars($listID['Name'], ENT_QUOTES, 'UTF-8'); ?>">
						<label for="postTitle">タイトル</label>
					</div>
					<a class='dropdown-trigger btn-small z-depth-0' href='#' data-target='selHeadline'><i class="material-icons left">format_size</i>見出し</a>
					<a class="waves-effect waves-light btn-small z-depth-0" onclick="contentAdd(1)"><i class="material-icons left">format_bold</i>太字</a>&nbsp;
					<a class="waves-effect waves-light btn-small z-depth-0" onclick="contentAdd(4)"><i class="material-icons left">format_quote</i>引用</a>&nbsp;
					<a class="waves-effect waves-light btn-small z-depth-0" onclick="contentAdd(6)"><i class="material-icons left">format_italic</i>斜線</a>&nbsp;
					<a class="waves-effect waves-light btn-small z-depth-0" onclick="contentAdd(7)"><i class="material-icons left">format_color_text</i>文字色</a>&nbsp;
					<a class="waves-effect waves-light btn-small z-depth-0" onclick="contentAdd(9)"><i class="material-icons left">subdirectory_arrow_left</i>改行</a>
					<div style="margin:5px 0;"></div>
					<a class="waves-effect waves-light btn-small z-depth-0" onclick="contentAdd(3)"><i class="material-icons left">format_list_bulleted</i>リスト</a>&nbsp;
					<a class="waves-effect waves-light btn-small z-depth-0" onclick="contentAdd(2)"><i class="material-icons left">code</i>コード</a>&nbsp;
					<a class="waves-effect waves-light btn-small z-depth-0" onclick="contentAdd(5)"><i class="material-icons left">insert_link</i>リンク</a>&nbsp;
					<a class="waves-effect waves-light btn-small z-depth-0" onclick="contentAdd(8)"><i class="material-icons left">photo_camera</i>画像</a>
					<div style="margin:10px 0;"></div>
					<div class="s12" style="border:solid 1px #d3d3d3;padding:5px;">
						<div class="row" style="margin:0;">
							<div class="input-field col s12">
								<textarea id="postText" name="content" class="materialize-textarea"  style="height:50vh;"><?php echo htmlspecialchars($dataInfo['Text'], ENT_QUOTES, 'UTF-8'); ?></textarea>
								<label for="postText">本文（Markdown）</label>
							</div>
						</div>
					</div>
					<br>

				</form>
			</div>

			<div style="padding:15px 10px 0;overflow:auto;border: solid 1px #d3d3d3;border-style:none none none solid;">
				<div class="postMenu">
					<h3>投稿</h3>

					<?php
						if(empty($_GET['postid'])){
							echo '<a class="waves-effect waves-light btn orange darken-3 z-depth-0" onclick="post_click(0);"><i class="material-icons left">save</i>下書きに保存</a>';
							echo '<a class="waves-effect waves-light btn right blue z-depth-0" onclick="post_click(1);"><i class="material-icons right">send</i>投稿</a>';
						}else{
							echo '<a class="waves-effect waves-light btn orange darken-3 z-depth-0" onclick="update_click(0);"><i class="material-icons left">save</i>下書きにして保存</a>';
							echo '<a class="waves-effect waves-light btn right blue z-depth-0" onclick="update_click(1);"><i class="material-icons right">send</i>更新</a>';
						}
					?>
				</div>
				<div class="postMenu">
					<h3>画像の挿入</h3>
					<p>画像クリックで挿入します。</p>
				<?php
				$query = "SELECT * FROM Media WHERE Owner = :UserID";
				$stmt = $dbh->prepare($query);
				$stmt->bindParam(':UserID', $_SESSION['userNo'], PDO::PARAM_INT);
				$stmt->execute();
				$mediaInfo = $stmt->fetchAll();

				if(count($mediaInfo) != 0){
						echo '<div class="mediaMGrid">';
						foreach($mediaInfo as $data){
								echo '<a href="#" onclick="addServerMedia('  . "'" . htmlspecialchars($data['linkPath'], ENT_QUOTES, 'UTF-8') . "'" . ')"><div class="mediaMBox">';
								echo '<img class="photoMBox" src="./storage/' . htmlspecialchars($data['linkPath'], ENT_QUOTES, 'UTF-8') . '">';
								echo '</div></a>';
						}
						echo '</div>';

				}
				?>
				</div>
			</div>
			
	</div>
	<ul id='selHeadline' class='dropdown-content'>
		<li><a href="#!" onclick="headlineAdd(1)">見出し 1</a></li>
		<li><a href="#!" onclick="headlineAdd(2)">見出し 2</a></li>
		<li><a href="#!" onclick="headlineAdd(3)">見出し 3</a></li>
		<li><a href="#!" onclick="headlineAdd(4)">見出し 4</a></li>
		<li><a href="#!" onclick="headlineAdd(5)">見出し 5</a></li>
		<li><a href="#!" onclick="headlineAdd(6)">見出し 6</a></li>
	</ul>
		<footer>
			<script>
				function post_click(value) {
					var target = document.getElementById("postData");
					if (value == 0) {
						target.action = "publish.php?md=1";
					}
					else if (value == 1) {
						target.action = "publish.php?md=2";
					}
					postData.submit();
				}
				
				function update_click(value) {
					var target = document.getElementById("postData");
					if (value == 0) {
						target.action = "publish.php?md=3&postid=<?php echo htmlspecialchars($_GET['postid'], ENT_QUOTES, 'UTF-8'); ?>";
					}
					else if (value == 1) {
						target.action = "publish.php?md=4&postid=<?php echo htmlspecialchars($_GET['postid'], ENT_QUOTES, 'UTF-8'); ?>";
					}
					postData.submit();
				}

				function contentAdd(value) {
					var content = document.getElementById('postText');
					var insertStringA = "";
					var insertStringB = "";
					switch(value){
						case 1:
							insertStringA = "** ";
							insertStringB = " **";
							break;
						case 2:
							insertStringA = "\n```\n";
							insertStringB = "\n```";
							break;
						case 3:
							insertStringA = "\n- 要素1\n- 要素2\n- 要素3";
							break;
						case 4:
							insertStringA = "> ";
							insertStringB = "\n";
							break;
						case 5:
							insertStringA = "[リンクづける文字列](URL)\n";
							break;
						case 6:
							insertStringA = "* ";
							insertStringB = " *";
							break;
						case 7:
							insertStringA = '<font color="16進数で色">';
							insertStringB = '</font>';
							break;
                                                case 8:
                                                        insertStringA = '\n![代替テキスト](画像のURL "画像のタイトル")';
                                                        break;
                                                case 9:
                                                        insertStringB = '  \n';
                                                        break;

					}
					content.value = content.value.substr(0, content.selectionStart) + insertStringA + content.value.substr(content.selectionStart, content.selectionEnd - content.selectionStart) + insertStringB + content.value.substr(content.selectionEnd);
				}

				function addServerMedia(urlPath) {
					var content = document.getElementById('postText');
					content.value = content.value.substr(0, content.selectionStart) + '\n![' + urlPath + '](storage/' + urlPath + ' "' + urlPath + '")' + content.value.substr(content.selectionStart, content.selectionEnd - content.selectionStart) + "\n" + content.value.substr(content.selectionEnd);
				}

                                function headlineAdd(value) {
                                        var content = document.getElementById('postText');
                                        var insertStringA = "";
					var insertStringB = "";
                                        switch(value){
                                                case 1:
                                                        insertStringA = "\n# ";
                                                        break;
                                                case 2:
                                                        insertStringA = "\n##";
                                                        break;
                                                case 3:
                                                        insertStringA = "\n### ";
                                                        break;
                                                case 4:
                                                        insertStringA = "\n#### ";
                                                        break;
                                                case 5:
                                                        insertStringA = "\n##### ";
                                                        break;
                                                case 6:
                                                        insertStringA = "\n####### ";
                                                        break;

                                        }
					content.value = content.value.substr(0, content.selectionStart) + insertStringA + content.value.substr(content.selectionStart, content.selectionEnd - content.selectionStart) + insertStringB + content.value.substr(content.selectionEnd);
                                }


				$(document).ready(function(){
					$('.modal').modal();
				});
				$(document).ready(function(){
					$('select').formSelect();
				});
				$('.dropdown-trigger').dropdown();
				$(document).ready(function() {
					$('textarea#postText').characterCounter();
				});
			</script>
		</footer>
</body>

