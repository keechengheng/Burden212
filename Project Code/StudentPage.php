<!-- Main Screen for Users -->
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

    $roundDAO = new RoundDAO();
    $round = $roundDAO ->retrieveRound();
    $_SESSION['round'] = $round;

    if ($round[0] == "0")
    {
        $message="The system is currently not open for bidding.";
    }
    if ($round[0] == "1")
    {
        $message="The system is currently on Round 1 of bidding.";
    }
    if ($round[0] == "2")
    {
        $message="The system is currently on Round 2 of bidding.";
    }

?>
<html>
<body>
    <h1>Hello <?= $user->name ?> from <?= $user->school ?>, welcome back!</h1> 
    <h3>You have <?= $user->edollar ?> credits left. What would you like to do? </h3>    
    <h3><?= $message ?>  </h3>

    <h1>
        <a href='BidMod.php'>Bid for Mods!</a>
        <br><br>
        <a href='Logout.php'>Log Out</a>
    </h1>

</body>
</html>