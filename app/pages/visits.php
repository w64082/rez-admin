<?php

$conn = new Connect();

$connAllPlacesRes = clone $conn;
$connAllPlacesRes
    ->setQueryMethod('GET')
    ->setQueryPath('places')
    ->process();

$places = json_decode($connAllPlacesRes->getHttpResponseBody(), true);
if(empty($places)) {
    $places = [];
}

$connAllWorkersRes = clone $conn;
$connAllWorkersRes
    ->setQueryMethod('GET')
    ->setQueryPath('workers')
    ->process();

$workers = json_decode($connAllWorkersRes->getHttpResponseBody(), true);
if(empty($workers)) {
    $workers = [];
}

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
    $connVisitDelete = clone $conn;
    $connVisitDelete
        ->setQueryMethod('DELETE')
        ->setQueryPath('visits/' . $_GET['delete_visit'])
        ->process();

    if($connVisitDelete->isResponseCodeSuccess()) {
        header("Location: /?action=visits&confirm=1");
        return;
    }
}
?>

    <?php if(!empty($_POST['new_visit_worker_id']) && !empty($_POST['new_visit_place_id']) && !empty($_POST['new_visit_date_from']) && !empty($_POST['new_visit_date_to']))
    {
        $connVisitAdd = clone $conn;
        $connVisitAdd
            ->setQueryMethod('POST')
            ->setQueryPath('visits')
            ->setQueryFormParams([
                    'id_worker' => $_POST['new_visit_worker_id'],
                    'id_place' => $_POST['new_visit_place_id'],
                    'date_start' => $_POST['new_visit_date_from'],
                    'date_to' => $_POST['new_visit_date_to']
                ])
            ->process();

        if($connVisitAdd->isResponseCodeSuccess()) {
            header("Location: /?confirm=1");
            return;
        }
    }
    ?>
    <div class="row-fluid">
        <form class="well form-inline" method="post">
            <label for="new_visit_worker_id">Worker: </label>
            <select style="margin-right: 10px;margin-left: 10px;" id="new_visit_worker_id" name="new_visit_worker_id">
                <?php
                foreach($sett->getWorkersDetails() as $key => $worker) {
                    echo '<option value="'.$key.'">'.$worker.'</option>';
                }
                ?>
            </select>
            <label for="new_visit_place_id">Place: </label>
            <select style="margin-right: 10px;margin-left: 10px;" id="new_visit_place_id" name="new_visit_place_id">
                <?php
                foreach($sett->getPlacesDetails() as $key => $place) {
                    echo '<option value="'.$key.'">'.$place.'</option>';
                }
                ?>
            </select>
            <input name="new_visit_date_from" type="text" class="input-medium" placeholder="Date from">
            <input name="new_visit_date_to" type="text" class="input-medium" placeholder="Date to">
            <button type="submit" class="btn">Add</button>
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

            if(empty($array['client_name']['String'])) {
                $deleteLink = '<a class="btn btn-danger" href="?action=visits&delete_visit='.$array['id'].'">DELETE</a>';
            } else {
                $deleteLink = '';
            }

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