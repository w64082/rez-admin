<?php
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

<!-- Main hero unit for a primary marketing message or call to action -->
<div class="hero-unit">
    <h2 style="text-transform: uppercase">Visits</h2>
</div>

<?php if($_GET['confirm'] == 1): ?>
    <div class="row-fluid">
        <div class="alert alert-success">Operation confirmed.</div>
    </div>
<?php endif; ?>

<?php if(!empty($_GET['delete_visit']))
{
    $connVisitsRes = clone $conn;
    $connVisitsRes
        ->setQueryMethod('DELETE')
        ->setQueryPath('visits/' . $_GET['delete_visit'])
        ->process();

    if($connVisitsRes->isResponseCodeSuccess()) {
        header("Location: /?action=visits&confirm=1");
        return;
    }
}
?>

    <?php if(!empty($_POST['reservation_name']) && !empty($_POST['reservation_surname']))
    {
        $connVisitsRes = clone $conn;
        $connVisitsRes
            ->setQueryMethod('POST')
            ->setQueryPath('visits/' .$_GET['id'].'/reservation')
            ->setQueryFormParams(['client_name' => $_POST['reservation_name'], 'client_surname' => $_POST['reservation_surname']])
            ->process();

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
            <th>Client</th>
            <th>Status</th>
            <th>Options</th>
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

            $isReserved = $array['is_reserved'] ? '<span class="btn btn-danger">RESERVED</span>' : '<span class="btn btn-success">AVAILABLE</span>';

            $deleteLink = '<a class="btn btn-danger" href="?action=visits&delete_visit='.$array['id'].'">DELETE</a>';

            echo '<tr>';
            echo '<td>'.$array['id'].'</td>';
            echo '<td>'.$sett->getNameOfPlaceById($array['id_place']).'</td>';
            echo '<td>'.$sett->getNameOfWorkerById($array['id_worker']).'</td>';
            echo '<td>'.$array['date_start'].'</td>';
            echo '<td>'.$array['date_to'].'</td>';
            echo '<td>'.$array['client_name']['String'].' '.$array['client_surname']['String'].'</td>';
            echo '<td>'.$isReserved.'</td>';
            echo '<td>'.$deleteLink.'</td>';
            echo '</tr>';
        }

        ?>
        </tbody>
    </table>
</div>