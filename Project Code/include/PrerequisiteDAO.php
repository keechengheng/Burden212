<?php

class PrerequisiteDAO {

    public function add($prerequisite) {
        $sql = "INSERT IGNORE INTO prerequisite (courseid, prerequisiteid) VALUES (:courseid, :prerequisiteid)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':courseid', $prerequisite->courseid, PDO::PARAM_STR);
        $stmt->bindParam(':prerequisiteid', $prerequisite->prerequisiteid, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }

    public function retrieveAll () {
        $sql = "select * from prerequisite";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        
        $prerequisite = [];
        while ($row =$stmt->fetch()){
            $prerequisite[] = new Prerequisite($row['courseid'], $row['prerequisiteid']);
        }

        return $prerequisite;
    }

    public function retrieveDT () {
        $sql = "select * from prerequisite order by courseid ASC, prerequisiteid ASC";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        
        $prerequisite = [];
        while ($row =$stmt->fetch()){
            $prerequisite[] = ["course" => $row['courseid'], "prerequisite" => $row['prerequisiteid']];
        }

        return $prerequisite;
    }

    public function retrievePrerequisites($course) {
        $sql = "select prerequisiteid from prerequisite where courseid=:course";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->execute();
        
        $courses = array();
        while ($row =$stmt->fetch()){
            array_push($courses, $row['prerequisiteid']);
        }

        return $courses;
    }
	
	 public function removeAll() {
        $sql = 'TRUNCATE TABLE prerequisite';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
}


