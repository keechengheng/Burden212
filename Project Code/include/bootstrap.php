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

				$bidDAO = new BidDAO();
				$bidDAO -> removeAll();

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
				
				# for this lab, assume the only error you should check for is that each CSV field 
				# must not be blank 
				
				# for the project, the full error list is listed in the wiki

				$data = fgetcsv($course);
				$row_Count = 1;
			
				//Validation format 
				//
				while( ($data = fgetcsv ($course)) !== false){
					$row_Count ++; //header is row 1, code starts from 0
					$encountered_Error = array();
					$encountered_Error['file'] = "course.csv";
					$encountered_Error['line'] = $row_Count;
					$encountered_Error['message'] = array();

					//check if empty
					if (isEmpty($data[0]) || isEmpty($data[1]) || isEmpty($data[2]) || isEmpty($data[3]) || isEmpty($data[4]) || isEmpty($data[5]) || isEmpty($data[6])){
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
					if (isEmpty($encountered_Error['message'])){
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

				$data = fgetcsv($bid);
				$row_Count = 1;

				while( ($data = fgetcsv ($bid)) !== false){
					$row_Count ++; //header is row 1, code starts from 0
					$encountered_Error = array();
					$encountered_Error['file'] = "bid.csv";
					$encountered_Error['line'] = $row_Count;
					$encountered_Error['message'] = array();

					//check invalid userid (buggy)
					// if (validateDate($data[4])==false){
					// 	array_push ($encountered_Error['message'],'invalid userid');
					// }

					//check invalid amount (buggy)
					// if (validateTime($data[5])==false){
					// 	array_push ($encountered_Error['message'],'invalid amount');
					// }

					//check invalid course (buggy)
					// if (validateTime($data[6])==false){
					// 	array_push ($encountered_Error['message'],'invalid course');
					// }

					//check invalid section (buggy)
					// if (validateTime($data[6])==false){
					// 	array_push ($encountered_Error['message'],'invalid section');
					// }

					//missing LOGIC Validations (7)

					//"not own school course"
					//"class timetable clash"
					//"exam timetable clash"
					//"incomplete prerequisites"	
					//"course completed"
					//"section limit reached"
					//"not enough e-dollar"



					//check if empty
					if (isEmpty($data[0]) || isEmpty($data[1]) || isEmpty($data[2]) || isEmpty($data[3])){

						array_push ($encountered_Error['message'],'Empty Field Encountered');
 						
					}
					//check if any errors
					if (isEmpty($encountered_Error['message'])){
						$newCourse = new Course($data[0], $data[1], $data[2], $data[3]);
						$newBid = new Bid($data[0], $data[1], $data[2], $data[3]);
						$bidDAO->add( $newBid );
						$bid_processed++;
					}
					else{
						$errors[] = $encountered_Error;
					}

				
				}

				// clean up
				fclose($bid);
				@unlink($bid_path);
				
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

					//check invalid userid (buggy)
					// if (validateDate($data[4])==false){
					// 	array_push ($encountered_Error['message'],'invalid userid');
					// }

					//check invalid course (buggy)
					// if (validateTime($data[5])==false){
					// 	array_push ($encountered_Error['message'],'invalid course');
					// }
					
					//check invalid course completed (buggy)
					// if (validateTime($data[5])==false){
					// 	array_push ($encountered_Error['message'],'invalid course completed');
					// }
					
			
					//check if empty
					if (isEmpty($data[0]) || isEmpty($data[1])){
						echo ('hitEmpty');
						array_push ($encountered_Error['message'],'Empty Field Encountered');
 						
					}
					//check if any errors
					if (isEmpty($encountered_Error['message'])){
						$newCourseCompleted = new CourseCompleted( $data[0], $data[1] );
						$CourseCompletedDAO->add( $newCourseCompleted );

						$course_completed_processed++;
					}
					else{
						$errors[] = $encountered_Error;
					}

				}
				//Clean up
				fclose($course_completed);
				@unlink($course_completed_path);

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

					//check invalid userid (buggy)
					// if (validateDate($data[4])==false){
					// 	array_push ($encountered_Error['message'],'invalid course');
					// }

					//check invalid code (buggy)
					// if (validateTime($data[5])==false){
					// 	array_push ($encountered_Error['message'],'invalid prerequisite');
					// }
					

					//check if empty
					if (isEmpty($data[0]) || isEmpty($data[1])){

						array_push ($encountered_Error['message'],'Empty Field Encountered');
 						
					}
					//check if any errors
					if (isEmpty($encountered_Error['message'])){
						$newPrerequisite = new Prerequisite( $data[0], $data[1] );
						$PrerequisiteDAO->add( $newPrerequisite );

						$prerequisite_processed++;
					}
					else{
						$errors[] = $encountered_Error;
					}

				}

				//Clean up
				fclose($prerequisite);
				@unlink($prerequisite_path);

				// Section
				// process each line, check for errors, then insert if no errors

				$data = fgetcsv($section);
				$row_Count = 1;

				while( ($data = fgetcsv ($section)) !== false){
					$row_Count ++; //header is row 1, code starts from 0
					$encountered_Error = array();
					$encountered_Error['file'] = "section.csv";
					$encountered_Error['line'] = $row_Count;
					$encountered_Error['message'] = array();
					
					//check invalid course (buggy)
					
					//check section number is less than 100
					if ($data[1][0] != 'S' || !is_numeric(substr($data[1],1)) || substr($data[1],1) > 99 || substr($data[1],1) < 1 ){
						array_push ($encountered_Error['message'],'invalid day');
					}

					//check invalid day between 1 - 7 only 
					if ($data[2] > 7 && $data[2] < 1){
						array_push ($encountered_Error['message'],'invalid day');
					}

					//check exam start time format
					if (validateTime($data[3])==false){
						array_push ($encountered_Error['message'],'invalid start');
					}

					//check exam end time format
					if (validateTime($data[4])==false){
						array_push ($encountered_Error['message'],'invalid end');
					}

					//validate end time should be later than start (buggy)
					// if ($data[3]>$data[4]){
					// 	array_push ($encountered_Error['message'],'invalid exam end');
					// }

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

					//check if empty
					if (isEmpty($data[0]) || isEmpty($data[1]) || isEmpty($data[2]) || isEmpty($data[3]) || isEmpty($data[4]) || isEmpty($data[5]) || isEmpty($data[6]) || isEmpty($data[7])){

						array_push ($encountered_Error['message'],'Empty Field Encountered');
 						
					}
					//check if any errors
					if (isEmpty($encountered_Error['message'])){
						$newSection = new Section($data[0], $data[1], $data[2], $data[3] , $data[4] , $data[5] , $data[6], $data[7]);
						$SectionDAO->add($newSection);
						$section_processed++;
					}
					else{
						$errors[] = $encountered_Error;
					}
				}

				// clean up
				fclose($section);
				@unlink($section_path);

				// Student
				// process each line, check for errors, then insert if no errors
				$data = fgetcsv($student);
				$row_Count = 1;
				$encounteredUsers = array();

				while( ($data = fgetcsv ($student)) !== false){
					$row_Count ++; //header is row 1, code starts from 0
					$encountered_Error = array();
					$encountered_Error['file'] = "student.csv";
					$encountered_Error['line'] = $row_Count;
					$encountered_Error['message'] = array();
					
					//check if empty
					if (isEmpty($data[0]) || isEmpty($data[1]) || isEmpty($data[2]) || isEmpty($data[3]) || isEmpty($data[4]) ){
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
					else{
						array_push ($encounteredUsers,$data[0]);
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
					//check password <128 char
					if (strlen($data[2])>128){
						array_push ($encountered_Error['message'],'invalid name');
					}
					
					//check if any errors
					if (isEmpty($encountered_Error['message'])){
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

			}
		}
	}

	# Sample code for returning JSON format errors. remember this is only for the JSON API. Humans should not get JSON errors.

	if (!isEmpty($errors))
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