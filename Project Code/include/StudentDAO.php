<?php

class UserDAO {
    
    public  function retrieve($username) {
        $sql = 'select username, password, name, school, edollar from student where username=:username';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
            
        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return new User($row['username'], $row['password'], $row['name'], $row['school'], $row['edollar']);
        }
    }

    public  function retrieveAll() {
        $sql = 'select * from user';
        
        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();

        $stmt = $conn->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        $result = array();


        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = new Student($row['username'], $row['password'], $row['name'], $row['school'], $row['edollar']);
        }
        return $result;
    }

    public function add($user) {
        $sql = "INSERT IGNORE INTO student (username, password, name, school, edollar) VALUES (:username, :password, :name, :school, :edollar)";

        $connMgr = new ConnectionManager();      
        $conn = $connMgr->getConnection();
        $stmt = $conn->prepare($sql);
        
        $user->password = password_hash($user->password,PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $user->username, PDO::PARAM_STR);
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
        $sql = 'UPDATE user SET edollar=:edollar, school=:school, password=:password, name=:name WHERE username=:username';      
        
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
        $sql = 'TRUNCATE TABLE user';
        
        $connMgr = new ConnectionManager();
        $conn = $connMgr->getConnection();
        
        $stmt = $conn->prepare($sql);
        
        $stmt->execute();
        $count = $stmt->rowCount();
    }    
	
}


