<?php
// screen to view sectionid, sectionid, day, class time and exam time 
require_once 'include/common.php';

$dao = new SectionDAO();
$results = $dao->retrieveAll();
   
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
        <h1>Courses Available</h1>
        <!-- <p>
            <a href='logout.php'>Logout</a>
        </p> -->

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
                <th>Bid</th>
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
                <td>Bid</td>
            </tr>
            "; //<td><a href='bid.php?name=$section->name'>Bid</a></td>
            
        }
?>
        
        </table>
    </body>
</html>
