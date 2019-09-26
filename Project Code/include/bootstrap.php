<?php
require_once 'common.php';

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

				// Bid
				// process each line, check for errors, then insert if no errors

				// Course
				// process each line, check for errors, then insert if no errors

				$data = fgetcsv($course);

				while( ($data = fgetcsv ($course)) !== false){

					if (!empty($data[0]) && !empty($data[1]) && !empty($data[2]) && !empty($data[3]) && !empty($data[4]) && !empty($data[5]) && !empty($data[6])){
						$newCourse = new Course($data[0], $data[1], $data[2], $data[3] , $data[4] , $data[5] , $data[6]);
						$CourseDAO->add($newCourse);
						$course_processed++;
					}
				}

				// clean up
				fclose($course);
				@unlink($course_path);

				$data = fgetcsv($bid);
				while( ($data = fgetcsv ($bid)) !== false){
					
					if (!empty($data[0]) && !empty($data[1]) && !empty($data[2]) && !empty($data[3])){
						$newBid = new Bid($data[0], $data[1], $data[2], $data[3]);
						$bidDAO->add( $newBid );
						$bid_processed++;
					}
				}

				// clean up
				fclose($bid);
				@unlink($bid_path);
				
				// Course Completed
				// process each line, check for errors, then insert if no errors
				$data = fgetcsv($course_completed);

				while( ($data = fgetcsv ($course_completed)) !== false){

					if (!empty($data[0]) && !empty($data[1])){
						$newCourseCompleted = new CourseCompleted( $data[0], $data[1] );
						$CourseCompletedDAO->add( $newCourseCompleted );

						$course_completed_processed++;
					}

				}
				//Clean up
				fclose($course_completed);
				@unlink($course_completed_path);

				// Prerequisite
				// process each line, check for errors, then insert if no errors

				$data = fgetcsv($prerequisite);

				while( ($data = fgetcsv ($prerequisite)) !== false){

					if (!empty($data[0]) && !empty($data[1])){
						$newPrerequisite = new Prerequisite( $data[0], $data[1] );
						$PrerequisiteDAO->add( $newPrerequisite );

						$prerequisite_processed++;
					}

				}

				//Clean up
				fclose($prerequisite);
				@unlink($prerequisite_path);

				// Section
				// process each line, check for errors, then insert if no errors

				$data = fgetcsv($section);

				while( ($data = fgetcsv ($section)) !== false){

					if (!empty($data[0]) && !empty($data[1]) && !empty($data[2]) && !empty($data[3]) && !empty($data[4]) && !empty($data[5]) && !empty($data[6]) && !empty($data[7])){
						$newSection = new Section($data[0], $data[1], $data[2], $data[3] , $data[4] , $data[5] , $data[6], $data[7]);
						$SectionDAO->add($newSection);
						$section_processed++;
					}
				}

				// clean up
				fclose($section);
				@unlink($section_path);

				// Student
				// process each line, check for errors, then insert if no errors
				$data = fgetcsv($student);

				while( ($data = fgetcsv ($student)) !== false){
					if (!empty($data[0]) && !empty($data[1]) && !empty($data[2]) && !empty($data[3]) && !empty($data[4])){

						$newStudent = new Student($data[0], $data[1], $data[2], $data[3] , $data[4]);

						$StudentDAO->add($newStudent);
						$student_processed++;
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
			"messages" => $errors
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