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

    public function retrieveByCourse($section) {
        $sql = "select section from section where courseid=:course";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':course', $section, PDO::PARAM_STR);
        $stmt->execute();
        

        $sections = array();
        while ($row =$stmt->fetch()){
            array_push($sections, $row['section']);
        }
        
        return $sections;
    }

    public function retrieveByCourseSection($courseid,$section) {
        $sql = "select * from section where courseid=:course and section=:section";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':course', $courseid, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->execute();
        
        while ($row =$stmt->fetch()){
            return new Section ($row['courseid'],$row['section'], $row['day'],$row['start'],$row['end'],$row['instructor'],$row['venue'],$row['size']);
        }

    }
	
	 public function removeAll() {
        
        $sql = '
        SET FOREIGN_KEY_CHECKS = 0;  
        TRUNCATE TABLE section;
        SET FOREIGN_KEY_CHECKS = 1  ';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
    
    public function retrieveAll() {
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $sql = "select * from section";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $sectionAll = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $sectionAll[] = new Section($row['courseid'], $row['section'], $row['day'], 
                                    $row['start'],$row['end'],$row['instructor'],
                                    $row['venue'],$row['size']);
           
    }
        return $sectionAll;
}
}