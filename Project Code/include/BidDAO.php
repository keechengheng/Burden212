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

    public function drop($bid) {
        $sql = "DELETE FROM bid WHERE userid = :userid and courseid=:courseid and section=:section";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
        $stmt->bindParam(':courseid', $bid->courseid, PDO::PARAM_STR);
        $stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }

    public function update($userid,$amount,$courseid,$section) {
        $sql = "UPDATE bid set amount=:amount, section=:section where userid=:userid and courseid=:courseid";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':amount', $amount, PDO::PARAM_INT);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
        $stmt->bindParam(':courseid', $courseid, PDO::PARAM_STR);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }

    public function retrieveAll () {
        $sql = 'select * from bid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $studentBid = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row =$stmt->fetch()){
            $studentBid[]= new Bid ($row['userid'],$row['amount'],$row['courseid'],$row['section']);
        }
        return $studentBid; 
    }

    public function retrieveDT () {
        $sql = 'select * from bid order by courseid ASC, section ASC, amount DESC, userid ASC';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $studentBid = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row =$stmt->fetch()){
            $studentBid[]= ["userid" => $row['userid'], "amount" => (float)$row['amount'],
                        "course" => $row['courseid'], "section" => $row['section']];
        }
        return $studentBid; 
    }

    public function retrieveDB ($courseid, $section) {

        //Retrieve from bid table first then check with bidding results table
        $sql = 'select * from bid where courseid=:courseid AND section=:section order by amount DESC, userid ASC';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':courseid', $courseid, PDO::PARAM_STR);
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->execute();
        
        $studentBid = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        $sql2 = 'select * from bidding_results WHERE userid=:userid AND courseid=:courseid AND section=:section';
        $stmt2 = $conn->prepare($sql2);

        $rowNum = 1;
        while ($row =$stmt->fetch()){

            $stmt2->bindParam(':userid', $userid, PDO::PARAM_STR);
            $stmt2->bindParam(':courseid', $courseid, PDO::PARAM_STR);
            $stmt2->bindParam(':section', $section, PDO::PARAM_STR);
            $stmt2->execute();
            $stmt2->setFetchMode(PDO::FETCH_ASSOC);
            
            $status = '-';
            if ($stmt2->rowCount() > 0){
                $row2 = $stmt2->fetch();
                if($row2['status'] == "SUCCESSFUL"){
                    $status = "IN";
                }
                else{
                    $status = "OUT";
                }
            }

            $studentBid[]= ["row" => $rowNum, "userid" => $row['userid'], 
                        "amount" => (float)$row['amount'], "result" => $status];
            
            $rowNum++;
        }

        return $studentBid; 
    }

    public function retrieveBids($student) {
        $sql = 'select * from bid where userid=:userid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userid', $student, PDO::PARAM_STR);
        $stmt->execute();
        
        $studentBid = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row =$stmt->fetch()){
            $studentBid[]= new Bid ($row['userid'],$row['amount'],$row['courseid'],$row['section']);
        }
        return $studentBid; 
    }

    public function findError($courseid, $section) {
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        $error = array();

        $sql = 'select * from bid where courseid=:courseid';
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':courseid', $courseid, PDO::PARAM_STR);
        $stmt->execute();
        
        $studentBid = [];
        $stmt->setFetchMode(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() == 0){
            array_push($error, "invalid course");
        }
        else{
            array_push($error, "invalid section");
        }

        return $error; 
    }
    

    public function checkForSimilarCourseBid($student,$courseid){
        $sql = 'select * from bid where userid=:userid and courseid=:courseid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $student, PDO::PARAM_STR);
        $stmt->bindParam(':courseid', $courseid, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->rowCount();
    
    }

	
	 public function removeAll() {
        $sql = 'TRUNCATE TABLE bid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    

    public function processBID($sectionBids, $courseid, $section, $round) {
        #obtain class size - courseid and section
        $sql = "SELECT SIZE FROM SECTION WHERE COURSEID = :COURSEID AND SECTION = :SECTION";
    
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);  
    
        $stmt->bindParam(':COURSEID', $courseid, PDO::PARAM_STR);
        $stmt->bindParam(':SECTION', $section, PDO::PARAM_STR);
        $stmt->execute();
    
        $row = $stmt->fetch();
    
        $size = $row['SIZE'];
        $newSize = 0;
    
        if( sizeof($sectionBids) <= $size ){
            #update student bids to all success
            $sql = "INSERT IGNORE INTO bidding_results (userid, courseid, section, round, dtvalue, amount, status) VALUES (:userid, :courseid, :section, :round, :dtvalue, :amount, :status)";
    
            $connMgr = new ConnectionManager();      
            $conn = $connMgr->getConnection();
            $stmt = $conn->prepare($sql);
            $now = new DateTime();
            $time = $now->format('Y-m-d H:i:s');
            $status = 'SUCCESSFUL';
    
            foreach ($sectionBids as $bid){
                $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
                $stmt->bindParam(':courseid', $bid->courseid, PDO::PARAM_STR);
                $stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);
                $stmt->bindParam(':round', $round, PDO::PARAM_INT);
                $stmt->bindParam(':dtvalue', $time, PDO::PARAM_STR);
                $stmt->bindParam(':amount', $bid->amount, PDO::PARAM_INT);
                $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                
                $stmt->execute();
            }
    
            #NEED TO REDUCE COURSE + SECTION SIZE (USE SQL UPDATE)
            
            $newSize = $size - sizeof($sectionBids);
        }
        else{

            if ( $round == 1){
                $clearingPrice = $sectionBids[$size-1]->amount;
                $rejectPrice = $sectionBids[$size]->amount;
        
                $sql = "INSERT IGNORE INTO bidding_results (userid, courseid, section, round, dtvalue, amount, status) VALUES (:userid, :courseid, :section, :round, :dtvalue, :amount, :status)";
        
                $connMgr = new ConnectionManager();      
                $conn = $connMgr->getConnection();
                $stmt = $conn->prepare($sql);
                $now = new DateTime();
        
                $stmt->bindParam(':round', $round, PDO::PARAM_INT);
        
                if ($clearingPrice == $rejectPrice){
                    #run checking code to see if amount = clearing price in order to drop bid
                    $index = 1;
                    $numOfSuccess = 0;
                    $time = $now->format('Y-m-d H:i:s');
                    foreach ($sectionBids as $bid){
                        
                        if($index <= $size && $bid->amount != $clearingPrice){
        
                            $status = 'SUCCESSFUL';
                            $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
                            $stmt->bindParam(':courseid', $bid->courseid, PDO::PARAM_STR);
                            $stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);
                            $stmt->bindParam(':round', $round, PDO::PARAM_INT);
                            $stmt->bindParam(':dtvalue', $time, PDO::PARAM_STR);
                            $stmt->bindParam(':amount', $bid->amount, PDO::PARAM_INT);
                            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                            
                            $stmt->execute();
                            $numOfSuccess++;
                        }
                        elseif ($bid->amount == $clearingPrice){
        
                            $status = 'DROPPED';
                            $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
                            $stmt->bindParam(':courseid', $bid->courseid, PDO::PARAM_STR);
                            $stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);
                            $stmt->bindParam(':dtvalue', $time, PDO::PARAM_STR);
                            $stmt->bindParam(':amount', $bid->amount, PDO::PARAM_INT);
                            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                            
                            $stmt->execute();
                        }
                        else{
                            $status = 'FAILED';
                            $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
                            $stmt->bindParam(':courseid', $bid->courseid, PDO::PARAM_STR);
                            $stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);
                            $stmt->bindParam(':dtvalue', $time, PDO::PARAM_STR);
                            $stmt->bindParam(':amount', $bid->amount, PDO::PARAM_INT);
                            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                            
                            $stmt->execute();
                        }
        
                        $index++;
                    }
        
                    $newSize = $size - $numOfSuccess;
                }
                else{
                    #n+1 onwards all failed bid
                    $index = 1;
                    $time = $now->format('Y-m-d H:i:s');
                    foreach ($sectionBids as $bid) {
                        if ($index <= $size){
                            $status = 'SUCCESSFUL';
                            $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
                            $stmt->bindParam(':courseid', $bid->courseid, PDO::PARAM_STR);
                            $stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);
                            $stmt->bindParam(':dtvalue', $time, PDO::PARAM_STR);
                            $stmt->bindParam(':amount', $bid->amount, PDO::PARAM_INT);
                            $stmt->bindParam(':status', $status , PDO::PARAM_STR);
                            
                            $stmt->execute();
                        }
                        else{
                            $status = 'FAILED';
                            $stmt->bindParam(':userid', $bid->userid, PDO::PARAM_STR);
                            $stmt->bindParam(':courseid', $bid->courseid, PDO::PARAM_STR);
                            $stmt->bindParam(':section', $bid->section, PDO::PARAM_STR);
                            $stmt->bindParam(':dtvalue', $time, PDO::PARAM_STR);
                            $stmt->bindParam(':amount', $bid->amount, PDO::PARAM_INT);
                            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
                            
                            $stmt->execute();
                        }
        
                        $index++;
                    }
                }
            }
            else{
                
            }

        }
    
        #Truncate bids table
        $sql = "TRUNCATE TABLE BID";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $newSize;
    }
    
    
}


