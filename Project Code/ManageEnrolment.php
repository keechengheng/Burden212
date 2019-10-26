<?php
// screen to view bid (amount, course, section) 
require_once 'include/common.php';
if(isset($_SESSION['userid']) && $_SESSION['userid'] != 'Admin'){
    $dao = new StudentDAO();
    $user = $dao->retrieve($_SESSION['userid']);
}
else{
    header("Location: Login.php?error=Unauthorized Access");
    return;
}
$student = $_SESSION['userid'];
$dao = new BidDAO();
$results = $dao->retrieveBids($student);
$_SESSION['trigger'] = "Drop";
$currentAmountSpent = 0;

    foreach($results as $element){
        //calculate total Amount Spent
        $currentAmountSpent = $currentAmountSpent + $element->amount;
    }

    $BiddingResultsDAO = new BiddingResultsDAO();
    $retrieveResults = $BiddingResultsDAO->retrieveLatestResult($user->userid, $round[0],$round[1]); 
    
   
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Merlion University | Manage Enrolment</title>

    <link href="css\bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome\css\font-awesome.css" rel="stylesheet">
    <link href="css\plugins\iCheck\custom.css" rel="stylesheet">
    <link href="css\animate.css" rel="stylesheet">
    <link href="css\style.css" rel="stylesheet">

</head>

<body>
    <!-- body start-->
    <div id="wrapper">
   <!-- open navigation-->
   <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav metismenu" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element"> <span>
                                <img alt="image" class="img-circle" src="img\profile_small.jpg">
                                </span>
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?= $user->name ?></strong>
                                </span> <span class="text-muted text-xs block">Student <b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li class="divider"></li>
                                <li><a href="Logout.php">Logout</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            MU+
                        </div>
                    </li>
                    <li >
                        <a href="StudentPage.php"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                    </li>
                    <li >
                        <a href="ViewClasses.php"><i class="fa fa-laptop"></i> <span class="nav-label">View Classes</span></a>
                    </li>
                    <li >
                        <a href="LiveBidding.php"><i class="fa fa-laptop"></i> <span class="nav-label">Live Bidding</span></a>
                    </li>
                    <li >
                        <a href="ManageBids.php"><i class="fa fa-edit"></i> <span class="nav-label">Manage My Bids</span></a>
                    </li>
                    <li class="active">
                        <a href="ManageEnrolment.php"><i class="fa fa-edit"></i> <span class="nav-label">Manage Enrolment</span></a>
                    </li>
                    
                </ul>

            </div>
        </nav>
        <!-- close navigation-->
        <!-- page begin-->
        <div id="page-wrapper" class="gray-bg">
            <!-- nav bar top-->
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header">
                        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li>
                            <span class="m-r-sm text-muted welcome-message">Welcome to Merlion University intranet.</span>
                        </li>

                        <li>
                            <a href="logout.php">
                                <i class="fa fa-sign-out"></i> Log out
                            </a>
                        </li>
                        
                    </ul>

                </nav>
            </div>
            <!-- nav bar top close-->
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Current Modules Enrolled</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.htm">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Manage My Enrolment</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Modules Enrolled</h5>
                        
                    </div>
                    <div class="ibox-content">

                        <table class="table">
                            <thead>
                            <tr>
                                <th>S/N</th>
                                <th>Course</th>
                                <th>Section</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php            
                            for ($i = 1; $i <= count($retrieveResults); $i++) {
                                $bid = $retrieveResults[$i-1];
                                echo "
                                <tr>
                                    <td>$i</td>
                                    <td>$bid->courseid</td>
                                    <td>$bid->section</td>
                                    <td>$bid->amount</td>
                                    <td>$bid->status</td>
                                </tr>
                                "; 
                                
                            }
                    ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Drop Module</h5>
                            
                        </div>
                        <div class="ibox-content">
                            <form method="POST" action="ManageEnrolment-process.php" class="form-horizontal">
                                
                                <div class="form-group"><label class="col-lg-2 control-label">Course</label>

                                    <div class="col-lg-10"><input type="text" name="courseid" placeholder="Course" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group"><label class="col-lg-2 control-label">Section</label>
                                    <div class="col-lg-10"><input type="text" name="section" placeholder="Section" class="form-control"></div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-sm btn-white" type="submit">Drop Bid</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            
        </div>
        <div class="footer">
                <div class="pull-right">
                    Clean Dashboard
                </div>
                <div>
                    <strong>Copyright</strong> G8T6 &copy; 2019
                </div>
            </div>

        </div>
        </div>



    <!-- Mainly scripts -->
    <script src="js\jquery-3.1.1.min.js"></script>
    <script src="js\bootstrap.min.js"></script>
    <script src="js\plugins\metisMenu\jquery.metisMenu.js"></script>
    <script src="js\plugins\slimscroll\jquery.slimscroll.min.js"></script>

    <!-- Peity -->
    <script src="js\plugins\peity\jquery.peity.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js\inspinia.js"></script>
    <script src="js\plugins\pace\pace.min.js"></script>

    <!-- iCheck -->
    <script src="js\plugins\iCheck\icheck.min.js"></script>

    <!-- Peity -->
    <script src="js\demo\peity-demo.js"></script>

    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>

</body>

</html>