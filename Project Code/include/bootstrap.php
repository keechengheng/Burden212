<?php
require_once 'common.php';

function validateDate($date, $format = 'Ymd')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function validateTime($time)
{
        if (preg_match("/^(?(?=\d{2})(?:2[0-3]|[01][0-9])|[0-9]):[0-5][0-9]$/", $time)) {
            return true; //12hour and 24 hour clock correct
        }
        else{
        	return false;
        } 
}

function validateEndTime($startTime,$endTime)
{
	list($strHour, $strMin) = explode(':', $startTime);
	list($endHour, $endMin) = explode(':', $endTime);

	$startSeconds = mktime($strHour, $strMin);
	$endSeconds   = mktime($endHour, $endMin);

	if ($startSeconds > $endSeconds) {
		return true; //start time is bigger than end time - invalid data
	}
	else {
		return false;
	}
}

//trigger round 1 to begin
$roundDAO = new RoundDAO();
$roundDAO ->activateRoundOne();

function doBootstrap() {
	
	

	$errors = array();
	# need tmp_name -a temporary name create for the file and stored inside apache temporary folder- for proper read address
	$zip_file = $_FILES["bootstrap-file"]["tmp_name"];

	# Get temp dir on system for uploading
	$temp_dir = sys_get_temp_dir();
	# keep track of number of lines successfully processed for each file
	$bid_processed=0;
	$course_completed_processed=0;
	$course_processed=0;
	$prerequisite_processed = 0;
	$section_processed = 0;
	$student_processed = 0;

	# check file size
	if ($_FILES["bootstrap-file"]["size"] <= 0) {
		
		$errors[] = "input files not found";
	}
	else {
		$zip = new ZipArchive;
		$res = $zip->open($zip_file);
		if ($res === TRUE) {
			$zip->extractTo($temp_dir);
			$zip->close();
		
			$bid_path = "$temp_dir/bid.csv";
			$course_completed_path = "$temp_dir/course_completed.csv";
			$course_path = "$temp_dir/course.csv";
			$prerequisite_path = "$temp_dir/prerequisite.csv";
			$section_path = "$temp_dir/section.csv";
			$student_path = "$temp_dir/student.csv";

			$bid = @fopen($bid_path, "r");
			$course_completed = @fopen($course_completed_path, "r");
			$course = @fopen($course_path, "r");
			$prerequisite = @fopen($prerequisite_path, "r");
			$section = @fopen($section_path, "r");
			$student = @fopen($student_path, "r");

			if (empty($bid) || empty($course_completed) || empty($course) || empty($prerequisite) || empty($section) || empty($student)){
				$errors[] = "input files not found";
				if (!empty($bid)){
					fclose($bid);
					@unlink($bid);
				} 
				
				if (!empty($course_completed)) {
					fclose($course_completed);
					@unlink($course_completed);
				}
				
				if (!empty($course)) {
					fclose($course);
					@unlink($course);
				}

				if (!empty($prerequisite)) {
					fclose($prerequisite);
					@unlink($prerequisite);
				}
				
				if (!empty($section)) {
					fclose($section);
					@unlink($section);
				}

				if (!empty($student)) {
					fclose($student);
					@unlink($student);
				}
			}
			else {
				$connMgr = new ConnectionManager();
				$conn = $connMgr->getConnection();

				# start processing
				
				# truncate current SQL tables

				$BidDAO = new BidDAO();
				$BidDAO -> removeAll();

				$CourseCompletedDAO = new CourseCompletedDAO();
				$CourseCompletedDAO -> removeAll();

				$PrerequisiteDAO = new PrerequisiteDAO();
				$PrerequisiteDAO -> removeAll();

				$CourseDAO = new CourseDAO();
				$CourseDAO -> removeAll();

				$SectionDAO = new SectionDAO();
				$SectionDAO -> removeAll();

				$StudentDAO = new StudentDAO();
				$StudentDAO -> removeAll();

				
				# then read each csv file line by line (remember to skip the header)
				# $data = fgetcsv($file) gets you the next line of the CSV file which will be stored 
				# in the array $data
				# $data[0] is the first element in the csv row, $data[1] is the 2nd, ....
				
				# process each line and check for errors
				
				# for the project, the full error list is listed in the wiki

				$data = fgetcsv($course);
				// to align to the row number in excel
				$row_Count = 1;
				// append all courses encountered into array for further validation later
				$encounteredCourses = array();
			
				//Validation format 
				
				while( ($data = fgetcsv ($course)) !== false){
					$row_Count ++; //header is row 1, code starts from 0
					$encountered_Error = array();
					$encountered_Error['file'] = "course.csv";
					$encountered_Error['line'] = $row_Count;
					$encountered_Error['message'] = array();
					

					//check if empty
					if (empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[3]) || empty($data[4]) || empty($data[5]) || empty($data[6])){
						array_push ($encountered_Error['message'],'Empty Field Encountered');
						$errors[] = $encountered_Error;
					}
					else{

					//check exam date format
					if (validateDate($data[4])==false){
						array_push ($encountered_Error['message'],'invalid exam date');
					}
					//check exam start time format
					if (validateTime($data[5])==false){
						array_push ($encountered_Error['message'],'invalid exam start');
					}
					//check exam end time format + validate later than start
					if (validateTime($data[6])==false || validateEndTime($data[5],$data[6])){
						array_push ($encountered_Error['message'],'invalid exam end');
					}

					//check title <100 char
					if (strlen($data[2])>100){
						array_push ($encountered_Error['message'],'invalid title');
					}

					//check description <1000 char
					if (strlen($data[3])>1000){
						array_push ($encountered_Error['message'],'invalid description');
					}

					//check if any errors
					if (empty($encountered_Error['message'])){
						array_push ($encounteredCourses,$data[0]); //insert courses into array
						$newCourse = new Course($data[0], $data[1], $data[2], $data[3] , $data[4] , $data[5] , $data[6]);
						$CourseDAO->add($newCourse);
						$course_processed++;
					}
					else{
						$errors[] = $encountered_Error;
					}
				}
					


				}

				// clean up
				fclose($course);
				@unlink($course_path);

				// Student
				// process each line, check for errors, then insert if no errors
				$data = fgetcsv($student);
				$row_Count = 1;
				// append all users encountered into array for further validation later
				$encounteredUsers = array();

				while( ($data = fgetcsv ($student)) !== false){
					$row_Count ++; //header is row 1, code starts from 0
					$encountered_Error = array();
					$encountered_Error['file'] = "student.csv";
					$encountered_Error['line'] = $row_Count;
					$encountered_Error['message'] = array();
					
					//check if empty
					if (empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[3]) || empty($data[4]) ){
						array_push ($encountered_Error['message'],'Empty Field Encountered');
						$errors[] = $encountered_Error;
					}
					else{

						//check userid <128 char
					if (strlen($data[0])>128){
						array_push ($encountered_Error['message'],'invalid userid');
					}

					
					//check duplicate userid 
					if (in_array($data[0],$encounteredUsers)){
						array_push ($encountered_Error['message'],'duplicate userid');
					}

					//check e-dollar
					if ((!is_numeric($data[4])) || strlen(substr(strrchr($data[4], "."), 1)) > 3|| $data[4]<0)
					{
						array_push ($encountered_Error['message'],'invalid e-dollar');
					}
					
					//check password <128 char
					if (strlen($data[1])>128){
						array_push ($encountered_Error['message'],'invalid password');
					}
					//check name <100 char
					if (strlen($data[2])>100){
						array_push ($encountered_Error['message'],'invalid name');
					}
					
					//check if any errors
					if (empty($encountered_Error['message'])){
						array_push ($encounteredUsers,$data[0]); //hold it as a valid user
						$newStudent = new Student($data[0], $data[1], $data[2], $data[3] , $data[4]);
						$StudentDAO->add($newStudent);
						$student_processed++;
					}
					else{
						$errors[] = $encountered_Error;
					}

					}
					
					
				}

				// clean up
				fclose($student);
				@unlink($student_path);

				// Section
				// process each line, check for errors, then insert if no errors

				$data = fgetcsv($section);
				$row_Count = 1;
				// append both course & section encountered into array for further validation later
				$encounteredSections = array();

				while( ($data = fgetcsv ($section)) !== false){
					$row_Count ++; //header is row 1, code starts from 0
					$encountered_Error = array();
					$encountered_Error['file'] = "section.csv";
					$encountered_Error['line'] = $row_Count;
					$encountered_Error['message'] = array();

					//check if empty
					if (empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[3]) || empty($data[4]) || empty($data[5]) || empty($data[6]) || empty($data[7])){

						array_push ($encountered_Error['message'],'Empty Field Encountered');
						$errors[] = $encountered_Error;
					}
					else{

					//check invalid course - ensure course table is above section
					if (!in_array($data[0],$encounteredCourses)){
						array_push ($encountered_Error['message'],'invalid course');
					}
					
					//check section number is less than 100
					if ($data[1][0] != 'S' || !is_numeric(substr($data[1],1)) || substr($data[1],1) > 99 || substr($data[1],1) < 1 ){
						array_push ($encountered_Error['message'],'invalid section');
					}

					//check invalid day between 1 - 7 only 
					if ($data[2] > 7 || $data[2] < 1){
						array_push ($encountered_Error['message'],'invalid day');
					}

					//check exam start time format
					if (validateTime($data[3])==false){
						array_push ($encountered_Error['message'],'invalid start');
					}

					//check exam end time format + validate later than start
					if (validateTime($data[4])==false || validateEndTime($data[3],$data[4])){
						array_push ($encountered_Error['message'],'invalid end');
					}

					//check instructor <100 char
					if (strlen($data[5])>100){
						array_push ($encountered_Error['message'],'invalid instructor');
					}
					//check venue <100 char
					if (strlen($data[6])>100){
						array_push ($encountered_Error['message'],'invalid venue');
					}

					//check size positive numeric 
					if (!is_numeric($data[7]) || $data[7]<0){
						array_push ($encountered_Error['message'],'invalid size');
					}
					
					//check if any errors
					if (empty($encountered_Error['message'])){
						array_push ($encounteredSections,$data[0]);
						$newSection = new Section($data[0], $data[1], $data[2], $data[3] , $data[4] , $data[5] , $data[6], $data[7]);
						$SectionDAO->add($newSection);
						$section_processed++;
					}
					else{
						$errors[] = $encountered_Error;
					}
				}
				}

				// clean up
				fclose($section);
				@unlink($section_path);



				// Prerequisite
				// process each line, check for errors, then insert if no errors

				$data = fgetcsv($prerequisite);
				$row_Count = 1;

				while( ($data = fgetcsv ($prerequisite)) !== false){
					$row_Count ++; //header is row 1, code starts from 0
					$encountered_Error = array();
					$encountered_Error['file'] = "prerequisite.csv";
					$encountered_Error['line'] = $row_Count;
					$encountered_Error['message'] = array();

					//check if empty
					if (empty($data[0]) || empty($data[1])){
						array_push ($encountered_Error['message'],'Empty Field Encountered');
						$errors[] = $encountered_Error;
					}
					else{
					//check invalid course - ensure course table is above prerequisite
					if (!in_array($data[0],$encounteredCourses)){
						array_push ($encountered_Error['message'],'invalid course');
					}

					//check invalid prerequisite - ensure course table is above prerequisite
					if (!in_array($data[1],$encounteredCourses)){
						array_push ($encountered_Error['message'],'invalid prerequisite');
					}

					//check if any errors
					if (empty($encountered_Error['message'])){
						$newPrerequisite = new Prerequisite( $data[0], $data[1] );
						$PrerequisiteDAO->add( $newPrerequisite );
						$prerequisite_processed++;
					}
					else{
						$errors[] = $encountered_Error;
					}
				}

				}

				//Clean up
				fclose($prerequisite);
				@unlink($prerequisite_path);

				
				// Course Completed
				// process each line, check for errors, then insert if no errors
				$data = fgetcsv($course_completed);
				$row_Count = 1;

				while( ($data = fgetcsv ($course_completed)) !== false){
					$row_Count ++; //header is row 1, code starts from 0
					$encountered_Error = array();
					$encountered_Error['file'] = "course_completed.csv";
					$encountered_Error['line'] = $row_Count;
					$encountered_Error['message'] = array();
					//check if empty
					if (empty($data[0]) || empty($data[1])){
						array_push ($encountered_Error['message'],'Empty Field Encountered');
						$errors[] = $encountered_Error;
					}
					else{
					//check invalid userid - ensure student table is above courseCompleted
					
					if (!in_array($data[0],$encounteredUsers)){
						array_push ($encountered_Error['message'],'invalid userid');
					}

					//check invalid course - ensure course table is above courseCompleted
					if (!in_array($data[1],$encounteredCourses)){
						array_push ($encountered_Error['message'],'invalid course');
					}

					//check if course completed has a pre-req
					$preReqs = $PrerequisiteDAO->retrievePrerequisites($data[1]);
					$trigger = "0";
					if(!empty($preReqs))
					{
						$currentCourses = $CourseCompletedDAO->retrieveCourseCompleted($data[0]);
						foreach($preReqs as $element){
							if(!in_array($element,$currentCourses)){
								$trigger = "1";
							}
						}
						if ($trigger == "1"){
							array_push ($encountered_Error['message'],'invalid course completed');
						}
					}
						
					//check if any errors
					if (empty($encountered_Error['message'])){
						$newCourseCompleted = new CourseCompleted( $data[0], $data[1] );
						$CourseCompletedDAO->add( $newCourseCompleted );
						$course_completed_processed++;
					}
					else{
						$errors[] = $encountered_Error;
					}
				}

				}
				//Clean up
				fclose($course_completed);
				@unlink($course_completed_path);


				
				$data = fgetcsv($bid);
				$row_Count = 1;

				while( ($data = fgetcsv ($bid)) !== false){
					$row_Count ++; //header is row 1, code starts from 0
					$encountered_Error = array();
					$encountered_Error['file'] = "bid.csv";
					$encountered_Error['line'] = $row_Count;
					$encountered_Error['message'] = array();

					//check if empty
					if (empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[3])){

						array_push ($encountered_Error['message'],'Empty Field Encountered');
						$errors[] = $encountered_Error;
					}
					else{

					//check invalid userid - ensure student table is above bid
					
					if (!in_array($data[0],$encounteredUsers)){
						array_push ($encountered_Error['message'],'invalid userid');
					}

					//check input amount
					if ((!is_numeric($data[1])) || strlen(substr(strrchr($data[1], "."), 1)) > 2|| $data[1]<10)
					{
						array_push ($encountered_Error['message'],'invalid amount');
					}

					//check invalid course - ensure course table is above bid
					if (!in_array($data[2],$encounteredCourses)){
						array_push ($encountered_Error['message'],'invalid course');
					}
					else
					{
						// //check invalid section 
						if(!in_array($data[3], $SectionDAO->retrieveByCourse($data[2])))
						{
							array_push ($encountered_Error['message'],'invalid section');
						}
					}
					//validation completed
					//check error log, if empty - execute logic validation
					if (empty($encountered_Error['message'])){
					//LOGIC Validations (7)

					//"not own school course"

					if ($_SESSION['round']=="1"){
						$studentObj = $StudentDAO->retrieve($data[0]);
						$courseObj = $CourseDAO->retrieveSchool($data[2]);
						if ($studentObj->school != $courseObj){
							array_push ($encountered_Error['message'],'not own school course');
						}
					}
					
					//"class timetable clash"
					$retrieveBids = $BidDAO->retrieveBids($data[0]); //retrieve previous bids
					foreach($retrieveBids as $element){
						//retrieve bid's day and start time 
						$biddedSection = $SectionDAO ->retrieveByCourseSection($element->courseid,$element->section);
						//retrieve day and start time base on section and course
						$retrieveSection = $SectionDAO ->retrieveByCourseSection($data[2],$data[3]);
						if($biddedSection->day == $retrieveSection->day && $biddedSection->start == $retrieveSection->start){
							//it clashes
							array_push ($encountered_Error['message'],'class timetable clash');
						}
					}

					//"exam timetable clash" 
					$retrieveBids = $BidDAO->retrieveBids($data[0]); //retrieve previous bids
					foreach($retrieveBids as $element){
						//retrieve bid's day and start time 
						$biddedExam = $CourseDAO ->retrieveExam($element->courseid);
						//retrieve day and start time base on section and course
						$retrieveExam = $CourseDAO ->retrieveExam($data[2]);
						if($biddedSection->exam_date == $retrieveSection->exam_date && $biddedSection->exam_start == $retrieveSection->exam_start){
							//it clashes
							array_push ($encountered_Error['message'],'exam timetable clash');
						}
					}

					//"incomplete prerequisites" 
					$preReqs = $PrerequisiteDAO->retrievePrerequisites($data[2]);
					$trigger = "0";
					if(!empty($preReqs))
					{
						$coursesCompleted = $CourseCompletedDAO->retrieveCourseCompleted($data[0]);
						foreach($preReqs as $element){
							if(!in_array($element,$coursesCompleted)){
								$trigger = "1";
							}
						}
						if ($trigger == "1"){
							array_push ($encountered_Error['message'],'incomplete prerequisites');
						}
					}
					
					//"course completed"
					$coursesCompleted = $CourseCompletedDAO->retrieveCourseCompleted($data[0]);
					if(in_array($data[2],$coursesCompleted)){
						array_push ($encountered_Error['message'],'course completed');
					}

					//"section limit reached"
					if (count($BidDAO->retrieveBids($data[0]))>5)
					{
						array_push ($encountered_Error['message'],'section limit reached');
					}

					//"not enough e-dollar" (pending)
					$retrieveUser = $StudentDAO->retrieve($data[0]); //check current e-dollar amount
					if ($retrieveUser->edollar < $data[1]){
						array_push ($encountered_Error['message'],'not enough e-dollar');
					}
					else{
						//retrieve previous successful bids of this user
						$retrieveBids = $BidDAO->retrieveBids($data[0]);
						//check if its a previously bidded course
						foreach($retrieveBids as $element){
							if($element->courseid == $data[2]){
								//it is a previously bidded course
								$differenceAmount = $element->amount - $data[1];
							}
						}
					}

					if (empty($encountered_Error['message'])){
						$newBid = new Bid($data[0], $data[1], $data[2], $data[3]);
						$BidDAO->add( $newBid );
						$bid_processed++;
					}
					else{
						$errors[] = $encountered_Error;
					}

				}
				else{	
						$errors[] = $encountered_Error;
				}

				}
				
				}

				// clean up
				fclose($bid);
				@unlink($bid_path);

				
				

				
			}
		}
	}

	# Sample code for returning JSON format errors. remember this is only for the JSON API. Humans should not get JSON errors.

	if (!empty($errors))
	{	
		// $sortclass = new Sort();
		// $errors = $sortclass->sort_it($errors,"bootstrap");
		$result = [ 
			"status" => "error",
			"num-record-loaded" => [
				"bid.csv" => $bid_processed,
				"course_completed.csv" => $course_completed_processed,
				"course.csv" => $course_processed,
				"prerequisite.csv" => $prerequisite_processed,
				"section.csv" => $section_processed,
				"student.csv" => $student_processed
			],
			"error" => $errors
		];
	}
	else
	{	
		$result = [ 
			"status" => "success",
			"num-record-loaded" => [
				"bid.csv" => $bid_processed,
				"course_completed.csv" => $course_completed_processed,
				"course.csv" => $course_processed,
				"prerequisite.csv" => $prerequisite_processed,
				"section.csv" => $section_processed,
				"student.csv" => $student_processed
			]
		];
	}
	header('Content-Type: application/json');
	echo json_encode($result, JSON_PRETTY_PRINT);

	
}


?>