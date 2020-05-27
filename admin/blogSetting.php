<?php
session_start();

if(empty($_SESSION['userNo'])){
        header("Location: login.php");
}

                echo '<a class="waves-effect waves-light btn z-depth-0" href="settings.php"><i class="material-icons left">arrow_back</i>戻る</a><br>';
                echo '<h3>ブログ設定</h3><br>';
?>


                                <form method="post"  name="blogSetForm" action="doSetting.php?Setup=blogSet">
                                        <div class="input-field col s6">
                                                <input id="newBlogName" name="newBlogName" type="text" class="validate" value="<?php echo htmlspecialchars(BLOG_NAME, ENT_QUOTES, 'UTF-8'); ?>">
                                                <label for="newBlogName">ブログの名前</label>
                                        </div>
				<a class="waves-effect waves-light btn right blue z-depth-0" onclick="blogSetForm.submit();"><i class="material-icons right">send</i>設定更新</a>
				</form>
