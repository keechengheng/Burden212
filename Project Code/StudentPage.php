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
    $_SESSION['status'] = $round[1];

    if ($round[0] == "0")
    {
        $message="The system is currently not open for bidding.";
        $statusMessage = 'Closed';
        $roundNumber = "0";
    }
    if ($round[0] == "1")
    {
        $roundNumber = "1";
        if  ($round[1]=="0"){
            $message="The system is currently closed and processing Round 1 bidding results.";
            $statusMessage = 'Closed';
            
        }
        else{
            $message="The system is currently opened for Round 1 of bidding.";
            $statusMessage = 'Open';
        }
        
    }
    if ($round[0] == "2")
    {
        $roundNumber = "2";
        if  ($round[1]=="0"){
            $message="The system is currently closed and processing Round 2 bidding results.";
            $statusMessage = "Closed";
        }
        else{
            $message="The system is currently opened for Round 2 of bidding.";
            $statusMessage = "Open";
        }
    }

    $BidDAO = new BidDAO();
    $retrieveBids = $BidDAO->retrieveBids($user->userid); //retrieve previous confirmed bids
    $currentAmountSpent = 0;

    foreach($retrieveBids as $element){
        //calculate total Amount Spent
        $currentAmountSpent = $currentAmountSpent + $element->amount;
    }
?>
<html>
<body>
    <h1>Hello <?= $user->name ?> from <?= $user->school ?>, welcome back!</h1> 
    <h3>You have <?= $user->edollar ?> credits left. What would you like to do? </h3>    
    <h3>You will be using <?= $currentAmountSpent ?> credits for this bidding round. </h3>
    <h3><?= $message ?>  </h3>
    </br>
    <h2> Round: <?= $roundNumber ?></h2>
    <h2> Status: <?= $statusMessage ?></h2>
    <br/>

    <h1>
        <a href='ManageBids.php'>Bid for Mods!</a>
        <br><br>
        <a href='ViewBid.php'>View my current Bids!</a>
        <br><br>
        <a href='Logout.php'>Log Out</a>
    </h1>

</body>
</html>