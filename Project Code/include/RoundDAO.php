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


