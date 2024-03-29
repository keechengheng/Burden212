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

    public function retrieveDT() {
        $sql = "select * from course order by courseid ASC";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $courses = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = ["course" => $row['courseid'], "school" => $row['school'], "title" => $row['title'], 
                        "description" => $row['description'], "exam date" => $row['exam_date'], 
                        "exam start" => $row['exam_start'] , "exam end" => $row['exam_end']];
        }
        
        return $courses;
    }

    public function retrieveCourse($course) {
        $sql = "select * from course where courseid=:course";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->rowCount();
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
        $sql = 'select * from course where courseid=:courseid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':courseid', $course, PDO::PARAM_STR);
        $stmt->execute();

        while ($row =$stmt->fetch()){
            return new Course ($row['courseid'],$row['school'], $row['title'],$row['description'],$row['exam_date'],$row['exam_start'],$row['exam_end']);
    }
        
     
    }
	
	 public function removeAll() {
        $sql = '
            SET FOREIGN_KEY_CHECKS = 0;  
            TRUNCATE TABLE course;
            SET FOREIGN_KEY_CHECKS = 1';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
}


