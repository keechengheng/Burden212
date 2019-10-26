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
$courseid = $_POST['courseid'];
$section = $_POST['section'];

//insert validation for drop logic
function dropSection(){
    $BiddingResultsDAO = new BiddingResultsDAO();
    $BiddingResultsDAO->dropSection($user->userid,$courseid,$section);
    header("Location: ManageEnrolment.php");
}

dropSection();


?>
