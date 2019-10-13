<?php
// screen to view bid (amount, course, section) 
require_once 'include/common.php';

$student = $_SESSION['userid'];
$dao = new BidDAO();
$results = $dao->retrieveBids($student);
   
?>

<html>
    <head>
    </head>
    <body>
        <h1>Current Bids for <?= $student ?></h1>

        <table border="1">
            <tr>
                <th>S/N</th>
                <th>Course</th>
                <th>Section</th>
                <th>Amount</th>
                <th>Drop</th>    
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
                <td>Drop</td>
            </tr>
            "; 
            
        }
?>
        
        </table>
        <a href='ManageBids.php'>Make a Bid!</a>
        <br/>
        <h1>Drop Bid </h1>

        <form action="BidProcess.php">
        <table border="1">
            <tr>
                <th>Course</th>
                <th>Section</th>
               
            </tr>
            <tr>
                <td><input type="text" name="courseid"></td>
                <td><input type="text" name="section"></td>
            
            </tr>
            <tr>
                <td colspan="3"><input type="submit" name="cancel"value="Cancel" style="float: right;"/>
                <input type="submit" name="submit" value="Submit" style="float: right;"/></td>
            </tr>
        </table>
        </br>
        <a href='StudentPage.php'>Return to home!</a>
    </body>
</html>
