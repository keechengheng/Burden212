<?php
require_once 'include/common.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] !== 'Admin'){
    header("Location: Login.php?error=Unauthorized Access");
    return;
}

$user = $_SESSION['userid'];

$roundDAO = new RoundDAO();
$round = $roundDAO ->retrieveRound();
$_SESSION['round'] = $round[0];
$_SESSION['status'] = $round[1];

if ($round[0] == "0")
{
    $message="The system is currently not open for bidding.";
    $statusMessage = 'Closed';
    $roundNumber = "0";
    $style = "disabled";
}
if ($round[0] == "1")
{
    $roundNumber = "1";
    if  ($round[1]=="0"){
        $message="The system is currently closed and processing Round 1 bidding results.";
        $statusMessage = 'Closed';
        $style = "";
    }
    else{
        $message="The system is currently opened for Round 1 of bidding.";
        $statusMessage = 'Open';
        $style = "";
    }
    
}
if ($round[0] == "2")
{
    $roundNumber = "2";
    if  ($round[1]=="0"){
        $message="The system is currently closed and processing Round 2 bidding results.";
        $statusMessage = "Closed";
        $style = "";
    }
    else{
        $message="The system is currently opened for Round 2 of bidding.";
        $statusMessage = "Open";
        $style = "";
    }
}


?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Merlion University | Admin Dashboard</title>

    <link href="css\bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome\css\font-awesome.css" rel="stylesheet">

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
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?= $user ?></strong>
                                </span> <span class="text-muted text-xs block">Admin <b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li class="divider"></li>
                                <li><a href="Logout.php">Logout</a></li>
                            </ul>
                        </div>
                        <div class="logo-element">
                            MU+
                        </div>
                    </li>
                    <li class="active">
                        <a href="AdminPage.php"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                    </li>
                    <li >
                        <a href="bootstrap.php"><i class="fa fa-laptop"></i> <span class="nav-label">Bootstrap</span></a>
                    </li>
                    
                </ul>

            </div>
        </nav>
        <!-- close navigation-->
        <!-- page begin-->
        <div id="page-wrapper" class="gray-bg">
            <!-- nav bar top-->
            <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
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
            <!-- body wrap-->
                <div class="wrapper wrapper-content">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-success pull-right"><?= $statusMessage ?></span>
                                    <h5>System Status</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins"><?= $roundNumber ?></h1>
                                    <small>Round</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-success pull-right">Count</span>
                                    <h5>Number of Students</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins">48</h1>
                                    <small>Students</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-success pull-right">Count</span>
                                    <h5>Number of Courses</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins">72</h1>
                                    <small>Courses</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <span class="label label-success pull-right">Count</span>
                                    <h5>Number of Sections</h5>
                                </div>
                                <div class="ibox-content">
                                    <h1 class="no-margins">124</h1>
                                    <small>Sections</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row animated fadeInRight">
                        <div class="col-lg-4">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5>Round Control Panel</h5>
                                </div>
                                <div class="ibox-content">
                                    <p>
                                        <?= $message ?>
                                    </p>
                                    <form id='admin-form' action="AdminPage-process.php" method="post" enctype="multipart/form-data">
                                    
                                    <p>
                                        <button type="submit" name="submitBtn" class="btn btn-w-m btn-warning" <?= $style ?>>Next Round</button>
                                    </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    
                    </div>
            </div>    
            <!-- body wrap end-->

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
    <!-- body end-->

    <!-- Mainly scripts -->
    <script src="js\jquery-3.1.1.min.js"></script>
    <script src="js\bootstrap.min.js"></script>
    <script src="js\plugins\metisMenu\jquery.metisMenu.js"></script>
    <script src="js\plugins\slimscroll\jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="js\plugins\flot\jquery.flot.js"></script>
    <script src="js\plugins\flot\jquery.flot.tooltip.min.js"></script>
    <script src="js\plugins\flot\jquery.flot.spline.js"></script>
    <script src="js\plugins\flot\jquery.flot.resize.js"></script>
    <script src="js\plugins\flot\jquery.flot.pie.js"></script>
    <script src="js\plugins\flot\jquery.flot.symbol.js"></script>
    <script src="js\plugins\flot\jquery.flot.time.js"></script>

    <!-- Peity -->
    <script src="js\plugins\peity\jquery.peity.min.js"></script>
    <script src="js\demo\peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js\inspinia.js"></script>
    <script src="js\plugins\pace\pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="js\plugins\jquery-ui\jquery-ui.min.js"></script>

    <!-- Jvectormap -->
    <script src="js\plugins\jvectormap\jquery-jvectormap-2.0.2.min.js"></script>
    <script src="js\plugins\jvectormap\jquery-jvectormap-world-mill-en.js"></script>

    <!-- EayPIE -->
    <script src="js\plugins\easypiechart\jquery.easypiechart.js"></script>

    <!-- Sparkline -->
    <script src="js\plugins\sparkline\jquery.sparkline.min.js"></script>

    <!-- Sparkline demo data  -->
    <script src="js\demo\sparkline-demo.js"></script>

</body>
</html>
