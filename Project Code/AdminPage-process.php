<?php
require_once 'include/common.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] !== 'Admin'){
    header("Location: Login.php?error=Unauthorized Access");
    return;
}

$user = $_SESSION['userid'];
$round = $_SESSION['round'];
$status = $_SESSION['status'];
$roundDAO = new RoundDAO();
$sectionDAO = new SectionDAO();

if ($round == "1" && $status == "1"){
    //insert Round 1 clearing logic here
    $roundDAO ->closeRoundOne();
}
else if ($round == "1" && $status == "0" ){
    $roundDAO ->activateRoundTwo();
    $sectionDAO ->prepareRoundTwo();
}
else  if ($round == "2" && $status == "1" ){
    $roundDAO ->closeBid();
}
else{
    header("Location: AdminPage.php?error=Phase is Wrong");
    return;
}

header("Location: AdminPage.php");

?>
