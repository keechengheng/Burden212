<?php

class CourseCompletedDAO {

    public function retrieveAll () {
        $sql = 'select * from course_completed';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $courses = [];

        while ($row =$stmt->fetch()){
            $courses[] = new CourseCompleted($row['userid'], $row['courseid']);
        }

        return $courses; 
    }

    public function retrieveDT () {
        $sql = 'select * from course_completed order by courseid ASC, userid ASC';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $courses = [];

        while ($row =$stmt->fetch()){
            $courses[] = ["userid" => $row['userid'], "course" => $row['courseid']];
        }

        return $courses; 
    }

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
    
    public function retrieveCourseCompleted($user) {
        $sql = "select courseid from course_completed where userid=:user";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->execute();
        
        $courses = array();
        while ($row =$stmt->fetch()){
            array_push($courses, $row['courseid']);
        }

        return $courses;
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


