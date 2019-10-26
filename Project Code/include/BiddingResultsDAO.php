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

    //retrieving all bids
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
            $studentBid[]= new BiddingResults ($row['userid'],$row['courseid'],$row['section'],$row['round'],$row['dtvalue'],$row['amount'],$row['status']);
        }
        return $studentBid; 
    }

    public function dropSection($student,$course,$section) {
        $sql = 'UPDATE bidding_results SET status="DROPPED" WHERE userid=:userid AND courseid=:course AND section=:section AND status="SUCCESSFUL"';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid', $student, PDO::PARAM_STR);
        $stmt->bindParam(':course', $course, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        
        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }
        return $isUpdateOk;
    }

    //retrieving bid results for specific round
    public function retrieveLatestResult($student,$round,$status) {
        //determine current round. 
        //0 - either first launch or just ended round 2 
        //1 - bootstrap, if open no results. if closed, show distinct latest
        //2 - if open, show distinct latest. 
        // status 0 = close, 1 = open
        
        $sql = "SELECT DISTINCT dtvalue FROM bidding_results ORDER BY dtvalue DESC";
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        $studentBid = [];
        while ($row =$stmt->fetch()){
            $dtRound[] = [$row['dtvalue']];
        }
        if ($round == 0 && count($dtRound)>=2){
            $selectedRound = $dtRound[0];
        }
        elseif ($round == 1 && $status == 0 ){
            $selectedRound = $dtRound[0];
        }
        elseif ($round == 2){
            $selectedRound = $dtRound[0];
        }
        else{
            return $studentBid; 
        }
        
        $sql = 'select * from bidding_results where userid=:userid and dtvalue=:dtvalue';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid', $student, PDO::PARAM_STR);
        $stmt->bindParam(':dtvalue', $selectedRound[0], PDO::PARAM_STR);
        $stmt->execute();
         
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row =$stmt->fetch()){
            $studentBid[]= new BiddingResults ($row['userid'],$row['courseid'],$row['section'],$row['round'],$row['dtvalue'],$row['amount'],$row['status']);
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


