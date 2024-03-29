<?php

class RoundDAO {

    public function retrieveRound() {
        $sql = "select roundid, statusid from round";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        
        $results = array();
        while ($row =$stmt->fetch()){
            array_push($results, $row['roundid']);
            array_push($results, $row['statusid']);
        }

        return $results;
    }

    public function closeBid() {
        $sql = "UPDATE `round` SET `roundid`='0',`statusid`='0' WHERE `rowid`='1'";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        
        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }

        return $isUpdateOk;
    }

    public function activateRoundOne() {
        $sql = "UPDATE `round` SET `roundid`='1', `statusid`='1' WHERE `rowid`='1'";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        
        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }

        return $isUpdateOk;
    }

    public function closeRoundOne() {
        $sql = "UPDATE `round` SET `roundid`='1', `statusid`='0' WHERE `rowid`='1'";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        
        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }

        $sql = "SELECT DISTINCT COURSEID, SECTION FROM BID";


        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        while ($row =$stmt->fetch()){
            $scBids[] = [$row['COURSEID'],$row['SECTION']];
        }

        foreach ($scBids as $item) {
            $sql = "SELECT * FROM BID WHERE COURSEID = :COURSEID AND SECTION = :SECTION ORDER BY AMOUNT DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':COURSEID', $item[0], PDO::PARAM_STR);
            $stmt->bindParam(':SECTION', $item[1], PDO::PARAM_STR);
            $stmt->execute();

            while ($row =$stmt->fetch()){
                $sectionBids[] = new Bid ($row['userid'],$row['amount'],$row['courseid'],$row['section']);
            }


            $bidDAO = new BidDAO();
            $size = $bidDAO->processBID($sectionBids, $item[0], $item[1], 1);

            $sql = "UPDATE section set size = :size where section = :section and courseid = :courseid";
    

            $stmt = $conn->prepare($sql);
  
            $stmt->bindParam(':size', $size, PDO::PARAM_INT);
            $stmt->bindParam(':section', $item[1], PDO::PARAM_STR);
            $stmt->bindParam(':courseid', $item[0], PDO::PARAM_STR);
    
            $stmt->execute();
            
            $sectionBids = []; 
        }

        return $isUpdateOk;
    }


    public function activateRoundTwo() {
        $sql = "UPDATE `round` SET `roundid`='2', `statusid`='1' WHERE `rowid`='1'";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        
        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }

        return $isUpdateOk;
    }

	
}


