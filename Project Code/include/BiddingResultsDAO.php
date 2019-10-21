<?php

class BiddingResultsDAO {

    public function retrieveDT() {
        $sql = 'select * from bidding_results WHERE status="successful" order by courseid ASC, userid ASC';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $studentBid = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row =$stmt->fetch()){
            $studentBid[]= ["userid" => $row['userid'], "course" => $row['courseid'],
                        "section" => $row['section'], "amount" => (float)$row['amount']];
        }
        return $studentBid; 
    }

    public function retrieveDS($courseid, $section) {
        $sql = 'select * from bidding_results WHERE status="successful" AND courseid=:courseid AND section=:section
                 order by userid ASC';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':courseid', $courseid, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->execute();
        
        $studentBid = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row =$stmt->fetch()){
            $studentBid[]= ["userid" => $row['userid'], "amount" => (float)$row['amount']];
        }
        return $studentBid; 
    }

    public function retrieveBids($student) {
        $sql = 'select * from bidding_results where userid=:userid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid', $student, PDO::PARAM_STR);
        $stmt->execute();
        
        $studentBid = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row =$stmt->fetch()){
            $studentBid[]= new BiddingResults ($row['userid'],$row['courseid'],$row['section'],$row['round'],$row['datetime'],$row['amount'],$row['status']);
        }
        return $studentBid; 
    }
    

	
	 public function removeAll() {
        $sql = 'TRUNCATE TABLE bidding_results';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
    
    
}


