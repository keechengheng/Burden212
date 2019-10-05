<?php

class RoundDAO {

    // public function add($prerequisite) {
    //     $sql = "INSERT IGNORE INTO prerequisite (courseid, prerequisiteid) VALUES (:courseid, :prerequisiteid)";

    //     $connMgr = new ConnectionManager();      
    //     $conn = $connMgr->getConnection();
    //     $stmt = $conn->prepare($sql);
        
    //     $stmt->bindParam(':courseid', $prerequisite->courseid, PDO::PARAM_STR);
    //     $stmt->bindParam(':prerequisiteid', $prerequisite->prerequisiteid, PDO::PARAM_STR);

    //     $isAddOK = False;
    //     if ($stmt->execute()) {
    //         $isAddOK = True;
    //     }

    //     return $isAddOK;
    // }

    public function retrieveRound() {
        $sql = "select roundid from round";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        
        $round = array();
        while ($row =$stmt->fetch()){
            array_push($round, $row['roundid']);
        }

        return $round;
    }

	
}


