<?php

class BiddingResultsDAO {

    

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


