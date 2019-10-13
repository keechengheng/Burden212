<?php

require_once 'include/bootstrap.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] !== 'Admin'){
    header("Location: Login.php?error=Unauthorized Access");
    return;
}

echo '<html>
<body>
    <form action="AdminPage.php">
        <input type="submit" name="submit" value="Back to Main Page">
    </form>
</body>
</html>';

doBootstrap();

?>

