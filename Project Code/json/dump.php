<?php
require_once '../include/common.php';

//Retrieve, loop, populate temp array, display
$CourseDAO = new CourseDAO();
$courses = $CourseDAO -> retrieveDT();

$SectionDAO = new SectionDAO();
$sections = $SectionDAO -> retrieveDT();

$StudentDAO = new StudentDAO();
$students = $StudentDAO -> retrieveDT();

$PrerequisiteDAO = new PrerequisiteDAO();
$pr = $PrerequisiteDAO -> retrieveDT();

$BidDAO = new BidDAO();
$bids = $BidDAO -> retrieveDT();

$CourseCompletedDAO = new CourseCompletedDAO();
$cc = $CourseCompletedDAO -> retrieveDT();

$BiddingResultDAO = new BiddingResultsDAO();
$biddingResult = $BiddingResultDAO -> retrieveDT();

$result = [
    "status" => "success",
    "course" => $courses,
    "section" => $sections,
    "student" => $students,
    "prerequisite" => $pr,
    "bid" => $bids,
    "course completed" => $cc,
    "section-student" => $biddingResult
];

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);


?>