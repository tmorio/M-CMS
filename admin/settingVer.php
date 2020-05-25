<?php

session_start();

if(empty($_SESSION['userNo'])){
        header("Location: login.php");
}

?>

<a class="waves-effect waves-light btn" href="settings.php"><i class="material-icons left">arrow_back</i>戻る</a><br>
<div class="container" style="color:#000;">
	<h3>Version Infomation</h3>
	<div class="valign-wrapper">
		<img class="logo-image" src="img/logo.png"><br>
		<h4>Ver 1.0</h4>
	</div><br>
	<b>
		Release: 2020/5/20<br>
		Update Channel: Main - LivePatch<br>
		Organization Name: <?php echo LAB_NAME; ?><br>
		License ID: 20248BW-BDUWUG8236NDBWIAKK82621<br><br>
	</b>
	<h3>Update Infomation</h3>
	<h3>License</h3>
		Copyright <?php echo date('Y'); ?> Takenori Morio<br>
		<p>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
		to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
		and to permit persons to whom the Software is furnished to do so, subject to the following conditions:</p>
		<p>The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.</p>
		<p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
		FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. <br>
			IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, 
		ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p>
	<h3>Third Party License</h3>
		<h5>Materialize CSS</h5>
		<p>The MIT License (MIT)</p>
	<p>Copyright (c) 2014-<?php echo date('Y'); ?> Materialize</p>
		<p>Permission is hereby granted, free of charge, to any person obtaining a copy
		of this software and associated documentation files (the "Software"), to deal
		in the Software without restriction, including without limitation the rights
		to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
		copies of the Software, and to permit persons to whom the Software is
		furnished to do so, subject to the following conditions:</p>
		<p>The above copyright notice and this permission notice shall be included in all
		copies or substantial portions of the Software.</p>
		<p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
		IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
		FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
		AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
		LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
		OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
		SOFTWARE.</p>
	<br>
</div>

