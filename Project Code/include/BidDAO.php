<?php

class BidDAO {

    public function add($bid) {
        $sql = "INSERT IGNORE INTO bid (userid, amount, courseid, section) VALUES (:userid, :amount, :courseid, :section)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
        $stmt->bindParam(':amount', $bid->amount, PDO::PARAM_INT);
        $stmt->bindParam(':courseid', $bid->courseid, PDO::PARAM_STR);
        $stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }

    public  function retrieveBids($student) {
        $sql = 'select * from bid where userid=:userid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $student, PDO::PARAM_STR);
        $stmt->execute();

        $studentBid = array();
        while ($row =$stmt->fetch()){
            array_push($studentBid, $row['amount']);
            array_push($studentBid, $row['courseid']);
            array_push($studentBid, $row['section']);
        }
        return $studentBid; 
    }
	
	 public function removeAll() {
        $sql = 'TRUNCATE TABLE bid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
}


