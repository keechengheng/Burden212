<?php
require_once 'include/common.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] !== 'Admin'){
    header("Location: Login.php?error=Unauthorized Access");
    return;
}

$user = $_SESSION['userid'];

$roundDAO = new RoundDAO();
$round = $dao ->retrieveRound();

if ($round == 0)
{
    $message="The system is currently not open for bidding.";
}
if ($round == 1)
{
    $message="The system is currently on Round 1 of bidding.";
}
if ($round == 2)
{
    $message="The system is currently on Round 2 of bidding.";
}

?>

<html>
<body>
    <h1>Hello, <?= $user ?>, welcome back!</h1> 
    <h3><?= $message ?>  </h3>
    <h1>
        <a href='Bootstrap.php'>Bootstrap</a>
        <br><br>
        <a href='Settings.php'>Settings</a>
        <br><br>
        <a href='Logout.php'>Log Out</a>
    </h1>

</body>
</html>