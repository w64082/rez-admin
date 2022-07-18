<?php
if(!empty($_POST['admin_password']) && ($_POST['admin_password']) == 'admin') {
    $_SESSION['logged'] = 1;
    header("Location: /");
    return;
}
?>
<div class="row-fluid">
    <form class="well form-inline" method="post">
        <input name="admin_password" type="password" class="input" placeholder="Admin password">
        <button type="submit" class="btn">Login</button>
    </form>
</div>