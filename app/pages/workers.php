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
    <h2 style="text-transform: uppercase">Workers</h2>
</div>

<?php if($_GET['confirm'] == 1): ?>
    <div class="row-fluid">
        <div class="alert alert-success">Operation confirmed.</div>
    </div>
<?php endif; ?>

<?php if(!empty($_GET['delete_worker']))
{
    $connVisitsRes = clone $conn;
    $connVisitsRes
        ->setQueryMethod('DELETE')
        ->setQueryPath('workers/' . $_GET['delete_worker'])
        ->process();

    if($connVisitsRes->isResponseCodeSuccess()) {
        header("Location: /?action=workers&confirm=1");
        return;
    }
}
?>

<?php if(!empty($_POST['new_worker_name']) && !empty($_POST['new_worker_surname']))
{
    $newWorkerNameConn = clone $conn;
    $newWorkerNameConn
        ->setQueryMethod('POST')
        ->setQueryPath('workers')
        ->setQueryFormParams(['name' => $_POST['new_worker_name'],'surname' => $_POST['new_worker_surname']])
        ->process();

    if($newWorkerNameConn->isResponseCodeSuccess()) {
        header("Location: /?action=workers&confirm=1");
        return;
    }
}
?>
<div class="row-fluid">
    <form class="well form-inline" method="post">
        <input name="new_worker_name" type="text" class="input-small" placeholder="Name">
        <input name="new_worker_surname" type="text" class="input-small" placeholder="Surname">
        <button type="submit" class="btn">Add</button>
    </form>
</div>


<!-- Example row of columns -->
<div class="row-fluid">
    <table class="table table-bordered">
        <thead style="text-transform: uppercase">
        <tr>
            <th>ID</th>
            <th width="25%">Name</th>
            <th width="25%">Surname</th>
            <th width="25%">Options</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $connVisitsList = clone $conn;
        $connVisitsList
            ->setQueryMethod('GET')
            ->setQueryPath('workers')
            ->process();

        $r = json_decode($connVisitsList->getHttpResponseBody(), true);

        foreach($r as $k => $array) {

            $deleteLink = '<a class="btn btn-danger" href="?action=workers&delete_worker='.$array['id'].'">DELETE</a>';

            echo '<tr>';
            echo '<td>'.$array['id'].'</td>';
            echo '<td>'.$array['name'].'</td>';
            echo '<td>'.$array['surname'].'</td>';
            echo '<td>'.$deleteLink.'</td>';
            echo '</tr>';
        }

        ?>
        </tbody>
    </table>
</div>