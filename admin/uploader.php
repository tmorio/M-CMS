<?php
require_once('./myid.php');
session_start();

if(empty($_SESSION['userNo'])){
     header("Location: login.php");
     exit(0);
}

?>
    <a class="waves-effect waves-light btn z-depth-0" href="settings.php?page=media"><i class="material-icons left">arrow_back</i>戻る</a><br>
    <h3>メディアのアップロード</h3>
    <br>
    <form class="col s12 m12 card blue-grey lighten-5 z-depth-0" action="uploadCheck.php" enctype="multipart/form-data" method="post">
        <div class="card-content grey-text text-darken-4">
            <div class="row">
                  <div class="file-field input-field">
                    <div class="btn z-depth-0">
                        <span>ファイルを選択</span>
                        <input type="file" name="iconImg" id="iconImg" accept="image/png, image/jpeg">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="png, jpgのみ選択できます。">
                    </div>
                    <div class="center" id="iconPreview" style="display:none;">
                        <br><p>プレビュー</p><br>
                        <img id="preview" style="height:auto;width:10vw;max-height:50vh;"><br>
                    </div>
                </div>
                <button class="btn waves-effect waves-light right z-depth-0" type="submit">アップロード</button>
            </div>
        </div>
    </form>

<footer>
    <script>
        $(function(){
            $('#iconImg').change(function(e){
                var file = e.target.files[0];
                var reader = new FileReader();
                if(file.type.indexOf("image") < 0){
                    alert("画像ファイル(png, jpg)を指定してください。");
                    return false;
                }
                reader.onload = (function(file){
                    return function(e){
                        $("#preview").attr("src", e.target.result);
                        $("#preview").attr("title", file.name);
                        document.getElementById("iconPreview").style.display="block";
                    };
                })(file);
                reader.readAsDataURL(file);
            });
        });
    </script>
</footer>

