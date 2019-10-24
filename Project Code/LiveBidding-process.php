<?php
require_once 'include/common.php';

if(isset($_SESSION['userid']) && $_SESSION['userid'] != 'Admin'){
    $dao = new StudentDAO();
    $user = $dao->retrieve($_SESSION['userid']);
}
else{
    header("Location: Login.php?error=Unauthorized Access");
    return;
}

?>
