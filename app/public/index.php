<?php
error_reporting(E_ERROR);
ob_start();
session_start();

if($_GET['logout'] == 1) {
    session_destroy();
    header("Location: /?action=login");
    return;
}

if(!$_SESSION['logged'] && $_GET['action'] != 'login') {
    header("Location: /?action=login");
}

require_once '../Connect.php';
require_once '../Settings.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rez API Admin</title>

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
    <![endif]-->

</head>

<body>

<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="/">Rez API Admin</a>
            <div class="nav-collapse">
                <ul class="nav">
                    <li><a href="/?action=visits">Visits</a></li>
                    <li><a href="/?action=workers">Workers</a></li>
                    <li><a href="/?action=places">Places</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <?php

    $action = $_GET['action'];
    if(empty($action)) {
        $action = 'visits';
    }

    switch($action) {
        case 'visits':
            require_once '../pages/visits.php';
            break;
        case 'places':
            require_once '../pages/places.php';
            break;
        case 'workers':
            require_once '../pages/workers.php';
            break;
        default:
            require_once '../pages/login.php';
            break;
    }




    ?>

    <hr>

    <?php
    $logoutLink = '';
    if($_SESSION['logged']) {
        $logoutLink = '<a class="btn btn-small btn-danger pull-right" href="/?logout=1">Logout</a>';
    }

    ?>

    <footer>
        <p>Rez API - Rez Admin <?= $logoutLink ?></p>
    </footer>

</div> <!-- /container -->

<script src="js/bootstrap.min.js"></script>

</body>
</html>