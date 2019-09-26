<?php
require_once 'include/common.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] !== 'Admin'){
    header("Location: Login.php?error=Unauthorized Access");
    return;
}

$user = $_SESSION['userid'];

?>

<html>
<body>
    <h1>Hello, <?= $user ?>, welcome back!</h1> 
    <h1>
        <a href='Bootstrap.php'>Bootstrap</a>
        <br><br>
        <a href='Settings.php'>Settings</a>
        <br><br>
        <a href='Logout.php'>Log Out</a>
    </h1>

</body>
</html>