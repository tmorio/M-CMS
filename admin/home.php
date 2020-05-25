<?php
session_start();

if(empty($_SESSION['userNo'])){
        header("Location: login.php");
}

                echo '<h3>管理ホーム</h3>';


                echo '
                        <div class="menuGrid">
                                <div class="col s12 m7">
                                        <div class="card horizontal">
                                                <div class="card-image">
                                                        <img src="img/menuPost.jpg">
                                                </div>
                                                <div class="card-stacked">
                                                        <div class="card-content">
                                                                <b>記事管理</b>
                                                                <p>記事の管理はこちら。</p>
                                                        </div>
                                                        <div class="card-action">
                                                                <a class="modal-trigger" href="?page=posts" style="color:#007bff">記事管理へ</a>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="col s12 m7">
                                        <div class="card horizontal">
                                                <div class="card-image">
                                                        <img src="img/menuUsers.jpg">
                                                </div>
                                                <div class="card-stacked">
                                                        <div class="card-content">
                                                                <b>ユーザー管理</b>
                                                                <p>複数人でブログを管理したり、アカウントに関する操作を行います。</p>
                                                        </div>
                                                        <div class="card-action">
                                                                <a href="?page=members" style="color:#007bff">ユーザー管理へ</a>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="col s12 m7">
                                        <div class="card horizontal">
                                                <div class="card-image">
                                                        <img src="img/menuMedia.jpg">
                                                </div>
                                                <div class="card-stacked">
                                                        <div class="card-content">
                                                                <b>メディア管理</b>
                                                                <p>今までアップロードした画像や動画の管理はこちら。</p>
                                                        </div>
                                                        <div class="card-action">
                                                                <a href="?page=media" style="color:#007bff">メディア管理へ</a>
                                                        </div>
                                                </div>
                                        </div>
                                </div>

                                <div class="col s12 m7">
                                        <div class="card horizontal">
                                                <div class="card-image">
                                                        <img src="img/menuKey.jpg">
                                                </div>
                                                <div class="card-stacked">
                                                        <div class="card-content">
                                                                <b>パスワード変更</b>
                                                                <p>管理画面のパスワードを変更できます。</p>
                                                        </div>
                                                        <div class="card-action">
                                                                <a class="modal-trigger" href="#changePassword" style="color:#007bff">変更する</a>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <div class="col s12 m7">
                                        <div class="card horizontal">
                                                <div class="card-image">
                                                        <img src="img/menuInfo.jpg">
                                                </div>
                                                <div class="card-stacked">
                                                        <div class="card-content">
                                                                <b>システム情報</b>
                                                                <p>バージョンやライブラリのライセンスの確認はこちら。</p>
                                                        </div>
                                                        <div class="card-action">
                                                                <a href="?page=version" style="color:#007bff">確認する</a>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div><br>
              ';
?>
