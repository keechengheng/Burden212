<?php
// screen to insert bid (amount, course, section) 
require_once 'include/common.php';

$student = $_SESSION['userid'];
$dao = new BidDAO();
$results = $dao->retrieveBids($student);
   
?>

<html>
    <!-- <head>

    </head> -->
    <body>
        <h1>Insert Bids for <?= $student ?></h1>
        <!-- <p>
            <a href='logout.php'>Logout</a>
        </p> -->
        <br><br><br>
        <form action="BidProcess.php">
        <table border="1">
            <tr>
                <th>Course</th>
                <th>Section</th>
                <th>Bid Amount</th>   
            </tr>
            <tr>
                <td><input type="text" name="courseid"></td>
                <td><input type="text" name="section"></td>
                <td><input type="text" name="amount"></td>
            </tr>
            <tr>
                <td colspan="3"><input type="submit" name="cancel"value="Cancel" style="float: right;"/>
                <input type="submit" name="submit" value="Submit" style="float: right;"/></td>
            </tr>
            
<?php            
        // for ($i = 1; $i <= count($results); $i++) { style="float: centre;"
        //     $bid = $results[$i-1];
        //     echo "
        //     <tr>
        //         <td>$i</td>
        //         <td>$bid->amount</td>
        //         <td>$bid->courseid</td>
        //         <td>$bid->section</td>
        //     </tr>
        //     "; 
            
        // }
?>
        
        </table>
            
        </form>
    
        
    </body>
</html>
