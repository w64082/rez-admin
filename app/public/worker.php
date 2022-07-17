<?php
ob_start();
require_once '../Connect.php';
require_once '../Settings.php';
$conn = new Connect();

$connAllPlacesRes = clone $conn;
$connAllPlacesRes
    ->setQueryMethod('GET')
    ->setQueryPath('places')
    ->process();

$places = json_decode($connAllPlacesRes->getHttpResponseBody(), true);

$connAllWorkersRes = clone $conn;
$connAllWorkersRes
    ->setQueryMethod('GET')
    ->setQueryPath('workers')
    ->process();

$workers = json_decode($connAllWorkersRes->getHttpResponseBody(), true);

$sett = new Settings();
$sett
    ->setPlacesDetails($places)
    ->setWorkersDetails($workers);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rez API Worker</title>

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
            <a class="brand" href="/worker.php">Rez API Worker</a>
        </div>
    </div>
</div>

<div class="container">

    <!-- Main hero unit for a primary marketing message or call to action -->
    <div class="hero-unit">
        <h2 style="text-transform: uppercase">Personal calendar</h2>
    </div>

    <?php if(!empty($_POST['input_id']))
    {
        if(!$sett->getNameOfWorkerById($_POST['input_id'])) {
            header("Location: /worker.php");
            return;
        }

        header("Location: /worker.php?show=1&id=$_POST[input_id]");
        return;
    }
    ?>

    <div class="row-fluid">
        <form class="well form-inline" method="post" action="/worker.php">
            <input name="input_id" type="text" class="input" placeholder="Personal ID">
            <button type="submit" class="btn">Show calendar</button>
        </form>
    </div>


    <?php if($_GET['show'] == 1): ?>
    <div class="row-fluid">
        <div class="alert alert-info">Calendar for ID: <?php echo $_GET['id']; ?></div>
    </div>
    <?php endif; ?>

    <?php if($_GET['show'] == 1): ?>

    <!-- Example row of columns -->
    <div class="row-fluid">
        <table class="table table-bordered">
            <thead style="text-transform: uppercase">
                <tr>
                    <th>ID</th>
                    <th>Place</th>
                    <th>Date FROM</th>
                    <th>Date TO</th>
                    <th>Reservation</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $connVisitsList = clone $conn;
            $connVisitsList
                ->setQueryMethod('GET')
                ->setQueryPath('visits')
                ->process();

            $r = json_decode($connVisitsList->getHttpResponseBody(), true);

            foreach($r as $k => $array) {

                if($_GET['id'] !== $array['id_worker']) {
                    continue;
                }

                $isReserved = $array['is_reserved'] ? '<span class="btn btn-danger">YES</span>' : '<span class="btn btn-success">NO</span>';

                echo '<tr>';
                echo '<td>'.$array['id'].'</td>';
                echo '<td>'.$sett->getNameOfPlaceById($array['id_place']).'</td>';
                echo '<td>'.$array['date_start'].'</td>';
                echo '<td>'.$array['date_to'].'</td>';
                echo '<td>'.$isReserved.'</td>';
                echo '</tr>';
            }

            ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>


    <hr>

    <footer>
        <p>Rez API - Rez Worker</p>
    </footer>

</div> <!-- /container -->

<script src="js/bootstrap.min.js"></script>

</body>
</html>