<?php

class CourseCompletedDAO {

    public function add($course_completed) {
        $sql = "INSERT IGNORE INTO course_completed (userid, courseid) VALUES (:userid, :courseid)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':userid', $course_completed->userid, PDO::PARAM_STR);
        $stmt->bindParam(':courseid', $course_completed->courseid, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }
	
	 public function removeAll() {
        $sql = 'TRUNCATE TABLE course_completed';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
}


