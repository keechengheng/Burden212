<!-- Main Screen for Users -->
<?php
require_once 'include/common.php';


if(isset($_SESSION['userid'])){
    $dao = new StudentDAO();
    $user = $dao->retrieve($_SESSION['userid']);
}
else{
    header("Location: Login.php?error=Missing login session");
    return;
}

var_dump($user);

#Non-admin


#Admin


?>
<html>
<body>
    <h1>Hello, <?= $user->name ?> from <?= $user->school ?>, welcome back!</h1> 
    <h3>You have <?= $user->edollar ?> credits left. What would you like to do? </h3>    

    <h1>
        <a href='Logout.php'>Log Out</a>
    </h1>

</body>
</html>