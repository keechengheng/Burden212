<?php

class StudentDAO {
    
    public  function retrieve($username) {
        $sql = 'select userid, password, name, school, edollar from student where userid=:userid';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':userid', $username, PDO::PARAM_STR);
        $stmt->execute();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new Student($row['userid'], $row['password'], $row['name'], $row['school'], $row['edollar']);
        }
    }

    public  function retrieveAll() {
        $sql = 'select * from student';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Student($row['userid'], $row['password'], $row['name'], $row['school'], $row['edollar']);
        }
        return $result;
    }

    public  function retrieveDT() {
        $sql = 'select * from student order by userid ASC';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = ["userid" => $row['userid'], "password" => $row['password'], 
                    "name" => $row['name'], "school" => $row['school'], "edollar" => (float)$row['edollar']];
        }
        return $result;
    }

    public function add($user) {
        $sql = "INSERT IGNORE INTO student (userid, password, name, school, edollar) VALUES (:userid, :password, :name, :school, :edollar)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':userid', $user->userid, PDO::PARAM_STR);
        $stmt->bindParam(':password', $user->password, PDO::PARAM_STR);
        $stmt->bindParam(':name', $user->name, PDO::PARAM_STR);
        $stmt->bindParam(':school', $user->school, PDO::PARAM_STR);
        $stmt->bindParam(':edollar', $user->edollar, PDO::PARAM_INT);

        $isAddOK = False;
        if ($stmt->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }

     public function update($user) {
        $sql = 'UPDATE student SET edollar=:edollar, school=:school, password=:password, name=:name WHERE username=:username';      
        
        $connMgr = new ConnectionManager();           
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $user->password = password_hash($user->password,PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $user->username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $user->password, PDO::PARAM_STR);
        $stmt->bindParam(':name', $user->name, PDO::PARAM_STR);
        $stmt->bindParam(':school', $user->school, PDO::PARAM_STR);
        $stmt->bindParam(':edollar', $user->edollar, PDO::PARAM_INT);

        $isUpdateOk = False;
        if ($stmt->execute()) {
            $isUpdateOk = True;
        }

        return $isUpdateOk;
    }
	
	 public function removeAll() {
        $sql = '
            SET FOREIGN_KEY_CHECKS = 0;      
            TRUNCATE TABLE student;
            SET FOREIGN_KEY_CHECKS = 1';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
}


