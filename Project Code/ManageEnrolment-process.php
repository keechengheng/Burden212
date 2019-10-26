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


//insert validation for drop logic
function dropSection(){
    $courseid = $_POST['courseid'];
    $section = $_POST['section'];
    $user = $_SESSION['userid'];
    $BiddingResultsDAO = new BiddingResultsDAO();
    $BiddingResultsDAO->dropSection($user,$courseid,$section);
    header("Location: ManageEnrolment.php");
}

dropSection();


?>
