<?php
// screen to view bid (amount, course, section) 
require_once 'include/common.php';

$student = $_SESSION['userid'];
$dao = new BidDAO();
$results = $dao->retrieveBids($student);
   
?>

<html>
    <head>
        <!-- <link rel="stylesheet" type="text/css" href="include/style.css"> -->
        <!-- <style>
	        table{border: 1px solid black;}
            th,td{border: 1px solid black; text-align: center;}   
            </style> -->
    </head>
    <body>
        <h1>Current Bids for <?= $student ?></h1>
        <!-- <p>
            <a href='logout.php'>Logout</a>
        </p> -->

        <table border="1">
            <tr>
                <th>S/N</th>
                <th>Course</th>
                <th>Section</th>
                <th>Amount</th>
                <th>Status</th>    
            </tr>
<?php            
        for ($i = 1; $i <= count($results); $i++) {
            $bid = $results[$i-1];
            echo "
            <tr>
                <td>$i</td>
                <td>$bid->courseid</td>
                <td>$bid->section</td>
                <td>$bid->amount</td>
                <td>Status</td>
            </tr>
            "; 
            
        }
?>
        
        </table>
        
        <p>
        <a id="edit" href="edit.php">Edit</a>
        </p>
    </body>
</html>
