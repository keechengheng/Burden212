<?php

class ConnectionManager {
   
    public function getConnection() {
        
        $host = "localhost";
        $username = "root";

        // For Windows
        // $password = "";

        //For MacOSC
        //$password = "root";  

        //For AWS
        $password = "FPhD75eE2LHQ";

        $dbname = "SPM_Proj_2019";
        $port = 3306;    

        $url  = "mysql:host={$host};dbname={$dbname};port={$port}";
        
        $conn = new PDO($url, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        return $conn;  
        
    }
    
}
?>
