<?php

require_once 'include/bootstrap.php';

if(!isset($_SESSION['userid'])){
    header("Location: Login.php?error=Unauthorized Access");
    return;
}

function checkMinBid(){
    $courseid = $_POST['courseid'];
    $section = $_POST['section'];
    $amount = $_POST['amount'];
    $sectionDAO = new SectionDAO();
    $selectedSection = $sectionDAO->retrieveByCourseSection($courseid,$section);
    if($amount >= $selectedSection->minbid){
        round2InsertManualBid();
    }
    else{
        header("Location: LiveBidding.php?error=Bid Value Too Low");
        return;
    }
}

function updateMinBid($courseid,$section,$amount){
    //retrieve current minbid
    $sectionDAO = new SectionDAO();
    $retrievedBid = $sectionDAO->retrieveByCourseSection($courseid,$section);  
    $currentMinBid = $retrievedBid ->minbid;
    $bidDAO = new BidDAO();
    $consolidatedBids = $bidDAO->round2SlotsRemaining($courseid,$section,$retrievedBid->size);
    $slotsRemain = ($retrievedBid->size) - count($consolidatedBids);
    if ($slotsRemain == 0 && $amount+1 >$currentMinBid){
        $sectionDAO ->updateMinBid($courseid,$section,$amount+1);
    }
}

function round2InsertManualBid(){
    $BidDAO = new BidDAO();
    $CourseDAO = new CourseDAO();
    $StudentDAO = new StudentDAO();
    $PrerequisiteDAO = new PrerequisiteDAO();
    $SectionDAO = new SectionDAO();
    $CourseCompletedDAO = new CourseCompletedDAO();
    $roundDAO = new RoundDAO();
    $round = $roundDAO ->retrieveRound();
   
    $encountered_Error = array();
    $encountered_Error['message'] = array();
    $user = $_SESSION['userid'];
    $courseid = $_POST['courseid'];
    $section = $_POST['section'];
    $amount = $_POST['amount'];

    if (isset($_POST['courseid']) && isset($_POST['section']) && isset($_POST['amount'])){
        
        //check input amount
        if ((!is_numeric($amount) || strlen(substr(strrchr($amount, "."), 1)) > 2|| $amount<10))
        {
            array_push ($encountered_Error['message'],'invalid amount');
        }

        //check invalid course 
        if (($CourseDAO -> retrieveCourse($courseid))==false ){
            array_push ($encountered_Error['message'],'invalid course');
        }
        else
        {    
            //check invalid section 
            if(!in_array($section, $SectionDAO->retrieveByCourse($courseid)))
            {
                array_push ($encountered_Error['message'],'invalid section');
            }
        }

        $retrieveBids = $BidDAO->retrieveBids($user);

        //if matching course, skips exam timetable check
        if (($BidDAO ->checkForSimilarCourseBid($user,$courseid))==false){
            //"class timetable clash"
            foreach($retrieveBids as $element){
                //retrieve bid's day and start time 
                $biddedSection = $SectionDAO ->retrieveByCourseSection($element->courseid,$element->section);
                //retrieve day and start time base on section and course
                $retrieveSection = $SectionDAO ->retrieveByCourseSection($courseid,$section);
                if(($biddedSection->day == $retrieveSection->day) && ($biddedSection->start == $retrieveSection->start)){
                //it clashes
                array_push ($encountered_Error['message'],'class timetable clash');
                }
            }

            //"exam timetable clash" 
            foreach($retrieveBids as $element){
                //retrieve bid's day and start time 
                $biddedExam = $CourseDAO ->retrieveExam($element->courseid);
                //retrieve day and start time base on section and course
                $retrieveExam = $CourseDAO ->retrieveExam($courseid);
                if(($biddedExam->exam_date == $retrieveExam->exam_date) && ($biddedExam->exam_start == $retrieveExam->exam_start)){
                //it clashes
                array_push ($encountered_Error['message'],'exam timetable clash');
                }
            }
        }

        //"incomplete prerequisites" 
        $preReqs = $PrerequisiteDAO->retrievePrerequisites($courseid);
        $trig = "0";
        if(!isEmpty($preReqs))
        {
            $coursesCompleted = $CourseCompletedDAO->retrieveCourseCompleted($user);
            foreach($preReqs as $element){
                if(!in_array($element,$coursesCompleted)){
                    $trig = "1";
                }
            }
            if ($trig == "1"){
                array_push ($encountered_Error['message'],'incomplete prerequisites');
            }
        }

        //"course completed"
        $coursesCompleted = $CourseCompletedDAO->retrieveCourseCompleted($user);
        if(in_array($courseid,$coursesCompleted)){
            array_push ($encountered_Error['message'],'course completed');
        }
        
        //"section limit reached"
        if (count($BidDAO->retrieveBids($user))>=5)
        {
            array_push ($encountered_Error['message'],'section limit reached');
        }

        if (isEmpty($encountered_Error['message'])){
            //"not enough e-dollar" 
            $retrieveUser = $StudentDAO->retrieve($user); //check current e-dollar amount
            $currentAmountSpent = 0;
            $trig = "0";
            $previousBid = 0;
            //calculate current Amount Spent
            foreach($retrieveBids as $element){
                //check if its an existing bid
                if ($element->courseid == $courseid)
                {
                    //bidded for the same course previously
                    $previousBid = $element->amount;
                    $trig="1";
                }
                //calculate total Amount Spent
                $currentAmountSpent = $currentAmountSpent + $element->amount;
            }

            if ($trig == "1"){
                //check if new balance allows or not
                if ($retrieveUser->edollar < ($currentAmountSpent + $amount - $previousBid)){
                    array_push ($encountered_Error['message'],'not enough e-dollar');
                    $errors = $encountered_Error;
                    var_dump ($errors);
                }
                else{
                    //update with new bid amount + new section
                    $BidDAO -> update($user,$amount,$courseid,$section);
                    updateMinBid($courseid,$section,$amount);
                    header("Location: LiveBidding.php");
                }
            }
            else{
                if ($retrieveUser->edollar < ($currentAmountSpent + $amount)){
                    array_push ($encountered_Error['message'],'not enough e-dollar');
                    $errors = $encountered_Error;
                    var_dump ($errors);
                }
                else{
                    //update with new bid amount
                    $newBid = new Bid($user,$amount,$courseid,$section);
                    $BidDAO->add( $newBid );
                    updateMinBid($courseid,$section,$amount);
                    header("Location: LiveBidding.php");
                }
            }
        }
        else{
            $errors = $encountered_Error;
            var_dump ($errors);
        }
    }

}
    
function insertManualBid()
{
    $BidDAO = new BidDAO();
    $CourseDAO = new CourseDAO();
    $StudentDAO = new StudentDAO();
    $PrerequisiteDAO = new PrerequisiteDAO();
    $SectionDAO = new SectionDAO();
    $CourseCompletedDAO = new CourseCompletedDAO();
    $roundDAO = new RoundDAO();
    $round = $roundDAO ->retrieveRound();
   
    $encountered_Error = array();
    $encountered_Error['message'] = array();
    $user = $_SESSION['userid'];
    $courseid = $_POST['courseid'];
    $section = $_POST['section'];
    $amount = $_POST['amount'];

    if (isset($_POST['courseid']) && isset($_POST['section']) && isset($_POST['amount'])){
        
        //check input amount
        if ((!is_numeric($amount) || strlen(substr(strrchr($amount, "."), 1)) > 2|| $amount<10))
        {
            array_push ($encountered_Error['message'],'invalid amount');
        }

        //check invalid course 
        if (($CourseDAO -> retrieveCourse($courseid))==false ){
            array_push ($encountered_Error['message'],'invalid course');
        }
        else
        {    
            //check invalid section 
            if(!in_array($section, $SectionDAO->retrieveByCourse($courseid)))
            {
                array_push ($encountered_Error['message'],'invalid section');
            }
        }

        //"not own school course"
        if ($round[0]=="1"){
            $studentObj = $StudentDAO->retrieve($user);
            $courseObj = $CourseDAO->retrieveSchool($courseid);
            if ($studentObj->school != $courseObj){
                array_push ($encountered_Error['message'],'not own school course');
            }
        }

        $retrieveBids = $BidDAO->retrieveBids($user);

        //if matching course, skips exam timetable check
        if (($BidDAO ->checkForSimilarCourseBid($user,$courseid))==false){
            //"class timetable clash"
            foreach($retrieveBids as $element){
                //retrieve bid's day and start time 
                $biddedSection = $SectionDAO ->retrieveByCourseSection($element->courseid,$element->section);
                //retrieve day and start time base on section and course
                $retrieveSection = $SectionDAO ->retrieveByCourseSection($courseid,$section);
                if(($biddedSection->day == $retrieveSection->day) && ($biddedSection->start == $retrieveSection->start)){
                //it clashes
                array_push ($encountered_Error['message'],'class timetable clash');
                }
            }

            //"exam timetable clash" 
            foreach($retrieveBids as $element){
                //retrieve bid's day and start time 
                $biddedExam = $CourseDAO ->retrieveExam($element->courseid);
                //retrieve day and start time base on section and course
                $retrieveExam = $CourseDAO ->retrieveExam($courseid);
                if(($biddedExam->exam_date == $retrieveExam->exam_date) && ($biddedExam->exam_start == $retrieveExam->exam_start)){
                //it clashes
                array_push ($encountered_Error['message'],'exam timetable clash');
                }
            }
        }

        //"incomplete prerequisites" 
        $preReqs = $PrerequisiteDAO->retrievePrerequisites($courseid);
        $trig = "0";
        if(!isEmpty($preReqs))
        {
            $coursesCompleted = $CourseCompletedDAO->retrieveCourseCompleted($user);
            foreach($preReqs as $element){
                if(!in_array($element,$coursesCompleted)){
                    $trig = "1";
                }
            }
            if ($trig == "1"){
                array_push ($encountered_Error['message'],'incomplete prerequisites');
            }
        }

        //"course completed"
        $coursesCompleted = $CourseCompletedDAO->retrieveCourseCompleted($user);
        if(in_array($courseid,$coursesCompleted)){
            array_push ($encountered_Error['message'],'course completed');
        }
        
        //"section limit reached"
        if (count($BidDAO->retrieveBids($user))>=5)
        {
            array_push ($encountered_Error['message'],'section limit reached');
        }

        if (isEmpty($encountered_Error['message'])){
            //"not enough e-dollar" 
            $retrieveUser = $StudentDAO->retrieve($user); //check current e-dollar amount
            $currentAmountSpent = 0;
            $trig = "0";
            $previousBid = 0;
            //calculate current Amount Spent
            foreach($retrieveBids as $element){
                //check if its an existing bid
                if ($element->courseid == $courseid)
                {
                    //bidded for the same course previously
                    $previousBid = $element->amount;
                    $trig="1";
                }
                //calculate total Amount Spent
                $currentAmountSpent = $currentAmountSpent + $element->amount;
            }

            if ($trig == "1"){
                //check if new balance allows or not
                if ($retrieveUser->edollar < ($currentAmountSpent + $amount - $previousBid)){
                    array_push ($encountered_Error['message'],'not enough e-dollar');
                    $errors = $encountered_Error;
                    var_dump ($errors);
                }
                else{
                    //update with new bid amount + new section
                    $BidDAO -> update($user,$amount,$courseid,$section);
                    header("Location: ManageBids.php");
                }
            }
            else{
                if ($retrieveUser->edollar < ($currentAmountSpent + $amount)){
                    array_push ($encountered_Error['message'],'not enough e-dollar');
                    $errors = $encountered_Error;
                    var_dump ($errors);
                }
                else{
                    //update with new bid amount
                    $newBid = new Bid($user,$amount,$courseid,$section);
                    $BidDAO->add( $newBid );
                    header("Location: ManageBids.php");
                }
            }
        }
        else{
            $errors = $encountered_Error;
            var_dump ($errors);
        }
    }
}
function dropManualBid()
{
    $BidDAO = new BidDAO();
    $CourseDAO = new CourseDAO();
    $SectionDAO = new SectionDAO();
    $roundDAO = new RoundDAO();
    $round = $roundDAO ->retrieveRound();
    $encountered_Error = array();
    $encountered_Error['message'] = array();
    
    $courseid = $_POST['courseid'];
    $section = $_POST['section'];
    $user = $_SESSION['userid'];
    $amount = "";

    if (isset($_POST['courseid']) && isset($_POST['section'])){
        if ($round[1]=="1"){
            //check invalid course 
            if (($BidDAO ->checkForSimilarCourseBid($user,$courseid))==false){
                array_push ($encountered_Error['message'],'invalid course');
            }
            else
            {    
                //check invalid section 
                if(!in_array($section, $SectionDAO->retrieveByCourse($courseid)))
                {
                    array_push ($encountered_Error['message'],'invalid section');
                }
            }
        }
        else{
            array_push ($encountered_Error['message'],'round ended');
        }
        if (isEmpty($encountered_Error['message'])){
            $newBid = new Bid($user,$amount,$courseid,$section);
            $BidDAO->drop($newBid);
            header("Location: ManageBids.php");
            }
            else{
                $errors = $encountered_Error;
                var_dump ($errors);
            }   

        
    }

}
if ($_SESSION['trigger'] == "Insert"){
    insertManualBid();
}
if ($_SESSION['trigger'] == "r2Insert"){
    checkMinBid();
}
if ($_SESSION['trigger'] == "Drop"){
    dropManualBid();
}


?>

