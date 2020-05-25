<?php
session_start();
session_destroy();
echo "ログアウト処理中です...しばらくお待ちください...";
header("Location: login.php");

?>
