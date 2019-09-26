<?php

class SectionDAO {

    public function add($section) {
        $sql = "INSERT IGNORE INTO section (courseid, section, day, start, end, instructor, venue, size) VALUES (:courseid, :section, :day, :start, :end, :instructor, :venue, :size)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':courseid', $section->courseid, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section->sectionid, PDO::PARAM_STR);
        $stmt->bindParam(':day', $section->day, PDO::PARAM_STR);
        $stmt->bindParam(':start', $section->start, PDO::PARAM_STR);
        $stmt->bindParam(':end', $section->end, PDO::PARAM_STR);
        $stmt->bindParam(':instructor', $section->instructor, PDO::PARAM_STR);
        $stmt->bindParam(':venue', $section->venue, PDO::PARAM_STR);
        $stmt->bindParam(':size', $section->size, PDO::PARAM_INT);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }
	
	 public function removeAll() {
        
        $sql = '
        SET FOREIGN_KEY_CHECKS = 0;  
        TRUNCATE TABLE section
        SET FOREIGN_KEY_CHECKS = 1;  ';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
}


