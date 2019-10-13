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
        <br/>
        <br/>
        <a href='StudentPage.php'>Return to home!</a>
    </body>
</html>
