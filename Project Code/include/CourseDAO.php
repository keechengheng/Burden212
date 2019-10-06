<?php

class CourseDAO {

    public function add($course) {
        $sql = "INSERT IGNORE INTO course (courseid, school, title, description, exam_date, exam_start, exam_end) VALUES (:courseid, :school, :title, :description, :exam_date, :exam_start, :exam_end)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':courseid', $course->courseid, PDO::PARAM_STR);
        $stmt->bindParam(':school', $course->school, PDO::PARAM_STR);
        $stmt->bindParam(':title', $course->title, PDO::PARAM_STR);
        $stmt->bindParam(':description', $course->description, PDO::PARAM_STR);
        $stmt->bindParam(':exam_date', $course->exam_date, PDO::PARAM_STR);
        $stmt->bindParam(':exam_start', $course->exam_start, PDO::PARAM_STR);
        $stmt->bindParam(':exam_end', $course->exam_end, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }
    
    public  function retrieveSchool($course) {
        $sql = 'select school from course where courseid=:courseid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':courseid', $course, PDO::PARAM_STR);
        $stmt->execute();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return $value = $row['school'];
          }
    }


    public  function retrieveExam($course) {
        $sql = 'select exam_date,exam_start from course where courseid=:courseid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':courseid', $course, PDO::PARAM_STR);
        $stmt->execute();


        $exams = array();
        while ($row =$stmt->fetch()){
            array_push($exams, $row['exam_date']);
            array_push($exams, $row['exam_start']);
        }
        
        return $exams;
    }
	
	 public function removeAll() {
        $sql = '
            SET FOREIGN_KEY_CHECKS = 0;  
            TRUNCATE TABLE course
            SET FOREIGN_KEY_CHECKS = 1;';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
}


