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

    public function update($userid, $courseid, $amount,$section) {
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

    public  function retrieveBids($student) {
        $sql = 'select * from bid where userid=:userid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $student, PDO::PARAM_STR);
        $stmt->execute();

        while ($row =$stmt->fetch()){
            $studentBid[]= new Bid ($row['userid'],$row['amount'],$row['courseid'],$row['section']);
        }
        return $studentBid; 
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

        $sql = "SELECT SIZE FROM SECTION WHERE COURSEID = :COURSEID AND SECTIONID = :SECTIONID";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':COURSEID', $courseid, PDO::PARAM_STR);
        $stmt->bindParam(':SECTION', $section, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch();
        $size = $row['SIZE'];

        #n = size
        if( sizeof($sectionBids) <= $size ){
            #update student bids to all success
        }
        else{
            $clearingPrice = $sectionBids[$size-1]->amount;
            $rejectPrice = $sectionBids[$size]->amount;

            if ($clearingPrice == $rejectPrice){
                #run checking code to see if amount = clearing price in order to drop bid
            }
            else{
                #n+1 onwards all failed bid
            }
        }

    }
	
}


