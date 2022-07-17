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
    <title>Rez API Widget</title>

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
            <a class="brand" href="/">Rez API Widget</a>
        </div>
    </div>
</div>

<div class="container">

    <!-- Main hero unit for a primary marketing message or call to action -->
    <div class="hero-unit">
        <h2 style="text-transform: uppercase">Please select visit date from list</h2>
    </div>

    <?php if($_GET['confirm'] == 1): ?>
    <div class="row-fluid">
        <div class="alert alert-success">Reservation confirmed for ID: <?php echo $_GET['id']; ?></div>
    </div>
    <?php endif; ?>

    <?php if($_GET['reservation'] == 1): ?>

    <?php if(!empty($_POST['reservation_name']) && !empty($_POST['reservation_surname']))
    {
        $connVisitsRes = clone $conn;
        $connVisitsRes
            ->setQueryMethod('POST')
            ->setQueryPath('visits/' .$_GET['id'].'/reservation')
            ->setQueryFormParams(['client_name' => $_POST['reservation_name'], 'client_surname' => $_POST['reservation_surname']])
            ->process();

        print_r($connVisitsRes->getHttpResponseBody());

        if($connVisitsRes->isResponseCodeSuccess()) {
            header("Location: /?confirm=1&id=$_GET[id]");
            return;
        }
    }
    ?>
    <div class="row-fluid">
        <form class="well form-inline" method="post">
            <input name="reservation_name" type="text" class="input-small" placeholder="Name">
            <input name="reservation_surname" type="password" class="input-small" placeholder="Surname">
            <button type="submit" class="btn">Confirm</button>
        </form>
    </div>

    <?php endif; ?>

    <!-- Example row of columns -->
    <div class="row-fluid">
        <table class="table table-bordered">
            <thead style="text-transform: uppercase">
                <tr>
                    <th>ID</th>
                    <th>Place</th>
                    <th>Worker</th>
                    <th>Date FROM</th>
                    <th>Date TO</th>
                    <th>Make reservation</th>
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

                $isReserved = !$array['is_reserved'] ? '<a class="btn btn-success" href="/?reservation=1&id='.$array['id'].'">Make reservation</a>' : '<button class="btn btn-danger" disabled="disabled">Not available</button>';

                echo '<tr>';
                echo '<td>'.$array['id'].'</td>';
                echo '<td>'.$sett->getNameOfPlaceById($array['id_place']).'</td>';
                echo '<td>'.$sett->getNameOfWorkerById($array['id_worker']).'</td>';
                echo '<td>'.$array['date_start'].'</td>';
                echo '<td>'.$array['date_to'].'</td>';
                echo '<td>'.$isReserved.'</td>';
                echo '</tr>';
            }

            ?>
            </tbody>
        </table>
    </div>



    <hr>

    <footer>
        <p>Rez API - Rez Widget</p>
    </footer>

</div> <!-- /container -->

<script src="js/bootstrap.min.js"></script>

</body>
</html>