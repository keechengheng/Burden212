<?php

require_once 'include/common.php';

if(!isset($_SESSION['userid']) || $_SESSION['userid'] !== 'Admin'){
    header("Location: Login.php?error=Unauthorized Access");
    return;
}

$user = $_SESSION['userid'];

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
	<link href="css\plugins\iCheck\custom.css" rel="stylesheet">
	<link href="css\plugins\dropzone\basic.css" rel="stylesheet">
    <link href="css\plugins\dropzone\dropzone.css" rel="stylesheet">
    <link href="css\plugins\jasny\jasny-bootstrap.min.css" rel="stylesheet">
    <link href="css\plugins\codemirror\codemirror.css" rel="stylesheet">
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
                    <h2>Bootstrap</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="AdminPage.php">Home</a>
                        </li>
                        
                        <li class="active">
                            <strong>Bootstrap</strong>
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
                            <h5>Upload file to bootstrap system</h5>
                           
						</div>
						
                          
                        <div class="ibox-content">
						<form id='admin-form' action="Bootstrap-process.php" method="post" enctype="multipart/form-data">
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="..."></span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
							</div>
							<p>
                            <button type="submit" name="submitBtn" class="btn btn-w-m btn-warning">Upload</button>
                        </p>           
                        </form>
						</div>      
						
                    </div>
                </div>
                    
            </div>
		
            <div class="row">
                <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Basic Table</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Larry</td>
                                <td>the Bird</td>
                                <td>@twitter</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Striped Table </h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="#">Config option 1</a>
                                </li>
                                <li><a href="#">Config option 2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                                <th>User</th>
                                <th>Value</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td><span class="line">5,3,2,-1,-3,-2,2,3,5,2</span></td>
                                <td>Samantha</td>
                                <td class="text-navy"> <i class="fa fa-level-up"></i> 40% </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><span class="line">5,3,9,6,5,9,7,3,5,2</span></td>
                                <td>Jacob</td>
                                <td class="text-warning"> <i class="fa fa-level-down"></i> -20% </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><span class="line">1,6,3,9,5,9,5,3,9,6,4</span></td>
                                <td>Damien</td>
                                <td class="text-navy"> <i class="fa fa-level-up"></i> 26% </td>
                            </tr>
                            </tbody>
                        </table>
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
	
	<!-- Jasny -->
	<script src="js\plugins\jasny\jasny-bootstrap.min.js"></script>
	
	 <!-- DROPZONE -->
	 <script src="js\plugins\dropzone\dropzone.js"></script>

    <!-- iCheck -->
	<script src="js\plugins\iCheck\icheck.min.js"></script>
	
	<!-- CodeMirror -->
    <script src="js\plugins\codemirror\codemirror.js"></script>
    <script src="js\plugins\codemirror\mode\xml\xml.js"></script>

    <!-- Peity -->
    <script src="js\demo\peity-demo.js"></script>

    <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
		Dropzone.options.dropzoneForm = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            dictDefaultMessage: "<strong>Drop files here or click to upload. </strong></br> (This is just a demo dropzone. Selected files are not actually uploaded.)"
        };

        $(document).ready(function(){

            var editor_one = CodeMirror.fromTextArea(document.getElementById("code1"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code2"), {
                lineNumbers: true,
                matchBrackets: true
            });

            var editor_two = CodeMirror.fromTextArea(document.getElementById("code3"), {
                lineNumbers: true,
                matchBrackets: true
            });

       });
    </script>

</body>

</html>


