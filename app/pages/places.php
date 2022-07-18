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
    <h2 style="text-transform: uppercase">Places</h2>
</div>

<?php if($_GET['confirm'] == 1): ?>
    <div class="row-fluid">
        <div class="alert alert-success">Operation confirmed.</div>
    </div>
<?php endif; ?>

<?php if(!empty($_GET['delete_place']))
{
    $deletePlaceConn = clone $conn;
    $deletePlaceConn
        ->setQueryMethod('DELETE')
        ->setQueryPath('places/' . $_GET['delete_place'])
        ->process();

    if($deletePlaceConn->isResponseCodeSuccess()) {
        header("Location: /?action=places&confirm=1");
        return;
    }
}
?>

<?php if(!empty($_POST['new_place_name']))
{
    $newPlaceNameConn = clone $conn;
    $newPlaceNameConn
        ->setQueryMethod('POST')
        ->setQueryPath('places')
        ->setQueryFormParams(['name' => $_POST['new_place_name']])
        ->process();

    if($newPlaceNameConn->isResponseCodeSuccess()) {
        header("Location: /?action=places&confirm=1");
        return;
    }
}
?>
<div class="row-fluid">
    <form class="well form-inline" method="post">
        <input name="new_place_name" type="text" class="input-small" placeholder="Name">
        <button type="submit" class="btn">Add</button>
    </form>
</div>


<!-- Example row of columns -->
<div class="row-fluid">
    <table class="table table-bordered">
        <thead style="text-transform: uppercase">
        <tr>
            <th>ID</th>
            <th width="50%">Name</th>
            <th width="25%">Options</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $connVisitsList = clone $conn;
        $connVisitsList
            ->setQueryMethod('GET')
            ->setQueryPath('places')
            ->process();

        $r = json_decode($connVisitsList->getHttpResponseBody(), true);

        foreach($r as $k => $array) {

            $deleteLink = '<a class="btn btn-danger" href="?action=places&delete_place='.$array['id'].'">DELETE</a>';

            echo '<tr>';
            echo '<td>'.$array['id'].'</td>';
            echo '<td>'.$array['name'].'</td>';
            echo '<td>'.$deleteLink.'</td>';
            echo '</tr>';
        }

        ?>
        </tbody>
    </table>
</div>