<?php
require_once 'include/common.php';
// require_once 'include/token.php';

$error = '';

if  (isset($_SESSION['userid'])) {
	session_unset();
}

if ( isset($_GET['error']) ) {
    $error = $_GET['error'];
} elseif ( isset($_POST['userid']) && isset($_POST['password']) ) {
    $username = $_POST['userid'];
    $password = $_POST['password'];
    
    $dao = new StudentDAO();


    if($username !== 'Admin'){
        $user = $dao->retrieve($username);

        if ( $user != null ){
            if ( $user->authenticate($password) ){
                $_SESSION['userid'] = $username; 
                header("Location: StudentPage.php");
                return;
            }
            else{
                $error = 'Incorrect Password!';
            }
        }
        else{
            $error = 'Incorrect UserID!';
        }
    }
    else{
        $user = new Student();
        if ( $user->adminLogin($password) ) {
            $_SESSION['userid'] = $username; 
            header("Location: AdminPage.php");
            return;

        } else {
            $error = 'Incorrect Password!';
        }
    }



}

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Merlion University | Login</title>

    <link href="css\bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome\css\font-awesome.css" rel="stylesheet">

    <link href="css\animate.css" rel="stylesheet">
    <link href="css\style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">MU+</h1>

            </div>
            <h3>Merlion University</h3>
            <p>Welcome to BIOS (Bidding Online System).
            </p>
            <p>Login to see it in action.</p>
            <form class="m-t" role="form" method='POST' action='Login.php'>
                <div class="form-group">
                    <input type="text" name="userid" class="form-control" placeholder="Username" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

            </form>
            <p style="color:red">
            <?=$error?>

        </p>
            <p class="m-t"> <small>G8T6 - Ellesse101@ &copy; 2019</small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js\jquery-3.1.1.min.js"></script>
    <script src="js\bootstrap.min.js"></script>

</body>

</html>
