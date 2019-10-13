<?php
// screen to view sectionid, sectionid, day, class time and exam time 
require_once 'include/common.php';

$dao = new SectionDAO();
$results = $dao->retrieveAll();
   
?>

<html>
    <head>
    </head>
    <body>
        <h1>Courses and Sections Available</h1>

        <table border="1">
            <tr>
                <th>S/N</th>
                <th>Course</th>
                <th>Section</th>
                <th>Day</th>  
                <th>Start</th>
                <th>End</th>
                <th>Instructor</th>
                <th>Venue</th>
                <th>Size</th>
            </tr>
        <?php            
                for ($i = 1; $i <= count($results); $i++) {
                    $section = $results[$i-1];
                    echo "
                    <tr>
                        <td>$i</td>
                        <td>$section->courseid</td>
                        <td>$section->sectionid</td>
                        <td>$section->day</td>
                        <td>$section->start</td>
                        <td>$section->end</td>
                        <td>$section->instructor</td>
                        <td>$section->venue</td>
                        <td>$section->size</td>
                    </tr>
                    ";
                    
                }
        ?>  
        </table>

        <h1>Insert Bid </h1>

        <form method="POST" action="Bid-process.php">
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
        </table>
                <br/>
        <a href='StudentPage.php'>Return to home!</a>
    </body>
</html>
