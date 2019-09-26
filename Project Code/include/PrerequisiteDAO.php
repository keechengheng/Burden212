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
	
	 public function removeAll() {
        $sql = 'TRUNCATE TABLE prerequisite';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
}


