<?php

require_once 'include/common.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] !== 'Admin'){
    header("Location: Login.php?error=Unauthorized Access");
    return;
}

?>

<form id='bootstrap-form' action="Bootstrap-process.php" method="post" enctype="multipart/form-data">
	Bootstrap file: 
	<input id='bootstrap-file' type="file" name="bootstrap-file"></br>
	<input type="submit" name="submit" value="Import">
</form>

