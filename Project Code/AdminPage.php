<?php
require_once 'include/common.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] !== 'Admin'){
    header("Location: Login.php?error=Unauthorized Access");
    return;
}

$user = $_SESSION['userid'];

$roundDAO = new RoundDAO();
$round = $roundDAO ->retrieveRound();
$_SESSION['round'] = $round[0];
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


?>

<html>
<body>
    <h1>Hello <?= $user ?>, welcome back!</h1> 
    <h3> <?= $message ?></h1> 
    </br>
    <h2> Round: <?= $roundNumber ?></h2>
    <h2> Status: <?= $statusMessage ?></h2>
    <br/>
    <form id='admin-form' action="AdminPage-process.php" method="post" enctype="multipart/form-data">
	Next Phase: 
    </br>
	<input type="submit" name="submit" value="Next Phase">
    </form>
    <br/>
    </br>
    <h1>
        <a href='Bootstrap.php'>Bootstrap</a>
        <br><br>
        <a href='Settings.php'>Settings</a>
        <br><br>
        <a href='Logout.php'>Log Out</a>
    </h1>



</body>
</html>