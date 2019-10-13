<?php

require_once 'include/bootstrap.php';

if(!isset($_SESSION['userid'])){
    header("Location: Login.php?error=Unauthorized Access");
    return;
}

function insertManualBid()
{
   
    $BidDAO = new BidDAO();
    $CourseDAO = new CourseDAO();
    $roundDAO = new RoundDAO();
    $StudentDAO = new StudentDAO();
    $PrerequisiteDAO = new PrerequisiteDAO();
    $SectionDAO = new SectionDAO();
    $CourseCompletedDAO = new CourseCompletedDAO();

    $round = $roundDAO ->retrieveRound();
    $user = $_SESSION['userid'];
    $encountered_Error = array();
    $encountered_Error['message'] = array();

    $courseid = $_POST['courseid'];
    $section = $_POST['section'];
    $amount = $_POST['amount'];

    if (isset($_POST['courseid']) && isset($_POST['section']) && isset($_POST['amount'])){
        
        //check input amount
        if ((!is_numeric($amount) || strlen(substr(strrchr($amount, "."), 1)) > 2|| $amount<10))
        {
            array_push ($encountered_Error['message'],'invalid amount');
        }

        //check invalid course - ensure course table is above bid
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
                    echo ($errors);
                }
                else{
                    //update with new bid amount + new section
                    $BidDAO -> update($user,$amount,$courseid,$section);
                }
            }
            else{
                if ($retrieveUser->edollar < ($currentAmountSpent + $amount)){
                    array_push ($encountered_Error['message'],'not enough e-dollar');
                    $errors = $encountered_Error;
                    echo ($errors);
                }
                else{
                    //update with new bid amount
                    $newBid = new Bid($user,$amount,$courseid,$section);
                    $BidDAO->add( $newBid );
                    header("Location: ViewBid.php");
                }
            }
        }
        else{
            $errors = $encountered_Error;
            echo ($errors);
        }
    }
}
insertManualBid();

?>

